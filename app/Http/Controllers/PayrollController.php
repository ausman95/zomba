<?php

namespace App\Http\Controllers;

use App\Models\Banks;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Loan;
use App\Models\Month;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayrollController extends Controller
{
    public function show(Payroll $payroll)
    {
        $payroll->load(['labourer', 'month', 'payrollItems']);
        $banks = Banks::all(); // Assuming you have a Bank model
        $cpage = 'human-resources'; // Set the active page variable
        return view('payrolls.show', compact('banks', 'payroll', 'cpage')); // Pass $cpage to the view
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->payrollItems()->delete();
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success-notification', 'Payroll deleted successfully.');
    }

    public function index()
    {
        $payrolls = Payroll::with(['labourer', 'month'])->paginate(10); // Adjust pagination as needed
        $cpage = 'human-resources';
        return view('payrolls.index', compact('cpage', 'payrolls'));
    }

    public function create()
    {
        $months = Month::orderBy('id', 'DESC')->get(); // Assuming you have a Month model
        $labourers = Labourer::all();
        $cpage = 'human-resources';
        return view('payrolls.create', compact('cpage', 'months', 'labourers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_id' => 'required|exists:months,id',
            'payroll_date' => 'required',
            'labourer_id' => 'nullable|exists:labourers,id',
        ]);

        if ($request->labourer_id) {
            // Single Labourer
            $this->generatePayrollIfValid($request->payroll_date,$request->labourer_id, $request->month_id);
        } else {
            // All Labourers
            $labourers = Labourer::all();
            foreach ($labourers as $labourer) {
                $this->generatePayrollIfValid($request->payroll_date,$labourer->id, $request->month_id);
            }
        }

        return redirect()->back()->with('success-notification', 'Payroll(s) created successfully.');
    }

    private function generatePayrollIfValid($date,$labourerId, $monthId)
    {
        $labourer = Labourer::find($labourerId);

        // Skip if payroll already exists
        if (Payroll::where('labourer_id', $labourerId)->where('month_id', $monthId)->exists()) {
            Log::warning('Payroll already exists for Labourer ID ' . $labourerId . ' in Month ID ' . $monthId . '. Skipping.');
            return; // Skip to the next iteration
        }

        // Skip if labourer has no contract
        if (!$labourer->contracts()->exists()) {
            Log::warning('Labourer with ID ' . $labourerId . ' has no contract. Skipping.');
            return; // Skip to the next iteration
        }

        $this->generatePayrollForLabourer($date,$labourerId, $monthId);
    }

    private function generatePayrollForLabourer($date,$labourerId, $monthId)
    {
        $labourer = Labourer::find($labourerId);
        $contract = $labourer->contracts()->latest()->first();

        if ($contract) {
            $benefits = $contract->benefits()->get();

            $loan = Loan::where(['labourer_id' => $labourerId])->where('remaining_balance', '>', 0)->latest()->first();
            $loanRepayment = 0;
            $loanAccountId = null;

            if ($loan) {
                $loanRepayment = $loan->monthly_repayment;
                $loanAccountId = $loan->account_id;
            }

            $payroll = Payroll::create([
                'labourer_id' => $labourerId,
                'month_id' => $monthId,
                'payroll_date'=>$date,
                'created_by'=> auth()->id(),
               'updated_by'=> auth()->id(),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($benefits as $benefit) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'account_id' => $benefit->account->id,
                    'amount' => $benefit->amount,
                    'type' => 'benefit',
                ]);


                $totalAmount += $benefit->amount;
            }

            if ($loanRepayment > 0) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'account_id' => $loanAccountId,
                    'amount' => -$loanRepayment,
                    'type' => 'loan_repayment',
                ]);
                $totalAmount -= $loanRepayment;
            }

            $payroll->update(['total_amount' => $totalAmount]);
        } else {
            Log::warning('Labourer with ID ' . $labourerId . ' has no contract.');
            Payroll::create([
                'labourer_id' => $labourerId,
                'month_id' => $monthId,
                'total_amount' => 0,
            ]);
        }
    }

    private function createLabourerPayment(
        $amount,
        $expenseName,
        $type,
        $labourerId,
        $accountId,
        $projectId,
        $method,
        $description,
        $createdBy,
        $updatedBy,
        $date
    )
    {
        try {
            LabourerPayments::create([
                'amount' => $amount,
                'expense_name' => $expenseName,
                'type' => $type,
                'labourer_id' => $labourerId,
                'account_id' => $accountId,
                'project_id' => $projectId,
                'method' => $method,
                'description' => $description,
                'created_by' => $createdBy,
                'updated_by' => $updatedBy,
                'date' => $date,
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating LabourerPayment: ' . $e->getMessage());

            // Throw a custom exception
            throw new \Exception('Failed to create Labourer Payment. ' . $e->getMessage() . '. Please check the logs.');
        }
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'reference' => 'required',
            'bank_id' => 'required|exists:banks,id',
        ]);

        $payroll->update([
            'status' => 'Approved',
            'reference' => $request->reference,
            'updated_by'=> auth()->id(),
            'bank_id' => $request->bank_id,
        ]);

        // Get all payroll items for the payroll
        $payrollItems = $payroll->payrollItems;

        // Get the labourer from the payroll
        $labourer = $payroll->labourer;

        // Create LabourerPayment records for each payroll item
        $type = 2;
        foreach ($payrollItems as $item) {
            $this->createLabourerPayment(
                $item->amount,
                $item->account->name . ' ' . $labourer->name,
                $item->type,
                $labourer->id,
                $item->account_id,
                5,
                3,
                1,
                auth()->id(),
                auth()->id(),
                $payroll->payroll_date
            );
            $description = $item->account->name . ' ' . $labourer->name;
            if($item->amount<0){
                $type = 1;
                $description = 'Loan Monthly Repayment';
            }
            // Create Payment record for each payroll item
            $this->createPaymentRecord(
                $item->account_id,
                $item->amount,
                $description,
                $payroll->payroll_date,
                $payroll->month_id,
                auth()->id(),
                auth()->id(),
                'Payroll Payment', // Specification
                $request->bank_id,
                $type,
                3, // Payment Method
                $request->reference
            );
        }

        return redirect()->back()->with('success-notification', 'Payroll approved, Labourer Payments and Payment records created successfully.');
    }


    private function createPaymentRecord(
        $accountId,
        $amount,
        $transactionsName,
        $tDate,
        $monthId,
        $createdBy,
        $updatedBy,
        $specification,
        $bankId,
        $type,
        $paymentMethod,
        $reference
    ) {
        $rawData = [
            'account_id' => $accountId,
            'amount' => $amount,
            'name' => $transactionsName,
            't_date' => $tDate,
            'month_id' => $monthId,
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
            'specification' => $specification,
            'bank_id' => $bankId,
            'type' => $type,
            'payment_method' => $paymentMethod,
            'reference' => $reference,
        ];

        if (Payment::where($rawData)->exists()) {
            throw new \Exception("You have already created this transaction today. Check the transaction properly.");
        }

        Payment::create($rawData);
    }
}
