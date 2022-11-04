@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Asset Category
        </h4>
        <p>
            Manage Asset Category
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Asset Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$category->name}}</li>
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
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$category->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$category->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$category->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$category->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        @if(request()->user()->designation==='administrator'||request()->user()->designation==='accountant')
                                            <div>
                                                <a href="{{route('categories.edit',$category->id)}}"
                                                   class="btn btn-primary rounded-0" style="margin: 2px">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                            </div>
{{--                                            @if(request()->user()->designation==='administrator')--}}
{{--                                                <div class="">--}}
{{--                                                    <form action="{{route('categories.destroy',$category->id)}}" method="POST" id="delete-form">--}}
{{--                                                        @csrf--}}
{{--                                                        <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                    </form>--}}
{{--                                                    <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                        <i class="fa fa-trash"></i>Delete--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
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
                        <i class="fa fa-microscope"></i>Transactions
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            @if($assets->count() === 0)
                                <i class="fa fa-info-circle"></i>There are no Assets!
                            @else
                                <div style="overflow-x:auto;">
                                <table class="table  table1 table-bordered table-striped" id="data-table">
                                    <caption style=" caption-side: top; text-align: center">ASSETS</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAME</th>
                                        <th>SERIAL / REG NUMBER</th>
                                        <th>CATEGORY</th>
                                        <th>COST (MK)</th>
                                        <th>LIFE</th>
                                        <th>DATE ACQU..</th>
                                        <th>DEPRECIATION (MK)</th>
                                        <th>NETBOOK VALUE (MK)</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  $c= 1;?>
                                    @foreach($assets as $asset)
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td>{{ucwords($asset->name) }}</td>
                                            <td>{{ucwords($asset->serial_number) }}</td>
                                            <td>{{ucwords($asset->category->name) }}</td>
                                            <td>{{number_format($asset->cost) }}</td>
                                            <td>{{number_format($asset->life) }}</td>
                                            <td>{{ucwords($asset->bought_date) }}</td>
                                            <td>{{number_format($asset->getDays($asset->bought_date,$asset->life,$asset->cost)) }}</td>
                                            <td>{{number_format($asset->cost-$asset->getDays($asset->bought_date,$asset->life,$asset->cost)) }}</td>

                                            <td class="pt-1">
                                                <a href="{{route('assets.show',$asset->id)}}"
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
