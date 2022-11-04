@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bx-abacus"></i>&nbsp; Material Prices
        </h4>
        <p>
            Material Prices
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Prices</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#price">
                <i class="fa fa-plus-circle"></i> New Price
            </button>
            <div class="modal " id="price" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Adding Prices</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('prices.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label>Materials</label>
                                    <select name="material_id"
                                            class="form-select select-relation @error('material_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($materials as $material)
                                            <option value="{{$material->id}}"
                                                {{old('material_id')===$material->id ? 'selected' : ''}}>{{$material->name.' OF '.$material->specifications}}</option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select name="supplier_id"
                                            class="form-select select-relation @error('supplier_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}"
                                                {{old('supplier_id')===$supplier->id ? 'selected' : ''}}>{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Price / Item / Kg</label>
                                    <input type="number" name="price"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{old('price')}}"
                                           placeholder="Price" >
                                    @error('price')
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

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($prices->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Prices!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">MATERIAL PRICES</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>MATERIAL</th>
                                            <th>DESCRIPTION</th>
                                            <th>PRICE / ITEM (MK)</th>
                                            <th>SUPPLIER</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($prices as $price)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($price->material->name) }}</td>
                                                <td>{{ucwords($price->material->specifications) }}</td>
                                                <td>{{number_format($price->price) }}</td>
                                                <td>{{ucwords(@$price->supplier->name) }}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('prices.show',$price->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                      <i class="fa fa-list-ol"></i>  Manage
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


        $(document).ready(function () {
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop

