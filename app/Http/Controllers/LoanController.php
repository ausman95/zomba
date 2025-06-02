<?php

namespace App\Http\Controllers;

use App\Models\LabourerPayments;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function deactivate(Loan $loan)
    {
        // Only deactivate if the loan is active
        if ($loan->loan_status === 'active') {
            try {
                $loan->loan_status = 'deactivated';
                $loan->updated_by = auth()->id();
                $loan->save();

                return redirect()->route('loans.show', $loan->id)->with('success-notification', 'Loan deactivated successfully!');
            } catch (\Exception $e) {
                Log::error("Error deactivating loan {$loan->id}: " . $e->getMessage());
                return back()->with('error-notification', 'Failed to deactivate loan: ' . $e->getMessage());
            }
        }
        return back()->with('info-notification', 'Loan is not in an active state to be deactivated.');
    }

    /**
     * Activate a deactivated or rejected loan.
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function activate(Loan $loan)
    {
        // Allow activation if the loan is 'deactivated' or 'rejected'
        if ($loan->loan_status === 'deactivated' || $loan->loan_status === 'rejected') {
            try {
                $loan->loan_status = 'active'; // Or 'pending' if it needs re-approval
                $loan->updated_by = auth()->id();
                $loan->save();

                return redirect()->route('loans.show', $loan->id)->with('success-notification', 'Loan activated successfully!');
            } catch (\Exception $e) {
                Log::error("Error activating loan {$loan->id}: " . $e->getMessage());
                return back()->with('error-notification', 'Failed to activate loan: ' . $e->getMessage());
            }
        }
        return back()->with('info-notification', 'Loan cannot be activated from its current status.');
    }
    public function updateRepayment(Request $request, Loan $loan)
    {
        $request->validate([
            'monthly_repayment' => 'required|numeric|min:0',
        ]);

        $loan->update([
            'monthly_repayment' => $request->monthly_repayment,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Monthly repayment updated successfully.');
    }
    public function index()
    {
        $loans= Loan::orderBy('id','desc')->get();
        $cpage = 'human-resources';
        return view('loans.index', compact('cpage',  'loans'));
    }
    public function show(Loan $loan)
    {
        $cpage = 'human-resources';

        // Get all loans for the same employee
        $employeeLoans = Loan::where('labourer_id', $loan->labourer_id)->get();

        // Get all loan repayments for the same employee
        $repayments = LabourerPayments::where('labourer_id', $loan->labourer_id)
            ->where('type', 'loan_repayment')
            ->get();

        return view('loans.show', compact('loan', 'cpage', 'employeeLoans', 'repayments'));
    }
}
