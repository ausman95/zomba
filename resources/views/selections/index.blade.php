@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Supplier Material Allocations
        </h4>
        <p>
            Manage Supplier Material accounts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Supplier Material Allocations</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation==='accountant' || request()->user()->designation==='administrator')
            <a href="{{route('selections.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Allocation
            </a>
            @endif
{{--            <a href="{{route('materials.index')}}" class="btn btn-outline-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-screwdriver"></i>Materials--}}
{{--            </a>--}}
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($selections->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Allocations!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-primary  table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">SUPPLIER MATERIAL ALLOCATIONS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>SUPPLIER</th>
                                            <th>MATERIAL</th>
                                            <th>PHONE</th>
                                            <th>EMAIL</th>
                                            <th>LOCATION</th>
                                            <th>DATE</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($selections as $selection)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$selection->supplier->name}}</td>
                                                <td>{{$selection->material->name}}</td>
                                                <td>{{$selection->supplier->phone_number}}</td>
                                                <td>{{$selection->supplier->email}}</td>
                                                <td>{{$selection->supplier->location}}</td>
                                                <td>{{$selection->created_at}}</td>
                                                <td class="pt-1">
                                                    <form action="{{route('selections.destroy',$selection->id)}}" method="POST" id="delete-form">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                    </form>
                                                    @if(request()->user()->designation==='administrator')
                                                    <button class="btn btn-danger btn-md rounded-0" id="delete-btn">
                                                        <i class="fa fa-trash"></i>Remove
                                                    </button>
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
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop

