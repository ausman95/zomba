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
                                           <td>{{number_format($asset->quantity,2) }}</td>
                                       </tr>
                                        <tr>
                                            <td>Cost (MK)</td>
                                            <td>{{number_format($asset->cost*$asset->quantity,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Location</td>
                                            <td>{{ucwords($asset->location) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Acquisition Date</td>
                                            <td>{{date('d F Y', strtotime($asset->t_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>life</td>
                                            <td>{{ucwords($asset->life) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Condition</td>
                                            <td>{{ucwords($asset->condition) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Depreciation %</td>
                                            <td>{{number_format($asset->depreciation,2) }}</td>
                                        </tr>
                                        @if($asset->depreciation)
                                            <tr>
                                                <td>Depreciation Amount (MK)</td>
                                                <td>{{number_format($asset->cost - $asset->getDays($asset->t_date,$asset->life,$asset->cost,$asset->depreciation,$asset->quantity),2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>NetBook Value (MK)</td>
                                                <td>{{number_format($asset->getDays($asset->t_date,$asset->life,$asset->cost,$asset->depreciation,$asset->quantity),2) }}</td>
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
                                        <tr>
                                            <td>Update By</td>
                                            <td>{{@$asset->userName($asset->updated_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{@$asset->userName($asset->created_by)}}</td>
                                        </tr>
                                    </table>
                                    <a href="{{route('assets.edit',$asset->id)}}"
                                       class="btn btn-primary rounded-0" style="margin: 2px">
                                        <i class="fa fa-edit"></i>Update
                                    </a>
                                    <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                        <i class="fa fa-trash"></i>Delete
                                    </button>
                                    <form action="{{route('assets.destroy',$asset->id)}}" method="POST" id="delete-form">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="{{$asset->id}}">
                                    </form>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Asset?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
