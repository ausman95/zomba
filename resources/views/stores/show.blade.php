@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Stock Flow list
        </h4>
        <p>
            Manage Stock Flow list
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('stores.index')}}">Stores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Stock Flows</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($flows->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Stock!
                                @else
                                    <table class="table table-borderless table-striped" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Material</th>
                                            <th>Amount / Item / KG (MK)</th>
                                            <th>Qty in Kgs</th>
                                            <th class="d-none">Accumulate Qty in Kgs</th>
                                            <th>Total Amount</th>
                                            <th>Specification</th>
                                            <th>Flow (To)</th>
                                            <th>Qty in Stores (Remaining)</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1; $balance = 0;$balance_qty = 0;$qty = 0;?>
                                        @foreach($flows as $flow)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td >{{ucwords($flow->material->name) }}</td>
                                                <td style="text-align: center">{{number_format($flow->amount) }}</td>
                                                <td style="text-align: center">
                                                    @if($flow->flow==4)
                                                        {{number_format( $qty = $qty+$flow->quantity)}}
                                                    @else
                                                        - {{number_format($flow->quantity)}}
                                                    @endif</td>
                                                <td style="text-align: center" class="d-none">
                                                    @if($flow->flow==4)
                                                        {{number_format($balance_qty)}}
                                                    @else
                                                        {{number_format($balance_qty = $balance_qty+$flow->quantity)}}
                                                    @endif</td>
                                                <td style="text-align: center">{{number_format($flow->quantity*$flow->amount) }}</td>
                                                <td>{{ucwords($flow->material->specifications) }}</td>
                                                <td>
                                                    @if($flow->flow==1)
                                                        {{ucwords('Projects') }}
                                                    @elseif($flow->flow==2)
                                                        {{'Administration'}}
                                                    @elseif($flow->flow==3)
                                                        {{'Disposed/Damaged'}}
                                                    @elseif($flow->flow==4)
                                                        {{'Returned to Stores'}}
                                                    @else
                                                        {{'LOST TRACK'}}
                                                    @endif
                                                </td>
                                                <td style="text-align: center">{{number_format($balance = $flow->getBalance($flow->material_id)-$balance_qty+$qty) }}</td>
                                                <td>{{ucwords($flow->material->created_at) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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

            const myTable = document.querySelector("#data-table");
            const dataTable = new simpleDatatables.DataTable(myTable, {
                layout: {
                    top: "{search}",
                    bottom: "{pager}{info}"
                },
                header: true
            });
        })
    </script>
@stop
