<?php

namespace App\Http\Controllers;

use App\Models\Creditor;
use App\Models\CreditorInvoice;
use App\Models\CreditorStatement;
use App\Models\Debtor;
use App\Models\DebtorStatement;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index() // Show invoices for a specific creditor
    {
        $invoices =  Invoice::all();
        $cpage = 'finances';
        return view('invoices.index', compact('cpage', 'invoices'));
    }
    public function create()
    {
        $creditors = Creditor::all(); // Fetch all creditors
        $debtors = Member::orderBy('name','ASC')->get();     // Fetch all debtors

        // Combine creditors and debtors into a single collection
        $cpage = 'finances';

        $accounts = \App\Models\Accounts::all(); // Fetch all accounts

        return view('invoices.create', compact('debtors','cpage','creditors', 'accounts'));
    }

    public function store(Request $request)
    {
        $partyType = $request->input('party');
        $invoice_number = $this->generateUniqueInvoiceNumber();

        if ($request->input('invoice_number')) {
            $invoice_number = $request->input('invoice_number');
        }

        $rules = [
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string',
            'party' => 'required|string',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ];

        $party = null;

        if ($partyType === 'creditor') {
            $rules['creditor_id'] = 'required|exists:creditors,id';
            $request->validate($rules);

            $party = Creditor::find($request->input('creditor_id'));
            // No statementModel needed here for creditors.
        } elseif ($partyType === 'debtor') {
            $rules['member_id'] = 'required|exists:members,id';
            $request->validate($rules);

            $party = Member::find($request->input('member_id'));
        } else {
            return back()->with('error-notification', 'Invalid party type.');
        }

        DB::beginTransaction();

        try {
            $invoice = new Invoice();
            $invoice->invoice_number = $invoice_number;
            $invoice->invoice_date = $request->input('invoice_date');
            $invoice->amount = $request->input('amount');
            $invoice->description = $request->input('description');
            $invoice->account_id = $request->input('account_id');
            $invoice->party = $partyType;
            $invoice->creditor_id = ($partyType === 'creditor') ? $party->id : null;
            $invoice->member_id = ($partyType === 'debtor') ? $party->id : null;
            $invoice->created_by = $request->input('created_by');
            $invoice->updated_by = $request->input('updated_by');
            $invoice->save();

            if ($partyType === 'debtor') {
                // Create a record in member_payments
                $payment = new MemberPayment();
                $payment->member_id = $party->id;
                $payment->account_id = $request->input('account_id');
                $payment->amount = $invoice->amount;
                $payment->transaction_type = 'invoice'; // Or another appropriate type
                $payment->name = 'Invoice #' . $invoice->invoice_number;
                $payment->t_date = $invoice->invoice_date;
                $payment->created_by = $request->input('created_by');
                $payment->updated_by = $request->input('updated_by');

                // Calculate the balance
                $previousPayment = MemberPayment::where('member_id', $party->id)
                    ->where('account_id', $request->input('account_id')) // Add account_id filter
                    ->orderBy('id', 'desc')
                    ->first();

                $previousBalance = $previousPayment ? $previousPayment->balance : 0;
                $payment->balance = $previousBalance + $invoice->amount;
                $payment->save();
            }

            DB::commit();

            return redirect()->route('invoices.index')->with('success-notification', 'Invoice created!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error-notification', $e->getMessage() . ' Error creating invoice. Please try again.');
        }
    }

    private function generateUniqueInvoiceNumber()
    {
        $prefix = 'INV';
        return $prefix . '-' . Str::uuid(); // Use UUIDs
        //Or the below solution if UUIDs are not desirable.
        /*
        $prefix = 'INV';
        $randomNumber = mt_rand(100000, 999999);
        $timestamp = time();
        $invoiceNumber = $prefix . '-' . $randomNumber . '-' . $timestamp;

        $existingInvoice = Invoice::where('invoice_number', $invoiceNumber)->first();

        if ($existingInvoice) {
            return $this->generateUniqueInvoiceNumber();
        }

        return $invoiceNumber;
        */
    }

    public function show(Invoice $invoice)
    {
        $cpage = 'finances';
        return view('invoices.show', compact('cpage', 'invoice'));
    }
    public function destroy(Invoice $invoice)
    {
        // Authorization check (highly recommended) - Add this back!
        // if (Gate::denies('delete-invoice', $invoice)) {
        //     abort(403);
        // }

        DB::beginTransaction();

        try {
            $amount = $invoice->amount;
            $partyType = $invoice->party; // Get the party type from the invoice

            if ($partyType === 'creditor') {
                $statement = CreditorStatement::where('creditor_invoice_id', $invoice->id)->first();
                if ($statement) {
                    $statement->delete();

                    // Update balances of subsequent creditor statements
                    CreditorStatement::where('creditor_id', $invoice->creditor_id) // Use $invoice->creditor_id
                    ->where('created_at', '>', $statement->created_at)
                        ->update(['balance' => DB::raw('balance - ' . $amount)]);
                }
            } elseif ($partyType === 'debtor') {
                $statement = DebtorStatement::where('debtor_invoice_id', $invoice->id)->first();
                if ($statement) {
                    $statement->delete();

                    // Update balances of subsequent debtor statements
                    DebtorStatement::where('debtor_id', $invoice->debtor_id) // Use $invoice->debtor_id
                    ->where('created_at', '>', $statement->created_at)
                        ->update(['balance' => DB::raw('balance - ' . $amount)]);
                }
            } else {
                // Handle cases where $invoice->party is neither 'creditor' nor 'debtor'
                DB::rollBack();
                return back()->with('error-notification', 'Invalid party type associated with this invoice.');
            }

            $invoice->delete();

            DB::commit();

            // Redirect to the correct index route (no need for $creditor or $debtor)
            return redirect()->route('invoices.index')->with('success-notification', 'Invoice deleted!');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error-notification', $e->getMessage() . ' Error deleting invoice. Please try again.');
        }
    }}
