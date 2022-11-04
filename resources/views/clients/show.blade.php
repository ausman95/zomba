@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bxs-city "></i>&nbsp; Clients
        </h4>
        <p>
            Manage client information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('clients.index')}}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$client->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$client->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$client->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{$client->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td>{{$client->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{$client->address}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created at</td>
                                            <td>{{$client->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated at</td>
                                            <td>{{$client->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('clients.edit',$client->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
{{--                                        @if(request()->user()->designation==='administrator')--}}
{{--                                            <div class="">--}}
{{--                                                <form action="{{route('clients.destroy',$client->id)}}" method="POST" id="delete-form">--}}
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
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="card " style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if($projects->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no Projects!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table class="table table1 table-bordered table-hover table-striped" id="data-table">
                                                <caption style=" caption-side: top; text-align: center">PROJECTS</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>PROJECT NAME</th>
                                                    <th>AMOUNT</th>
                                                    <th>LOCATION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $c= 1;?>
                                                @foreach($projects as $project)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{ucwords($project->name) }}</td>
                                                        <td>{{number_format($project->amount) }}</td>
                                                        <td>{{ucwords($project->location) }}</td>
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
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Projects Payments
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                                <div class="card " style="min-height: 30em;">
                                    <div class="card-body px-1">
                                        @if($incomes->count() === 0)
                                            <i class="fa fa-info-circle"></i>There are no Payment!
                                        @else
                                            <div style="overflow-x:auto;">
                                                <table class="table table1 table-bordered table-hover table-striped" id="data-table">
                                                    <caption style=" caption-side: top; text-align: center">PROJECT PAYMENTS</caption>
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>PROJECT NAME</th>
                                                        <th>ACCOUNT NAME</th>
                                                        <th>AMOUNT</th>
                                                        <th>DESCRIPTION</th>
                                                        <th>PAYMENT TYPE</th>
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
                                                            <td>{{ucwords($income->transaction_type == 2 ? "DR" : "CR") }}</td>
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
