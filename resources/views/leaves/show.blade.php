@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-file-alt"></i>Employee Leave
        </h4>
        <p>
            Employee Leave
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('leaves.index')}}">Employee Leave</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$labourer->name}} Leave Statement</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div style="overflow-x:auto;">
                            <table class="table  table-bordered table-hover table-striped">
                                <caption style=" caption-side: top; text-align: center">{{strtoupper($labourer->name)}} LEAVE STATEMENT</caption>
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>LEAVE DAYS TAKEN</th>
                                    <th>BALANCE</th>
                                    <th>COMPASSIONATE TAKEN</th>
                                    <th>COMPASSIONATE BALANCE</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>CREATED ON</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c= 1;?>
                                @foreach($leaves as $leave)
                                    <tr>
                                        <td>{{$c++}}</td>
                                        <td>{{ucwords($leave->leave_days) }}</td>
                                        <td>{{ucwords($leave->leave_balance) }}</td>
                                        <td>{{ucwords($leave->compassionate_days) }}</td>
                                        <td>{{ucwords($leave->compassionate_balance) }}</td>
                                        <td>
                                            @if($leave->start_date!='0000-00-00')
                                            {{date('d F Y', strtotime($leave->start_date)) }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($leave->start_date!='0000-00-00')
                                                {{date('d F Y', strtotime($leave->end_date)) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{date('d F Y', strtotime($leave->created_at)) }}</td>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this account?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
