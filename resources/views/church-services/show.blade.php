@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Church Services
        </h4>
        <p>
            Manage Service
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item"><a href="{{route('services.index')}}">Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$service->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
                    <div class="row">
                        <div class="col-sm-4 mb-2">
                            <div class="mt-5">
                                <h5>
                                    <i class="fa fa-microscope"></i>Service Information
                                </h5>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$service->name}} Service</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$service->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$service->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
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
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($service->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Active
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            @if(request()->user()->designation=='administrator')
                                            <a href="{{route('services.edit',$service->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{route('services.destroy',$service->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{$service->id}}">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
{{--            <div class="row">--}}
{{--                <div class="col-sm-12 mb-2">--}}
{{--                    <div class="mt-5">--}}
{{--                        <h5>--}}
{{--                            <i class="fa fa-microscope"></i>Home Cells--}}
{{--                        </h5>--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-body px-1">--}}
{{--                                @if($zone->churches->count() === 0)--}}
{{--                                    <i class="fa fa-info-circle"></i>There are no  Home Churches!--}}
{{--                                @else--}}
{{--                                    <div style="overflow-x:auto;">--}}
{{--                                        <table class="table  table-bordered table-hover table-striped" id="data-table">--}}
{{--                                            <caption style=" caption-side: top; text-align: center">HOME CELLS</caption>--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th>NO</th>--}}
{{--                                                <th>NAME</th>--}}
{{--                                                <th>LEADER</th>--}}
{{--                                                <th>PHONE</th>--}}
{{--                                                <th>ACTION</th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                            <tbody>--}}
{{--                                            <?php $c = 1;?>--}}
{{--                                            @foreach($zone->churches as $church)--}}
{{--                                                <tr>--}}
{{--                                                    <td>{{$c++}}</td>--}}
{{--                                                    <td>{{$church->name}}</td>--}}
{{--                                                    <td>{{@$church->member->name}}</td>--}}
{{--                                                    <td>{{@$church->member->phone_number}}</td>--}}
{{--                                                    <td class="pt-1">--}}
{{--                                                        @if(request()->user()->designation=='administrator')--}}
{{--                                                        <a href="{{route('churches.show',$church->id)}}"--}}
{{--                                                           class="btn btn-primary btn-md rounded-0">--}}
{{--                                                            <i class="fa fa-list-ol"></i>  Manage--}}
{{--                                                        </a>--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
