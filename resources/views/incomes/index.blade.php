@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Payments
        </h4>
        <p>
            Manage Payments
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payments</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('incomes.create')}}" class="btn btn-outline-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Payment
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($incomes->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Payment!
                                @else
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Project Name</th>
                                            <th>Account Name</th>
                                            <th>Amount (MK)</th>
                                            <th>Description</th>
                                            <th>Payment Type</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($incomes as $income)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($income->project->name) }}</td>
                                                <td>{{ucwords($income->account->name) }}</td>
                                                <td>{{number_format($income->amount) }}</td>
                                                <td>{{ucwords($income->description) }}</td>
                                                <td>{{ucwords($income->transaction_type == 1 ? "CR" : "DR") }}</td>

                                                <td class="pt-1">
                                                    <a href="{{route('incomes.show',$income->id)}}"
                                                       class="btn btn-md btn-outline-success">
                                                        <i class="fa fa-list-ol"></i>   Manage
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
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


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });

            const myTable = document.querySelector("#data-table");
            const dataTable = new simpleDatatables.DataTable(myTable, {
                layout: {
                    top: "{search}",
                    bottom: "{pager}{info}"
                },
                header: true
            });
        })
    </script>
@stop

