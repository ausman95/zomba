@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Departments
        </h4>
        <p>
            Departments information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation!='clerk')
                    <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{$department->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">

                <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped"
                                               id="data-table">
                                            <caption
                                                style=" caption-side: top; text-align: center">{{$department->name}}
                                                DEPARTMENT
                                            </caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{$department->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$department->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$department->updated_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($department->soft_delete==1)
                                                        <p style="color: red">Deleted, and Reserved for Audit</p>
                                                    @else
                                                        Active
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        @if(request()->user()->designation!='clerk')
                                            <div>
                                                <a href="{{route('departments.edit',$department->id)}}"
                                                   class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{route('departments.destroy',$department->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{$department->id}}">
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                        <div class="col-sm-12 mt-2 mb-2 col-md-3 col-lg-4">--}}
{{--                            <div class="card h-100">--}}
{{--                                <div class="card-body">--}}
{{--                                    <header>Attendance & Reports</header>--}}
{{--                                    <div class="my-2">--}}
{{--                                        <small class="my-4">--}}
{{--                                            View attendance report for members of this project / Site on a specific day--}}
{{--                                        </small>--}}
{{--                                    </div>--}}

{{--                                    <form action="{{route('attendance.report',$department->id)}}" method="GET">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <input type="date" class="form-control  @error('date') is-invalid @enderror" name="date">--}}
{{--                                            @error('date')--}}
{{--                                            <span class="invalid-feedback">--}}
{{--                                                {{$message}}--}}
{{--                                            </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div>--}}
{{--                                            <button class="btn btn-outline-secondary">--}}
{{--                                                Go &rarr;--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                                <div class="card-footer">--}}
{{--                                    <a href="{{route('attendance.record',$department->id)}}"--}}
{{--                                       class="btn btn-primary rounded-0">--}}
{{--                                        Record Attendance--}}
{{--                                    </a>--}}
{{--                                    <a href="{{route('attendance.view',$department->id)}}"--}}
{{--                                       class="btn btn-primary rounded-0">--}}
{{--                                        View Attendance--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Employees
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="card " style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if($labourers->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no  Employees!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table
                                                class="table1 table table-bordered table-hover table-striped"
                                                id="data-table">
                                                <caption style=" caption-side: top; text-align: center">EMPLOYEES UNDER
                                                    THIS DEPARTMENT
                                                </caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NAME</th>
                                                    <th>PROFESSIONAL</th>
                                                    <th>EMPLOYMENT TYPE</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $c = 1; ?>
                                                @foreach($labourers as $labourer)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{$labourer->name}}</td>
                                                        <td>{{$labourer->labour->name}}</td>
                                                        <td>
                                                            @if($labourer->type==1)
                                                                {{'Employed'}}
                                                            @elseif($labourer->type==2)
                                                                {{'Sub-Contactor'}}
                                                            @else
                                                                {{'Temporary Workers'}}
                                                            @endif
                                                        </td>
                                                        <td class="pt-1">
                                                            @if(request()->user()->designation==='administrator')
                                                            <a href="{{route('labourers.show',$labourer->id)}}"
                                                               class="btn btn-md btn-primary rounded-0">
                                                                <i class="fa fa-list-ol"></i>   Manage
                                                            </a>
                                                            @endif
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
                    @if(request()->user()->designation==='accountant' || request()->user()->designation==='project' || request()->user()->designation==='administrator')
                        <div class="col-sm-12 mb-2 col-md-12">
                            <div class="mt-5">
                                <h5>
                                    <i class="fa fa-microscope"></i>Department Transactions
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        @if($incomes->count() === 0)
                                            <i class="fa fa-info-circle"></i>There are no Transactions!
                                        @else
                                            <div style="overflow-x:auto;">
                                                <table class="table  table2 table-bordered table-striped" id="incomes-table">
                                                    <caption style=" caption-side: top; text-align: center">PROJECT TRANSACTIONS</caption>
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
                                                    @foreach($incomes as $income)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($income->payment_name) }}</td>
                                                            <th>
                                                                @if($income->payment_type == 1)
                                                                    {{number_format($income->amount) }}
                                                                @elseif($income->payment_type == 2)
                                                                    ({{number_format($income->amount) }})
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if($income->balance<0)
                                                                    ({{number_format($income->balance*-1) }})
                                                                @else
                                                                    {{number_format($income->balance) }}
                                                                @endif

                                                            </th>
                                                            <td>{{ucwords($income->created_at) }}</td>
                                                            <td>{{ucwords($income->payment_type == 1 ? "CR" : "DR") }}</td>
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
                    @endif
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
