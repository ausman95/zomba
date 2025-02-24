<?php

namespace App\Http\Controllers;

use App\Models\Creditor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule for unique validation

class CreditorController extends Controller
{
    /**
     * Display a listing of the creditors.
     */
    public function index()
    {
        $cpage = 'finances';
        $creditors = Creditor::with('latestStatement')->get();
        return view('creditors.index', compact('cpage','creditors')); // Create a view named index.blade.php
    }

    /**
     * Show the form for creating a new creditor.
     */
    public function create()
    {
        $cpage = 'finances';
        return view('creditors.create',compact('cpage') ); // Create a view named create.blade.php
    }

    /**
     * Store a newly created creditor in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20', // Adjust max length as needed
            'address' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', Rule::unique('creditors', 'email')], // Unique email validation
            'created_by' => 'nullable|exists:users,id', // Validate created_by exists in users table
            'updated_by' => 'nullable|exists:users,id', // Validate updated_by exists in users table

        ]);

        Creditor::create($validatedData);

        return redirect()->route('creditors.index')->with('success-notification', 'Creditor created successfully.');
    }

    /**
     * Display the specified creditor.
     */
    public function show(Creditor $creditor)
    {
        $cpage = 'finances';
        return view('creditors.show', compact('cpage','creditor')); // Create a view named show.blade.php
    }

    /**
     * Show the form for editing the specified creditor.
     */
    public function edit(Creditor $creditor)
    {
        $cpage = 'finances';
        return view('creditors.edit', compact('cpage','creditor')); // Create a view named edit.blade.php
    }

    /**
     * Update the specified creditor in storage.
     */
    public function update(Request $request, Creditor $creditor)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', Rule::unique('creditors', 'email')->ignore($creditor->id)], // Ignore current creditor's email
            'updated_by' => 'nullable|exists:users,id', // Validate updated_by exists in users table
        ]);

        $creditor->update($validatedData);

        return redirect()->route('creditors.index')->with('success-notification', 'Creditor updated successfully.');
    }

    /**
     * Remove the specified creditor from storage.
     */
    public function destroy(Creditor $creditor)
    {
        $creditor->delete();

        return redirect()->route('creditors.index')->with('success-notification', 'Creditor deleted successfully.');
    }
}
