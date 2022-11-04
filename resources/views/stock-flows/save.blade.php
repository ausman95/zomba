@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Stock Movement
        </h4>
        <p>
            Create Stock Movement
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('stores.index')}}">Stores</a></li>
                <li class="breadcrumb-item"><a href="{{route('stock.next')}}">Selection</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Stoke Movement</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                Creating a stock Movement
            </p>

            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-4">
                    <p>Select Destination</p>
                    <form action="{{route('stock.add')}}" method="GET">
                        @csrf
                        <div class="form-group">
                            <select name="destination" class="form-select  select-relation check_department  @error('destination') is-invalid @enderror">
                                    <option value="3">Usage</option>
                                <option value="2">Opening Balance</option>
                                    <option value="1">Department / Project</option>
                                    <option value="4">Returned to Stores</option>
                            </select>
                            @error('destination')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="d-none projects">
                            <div class="form-group">
                                <label for="">Select Department / Project</label>
                                <select name="project_id" class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select Projects --</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
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
