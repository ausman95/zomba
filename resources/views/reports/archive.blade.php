@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Out Tray Reports
        </h4>
        <p>
            Out Tray Employee
            Weekly Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Out Tray Reports</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('reports.determine')}}" class="btn btn-primary rounded-0">
                <i class="fa fa-plus-circle"></i>  Create a New Report
            </a>
            <a href="{{route('reports.index')}}" class="btn btn-primary rounded-0">
                <i class="fa fa-archive"></i>  In Tray
            </a>
            <div class="mt-3">

                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($reports ->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Weekly Reports!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-primary table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">OUT TRAY</caption>
                                            <thead>
                                            <tr>
                                                <th> NO.</th>
                                                <th>PREPARED BY</th>
                                                <th>ITEMS</th>
                                                <th>PREPARED DATE</th>
                                                <th>PERIOD</th>
                                                <th style="color: red">RATE (OUT OF 100%)</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1; ?>
                                            @foreach($reports as $item)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{$item->user->name}}</td>
                                                    <td>{{$item->report()->count()}}</td>
                                                    <td>{{date('d F Y', strtotime($item->created_at))}}
                                                    <td>{{date('d F Y', strtotime($item->start_date))}} To {{date('d F Y', strtotime($item->end_date))}}</td>
                                                    <td style="color: red">{{($item->rating()->count()/$item->report()->count())*100}}</td>
                                                    <td class="pt-1">
                                                        <a href="{{route('reports.show',$item->id)}}"
                                                           class="btn btn-primary rounded-0">
                                                            <i class="fa fa-list-ol"></i> Details
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
