@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Company Information
        </h4>
        <p>
            Manage Information
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('informations.index')}}">Information</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$company->id}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <tbody>
                                            <tr>
                                                <td>Mission</td>
                                                <td>{{$company->mission}}</td>
                                            </tr>
                                            <tr>
                                                <td>Vision</td>
                                                <td>{{$company->vision}}</td>
                                            </tr>
                                            <tr>
                                                <td>Goal</td>
                                                <td>{{$company->goal}}</td>
                                            </tr>
                                            <tr>
                                                <td>What We Do</td>
                                                <td>{{$company->what_we_do}}</td>
                                            </tr>
                                            <tr>
                                                <td>Who we Are </td>
                                                <td>{{$company->who_we_are}}</td>
                                            </tr>
                                            <tr>
                                                <td>Image</td>
                                                <td>
                                                    <img id="preview" src="../img/blog/{{$company->url}}" alt="" style="max-width: 100%; max-height: 200px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$company->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$company->updated_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update By</td>
                                                <td>{{\App\Models\Budget::userName($company->updated_by)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                                <td>{{\App\Models\Budget::userName($company->created_by)}}</td>
                                            </tr>
                                        </table>
                                            <div class="mt-3">
                                                <div>
                                                    <a href="{{route('informations.edit',$company->id)}}"
                                                       class="btn btn-primary rounded-0" style="margin: 2px">
                                                        <i class="fa fa-edit"></i>Update
                                                    </a>
                                                    @if( request()->user()->designation=='administrator')
                                                    <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                        <i class="fa fa-trash"></i>Delete
                                                    </button>
                                                    @endif
                                                    <form action="{{route('informations.destroy',$company->id)}}" method="POST" id="delete-form">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="id" value="{{$company->id}}">
                                                    </form>
                                                </div>
                                            </div>
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
