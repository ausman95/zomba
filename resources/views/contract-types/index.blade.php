@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Contract Types
        </h4>
        <p>
            Manage Contract Types
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('contracts.index')}}">Contracts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contract Types</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('contract-types.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Contract Type
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($contracts->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Contract Type!
                                @else
                                    <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">CONTRACT TYPES</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>DESCRIPTION</th>
                                            <th>CREATED ON</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($contracts as $contract)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($contract->name) }}</td>
                                                <td>{{ucwords($contract->description) }}</td>
                                                <td>{{ucwords($contract->created_at) }}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('contract-types.show',$contract->id)}}"
                                                       class="btn btn-md btn-primary rounded-0">
                                                     <i class="fa fa-list-ol"></i>   Manage
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

