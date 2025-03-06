@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-money-bill-wave"></i> Payroll Records</h4>
        <p>Manage Payroll Records</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payroll Records</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{ route('payrolls.create') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Payroll Record
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if ($payrolls->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no Payroll Records!
                                @else
                                    <div style="overflow-x: auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">PAYROLL RECORDS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>LABOURER NAME</th>
                                                <th>MONTH</th>
                                                <th>BENEFITS</th>
                                                <th>TOTAL AMOUNT</th>
                                                <th>CREATED BY</th>
                                                <th>STATUS</th>
                                                <th>ACTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = 1; @endphp
                                            @foreach ($payrolls as $payroll)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{date('d F Y', strtotime($payroll->payroll_date)) }}</td>
                                                    <td>{{ @$payroll->labourer->name }}</td>
                                                    <td>{{ @$payroll->month->name }}</td>
                                                    <td>{{ @count($payroll->payrollItems) }}</td>
                                                    <td>{{ number_format($payroll->total_amount, 2) }}</td>
                                                    <td>{{ $payroll->creator->name }}</td>
                                                    <td>{{ @$payroll->status }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{ route('payrolls.show', $payroll->id) }}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-eye"></i> View Details
                                                        </a>

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
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }

        $(document).ready(function() {
            $(".delete-btn").on('click', function() {
                $url = $(this).attr('data-target-url');
                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this payroll record?", "Yes,Delete",
                    function() {
                        $("#delete-form").submit();
                    })
            });
        })
    </script>
@stop
