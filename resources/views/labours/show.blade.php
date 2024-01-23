@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Positions
        </h4>
        <p>
            Manage Positions
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labours.index')}}">Positions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$labour->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$labour->name}} INFORMATION</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{$labour->name}}</td>
                                            </tr>

                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$labour->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$labour->updated_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($labour->soft_delete==1)
                                                        <p style="color: red">Deleted, and Reserved for Audit</p>
                                                    @else
                                                        Active
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="">
                                            <a href="{{route('labours.edit',$labour->id)}}"
                                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                <i class="fa fa-trash"></i>Delete
                                            </button>
                                            <form action="{{route('labours.destroy',$labour->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="{{$labour->id}}">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>{{$labour->name}}s
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            @if($labourers->count() === 0)
                                <i class="fa fa-info-circle"></i>There are no  labourers!
                            @else
                                <div style="overflow-x:auto;">
                                    <table class="table  table1 table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">LABOURERS</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>GENDER</th>
                                            <th>DEPARTMENT</th>
                                            <th>PHONE</th>
{{--                                            <th>AGE</th>--}}
                                            <th>PROFESSIONAL</th>
{{--                                            <th>EXPERIENCE</th>--}}
                                            <th>TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($labourers as $labourer)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$labourer->name}}</td>
                                                <td>{{$labourer->gender}}</td>
                                                <td>{{$labourer->department->name}}</td>
                                                <td>{{$labourer->phone_number}}</td>
{{--                                                <td>{{$labourer->getAgeAttribute()}}</td>--}}
                                                <td>{{$labourer->labour->name}}</td>
{{--                                                <td>{{$labourer->period}}</td>--}}
                                                <td>
                                                    @if($labourer->type==1)
                                                        {{'Employed'}}
                                                    @elseif($labourer->type==2)
                                                        {{'Sub-Contactor'}}
                                                    @else{
                                                    {{'Temporary Workers'}}
                                                    @endif
                                                </td>
                                                <td class="pt-1">
                                                    <a href="{{route('labourers.show',$labourer->id)}}"
                                                       class="btn btn-md btn-primary rounded-0">
                                                        <i class="fa fa-list-ol"></i>  Manage
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
