<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans= Loan::orderBy('id','desc')->get();
        $cpage = 'human-resources';
        return view('loans.index', compact('cpage',  'loans'));
    }
}
