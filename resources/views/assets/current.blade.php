@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-folder-open"></i> &nbsp; Current Assets
        </h4>
        <p>
            Current Assets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Current Assets</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-8">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($banks->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Bank Accounts!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered  table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">BANKS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>AMOUNT (MK)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php  $c= 1;?>
                                            @foreach($banks as $bank)
                                                @if($bank->getBalance()>0)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{ucwords($bank->account_name) }}</td>
                                                    <th>
                                                         {{number_format($bank->getBalance(),2)}}
                                                    </th>
                                                </tr>
                                                @endif
                                            @endforeach
                                                @foreach($suppliers as $supplier)
                                                    @if($supplier->getBalance($supplier->id)>0)
                                                        <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{$supplier->name}}</td>
                                                        <th>{{number_format($supplier->getBalance($supplier->id),2)}}</th>
                                                    </tr>
                                                    @endif
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

