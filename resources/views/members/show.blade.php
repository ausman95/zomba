@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bxs-city "></i>&nbsp; Church Members
        </h4>
        <p>
            Members
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$member->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            {{--            <div class="mb-2">--}}
            {{--                <a href="{{route('requisitions.projects.index',$project->id)}}" class="btn btn-primary btn-md rounded-0">--}}
            {{--                    <i class="fa fa-list-ol"></i>Requisitions--}}
            {{--                </a>--}}
            {{--            </div>--}}
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$member->name}} INFORMATION</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{$member->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone Number</td>
                                                <td>{{($member->phone_number)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Ministry</td>
                                                <td>{{$member->ministry->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Home Church</td>
                                                <td>{{$member->church->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>  @if($member->status==1)
                                                        {{'ACTIVE'}}
                                                    @elseif($member->status==2)
                                                        {{'MOVED'}}
                                                    @else
                                                        {{'DECEASED'}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Start Date</td>
                                                <td>{{$member->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated Date</td>
                                                <td>{{$member->updated_at}}</td>
                                            </tr>
                                        </table>
                                        <div class="mt-3">
                                            <div>
                                                <a href="{{route('members.edit',$member->id)}}"
                                                   class="btn btn-primary rounded-0" style="margin: 2px">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                            </div>
                                            <div class="">
                                                {{--                                                @if( request()->user()->designation==='administrator')--}}
                                                {{--                                                    <form action="{{route('projects.destroy',$project->id)}}" method="POST" id="delete-form">--}}
                                                {{--                                                        @csrf--}}
                                                {{--                                                        <input type="hidden" name="_method" value="DELETE">--}}
                                                {{--                                                    </form>--}}
                                                {{--                                                    <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
                                                {{--                                                        <i class="fa fa-trash"></i>Delete--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                @endif--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{--            <div class="row">--}}
            {{--                @if(request()->user()->designation==='hr' || request()->user()->designation==='accountant' || request()->user()->designation==='project' || request()->user()->designation==='administrator')--}}
            {{--                <div class="col-sm-12 mb-2 col-md-12">--}}
            {{--                    <div class="mt-5">--}}
            {{--                        <h5>--}}
            {{--                            <i class="fa fa-microscope"></i>Workers Allocated--}}
            {{--                        </h5>--}}
            {{--                        <div class="card">--}}
            {{--                            <div class="card-body">--}}
            {{--                                @if($members->count() === 0)--}}
            {{--                                    <i class="fa fa-info-circle"></i>There are no  Labourers!--}}
            {{--                                @else--}}
            {{--                                    <div style="overflow-x:auto;">--}}
            {{--                                    <table class="table table-primary  table1 table-bordered table-striped" id="workers-table">--}}
            {{--                                        <caption style=" caption-side: top; text-align: center">LABOURERS</caption>--}}
            {{--                                        <thead>--}}
            {{--                                        <tr>--}}
            {{--                                            <th>NO</th>--}}
            {{--                                            <th>NAME</th>--}}
            {{--                                            <th>GENDER</th>--}}
            {{--                                            <th>PHONE</th>--}}
            {{--                                            <th>LOCATION</th>--}}
            {{--                                            <th>SERVICE</th>--}}
            {{--                                            <th>TYPE</th>--}}
            {{--                                        </tr>--}}
            {{--                                        </thead>--}}
            {{--                                        <tbody>--}}
            {{--                                        <?php  $c = 1;?>--}}
            {{--                                        @foreach($members as $labourer_alloc)--}}
            {{--                                            <tr>--}}
            {{--                                                <td>{{$c++}}</td>--}}
            {{--                                                <td>{{$labourer_alloc->labourer->name}}</td>--}}
            {{--                                                <td>{{$labourer_alloc->labourer->gender}}</td>--}}
            {{--                                                <td>{{$labourer_alloc->labourer->phone_number}}</td>--}}
            {{--                                                <td>{{$labourer_alloc->labourer->address}}</td>--}}
            {{--                                                <td>{{$labourer_alloc->labourer->service->name}}</td>--}}
            {{--                                                <td>--}}
            {{--                                                    @if($labourer_alloc->labourer->type==1)--}}
            {{--                                                        {{'Employed'}}--}}
            {{--                                                    @elseif($labourer_alloc->labourer->type==2)--}}
            {{--                                                        {{'Sub-Contactor'}}--}}
            {{--                                                    @else--}}
            {{--                                                        {--}}
            {{--                                                        {{'Temporary Workers'}}--}}
            {{--                                                    @endif--}}
            {{--                                                </td>--}}
            {{--                                            </tr>--}}
            {{--                                        @endforeach--}}
            {{--                                        </tbody>--}}
            {{--                                    </table>--}}
            {{--                                    </div>--}}
            {{--                                @endif--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--                @endif--}}
            {{--                @if(request()->user()->designation==='accountant' || request()->user()->designation==='project' || request()->user()->designation==='administrator')--}}
                            <div class="col-sm-12 mb-2 col-md-12">
                                <div class="mt-5">
                                    <h5>
                                        <i class="fa fa-microscope"></i>Member Transactions
                                    </h5>
                                    <div class="card">
                                        <div class="card-body">
                                            @if($transactions->count() === 0)
                                                <i class="fa fa-info-circle"></i>There are no Transactions!
                                            @else
                                                <div style="overflow-x:auto;">
                                                <table class="table table2 table-bordered table-striped" id="incomes-table">
                                                    <caption style=" caption-side: top; text-align: center">MEMBER TRANSACTIONS</caption>
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>TRANSACTION NAME</th>
                                                        <th>AMOUNT (MK)</th>
                                                        <th>BALANCE (MK)</th>
                                                        <th>DATE</th>
                                                        <th>PAYMENT TYPE</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  $c = 1; $balance = 0;?>
                                                    @foreach($transactions as $transaction)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($transaction->name) }}</td>
                                                            <th>
                                                                @if($transaction->transaction_type == 1)
                                                                    {{number_format($transaction->amount) }}
                                                                @elseif($transaction->transaction_type == 2)
                                                                    ({{number_format($transaction->amount) }})
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if($transaction->balance<0)
                                                                ({{number_format($transaction->balance*-1) }})
                                                                @else
                                                                    {{number_format($transaction->balance) }}
                                                                @endif

                                                            </th>
                                                            <td>{{ucwords($transaction->created_at) }}</td>
                                                            <td>{{ucwords($transaction->transaction_type == 1 ? "CR" : "DR") }}</td>
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
