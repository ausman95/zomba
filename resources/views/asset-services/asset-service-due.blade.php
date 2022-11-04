@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i>Asset Services Due
        </h4>
        <p>
            Asset Services Due
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('asset-services.index')}}">Assets Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assets Services Due</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($services->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Asset Services!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table t table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">ASSET SERVICES DUE</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>ASSETS</th>
                                            <th>SERIAL / REG NUMBER</th>
                                            <th>SERVICE</th>
                                            <th>DAYS REMAINING</th>
                                            <th>SERVICE DUE</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c = 1;?>
                                        @foreach($services as $service)
                                            @if($service->getDaysRemainingAttribute() >0 && $service->getDaysRemainingAttribute() <=30 )
                                            <tr style="color: red">
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($service->asset->name) }}</td>
                                                <td>{{ucwords($service->asset->serial_number) }}</td>
                                                <td>{{ucwords($service->provider->service) }}</td>
                                                <td style="text-align: center">{{$service->getDaysRemainingAttribute() }}</td>
                                                <td>{{date('d F Y', strtotime($service->service_due)) }}</td>

                                                <td class="pt-1">
                                                    <a href="{{route('asset-services.show',$service->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                        <i class="fa fa-list-ol"></i> Manage
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
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

