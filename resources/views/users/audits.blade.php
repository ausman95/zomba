@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>User Audit Trail
        </h4>
        <p>
            Audit Trails
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Audit Trails</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
{{--                                    <h3 style="text-align: center">Log Activity Lists</h3>--}}
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">AUDIT TRAILS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>USER</th>
                                            <th>ACCESSED</th>
                                            <th>ACTIONS</th>
                                            <th>TIME</th>
                                        </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($logs as $log)
                                                <tr>
                                                    <td>{{$loop->iteration }}</td>
                                                    <td class="text-primary">{{ @$log->causer->name }}</td>
                                                    <td>{{ $log->log_name }}</td>
                                                    <td class="text-success">{{ $log->description }}</td>
                                                    <td class="text-danger">{{ $log->created_at }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

@stop

@section('scripts')
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
