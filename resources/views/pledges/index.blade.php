@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Pledges
        </h4>
        <p>
            Manage Pledges
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pledges</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('pledges.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Pledge
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            @if(!@$pledges)
                                <div class="text-center">
                                    <div class="alert alert-danger">
                                        Pledges not available at the moment!.
                                    </div>
                                </div>
                            @else
                                    <div class="card " style="min-height: 30em;">
                                        <div class="card-body px-1">
                                            @if($pledges->count() === 0)
                                                <i class="fa fa-info-circle"></i>There are no Pledges!
                                            @else
                                                <div style="overflow-x:auto;">
                                                    <table class="table  table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">Pledges</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>DATE</th>
                                                            <th>MEMBER</th>
                                                            <th>HOME CELL</th>
                                                            <th>PHONE</th>
                                                            <th>ACCOUNT</th>
                                                            <th>AMOUNT (MK)</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1;?>
                                                        @foreach($pledges as $pledge)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{date('d F Y', strtotime($pledge->date)) }}</td>
                                                                <td>{{ucwords($pledge->member->name) }}</td>
                                                                <td>{{ucwords($pledge->member->church->name) }}</td>
                                                                <td>{{ucwords($pledge->member->phone_number) }}</td>
                                                                <td>{{ucwords($pledge->account->name) }}</td>
                                                                <th>
                                                                    @if($pledge->type==2)
                                                                        ( {{number_format($pledge->amount,2) }})
                                                                    @else
                                                                         {{number_format($pledge->amount,2) }}
                                                                    @endif
                                                                </th>
                                                                <td class="pt-1">
                                                                    <a href="{{route('pledges.show',$pledge->id)}}"
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
                            @endif
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
