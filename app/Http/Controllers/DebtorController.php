<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\MemberPayment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DebtorController extends Controller
{
    public function index()
    {
        // Fetch members with calculated balances
        $members = MemberPayment::where('transaction_type', 0)
            ->distinct('member_id')
            ->pluck('member_id');

        $memberData = [];
        foreach ($members as $memberId) {
            $member = \App\Models\Member::find($memberId);

            if ($member) {
                $totalIn = MemberPayment::where('member_id', $memberId)
                    ->where('transaction_type', 0)
                    ->sum('amount');

                $totalOut = MemberPayment::where('member_id', $memberId)
                    ->where('transaction_type', 2)
                    ->sum('amount');

                $balance = $totalIn - $totalOut;

                $memberData[] = [
                    'member' => $member,
                    'balance' => $balance,
                ];
            }
        }

        // Fetch debtors for the second part of the view
        $cpage = 'finances';
        $debtors = \App\Models\Debtor::with('latestStatement')->get(); // Use the full namespace

        // Return the view with both member data and debtor data
        return view('debtors.index', compact('memberData', 'cpage', 'debtors'));
    }

    public function create()
    {
        $cpage = 'finances';
        return view('debtors.create',compact('cpage')); // Changed to debtors.create
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', Rule::unique('debtors', 'email')], // Changed to debtors table
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ]);

        Debtor::create($validatedData); // Changed to Debtor model

        return redirect()->route('debtors.index')->with('success-notification', 'Debtor created successfully.'); // Changed route and message
    }

    public function show(Debtor $debtor) // Changed to Debtor model
    {
        $cpage = 'finances';
        return view('debtors.show', compact('cpage','debtor')); // Changed to debtors.show
    }

    public function edit(Debtor $debtor) // Changed to Debtor model
    {
        $cpage = 'finances';
        return view('debtors.edit', compact('cpage','debtor')); // Changed to debtors.edit
    }

    public function update(Request $request, Debtor $debtor) // Changed to Debtor model
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', Rule::unique('debtors', 'email')->ignore($debtor->id)], // Changed to debtors table
            'updated_by' => 'nullable|exists:users,id',
        ]);

        $debtor->update($validatedData); // Changed to Debtor model

        return redirect()->route('debtors.index')->with('success-notification', 'Debtor updated successfully.'); // Changed route and message
    }

    public function destroy(Debtor $debtor) // Changed to Debtor model
    {
        $debtor->delete(); // Changed to Debtor model

        return redirect()->route('debtors.index')->with('success-notification', 'Debtor deleted successfully.'); // Changed route and message
    }
}
