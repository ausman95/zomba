@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Reports
        </h4>
        <p>
            Show User Weekly Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home.index')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('reports.index')}}">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$report->id}}</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-4">
            <div class="row">
                <div class="col-sm-12 mb-4 col-md-3">
                    <div class="text-black-50">
                        Weekly Report For
                    </div>
                    <h4>
                        {{$report->name}}
                    </h4>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Requisition No.
                        </div>
                        <h4>
                            {{$requisition->id}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Requisition Time.
                        </div>
                        <h4>
                            {{$requisition->created_at}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Status
                        </div>
                        <h4>
                            {{strtoupper($requisition->status)}}
                        </h4>
                    </div>
                    @if(request()->user()->designation==='administrator' || request()->user()->designation==='accountant')
                        @if($requisition->status=='closed')
                            <a href="{{route('request.generate')."?id={$requisition->id}"}}" target="_blank" class="btn btn-primary rounded-0" style="margin: 2px">
                                <i class="fa fa-vote-yea"></i> Generate Voucher
                            </a>
                        @endif
                    @endif
                    @if(request()->user()->designation==='administrator')
                        @if($requisition->status=='pending' || $requisition->status=='in-order')
{{--                            @if(request()->user()->id!=$requisition->user_id)--}}
                            @if($requisition->checkProjectUser($requisition->id,$requisition->status)==false)
                        <div class="mt-4">
                        <a href="#approve-requisition" id="requisition-approve-btn"
                           data-target-url="{{route('request.approval',$requisition->id)}}">
                            <button class="btn btn-primary rounded-0" style="margin: 2px">
                                <i class="fa fa-vote-yea"></i> Approve Requisition
                            </button>
                        </a>
                        <a href="#remove-requisition" id="requisition-cancel-btn"
                           data-target-url="{{route('requisitions.destroy',$requisition->id)}}">
                            <button class="btn btn-danger rounded-0" style="margin: 2px">
                                <i class="fa fa-times-circle"></i> Cancel Requisition
                            </button>
                        </a>
                    </div>
                            @endif
                            @endif
                        @endif

                </div>
                <div class="col-sm-12 mb-4 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Requisition Items
                        </div>
                        @if(count($requisition->requisitionItems) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <div class="card-body" style="overflow: auto">
                                <div class="text-center">
                            <div class="ul list-group list-group-flush">
                                <?php $sum = 0;?>
                                    @if($requisition->status=='closed')
                                        <div style="overflow-x:auto;">
                                            <table class="table  table-primary table-bordered table-hover table-striped" id="data-table">
                                                <caption style=" caption-side: top; text-align: center">Requisition Items</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>MATERIAL</th>
                                                    <th>REASONS</th>
                                                    <th>QUANTITY</th>
                                                    <th>PRICE / ITEM (MK)</th>
                                                    <th>TOTAL (MK)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $c= 1;?>
                                                @foreach($requisition->requisitionItems  as $list_item)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{ucwords($list_item->material->name.' - '.$list_item->material->specifications) }}</td>
                                                        <td>{{ucwords($list_item->reason) }}</td>
                                                        <td>{{ucwords($list_item->quantity.' - '.$list_item->material->units) }}</td>
                                                        <td>
                                                            @if($list_item->getPrice($list_item->material_id)!=false)
                                                                {{number_format($list_item->getPrice($list_item->material_id))}}
                                                            @else
                                                                {{'Not Available'}}
                                                            @endif</td>
                                                        <td class="pt-1">
                                                            @if($list_item->getPrice($list_item->material_id)!=false)
                                                                {{number_format($list_item->quantity*$list_item->getPrice($list_item->material_id))}}
                                                            @else
                                                                {{'Not Available'}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                @foreach($requisition->requisitionItems  as $list_item)
                                    <li class="list-group-item justify-content-between d-flex">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                {{$loop->iteration}}
                                            </div>
                                            <div>
                                                {{$list_item->material->name}}
                                                <div class="text-black-50">
                                                    {{$list_item->material->specifications}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div>

                                                <div class="text-black-50">
                                                    Reasons:   {{$list_item->reason}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="quantity{{$list_item->id}}">

                                                <div class="text-black-50 ">
                                                    Quantity:   {{$list_item->quantity.' '.$list_item->material->units}}
                                                </div>
                                            </div>
                                            <div class="d-none request{{$list_item->id}}">
                                                <form action="{{route('request.update',$list_item->id)}}" method="POST" autocomplete="off">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$requisition->id}}">
                                                    <input type="hidden" name="request_id" value="{{$list_item->id}}">

                                                    <div class="form-group">
                                                        <input type="number" name="quantity"
                                                               class="form-control @error('quantity') is-invalid @enderror"
                                                               value="{{$list_item->quantity}}"
                                                               placeholder="Change Quantity" >
                                                        @error('name')
                                                        <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-md btn-primary me-2">
                                                            <i class="fa fa-marker"></i>Change Qty
                                                        </button>
                                                        <button type="reset" class="btn btn-md btn-secondary Cancel"
                                                        name = "{{$list_item->id}}">
                                                            <i class="fa fa-times-circle"></i>Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div>
                                                <div class="">
                                                    Unit Price:
                                                    @if($list_item->getPrice($list_item->material_id)!=false)
                                                    MK{{number_format($list_item->getPrice($list_item->material_id))}}
                                                    @else
                                                    {{'Not Available'}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div>
                                                <div class="">
                                                    <strong>
                                                        @if($list_item->getPrice($list_item->material_id)!=false)
                                                        Total Price:
                                                        MK {{number_format($list_item->quantity*$list_item->getPrice($list_item->material_id))}}
                                                        @else
                                                            {{'Not Available'}}
                                                        @endif
                                                    </strong>
                                                </div>
                                                <div class="text-black-50 d-none">
                                                    @if($list_item->getPrice($list_item->material_id)!=false)
                                                    Total Price:
                                                    MK {{number_format($sum = $sum+$list_item->quantity*$list_item->getPrice($list_item->material_id))}}
                                                    @else
                                                        {{'Not Available'}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if(request()->user()->designation==='administrator')
                                            @if($requisition->checkProjectUser($requisition->id,$requisition->status)==false)
                                                @if($requisition->status=='pending' || $requisition->status=='in-order')
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary editBtn quantity{{$list_item->id}}"
                                                    name="{{$list_item->id}}"
                                                    style="margin:2px"
                                                    title="Edit item">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button
                                                class="btn btn-danger delete-btn quantity"
                                                data-target-url="{{route('requisitions.items.destroy',$list_item->id)}}"
                                                title="Remove item" style="margin:2px"> <i
                                                    class="fa fa-minus-circle"></i>
                                            </button>

                                        </div>
                                                @endif
                                        @endif
                                        @endif
                                    </li>
                                    @endforeach
                                    <hr>
                                    <li class="list-group-item justify-content-between d-flex">
                                        <div class="d-flex">

                                        </div>
                                        <div class="d-flex">

                                        </div>
                                        <div class="d-flex">

                                        </div>
                                        <div class="d-flex">

                                        </div>
                                        <div class="d-flex">
                                            <div>
                                                @if($list_item->getPrice($list_item->material_id)!=false)
                                                <div class="">
                                               <strong> <u> Total Requisition: MK  {{number_format($sum)}}</u>
                                               </strong>
                                                </div>
                                                    @else
                                                {{'Not Available'}}
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                            </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <form action="" id="approve-form" method="POST">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="{{$requisition->id}}">
        <input type="hidden" name="status" value="{{$requisition->status}}">
    </form>
    <form action="" id="delete-form" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="id" value="{{$requisition->id}}">
    </form>
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
            $(".editBtn").on('click', function () {
                let value = $(this).attr('name');
                let request = '.request'+value;
                let qty = '.quantity'+value;
                $(request).removeClass('d-none').addClass('show');
                $(qty).removeClass('show').addClass('d-none');
            });
            $(".Cancel").on('click', function () {
                let value = $(this).attr('name');
                let request = '.request'+value;
                let qty = '.quantity'+value;
                $(request).removeClass('show').addClass('d-none');
                $(qty).removeClass('d-none').addClass('show');
            });
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this requisition item?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
            $("#requisition-approve-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#approve-form").attr('action', $url);
                confirmationWindow("Confirm Approval", "Are you sure you want to approve this requisition?", "Yes,Continue", function () {
                    $("#approve-form").submit();
                })
            });

            //requisition-cancel-btn
            $("#requisition-cancel-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Cancelling", "Are you sure you want to cancel this requisition?", "Yes,Continue", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop
