@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-file-invoice"></i> Invoice Details
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoice #{{ $invoice->invoice_number }}</li>  {{-- Show invoice number --}}
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5>
                    <i class="fas fa-info-circle"></i> Invoice Information
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>Invoice Number</th>
                                <td>{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <th>Invoice Date</th>
                                <td>{{ $invoice->invoice_date }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ $invoice->amount }}</td>
                            </tr>
                            <tr>
                                <th>Party</th>
                                <td>{{ $invoice->party }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $invoice->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td>{{ date('d F Y', strtotime($invoice->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ \App\Models\User::find($invoice->created_by)?->name  ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{{ \App\Models\User::find($invoice->updated_by)?->name  ?? '-' }}</td>
                            </tr>

                            </tbody>
                        </table>

                        <div class="mt-3 d-flex gap-1"> {{-- Flexbox for buttons --}}
                            @if(request()->user()->designation == 'administrator')
                                <button class="btn btn-danger rounded-0" id="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @endif
                            <form action="{{ route('invoices.destroy',$invoice->id) }}" method="POST" id="delete-form" class="d-inline">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#delete-btn").on('click', function () {
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete this invoice?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#delete-form").submit();
                    }
                })
            });
        });
    </script>
@stop
