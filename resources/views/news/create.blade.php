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
                <li class="breadcrumb-item"><a href="{{route('news.determine')}}">step 1</a></li>
                <li class="breadcrumb-item active" aria-current="page">Step 2</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                News.
            </p>
            <div class="mt-4 row">
                <div class="row">
                    <div class="col-sm-12 col-md-4  mb-2">
                        <div class="card">
                            <div class="card card-header">
                             Paragraphs
                            </div>
                            <div class="card-body px-1">
                                <div class="col-sm-12 col-md-12  mb-2">
                                    <form action="{{route('news.enlist')}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Paragraph in Summary</label>
                                            <div class="form-group">
                                     <textarea name="duties" rows="7"
                                               class="form-control @error('duties')
                                                is-invalid @enderror"
                                               placeholder="One Paragraph at a time">{{old('description')}}</textarea>
                                                @error('duties')
                                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary rounded-0" type="submit">
                                                <i class="fa fa-plus-circle"></i>  Add Paragraph
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-8  mb-2">
                        <div class="card">
                            <div class="card card-header">
                                Paragraphs
                            </div>
                            <div class="card-body p-5" style="min-height: 19em;">
                                <div class="col-sm-12 mb-2 col-md-12">
                                        @if(count($report_list) === 0)
                                        <div class="text-center">
                                            List is empty
                                        </div>
                                        @else
                                            <div class="ul list-group list-group-flush">
                                                @foreach($report_list  as $list_item)
                                                    <li class="list-group-item justify-content-between d-flex">
                                                        <div class="d-flex">
                                                            <div class="me-3">
                                                                {{$loop->iteration}}
                                                            </div>
                                                            <div style="text-align: justify">
                                                                {{$list_item['duties']}}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="me-4">

                                                            </div>
                                                            <div>
                                                                <a href="{{route('news.delist',$list_item['duties'])}}"
                                                                   title="Remove item"> <i
                                                                        class="fa fa-minus-circle text-danger"></i></a>
                                                            </div>

                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer" style="text-align: center">
                    <form action="{{route('news.save')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-4  mb-2">
                                <div class="form-group">
                                    <label>Image Post</label>
                                    <input type="file" name="image" class="form-control" onchange="previewImage(event)" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <img id="preview" src="#" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-pencil-alt"></i>  Publish
                            </button>

                        </div>
                    </form>
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
