@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-file-contract"></i> Staffs Loans</h4>
        <p>Manage Staffs Loans</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Staffs Loans</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('loans.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> Apply for New Loan
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if ($loans->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no STAFF Loans!
                                @else
                                    <div style="overflow-x: auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style="caption-side: top; text-align: center">STAFF LOANS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>STAFF NAME</th>
                                                <th>LOAN AMOUNT</th>
                                                <th>START DATE</th>
                                                <th>DURATION (MONTHS)</th>
                                                <th>MONTHLY REPAYMENT</th>
                                                <th>REMAINING BALANCE</th>
                                                <th>LOAN STATUS</th>
                                                <th>ACTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = 1; @endphp
                                            @foreach ($loans as $loan)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{ @$loan->employee->name }}</td> {{-- Changed from labourer to employee --}}
                                                    <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                    <td>{{ date('d F Y', strtotime($loan->start_date)) }}</td> {{-- Changed from loan_start_date to start_date --}}
                                                    <td>{{ $loan->loan_term_months }}</td> {{-- Changed from loan_duration_months to loan_term_months --}}
                                                    <td>{{ number_format($loan->monthly_installment, 2) }}</td> {{-- Changed from monthly_repayment to monthly_installment --}}
                                                    <td>{{ number_format($loan->balance_outstanding, 2) }}</td> {{-- Changed from remaining_balance to balance_outstanding --}}
                                                    <td>{{ $loan->status }}</td> {{-- Changed from loan_status to status --}}
                                                    <td class="pt-1">
                                                        <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-eye"></i> View Details
                                                        </a>
                                                        {{-- Add Edit and Delete buttons if applicable --}}
                                                        {{--
                                                        <a href="{{route('loans.edit',$loan->id)}}"
                                                           class="btn btn-info btn-md rounded-0 mt-1">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{route('loans.destroy',$loan->id)}}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-md rounded-0 mt-1">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                        --}}
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

    <form action="" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('scripts')
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- SweetAlert2 for confirmation --}}
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

        document.addEventListener('DOMContentLoaded', function () {
            const dataTable = new simpleDatatables.DataTable("#data-table", {
                searchable: true,
                fixedheight: true,
                perPage: 10,
                labels: {
                    placeholder: "Search loans...",
                    perPage: "{select} entries per page",
                    noRows: "No loan applications found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query",
                },
            });

            document.querySelectorAll(".delete-form").forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formElement = this;
                    confirmationWindow(
                        "Confirm Deletion",
                        "Are you sure you want to delete this loan application? This action cannot be undone.",
                        "Yes, Delete",
                        function () {
                            formElement.submit();
                        }
                    );
                });
            });

            // Original delete button logic if it's still used elsewhere
            $(".delete-btn").on('click', function() {
                $url = $(this).attr('data-target-url');
                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete",
                    function() {
                        $("#delete-form").submit();
                    })
            });
        });
    </script>
@stop
