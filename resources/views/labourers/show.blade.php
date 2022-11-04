@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Labourer
        </h4>
        <p>
            Manage labourer information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$labourer->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body election-banner-card p-1">
                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <a href="{{route('labourer.allocate')."?id=$labourer->id"}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                <i class="fa fa-list-ol"></i>Allocate
                            </a>
                        </div>
                        <div>
                            <a href="{{route('labourers.edit',$labourer->id)}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                <i class="fa fa-edit"></i>Update
                            </a>
                        </div>
{{--                        <div class="">--}}
{{--                            <form action="{{route('labourers.destroy',$labourer->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-6">
                            <h5>
                                <i class="fa fa-microscope"></i>Personal Information
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$labourer->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$labourer->name}}</td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{$labourer->gender}}</td>
                                        </tr>

                                        <tr>
                                            <td>Phone Number</td>
                                            <td>{{$labourer->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Professional </td>
                                            <td>{{$labourer->labour->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{$labourer->department->name}}</td>
                                        </tr>

                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($labourer->type==1)
                                                    {{'Employed'}}
                                                @elseif($labourer->type==2)
                                                    {{'Sub-Contactor'}}
                                                @else{
                                                {{'Temporary Workers'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$labourer->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated On</td>
                                            <td>{{$labourer->updated_at}}</td>
                                        </tr>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7 col-lg-6">
                            <h5>
                                <i class="fa fa-microscope"></i>Projects Allocated
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @if($allocations->count() === 0)
                                            <i class="fa fa-info-circle"></i>There are no Project allocations!
                                        @else
                                            <div style="overflow-x:auto;">
                                                <table class="table table1  table-bordered table-hover table-striped" id="project-table">
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>PROJECT NAME</th>
                                                        <th>
                                                            {{--                                                        @if($labourer->type==2)--}}
                                                            {{--                                                            Amount Agreed (MK)/ Project--}}
                                                            {{--                                                        @else--}}
                                                            {{--                                                           Expectd Amount Agreed (MK)/ Month--}}
                                                            {{--                                                        @endif--}}
                                                            AMOUNT (MK)
                                                        </th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $sum = 0; $c = 1;?>
                                                    @foreach($allocations as $allocation)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($allocation->project->name) }}</td>
                                                            @if($labourer->type==2)
                                                                <td>{{number_format($allocation->amount)}}</td>
                                                                <td>{{number_format($sum = $sum+$allocation->amount)}}</td>
                                                            @else
                                                                <td>{{number_format($allocation->getProjectMonth($allocation->project_id)*$allocation->amount)}}</td>
                                                                <td>{{number_format($sum = $sum+$allocation->getProjectMonth($allocation->project_id)*$allocation->amount)}}</td>
                                                            @endif
                                                            <td class="pt-1">
                                                                <form action="{{route('allocations.destroy',$allocation->id)}}" method="POST" id="delete-forms">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                </form>
                                                                <button class="btn  btn-danger btn-md rounded-0" id="delete-bt">
                                                                    <i class="fa fa-trash"></i>Remove
                                                                </button>
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
            <div class="mt-5">
                <h5>
                    <i class="fa fa-microscope"></i>Transactions
                </h5>
                <div class="card">
                    <div class="card-body">
                        @if($payments->count() === 0)
                            <i class="fa fa-info-circle"></i>There are no  Transactions!
                        @else
                            <div style="overflow-x:auto;">
<<<<<<< HEAD
                            <table class="table  table2 table-bordered table-hover table-striped" id="data-table">
                                <caption style=" caption-side: top; text-align: center">TRANSACTIONS</caption>
=======
                            <table class="table table-primary table2 table-bordered table-hover table-striped" id="data-table">
                                <caption style=" caption-side: top; text-align: center">{{$labourer->name}} TRANSACTIONS</caption>
>>>>>>> 2cf49c8b454683e000a57590879039877e9292c6
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DESCRIPTION</th>
                                    <th>AMOUNT (MK)</th>
                                    <th>BALANCE (MK)</th>
                                    <th>DATE</th>
                                    <th>TYPE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c= 1; $balance = 0; ?>
                                @foreach($payments as $payment)
                                    @if($payment->amount!=0)
                                    <tr>
                                        <td>{{$c++}}</td>
                                        <td>{{$payment->expense_name}}</td>
                                        <th>
                                            @if($payment->type==2)
                                               ( {{number_format($payment->amount)}})
                                            @else
                                                {{number_format($payment->amount)}}
                                            @endif
                                        </th>
                                        <th>
                                            @if($payment->balance<0)
                                            ({{number_format($payment->balance*-1)}})
                                            @else
                                            {{number_format($payment->balance)}}
                                            @endif
                                        </th>
                                        <td>{{$payment->created_at}}</td>
                                        <td>{{$payment->type==1 ? "Dr" : "Cr"}}</td>
                                    </tr>
                                    @endif
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record ?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
            $("#delete-bt").on('click', function () {
                confirmationWindow("Confirm De~Allocation", "Are you sure you want to Remove this Labourer?", "Yes,Continue", function () {
                    $("#delete-forms").submit();
                });
            });
        })
    </script>
@stop
