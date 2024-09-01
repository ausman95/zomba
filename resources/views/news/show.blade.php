@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Church News
        </h4>
        <p>
            Manage News
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('news.index')}}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$news->title}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        @if(request()->user()->designation=='hr' || request()->user()->designation=='administrator')
            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#material">
                <i class="fa fa-plus-circle"></i> Add Paragraph
            </button>
            <div class="modal " id="material" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Adding Paragraph</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('paragraphs.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                                <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                                <input type="hidden"  name="new_id" value="{{$news->id}}" required>

                                <div class="form-group">
                                    <label>Requirement Item</label>
                                    <textarea name="item" rows="3" required
                                              class="form-control @error('name')
                                       is-invalid @enderror">{{old('name')}}</textarea>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="mt-2">

            <div class="row">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">{{$news->title}}</caption>
                                            <tbody>
                                            <tr>
                                                <td>Title</td>
                                                <td>{{$news->title}}</td>
                                            </tr>
                                            <tr>
                                                <td>Image</td>
                                                <td>
                                                    <img id="preview" src="../img/blog/{{$news->url}}" alt="" style="max-width: 100%; max-height: 200px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Paragraph</td>
                                                <td>
                                                    @foreach($news->paragraph as $paragraph)
                                                        <li style="text-align: justify">{{$paragraph->item}}</li>
                                                        @if(request()->user()->designation=='hr' || request()->user()->designation=='administrator')
                                                            <a href="{{route('paragraph.delist',$paragraph->id)}}"
                                                               class="btn btn-danger btn-md rounded-0" title="Remove item">
                                                                <i class="fa fa-trash-alt"> </i>Delete</a>
                                                        @endif
                                                        <hr>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$news->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$news->updated_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update By</td>
                                                <td>{{\App\Models\Budget::userName($news->updated_by)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                                <td>{{\App\Models\Budget::userName($news->created_by)}}</td>
                                            </tr>
                                        </table>
                                        @if(request()->user()->designation=='hr' || request()->user()->designation=='administrator')
                                            <div class="mt-3">
                                                <div>
                                                    <a href="{{route('news.edit',$news->id)}}"
                                                       class="btn btn-primary rounded-0" style="margin: 2px">
                                                        <i class="fa fa-edit"></i>Update
                                                    </a>
                                                    <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                        <i class="fa fa-trash"></i>Delete
                                                    </button>
                                                    <form action="{{route('news.destroy',$news->id)}}" method="POST" id="delete-form">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="id" value="{{$news->id}}">
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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

            function confirmationWindow(title, message, primaryLabel, callback) {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: primaryLabel
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                    }
                })
            }
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Item ?",
                    "Yes,Delete", function () {
                        $("#delete-form").submit();
                    });
            });
        })
    </script>
@stop
