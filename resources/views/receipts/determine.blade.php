@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> SALES
        </h4>
        <p>
            Create Sale
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('sales.index')}}">Stores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Sale</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                Creating a sale
            </p>

            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-4">
                    <p>Select Counter</p>
                    <form action="{{route('sale.add')}}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="">Counter</label>
                            <select name="counter_id" class="form-select select-relation @error('counter_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select Counter --</option>
                                @foreach($counters as $counter)
                                    <option value="{{$counter->id}}">{{$counter->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('counter_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-arrow-alt-circle-right"></i>Continue
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
            $('.check_department').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.projects').addClass('show').removeClass('d-none');
                }
                else{
                    $('.projects').addClass('d-none').removeClass('show');
                }
            });
            $('.payment_type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.bank').addClass('show').removeClass('d-none');
                }
                if(status==='2'){
                    $('.bank').addClass('d-none').removeClass('show');
                }
            });
        });
    </script>
@endsection
