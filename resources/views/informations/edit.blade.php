@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>News
        </h4>
        <p>
            Update News
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation!='department')
                    <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{route('companies.index')}}">Companies</a></li>
                <li class="breadcrumb-item"><a href="{{route('company-information.index')}}">Information</a></li>
                <li class="breadcrumb-item"><a href="{{route('company-information.show',$company->id)}}">{{$company->company->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('company-information.update',$company->id)}}"
                          method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>


                        <div class="form-group">
                                <label>Current Profile</label>
                            @if($company->pdf_url)
                                <a href="{{ asset('pdf/company/' . $company->pdf_url) }}" target="_blank" class="btn btn-success">Download</a>
                            @else
                                EMPTY
                            @endif
                        </div>
                        <div class="form-group">
                                <label>New Profile</label>
                                <input type="file" name="pdf_url" class="form-control">
                        </div>

                        <div class="form-group">
                                <textarea name="vision" rows="3"
                                          placeholder="Company vision"
                                          class="form-control @error('vision') is-invalid
                                       @enderror" required>{{old('vision')??  $company->vision}}</textarea>
                            @error('vision')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                                <textarea name="goal" rows="3"
                                          placeholder="Company Goal"
                                          class="form-control @error('goal') is-invalid
                                       @enderror" required>{{old('goal')??  $company->goal}}</textarea>
                            @error('goal')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea name="mission" rows="3"
                                      placeholder="Company mission"
                                      class="form-control @error('mission') is-invalid
                                       @enderror" required>{{old('mission') ??  $company->mission}}</textarea>
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
                                       @enderror" required>{{old('what_we_do') ??  $company->what_we_do}}</textarea>
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
                                       @enderror" required>{{old('who_we_are') ??  $company->who_we_are}}</textarea>
                            @error('who_we_are')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-4  mb-2">
                                <div class="form-group">
                                    <label>Image Post</label>
                                    <input type="file" name="image"
                                           class="form-control" onchange="previewImage(event)">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <label>Current Image Post</label>
                                <img id="" src="../../img/company/{{$company->url}}" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <label>New Image Post</label>
                                <img id="preview" src="" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Update
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
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('preview');
                output.src = reader.result;
                // Store the image name in a hidden input field
                document.getElementById('image_name').value = event.target.files[0].name;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.material').addClass('show').removeClass('d-none');
                    $('.others').addClass('d-none').removeClass('show');
                    $('.fuel').addClass('d-none').removeClass('show');
                }
                if(status==='2'){
                    $('.material').addClass('d-none').removeClass('show');
                    $('.others').addClass('show').removeClass('d-none');
                    $('.fuel').addClass('d-none').removeClass('show');
                }
                if(status==='3'){
                    $('.material').addClass('d-none').removeClass('show');
                    $('.others').addClass('d-none').removeClass('show');
                    $('.fuel').addClass('show').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
