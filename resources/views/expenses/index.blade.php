@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Expenditures
        </h4>
        <p>
            Manage Expenditures
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Expenditures</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('expenses.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Expense
            </a>
            <a href="{{route('purchases.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-list-ol"></i>Purchases
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($expenses->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Expenses!
                                @else
                                    <div style="overflow-x:auto;">
                                    <table class="table table-borderless table-striped" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Amount (MK)</th>
                                            <th>Account</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($expenses as $expense)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($expense->name) }}</td>
                                                <td>{{number_format($expense->amount) }}</td>
                                                <td>{{ucwords($expense->accounts->name) }}</td>
                                                <td>
                                                    @if($expense->type==2)
                                                        Admin
                                                    @elseif($expense->type==1)
                                                        Project
                                                    @elseif($expense->type==3)
                                                        Suppliers
                                                    @elseif($expense->type==4)
                                                        Labourer/Sub-contractor
                                                    @endif
                                                </td>
                                                <td>{{ucwords($expense->created_at) }}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('expenses.show',$expense->id)}}"
                                                       class="btn btn-md btn-outline-success">
                                                      <i class="fa fa-list-ol"></i>  Details
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
