@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i>Asset Services
        </h4>
        <p>
            Asset Services
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assets Services</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
{{--        <div class="modal " id="material" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">--}}
{{--            <div class="modal-dialog">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="staticBackdropLabel">Adding Asset Service</h5>--}}
{{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <form action="{{route('asset-services.store')}}" method="POST" autocomplete="off">--}}
{{--                            @csrf--}}
{{--                            <div class="form-group">--}}
{{--                                <label> Asset </label>--}}
{{--                                <select name="asset_id"--}}
{{--                                        class="form-select select-relation @error('asset_id') is-invalid @enderror" style="width: 100%">--}}
{{--                                    <option value="">-- Select ---</option>--}}
{{--                                    @foreach($assets as $asset)--}}
{{--                                        <option value="{{$asset->id}}"--}}
{{--                                            {{old('asset_id')===$asset->id ? 'selected' : ''}}>{{$asset->name.' of Serial # '.$asset->serial_number}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('asset_id')--}}
{{--                                <span class="invalid-feedback">--}}
{{--                               {{$message}}--}}
{{--                        </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label> Service Provider </label>--}}
{{--                                <select name="provider_id"--}}
{{--                                        class="form-select select-relation @error('provider_id') is-invalid @enderror" style="width: 100%">--}}
{{--                                    <option value="">-- Select ---</option>--}}
{{--                                    @foreach($providers as $provider)--}}
{{--                                        <option value="{{$provider->id}}"--}}
{{--                                            {{old('provider_id')===$provider->id ? 'selected' : ''}}>{{$provider->name.' '.$provider->service}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('provider_id')--}}
{{--                                <span class="invalid-feedback">--}}
{{--                               {{$message}}--}}
{{--                        </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Service Due</label>--}}
{{--                                <input type="date" name="service_due"--}}
{{--                                       class="form-control @error('service_due') is-invalid @enderror"--}}
{{--                                       value="{{old('service_due')}}"--}}
{{--                                       placeholder="Service Due" >--}}
{{--                                @error('service_due')--}}
{{--                                <span class="invalid-feedback">--}}
{{--                               {{$message}}--}}
{{--                        </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

{{--                            <hr style="height: .3em;" class="border-theme">--}}
{{--                            <div class="form-group">--}}
{{--                                <button class="btn btn-md btn-primary rounded-0">--}}
{{--                                    <i class="fa fa-paper-plane"></i>Save--}}
{{--                                </button>--}}

{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="mt-3">
{{--            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#material">--}}
{{--                <i class="fa fa-plus-circle"></i> New Asset Service--}}
{{--            </button>--}}
            <a href="{{route('asset-services.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Asset Service
            </a>
            <a href="{{route('service.due')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-exclamation-circle"></i>Due in 30 days
            </a>  <a href="{{route('service.archive')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-archive"></i>Archive Services
            </a>

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($services->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Asset Services!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">ASSET SERVICES</caption>
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
                                            @elseif($service->getDaysRemainingAttribute() >30)
                                                <tr>
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

