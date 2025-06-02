@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
    {{-- You might need a CSS for select2 if you use it for the month dropdown --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-check-alt"></i> Payrolls
        </h4>
        <p>
            Manage Monthly Payrolls
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payrolls</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            {{-- New "Create New Payroll" button --}}
            <a href="{{ route('payrolls.create') }}" class="btn btn-success btn-md rounded-0 mb-4">
                <i class="fa fa-plus-circle"></i> Create New Payroll
            </a>

            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Select Month to View Existing Payrolls
                        </div>
                        <div class="card-body">
                            <form action="{{ route('payrolls.index') }}" method="GET" class="mb-3">
                                <div class="form-group mb-3">
                                    <label for="month_id" class="form-label">Select Month</label>
                                    <select name="month_id" id="month_id"
                                            class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%" required>
                                        <option value="">-- Select Month ---</option>
                                        @foreach($months as $month)
                                            <option value="{{ $month->id }}"
                                                {{ (old('month_id', $selectedMonthId) == $month->id) ? 'selected' : '' }}>
                                                {{ $month->name }} {{ $month->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('month_id')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-md rounded-0">
                                        <i class="fa fa-eye"></i> View Payroll
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            @if($selectedMonthId)
                <h5 class="mt-4">Payroll for {{ $selectedMonth->name ?? 'N/A' }} {{ $selectedMonth->year ?? '' }}</h5>
                @if($hasPayrollForSelectedMonth)
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                                <div class="card " style="min-height: 30em;">
                                    <div class="card-body px-1">
                                        <div style="overflow-x: auto;">
                                            <table class="table table-bordered table-hover table-striped" >
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
                                                        <td>{{ date('d F Y', strtotime($payroll->payroll_date)) }}</td>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-3">
                        <i class="fa fa-info-circle"></i> No payroll has been generated for {{ $selectedMonth->name ?? 'N/A' }} {{ $selectedMonth->year ?? '' }} yet.
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-3">
                    <i class="fa fa-info-circle"></i> Please select a month from the dropdown above to view existing payrolls or generate a new one.
                </div>
            @endif
        </div>
    </div>

    <form action="" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('scripts')
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- SweetAlert2 for confirmation --}}
    {{-- You might need a JS for select2 if you use it for the month dropdown --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
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

        // Function to disable submit button to prevent double submission
        function disableSubmitButton(form) {
            const submitButton = form.querySelector('.submit-button');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            }
            return true; // Allow form submission
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Simple-DataTables only if the table exists
            const dataTableElement = document.getElementById("data-table");
            if (dataTableElement) {
                const dataTable = new simpleDatatables.DataTable(dataTableElement, {
                    searchable: true,
                    fixedheight: true,
                    perPage: 10,
                    labels: {
                        placeholder: "Search payrolls...",
                        perPage: "{select} entries per page",
                        noRows: "No payrolls found",
                        info: "Showing {start} to {end} of {rows} entries",
                        noResults: "No results match your search query",
                    },
                });
            }


            document.querySelectorAll(".delete-form").forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formElement = this;
                    confirmationWindow(
                        "Confirm Deletion",
                        "Are you sure you want to delete this payroll? This action cannot be undone.",
                        "Yes, Delete",
                        function () {
                            formElement.submit();
                        }
                    );
                });
            });

            // Initialize Select2 if you are using it
            // $('#month_id').select2({
            //     placeholder: "-- Select Month ---",
            //     allowClear: true
            // });
        });
    </script>
@stop
