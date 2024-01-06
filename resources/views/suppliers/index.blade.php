@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <br>
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bx-package"></i>&nbsp; Suppliers
        </h4>
        <p>
            Manage Suppliers
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="modal " id="supplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Adding Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('suppliers.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{old('name')}}"
                                           placeholder="Supplier's name"
                                    >
                                    @error('name')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="number" name="phone_number"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           value="{{old('phone_number')}}"
                                           placeholder="Supplier's phone number"
                                    >
                                    @error('phone_number')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location"
                                           class="form-control @error('location') is-invalid @enderror"
                                           value="{{old('location')}}"
                                           placeholder="Enter location i.e Lilongwe"
                                    >
                                    @error('location')
                                    <span class="invalid-feedback">
                               {{$message}}
                            </span>
                                    @enderror
                                </div>
                                <hr style="height: .3em;" class="border-theme">
                                <div class="form-group">
                                    <button class="btn btn-md btn-primary rounded-0">
                                        <i class="fa fa-paper-plane"></i>Save
                                    </button>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#supplier">
                <i class="fa fa-plus-circle"></i> New Supplier
            </button>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($suppliers->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Suppliers!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">SUPPLIERS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>PHONE</th>
                                            <th>LOCATION</th>
                                            <th>BALANCE (MK)</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($suppliers as $supplier)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$supplier->name}}</td>
                                                <td>{{$supplier->phone_number}}</td>
                                                <td>{{$supplier->location}}</td>
                                                <td>{{number_format($supplier->getBalance($supplier->id),2)}}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('suppliers.show',$supplier->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                     <i class="fa fa-list-ol"></i>   Manage
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


        // $(document).ready(function () {
        //
        //     const myTable = document.querySelector("#data-table");
        //     const dataTable = new simpleDatatables.DataTable(myTable, {
        //         layout: {
        //             top: "{search}",
        //             bottom: "{pager}{info}"
        //         },
        //         header: true
        //     });
        // })
    </script>
@stop
