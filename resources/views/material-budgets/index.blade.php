@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Budgets
        </h4>
        <p>
            Manage Material Budgets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Material Budgets</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('material-budgets.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Budgets
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($budgets->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no budgets!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered  table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">BUDGETS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DEPARTMENT</th>
                                                <th>MATERIAL</th>
                                                <th>BUDGETED QTY</th>
                                                <th>ACTUAL QTY</th>
                                                <th>VARIANCE QTY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php  $c= 1;?>
                                            @foreach($budgets as $budget)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{ucwords($budget->department->name) }}</td>
                                                    <td>{{ucwords($budget->material->name) }}</td>
                                                    <td>{{number_format($budget->quantity) }}</td>
                                                    <td>{{number_format($budget->balance($budget->material->id,$budget->department->id)) }}</td>
                                                    <td>{{number_format($budget->quantity-($budget->balance($budget->material->id,$budget->department->id))) }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{route('material-budgets.show',$budget->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i>  Details
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

