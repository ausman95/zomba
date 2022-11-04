@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i> &nbsp; Assets
        </h4>
        <p>
            Assets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Asset</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('assets.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="Asset name" >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{old('quantity')}}"
                                   placeholder="Quantity" >
                            @error('quantity')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Opening Balance</label>
                            <input type="number" name="cost"
                                   class="form-control @error('cost') is-invalid @enderror"
                                   value="{{old('cost')}}"
                                   placeholder="Opening Balance" >
                            @error('cost')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Category </label>
                            <select name="category_id"
                                    class="form-select select-relation @error('category_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}"
                                        {{old('category_id')===$category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Serial / Reg Number</label>
                            <input type="text" name="serial_number"
                                   class="form-control @error('cost') is-invalid @enderror"
                                   value="{{old('serial_number')}}"
                                   placeholder="Serial Number" >
                            @error('serial_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Depreciation %</label>
                            <input type="number" name="depreciation"
                                   class="form-control @error('life') is-invalid @enderror"
                                   value="{{old('depreciation')}}"
                                   placeholder="Depreciation %" >
                            @error('depreciation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Condition </label>
                            <select name="condition"
                                    class="form-select select-relation @error('condition') is-invalid @enderror" style="width: 100%">
                                <option value="Good">Good</option>
                                <option value="Bad">Bad</option>
                            </select>
                            @error('condition')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location"
                                   class="form-control @error('life') is-invalid @enderror"
                                   value="{{old('location')}}"
                                   placeholder="Location of the Asset" >
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
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            // $('.btn-success').click(function () {
            //     let check = $('.form-control').val();
            //     if(check){
            //         $(this).removeClass("fa fa-save").html('<span class="fa fa-spin fa-spinner"></span> Please Wait').attr('disabled',true);
            //     }
            // });
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='2'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                }
                if(status==='3'){
                    $('.suppliers').addClass('show').removeClass('d-none');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='4'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('show').removeClass('d-none');
                    $('.projects').addClass('show').removeClass('d-none');
                }
            });
            $('.t_type').on('change', function () {
                let check = $(this).val();
                if(check==='1'){
                    $('.supplier').addClass('d-none').removeClass('show');
                }
                if(check==='2'){
                    $('.supplier').addClass('show').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
