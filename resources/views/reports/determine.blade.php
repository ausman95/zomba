@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Reports
        </h4>
        <p>
            Weekly Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('reports.index')}}">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Step 1 of 2</li>
            </ol>
        </nav>
        <div class="mb-5">

            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-4">
                    <p>Input  Report Period</p>
                    <form action="{{route('reports.next')}}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="">From Start Date</label>
                            <input type="date" class="form-control" name="start_date" placeholder="Start Date">
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">To End Date</label>
                            <input type="date" class="form-control" name="end_date" placeholder="End Date">
                            @error('end_date')
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
                    $('.department').addClass('d-none').removeClass('show');
                }
                if(status==='2'){
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.department').addClass('show').removeClass('d-none');
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
