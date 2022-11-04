@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Reports
        </h4>
        <p>
            Show User Weekly Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('reports.index')}}">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$report->id}}</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-4">
            <div class="row">
                <div class="col-sm-12 mb-4 col-md-3">
                    <div class="text-black-50">
                        Weekly Report For
                    </div>
                    <h4>
                        {{$report->user->name}}
                    </h4>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Report No.
                        </div>
                        <h4>
                            {{$report->id}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            ReportTime.
                        </div>
                        <h4>
                            {{date('d F Y', strtotime($report->created_at))}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Period.
                        </div>
                        <h4>
                            {{date('d F Y', strtotime($report->start_date))}} To {{date('d F Y', strtotime($report->end_date))}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Status
                        </div>
                        <h4>
                            N/A
                            {{--                            {{strtoupper($requisition->status)}}--}}
                        </h4>
                    </div>

                    {{--              Some buttons will be here     --}}

                </div>
                <div class="col-sm-12 mb-4 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Report Items
                        </div>
                        @if(count($report->reportItems) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <div class="card-body" style="overflow: auto">
                                <div class="text-center">
                                    <div class="ul list-group list-group-flush">
                                        <?php $sum = 0;?>
                                        <div style="overflow-x:auto;">
                                            <table class="table  table-primary table-bordered table-hover table-striped" id="data-table">
                                                <caption style=" caption-side: top; text-align: center">Report Items</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>ITEM</th>
                                                    <th>STATUS</th>
                                                    @if($report->user_id==request()->user()->id)
                                                        <th>ACTION</th>
                                                    @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $c= 1;?>
                                                @foreach($report->reportItems  as $list_item)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{ucwords($list_item->item) }}</td>
                                                        <td>{{ucwords($list_item->status) }}</td>
                                                        @if($report->user_id==request()->user()->id)
                                                            <td class="pt-1">
                                                                <a href="{{route('report-items.show',$list_item->id)}}"
                                                                   class="btn btn-primary rounded-0">
                                                                    <i class="fa fa-list-ol"></i> Manage
                                                                </a>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
                let value = $(this).attr('name');
                let request = '.request'+value;
                let qty = '.quantity'+value;
                $(request).removeClass('d-none').addClass('show');
                $(qty).removeClass('show').addClass('d-none');
            });
            $(".Cancel").on('click', function () {
                let value = $(this).attr('name');
                let request = '.request'+value;
                let qty = '.quantity'+value;
                $(request).removeClass('show').addClass('d-none');
                $(qty).removeClass('d-none').addClass('show');
            });
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this requisition item?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
            $("#requisition-approve-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#approve-form").attr('action', $url);
                confirmationWindow("Confirm Approval", "Are you sure you want to approve this requisition?", "Yes,Continue", function () {
                    $("#approve-form").submit();
                })
            });

            //requisition-cancel-btn
            $("#requisition-cancel-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Cancelling", "Are you sure you want to cancel this requisition?", "Yes,Continue", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop
