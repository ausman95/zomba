@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-file-archive"></i>Contracts
        </h4>


        <p>The contract </p>
        <p>
            Manage Contracts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contracts</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('contracts.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Contracts
            </a>
            <a href="{{route('contract-types.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-screwdriver"></i>Contract Types
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($contracts->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Contracts!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped"
                                               id="data-table">
                                            <caption style=" caption-side: top; text-align: center">CONTRACTS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>DEPARTMENT</th>
                                                <th>PROFESSIONAL</th>
                                                <th>CONTRACT</th>
                                                <th>SALARY (MK)</th>
                                                <th>START</th>
                                                <th>END</th>
                                                <th>DUE</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @foreach($contracts as $contract)
                                                @if($contract->age<60)
                                                    <tr style="color: red !important;">
                                                @else
                                                    <tr>
                                                        @endif
                                                        <td>{{$c++}}</td>
                                                        <td>{{@$contract->labourer->name}}</td>
                                                        <td>{{@$contract->labourer->department->name}}</td>
                                                        <td>{{@$contract->labourer->labour->name}}</td>
                                                        <td>{{$contract->contractType->name}}</td>
                                                        <td>{{number_format($contract->salary)}}</td>
                                                        <td>{{$contract->start_date}}</td>
                                                        <td>{{$contract->end_date}}</td>
                                                        <td>
                                                            @if($contract->status=='ENDED')
                                                                0
                                                            @else
                                                            @if($contract->getAgeAttribute()>30)
                                                                {{$contract->getAgeAttribute()}} Days
                                                            @else
                                                                <p style="color: red"> {{$contract->getAgeAttribute()}}
                                                                    Days</p>
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <th>{{$contract->status}}</th>
                                                        <td class="pt-1">
                                                            <a href="{{route('contracts.show',$contract->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i> Manage
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
        })
    </script>
@stop

