<?php

namespace App\Http\Controllers;

use App\Events\GeneratePayroll;
use App\Models\Banks;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Loan;
use App\Models\Month;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Ensure only 'draft' payrolls can be deleted
        if ($payroll->status !== 'Pending') {
            return back()->with('error-notification', 'Only "draft" payrolls can be deleted.');
        }

        DB::beginTransaction();
        try {
            // Payroll items will be cascade deleted if foreign key constraint is set up
            $payroll->delete();
            DB::commit();

            // Log activity
            // activity('Payrolls')
            //     ->log("Deleted Payroll ID: {$payroll->id} for Employee: {$payroll->employee->name}")
            //     ->causer(request()->user());

            return redirect()->route('payrolls.index', ['month_id' => $payroll->month_id])->with('success-notification', 'Payroll deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payroll: ' . $e->getMessage(), ['exception' => $e, 'payroll_id' => $payroll->id]);
            return back()->with('error-notification', 'An unexpected error occurred while deleting the payroll. Please try again. Details: ' . $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $selectedMonthId = $request->input('month_id');
        $payrolls = collect(); // Initialize as an empty collection
        $hasPayrollForSelectedMonth = false;
        $selectedMonth = null; // To hold the Month model if selected

        if ($selectedMonthId) {
            $selectedMonth = Month::find($selectedMonthId);
            if ($selectedMonth) {
                $payrolls = Payroll::with(['month', 'creator'])
                    ->where('month_id', $selectedMonthId)
                    ->get();
                $hasPayrollForSelectedMonth = $payrolls->isNotEmpty();
            }
        }

        $months = Month::orderBy('id', 'desc')->get();

        return view('payrolls.index', compact('payrolls', 'months', 'selectedMonthId', 'hasPayrollForSelectedMonth', 'selectedMonth'))->with('cpage', 'human-resources');
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
            'month_id'     => 'required|numeric|exists:months,id',
            'payroll_date' => 'required|date',
            'labourer_id'  => 'nullable|numeric|exists:labourers,id', // 'labourer_id' from your reference
        ]);

        $selectedMonth = Month::findOrFail($request->input('month_id'));
        $payrollDate = Carbon::parse($request->input('payroll_date'));
        $employeeId = $request->input('labourer_id');

        DB::beginTransaction();

        try {
            if ($employeeId) {
                // Generate payroll for a single employee
                $this->generatePayrollIfValid($payrollDate, $employeeId, $selectedMonth->id);
            } else {
                // Generate payroll for all employees
                $employees = Labourer::all();
                foreach ($employees as $employee) {
                    $this->generatePayrollIfValid($payrollDate, $employee->id, $selectedMonth->id);
                }
            }

            DB::commit();

            return redirect()->route('payrolls.index', ['month_id' => $selectedMonth->id])->with('success-notification', 'Payroll(s) generated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating payroll: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return back()->withInput()->with('error-notification', 'An unexpected error occurred while generating the payroll. Please try again. Details: ' . $e->getMessage());
        }
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

            $loan = Loan::where(['labourer_id' => $labourerId])
                ->where('remaining_balance', '>', 0)
                ->where('loan_status', '=', 'active')
                ->latest()->first();
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
            'updated_by' => auth()->id(),
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

            if ($item->amount < 0) {
                $type = 1;
                $description = 'Loan Monthly Repayment';

                // Handle Loan Repayment
                $loan = \App\Models\Loan::where('labourer_id', $labourer->id)
                    ->where('loan_status', 'active') // Assuming 'Active' status for ongoing loans
                    ->first();

                if ($loan) {
                    $repaymentAmount = abs($item->amount); // Convert negative to positive for repayment

                    // Update Remaining Balance
                    $loan->remaining_balance -= $repaymentAmount;

                    // Update Loan Status if Fully Repaid
                    if ($loan->remaining_balance <= 0) {
                        $loan->loan_status = 'Completed';
                        $loan->remaining_balance = 0; // Ensure it's not negative
                    }

                    $loan->save();
                }
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
