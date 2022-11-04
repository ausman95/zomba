@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-briefcase"></i>Requisitions
        </h4>
        <p>
            Department Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('requisitions.index')}}">Requisitions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$requisition->id}}</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-4">
            <div class="row">
                <div class="col-sm-12 mb-4 col-md-3">
                    <div class="text-black-50">
                        Department
                    </div>
                    <h4>
                        {{$requisition->department->name}}
                    </h4>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Requisition No.
                        </div>
                        <h4>
                            {{$department->id}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Status
                        </div>
                        <h4>
                            {{$department->status}}
                        </h4>
                    </div>
                    <div class="mt-4">

                        <a href="#remove-requisition" id="requisition-cancel-btn"
                           data-target-url="{{route('request.cancel',$requisition->id)}}">
                            <button class="btn btn-danger">
                                <i class="fa fa-times-circle"></i> Cancel Requisition
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 mb-4 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Requisition Items
                        </div>
                        @if(count($requisition->departmentRequisition) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <div class="ul list-group list-group-flush">
                                <table class="table table-borderless table-striped" id="data-table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>Reason</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requisition->departmentRequisition  as $list_item)
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
                                            <td>{{$list_item->created_at}}</td>
                                            <td> {{$list_item->material->name}}
                                                <div class="text-black-50">
                                                    {{$list_item->material->specifications}}
                                                </div>
                                            </td>
                                            <td>{{$list_item->quantity}}</td>
                                            <td>{{$list_item->reason}}</td>
                                            <td>{{number_format($list_item->getPrice($list_item->material_id))}}</td>
                                            <td>{{number_format($list_item->quantity*$list_item->getPrice($list_item->material_id))}}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary editBtn" >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <div class="d-none request">
                                                    <form action="{{route('Ministries.store')}}" method="POST" autocomplete="off">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name"
                                                                   class="form-control @error('name') is-invalid @enderror"
                                                                   value="{{old('name')}}"
                                                                   placeholder="Account's name" >
                                                            @error('name')
                                                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                            @enderror
                                                        </div>
                                                        <hr style="height: .3em;" class="border-theme">
                                                        <div class="form-group">
                                                            <label>Account Type</label>
                                                            <select name="type" class="form-control @error('type') is-invalid @enderror">{{old('type')}}>
                                                                <option value="1">Cr</option>
                                                                <option value="2">Dr</option>
                                                            </select>
                                                            @error('type')
                                                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <button class="btn btn-md btn-success me-2">
                                                                <i class="fa fa-save"></i>Save
                                                            </button>

                                                        </div>
                                                    </form>
                                                </div>
                                                <button
                                                    class="btn btn-danger delete-btn"
                                                    data-target-url="{{route('request.trash',$list_item->id)}}"
                                                    title="Remove item"> <i
                                                        class="fa fa-minus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="delete-form" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
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
            $(".editBtn").on('click', function () {
                $(".request").removeClass('d-none').addClass('show');
            });
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this requisition item?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
            const myTable = document.querySelector("#data-table");
            const dataTable = new simpleDatatables.DataTable(myTable, {
                layout: {
                    top: "{search}",
                    bottom: "{pager}{info}"
                },
                header: true
            });

            //requisition-cancel-btn
            $("#requisition-cancel-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to cancel this requisition?", "Yes,Cancel", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop
