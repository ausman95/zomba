@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-file-invoice"></i> Creditor Invoices
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoices</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>

        <div class="mt-3">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-plus-circle"></i> New Invoice
            </a>

            <div class="card container-fluid" style="min-height: 30em;">
                <div class="card" style="min-height: 30em;">
                    <div class="card-body px-1">
                        @if ($invoices->count() === 0)
                            <i class="fas fa-info-circle"></i> There are no invoices for this creditor.
                        @else
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">Invoices</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Party</th>
                                        <th>Creditor/Debtor</th>
                                        <th>Created On</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1; ?>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ date('d F Y', strtotime($invoice->invoice_date)) }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ number_format($invoice->amount,2) }}</td>
                                            <td>{{ $invoice->description ?? '-' }}</td>
                                            <td>{{ $invoice->party ?? '-' }}</td>
                                            <td>@if($invoice->party==='debtor')
                                                {{ $invoice->member->name}}
                                                @else
                                                    {{ $invoice->creditor->name}}
                                                @endif
                                            </td>
                                            <td>{{ date('d F Y', strtotime($invoice->created_at)) }}</td>
                                            <td class="pt-1">
                                                    <a href="{{ route('invoices.show',$invoice->id) }}" class="btn btn-primary btn-sm rounded-0"> <i class="fas fa-list-ul"></i> Manage</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
