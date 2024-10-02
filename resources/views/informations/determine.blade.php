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
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('informations.index')}}">Information</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Information</li>
            </ol>
        </nav>
        <div class="mb-5">
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('informations.next')}}" method="GET" autocomplete="off">
                        @csrf
                            <div class="form-group">
                                <textarea name="vision" rows="3"
                                          placeholder="Church vision"
                                          class="form-control @error('vision') is-invalid
                                       @enderror" required>{{old('vision')}}</textarea>
                                @error('vision')
                                <span class="invalid-feedback">
                               {{$message}}
                            </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <textarea name="goal" rows="3"
                                          placeholder="Church Goal"
                                          class="form-control @error('goal') is-invalid
                                       @enderror" required>{{old('goal')}}</textarea>
                                @error('goal')
                                <span class="invalid-feedback">
                               {{$message}}
                            </span>
                                @enderror
                            </div>
                        <div class="form-group">
                            <textarea name="mission" rows="3"
                                      placeholder="Church mission"
                                      class="form-control @error('mission') is-invalid
                                       @enderror" required>{{old('mission')}}</textarea>
                            @error('mission')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea name="what_we_do" rows="3"
                                      placeholder="What we do"
                                      class="form-control @error('what_we_do') is-invalid
                                       @enderror" required>{{old('what_we_do')}}</textarea>
                            @error('what_we_do')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea name="who_we_are" rows="3"
                                      placeholder="Who we are"
                                      class="form-control @error('who_we_are') is-invalid
                                       @enderror" required>{{old('who_we_are')}}</textarea>
                            @error('who_we_are')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
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
