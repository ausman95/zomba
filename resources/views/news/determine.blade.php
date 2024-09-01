@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>News
        </h4>
        <p>
            Manage News
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('news.index')}}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create New</li>
            </ol>
        </nav>
        <div class="mb-5">
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('news.next')}}" method="GET" autocomplete="off">
                        @csrf
                        <div class="step_1">
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <div class="form-group">
                                <label>Title</label>
                                <textarea name="title" rows="3"
                                          placeholder="News Title here"
                                          class="form-control @error('note') is-invalid
                                       @enderror" required>{{old('title')}}</textarea>
                                @error('title')
                                <span class="invalid-feedback">
                               {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-check-circle"></i> Continue
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
