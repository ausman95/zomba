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
                    <li class="breadcrumb-item"><a href="{{route('reports.determine')}}">Step 1 of 2</a></li>
                <li class="breadcrumb-item active" aria-current="page">Step 2 of 2</li>
            </ol>
        </nav>
        <div class="mb-5">

            <p>
                Submit requisition for new materials.
            </p>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-2">
                    <form action="{{route('reports.enlist')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Item in Summary</label>
                            <div class="form-group">
                                     <textarea name="description" rows="2"
                                               class="form-control @error('description') is-invalid @enderror" placeholder="One todo item at a time">{{old('description')}}</textarea>
                                @error('description')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary rounded-0" type="submit">
                                <i class="fa fa-plus-circle"></i>  Add Item
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 mb-2 col-md-8">
                    <div class="card bg-light">
                        <div class="card-header">
                            Requisition Items List
                        </div>
                        @if(count($report_list) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <div class="ul list-group list-group-flush">
                                @foreach($report_list  as $list_item)
                                    <li class="list-group-item justify-content-between d-flex">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                {{$loop->iteration}}
                                            </div>
                                            <div>
                                                {{$list_item['description']}}
                                                <div class="text-black-50">
                                                  From :   {{date('d F Y', strtotime($_GET['start_date']))}} To {{date('d F Y', strtotime($_GET['end_date']))}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="me-4">

                                            </div>
                                            <div>
                                                <a href="{{route('reports.delist',$list_item['description'])}}"
                                                   title="Remove item"> <i
                                                        class="fa fa-minus-circle text-danger"></i></a>
                                            </div>

                                        </div>
                                    </li>
                                @endforeach
                            </div>
                        @endif
                        <div class="card-footer">
                            <a href="{{route('report.store')."?start_date={$_GET['start_date']}&end_date={$_GET['end_date']}"}}" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>  Submit Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.material').addClass('show').removeClass('d-none');
                    $('.others').addClass('d-none').removeClass('show');
                }
                if(status==='2'){
                    $('.material').addClass('d-none').removeClass('show');
                    $('.others').addClass('show').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
