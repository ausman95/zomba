@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Service Providers
        </h4>
        <p>
            Manage Service Provider information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('services.index')}}">Service Provider</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$service->name}}</li>
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
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$service->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$service->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Service</td>
                                            <td>{{$service->service}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{$service->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td>{{$service->phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{$service->address}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>{{$service->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated At</td>
                                            <td>{{$service->updated_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update By</td>
                                            <td>{{\App\Models\Budget::userName($service->updated_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{@\App\Models\Budget::userName($service->created_by)}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('services.edit',$service->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
                                        @if(request()->user()->designation==='administrator')
                                            <div class="">
                                                <form action="{{route('services.destroy',$service->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                                <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Activities
                    </h5>
                    <div class="card">
                        <div class="card-body">

                            <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                                <div class="card " style="min-height: 30em;">
                                    <div class="card-body px-1">
                                        @if($services->count() === 0)
                                            <i class="fa fa-info-circle"></i>There are no Asset Services!
                                        @else
                                            <div style="overflow-x:auto;">
                                            <table class="table table1 table-bordered table-hover table-striped" id="data-table">
                                                <caption style=" caption-side: top; text-align: center">ASSET SERVICES</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>SERVICE</th>
                                                    <th>COST (MK)</th>
                                                    <th>DATE SERVICED</th>
                                                    <th>DAYS REMAINING</th>
                                                    <th>SERVICE DUE</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $c= 1;?>
                                                @foreach($services as $service)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{ucwords($service->provider->service) }}</td>
                                                        <td>{{number_format($service->amount) }}</td>
                                                        <td>{{$service->service_date }}</td>
                                                        <td style="text-align: center">{{$service->getDays($service->service_due,$service->service_date ) }}</td>
                                                        <td>{{$service->service_due }}</td>

                                                        <td class="pt-1">
                                                            <a href="{{route('asset-services.show',$service->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i> Manage
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Account ?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
