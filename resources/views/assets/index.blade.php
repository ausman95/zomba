@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i> &nbsp; Assets
        </h4>
        <p>
            Asset Management
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assets</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation==='hr' || request()->user()->designation==='administrator')
            <a href="{{route('assets.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Assets
            </a>
{{--            <a href="{{route('asset-services.index')}}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-list-ol"></i>Assets Services--}}
{{--            </a>--}}
{{--            <a href="{{route('services.index')}}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-list-ol"></i>Service Providers--}}
{{--            </a>--}}
            <a href="{{route('current-assets')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-money-bill-alt"></i>Current Assets
            </a>
            <a href="{{route('liabilities')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-folder-open"></i>Liabilities
            </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($assets->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Assets!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered  table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">Non-Current Assets</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>SERIAL / REG NUMBER</th>
                                            <th>CATEGORY</th>
                                            <th>QUANTITY</th>
                                            <th>@ COST (MK)</th>
                                            <th>ACQ. DATE</th>
                                            <th>EST. LIFE</th>
                                            <th>DEPCTN %</th>
                                            <th>DEPCTN (MK)</th>
                                            <th>NETBOOK (MK)</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($assets as $asset)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($asset->name) }}</td>
                                                <td>{{ucwords($asset->serial_number) }}</td>
                                                <td>{{ucwords(@$asset->category->name) }}</td>
                                                <td>{{number_format($asset->quantity) }}</td>
                                                <td>{{number_format($asset->cost*$asset->quantity) }}</td>
                                                <td>{{date('d F Y', strtotime($asset->t_date)) }}</td>
                                                <td>{{ucwords($asset->life) }}</td>
                                                <td>{{number_format($asset->depreciation) }}</td>
                                                @if($asset->depreciation)
                                                <td>{{number_format(($asset->quantity*$asset->cost) - ($asset->getDays($asset->t_date,$asset->life,$asset->cost,$asset->depreciation,$asset->quantity)),2) }}</td>
                                                <td>{{number_format($asset->getDays($asset->t_date,$asset->life,$asset->cost,$asset->depreciation,$asset->quantity),2) }}</td>
                                                @else
                                                    <td>-</td>
                                                    <td>{{number_format($asset->cost*$asset->quantity) }}</td>
                                                @endif
                                                <td class="pt-1">
                                                    @if(request()->user()->designation==='hr' || request()->user()->designation==='administrator')
                                                    <a href="{{route('assets.show',$asset->id)}}"
                                                       class="btn btn-primary rounded-0">
                                                        <i class="fa fa-list-ol"></i> Manage
                                                    </a>
                                                    @endif
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

