@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i> Reports
        </h4>
        <p>
            Show item on user Report
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('reports.index')}}">Reports</a></li>
                    <li class="breadcrumb-item"><a href="{{route('reports.show',$reportItem->report_id)}}">{{$reportItem->report->user->name}} Weekly Report</a></li>
                <li class="breadcrumb-item active" aria-current="page">Single Item</li>
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
                                        <table class="table table-primary table-bordered table-hover table-striped"
                                               id="data-table">
                                            <tbody>
                                            <tr>
                                                <td>Item Description</td>
                                                <td>{{$reportItem->item}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$reportItem->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$reportItem->updated_at}}</td>
                                            </tr>
                                        </table>
                                        <div class="">
                                            <form action="{{route('report-items.destroy',$reportItem->id)}}"
                                                  method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                            <button class="btn btn-primary btn-md rounded-0" id="delete-btn"
                                                    style="margin: 5px">
                                                <i class="fa fa-markdown"></i>Achieved
                                            </button>
                                        </div>
                                    </div>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want Achieved this Record?", "Yes,Continue", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
