@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Budgets
        </h4>
        <p>
            Bank Budgets information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('material-budgets.index')}}">Material Budgets</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$budget->material->name}}</li>
            </ol>
        </nav>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$budget->material->name}} BUDGET INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Material Name </td>
                                            <td>{{$budget->material->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{$budget->department->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Budget Quantity</td>
                                            <td>{{ number_format($budget->quantity)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$budget->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$budget->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('material-budgets.edit',$budget->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
{{--                                        @if(request()->user()->designation==='administrator')--}}
{{--                                            <div class="">--}}
{{--                                                <form action="{{route('material-budgets.destroy',$budget->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="mt-5">--}}
{{--                    <h5>--}}
{{--                        <i class="fa fa-microscope"></i>Transactions--}}
{{--                    </h5>--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            @if($budget->getAllocated($budget->account_id,--}}
{{--                                                    $budget->financial->end_date,--}}
{{--                                                    $budget->financial->start_date)->count() === 0)--}}
{{--                                <i class="fa fa-info-circle"></i>There are no Transactions!--}}
{{--                            @else--}}
{{--                                <div style="overflow-x:auto;">--}}
{{--                                <table class="table table-borderless table-striped" id="data-table">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>No</th>--}}
{{--                                        <th>Project</th>--}}
{{--                                        <th>Amount (MK)</th>--}}
{{--                                        <th>Description</th>--}}
{{--                                        <th>Date</th>--}}
{{--                                        <th>Type</th>--}}
{{--                                        <th></th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <?php  $c= 1; $balance = 0 ?>--}}
{{--                                    @foreach($budget->getAllTransactions($budget->account_id,--}}
{{--                                                    $budget->financial->end_date,--}}
{{--                                                    $budget->financial->start_date) as $transaction)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{$c++}}</td>--}}
{{--                                            <td>{{ucwords($transaction->account->name) }}</td>--}}
{{--                                            <td>{{number_format($transaction->amount) }}</td>--}}
{{--                                            <td>{{ucwords($transaction->description) }}</td>--}}
{{--                                            <td>{{ucwords($transaction->created_at) }}</td>--}}
{{--                                            <td>{{ucwords($transaction->transaction_type == 1 ? "CR" : "DR") }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        $(document).ready(function () {

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


            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
