<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
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
        $cpage = 'human-resources'; // Add this line

        return view('loans.show', compact('loan', 'cpage')); // Pass $cpage to the view
    }
}
