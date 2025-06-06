@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Others
        </h4>
        <p>
            Manage Others
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Others</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
                <a href="{{route('others.create')}}" class="btn btn-primary btn-md rounded-0">
                    <i class="fa fa-user-plus"></i>New Other Payee
                </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($others->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Payee!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">PAYEE</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>CREATED ON</th>
                                            <th>CREATED BY</th>
                                            <th>UPDATED BY</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($others as $other)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($other->name) }}</td>
                                                <td>{{date('d F Y', strtotime($other->created_at)) }}</td>
                                                <td>{{\App\Models\Budget::userName($other->created_by)}}</td>
                                                <td>{{\App\Models\Budget::userName($other->updated_by)}}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('others.show',$other->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                       <i class="fa fa-list-ol"></i> Manage
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
    </div>

@stop
@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
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


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop

