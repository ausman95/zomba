@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Requisitions
        </h4>
        <p>
            Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('requisitions.index')}}">Requisitions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Selection</li>
            </ol>
        </nav>
        <div class="mb-5">

            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-4">
                    <p>Select Department</p>
                    <form action="{{route('requisitions.next')}}" method="GET">
                        @csrf
                        <div class=" department">
                            <div class="form-group">
                                <select name="department_id" class="form-select select-relation @error('department_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="d-none projects">
                            <div class="form-group">
                                <select name="project_id" class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select Projects --</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}-{{$project->location}}
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
