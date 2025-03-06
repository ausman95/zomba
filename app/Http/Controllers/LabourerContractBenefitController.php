<?php

namespace App\Http\Controllers;

use App\Models\LabourerContractBenefit;
use Illuminate\Http\Request;

class LabourerContractBenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // You might not need this for this specific scenario
        // If you need to list all benefits, implement it here
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // The modal handles creation, so this might not be needed
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'labourer_contract_id' => 'required|exists:labourer_contracts,id',
        ]);

        LabourerContractBenefit::create($request->all());

        return redirect()->back()->with('success-notification', 'Benefit added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Not used in this context, as benefits are shown within the contract's show view
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // The modal handles editing, so this might not be needed
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LabourerContractBenefit $labourerContractBenefit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LabourerContractBenefit $labourerContractBenefit)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
        ]);

        $labourerContractBenefit->update($request->all());

        return redirect()->back()->with('success-notification', 'Benefit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LabourerContractBenefit  $labourerContractBenefit
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabourerContractBenefit $labourerContractBenefit)
    {
        $labourerContractBenefit->delete();
        return redirect()->back()->with('success-notification', 'Benefit deleted successfully.');
    }
}
