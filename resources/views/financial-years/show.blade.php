@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-calendar-check"></i>Financial Years
        </h4>
        <p>
            Manage Financial Year
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item"><a href="{{route('financial-years.index')}}">Financial Years</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$financial_year->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$financial_year->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$financial_year->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>{{$financial_year->status==1 ? 'ACTIVE' : ' PREVIOUS'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Start Date</td>
                                            <td>{{$financial_year->start_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>End Date</td>
                                            <td>{{$financial_year->end_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$financial_year->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$financial_year->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('financial-years.edit',$financial_year->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
{{--                                        <div class="">--}}
{{--                                            @if( request()->user()->designation==='administrator')--}}
{{--                                                <form action="{{route('financial-years.destroy',$financial_year->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger rounded-0" id="delete-btn" style="margin: 2px">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="mt-5">--}}
{{--                    <h5>--}}
{{--                        <i class="fa fa-microscope"></i>Budget Allocated--}}
{{--                    </h5>--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            @if($transactions->count() === 0)--}}
{{--                                <i class="fa fa-info-circle"></i>There are no Transactions!--}}
{{--                            @else--}}
{{--                            <table class="table table-borderless table-striped" id="data-table">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>No</th>--}}
{{--                                    <th>Project</th>--}}
{{--                                    <th>Amount</th>--}}
{{--                                    <th>Description</th>--}}
{{--                                    <th>Date</th>--}}
{{--                                    <th>Type</th>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <?php  $c= 1; $balance = 0 ?>--}}
{{--                                @foreach($transactions as $transfer)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{$c++}}</td>--}}
{{--                                        <td>{{ucwords($transfer->project->name) }}</td>--}}
{{--                                        <td>{{number_format($transfer->amount) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->description) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->created_at) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->transaction_type == 1 ? "CR" : "DR") }}</td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                                @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@stop

@section('scripts')
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
