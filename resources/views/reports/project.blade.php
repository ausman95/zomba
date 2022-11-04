@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Requisitions
        </h4>
        <p>
           Project Requisitions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('requisitions.determine')}}">Selection</a></li>
                <li class="breadcrumb-item active" aria-current="page">Requisitions</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="mt-3">
                <a href="{{route('requisitions.determine')}}" class="btn btn-primary rounded-0">
                    <i class="fa fa-plus-circle"></i>  New Requisition
                </a>
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($requisitions->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no requisitions for this project!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-primary table-hover table-striped" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>REQ NO.</th>
                                            <th>USER</th>
                                            <th>ITEMS</th>
                                            <th>STATUS</th>
                                            <th>SENT DATE</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($requisitions as $requisition)
                                            <tr>
                                                <td>{{$requisition->id}}</td>
                                                <td>{{$requisition->user->name}}</td>
                                                <td>{{$requisition->requisitionItems()->count()}}</td>
                                                <td>{{ucwords($requisition->status)}}</td>
                                                <td>{{$requisition->created_at}}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('requisitions.show',$requisition->id)}}"
                                                       class="btn btn-primary rounded-0">
                                                        <i class="fa fa-list-ol"></i> Manage
                                                        + </a>
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
