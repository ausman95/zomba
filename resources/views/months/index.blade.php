@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-calendar-check"></i>Months
        </h4>
        <p>
            Manage Months
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Months</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('months.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-user-plus"></i>New Month
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($months->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no months!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">MONTHS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>START DATE</th>
                                            <th>END DATE</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($months as $month)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($month->name) }}</td>
                                                <td>{{date('d F Y', strtotime($month->start_date)) }}</td>
                                                <td>{{date('d F Y', strtotime($month->end_date)) }}</td>
                                                <th>
                                                   @if($month->start_date <= date('Y-m-d') && $month->end_date >= date('Y-m-d'))
                                                       ACTIVE
                                                    @elseif($month->end_date < date('Y-m-d'))
                                                      CLOSED
                                                    @else
                                                       NOT ACTIVE
                                                    @endif
                                                </th>
                                                <td class="pt-1">
                                                    <a href="{{route('months.show',$month->id)}}"
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

