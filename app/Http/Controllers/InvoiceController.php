<?php

namespace App\Http\Controllers;

use App\Models\Creditor;
use App\Models\CreditorInvoice;
use App\Models\CreditorStatement;
use App\Models\Debtor;
use App\Models\DebtorStatement;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $debtors = Debtor::all();     // Fetch all debtors

        // Combine creditors and debtors into a single collection
        $cpage = 'finances';

        $accounts = \App\Models\Accounts::all(); // Fetch all accounts

        return view('invoices.create', compact('debtors','cpage','creditors', 'accounts'));
    }

    public function store(Request $request)
    {
        $partyType = $request->input('party');

        $rules = [
            'invoice_number' => 'required',
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
            $statementModel = new CreditorStatement();
        } elseif ($partyType === 'debtor') {
            $rules['debtor_id'] = 'required|exists:debtors,id';
            $request->validate($rules);

            $party = Debtor::find($request->input('debtor_id'));
            $statementModel = new DebtorStatement();
        } else {
            return back()->with('error-notification', 'Invalid party type.');
        }

        $request->validate([
            'invoice_number' => 'unique:invoices,invoice_number', // Unique rule for the INVOICES table
        ]);

        DB::beginTransaction();

        try {
            // Create Invoice Record (in the 'invoices' table)
            $invoice = new Invoice();
            $invoice->invoice_number = $request->input('invoice_number');
            $invoice->invoice_date = $request->input('invoice_date');
            $invoice->amount = $request->input('amount');
            $invoice->description = $request->input('description');
            $invoice->account_id = $request->input('account_id');
            $invoice->party = $partyType; // Store the party type
            $invoice->creditor_id = ($partyType === 'creditor') ? $party->id : null; // Conditional creditor_id
            $invoice->debtor_id = ($partyType === 'debtor') ? $party->id : null;   // Conditional debtor_id
            $invoice->created_by = $request->input('created_by');
            $invoice->updated_by = $request->input('updated_by');
            $invoice->save();

            $statement = $statementModel;
            $statement->{$partyType . '_id'} = $party->id;
            $statement->account_id = $request->input('account_id');
            $statement->{$partyType . '_invoice_id'} = $invoice->id; // Use the new $invoice->id
            $statement->amount = $invoice->amount;
            $statement->type = 'invoice';
            $statement->description = 'Invoice #' . $invoice->invoice_number;
            $statement->created_by = $request->input('created_by');
            $statement->updated_by = $request->input('updated_by');

            $previousStatement = $statementModel::where($partyType . '_id', $party->id)
                ->orderBy('id', 'desc')
                ->first();

            $previousBalance = $previousStatement ? $previousStatement->balance : 0;
            $statement->balance = $previousBalance + $invoice->amount;
            $statement->save();

            DB::commit();

            return redirect()->route('invoices.index')->with('success-notification', 'Invoice created!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error-notification', $e->getMessage() . ' Error creating invoice. Please try again.');
        }
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
