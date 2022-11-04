@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Asset Services
        </h4>
        <p>
            Manage Asset Services
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('asset-services.index')}}">Asset Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$service->asset->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body px-1">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">ASSET SERVICES INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Asset Name</td>
                                            <td>{{$service->asset->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Serial / Reg Number</td>
                                            <td>{{$service->asset->serial_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Service Provider</td>
                                            <td>{{$service->provider->service}}</td>
                                        </tr>

                                        <tr>
                                            <td>Days Remaining</td>
                                            <td>{{$service->getDays($service->service_due,$service->service_date)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Date of Service Due</td>
                                            <td>{{$service->service_due}}</td>
                                        </tr>

                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$service->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$service->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('asset-services.edit',$service->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
{{--                                        @if(request()->user()->designation==='administrator')--}}
{{--                                            <div class="">--}}
{{--                                                <form action="{{route('asset-services.destroy',$service->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="mt-5">--}}
{{--                    <h5>--}}
{{--                        <i class="fa fa-microscope"></i>Transactions--}}
{{--                    </h5>--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            @if($transactions->count() === 0)--}}
{{--                                <i class="fa fa-info-circle"></i>There are no Transactions!--}}
{{--                            @else--}}
{{--                            <table class="table table-borderless table-striped" id="data-table">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>No</th>--}}
{{--                                    <th>Project</th>--}}
{{--                                    <th>Amount</th>--}}
{{--                                    <th>Description</th>--}}
{{--                                    <th>Date</th>--}}
{{--                                    <th>Type</th>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <?php  $c= 1; $balance = 0 ?>--}}
{{--                                @foreach($transactions as $transfer)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{$c++}}</td>--}}
{{--                                        <td>{{ucwords($transfer->project->name) }}</td>--}}
{{--                                        <td>{{number_format($transfer->amount) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->description) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->created_at) }}</td>--}}
{{--                                        <td>{{ucwords($transfer->transaction_type == 1 ? "CR" : "DR") }}</td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                                @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@stop

@section('scripts')
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
