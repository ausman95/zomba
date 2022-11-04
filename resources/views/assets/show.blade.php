@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i> &nbsp; Assets
        </h4>
        <p>
            Manage Asset
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$asset->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body election-banner-card p-1">
                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <a href="{{route('assets.edit',$asset->id)}}"
                               class="btn btn-primary rounded-0" style="margin: 2px">
                                <i class="fa fa-edit"></i>Update
                            </a>
                        </div>
{{--                        @if(request()->user()->designation==='administrator')--}}
{{--                        <div class="">--}}
{{--                            <form action="{{route('assets.destroy',$asset->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                            @endif--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered  table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$asset->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$asset->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Serial / Reg Number</td>
                                            <td>{{$asset->serial_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td>{{$asset->category->name}}</td>
                                        </tr>
                                       <tr>
                                           <td>Quantity</td>
                                           <td>{{number_format($asset->quantity) }}</td>
                                       </tr>
                                        <tr>
                                            <td>Cost (MK)</td>
                                            <td>{{number_format($asset->cost*$asset->quantity) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Location</td>
                                            <td>{{ucwords($asset->location) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Condition</td>
                                            <td>{{ucwords($asset->condition) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Depreciation %</td>
                                            <td>{{number_format($asset->depreciation) }}</td>
                                        </tr>
                                        @if($asset->depreciation)
                                            <tr>
                                                <td>Depreciation Amount (MK)</td>
                                                <td>{{number_format(($asset->depreciation/100)*($asset->cost*$asset->quantity)) }}</td>
                                            </tr>
                                            <tr>
                                                <td>NetBook Value (MK)</td>
                                                <td>{{number_format(($asset->cost*$asset->quantity)-(($asset->depreciation/100)*$asset->cost*$asset->quantity)) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{date('d F Y', strtotime($asset->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{date('d F Y', strtotime($asset->updated_at))}}</td>
                                        </tr>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Transactions
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            @if($services->count() === 0)
                                <i class="fa fa-info-circle"></i>There are no Asset Services!
                            @else
                                <div style="overflow-x:auto;">
                                <table class="table  table1 table-bordered table-striped" id="data-table">
                                    <caption style=" caption-side: top; text-align: center">{{$asset->name}} TRANSACTIONS</caption>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Asset?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
