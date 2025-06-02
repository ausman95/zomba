@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Other Payee
        </h4>
        <p>
            Manage Payee
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('others.index')}}">Other Payee</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create other Payee</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('others.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="Payee Name" >
                            @error('name')
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
