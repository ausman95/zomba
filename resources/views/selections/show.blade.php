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
                <li class="breadcrumb-item"><a href="{{route('hr.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Labourers</a></li>
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
                            <a href="{{route('members.edit',$labourer->id)}}"
                               class="btn btn-link text-primary text-undecorated">
                                <i class="fa fa-list-ol"></i>Allocate
                            </a>
                        </div>
                        <div>
                            <a href="{{route('members.edit',$labourer->id)}}"
                               class="btn btn-link text-primary text-undecorated">
                                <i class="fa fa-edit"></i>Update
                            </a>
                        </div>
                        <div class="">
                            <form action="{{route('members.destroy',$labourer->id)}}" method="POST" id="delete-form">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                            <button class="btn btn-link text-danger text-undecorated" id="delete-btn">
                                <i class="fa fa-trash"></i>Delete
                            </button>
                        </div>
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
                                    <table class="table table-striped">
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
                                            <td>Marital Status</td>
                                            <td>{{$labourer->marital_status}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td>{{$labourer->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Home Address</td>
                                            <td>{{$labourer->home_village}}</td>
                                        </tr>
                                        <tr>
                                            <td>Professional </td>
                                            <td>{{$labourer->labour->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Qualification</td>
                                            <td>{{$labourer->qualification}}</td>
                                        </tr>
                                        <tr>
                                            <td>Current Address</td>
                                            <td>{{$labourer->address}}</td>
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
                                            <table class="table table-borderless table-striped" id="project-table">
                                                <thead>
                                                <tr>
                                                    <th>Project Name</th>
                                                    <th>Amount Agreed</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $sum = 0;?>
                                                @foreach($allocations as $allocation)
                                                    <tr>
                                                        <td>{{ucwords($allocation->project->name) }}</td>
                                                        <td>{{number_format($allocation->amount)}}</td>
                                                        <td>{{number_format($sum = $sum+$allocation->amount)}}</td>

                                                        <td class="pt-1">
                                                            <form action="{{route('allocations.destroy',$allocation->id)}}" method="POST" id="delete-forms">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="DELETE">
                                                            </form>
                                                            <button class="btn  btn-outline-danger text-undecorated" id="delete-bt">
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
                            <table class="table table-borderless table-striped" id="data-table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c= 1; $balance = 0; ?>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{$c++}}</td>
                                        <td>{{$payment->expense->name}}</td>
                                        <th>
                                               - {{number_format($payment->amount)}}
                                        </th>
                                        <th>{{number_format($sum-($payment->amount))}}</th>
                                        <th>
                                            Dr
                                        </th>
                                        <td>{{$payment->created_at}}</td>
                                        <td>{{$payment->transaction_type==1 ? "CR" : "DR"}}</td>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this user account?", "Yes,Delete", function () {
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
