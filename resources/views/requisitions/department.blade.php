@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Requisitions
        </h4>
        <p>
            Department Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('requisitions.index')}}">Requisitions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$requisition->id}}</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-4">
            <div class="row">
                <div class="col-sm-12 mb-4 col-md-3">
                    <div class="text-black-50">
                        Department
                    </div>
                    <h4>
                        {{$requisition->department->name}}
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
                            {{date('d F Y', strtotime($requisition->created_at))}}
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Status
                        </div>
                        <h4>
                            @if($requisition->status==='in-order')
                                <p style="color: red">@  {{$requisition->findUser($requisition->id)}}, CHECKED & APPROVED </p>
                            @elseif($requisition->status==='closed')
                                <p style="color: red"> APPROVED by CEO </p>
                            @elseif($requisition->status==='acknowledged')
                                <p style="color: red"> @ {{$requisition->findUser($requisition->id)}}, Acknowledged and Closed </p>
                            @elseif($requisition->status==='Cancelled')
                                <p style="color: red"> @ {{$requisition->findUser($requisition->id)}}, Cancelled and Closed </p>
                            @else
                              <p style="color: red">NEW REQUISITION WAITING TO BE CHECKED & APPROVED</p>
                            @endif
                        </h4>
                    </div>
                    <div class="mt-3">
                        <div class="text-black-50">
                            Other Notes
                        </div>
                        @foreach($notes as $note)
                            <p style="color: red">
                               @ {{$note->user->name}},   {{strtoupper($note->notes)}}
                            </p>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        @if(request()->user()->designation==='administrator' || request()->user()->designation==='accountant')
                            @if($requisition->status=='closed')
                                <a href="{{route('requests.generate')."?id={$requisition->id}"}}" target="_blank" class="btn btn-primary rounded-0" style="margin: 2px">
                                    <i class="fa fa-vote-yea"></i> Generate Voucher
                                </a>
                                <a href="#acknowledge-requisition" id="requisition-acknowledge-btn"
                                   data-target-url="{{route('request.acknowledge',$requisition->id)}}">
                                    <button class="btn btn-primary" style="margin: 2px">
                                       Acknowledge Requisition
                                    </button>
                                </a>
                            @endif
                        @endif
                        @if(request()->user()->level>1)
                                @if($requisition->status=='in-order')
                                    @if($requisition->checkProjectUser($requisition->id)!=request()->user()->level)
                                        <form action="" id="approve-form" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="POST">
                                            <input type="hidden" name="id" value="{{$requisition->id}}">
                                            <input type="hidden" name="status" value="{{$requisition->status}}">
                                            <div class="form-group">
                                                <label for="">Notes</label>
                                                <div class="form-group">
                                     <textarea name="notes" rows="2"
                                               class="form-control @error('notes') is-invalid @enderror" placeholder="Notes Before Approval (Optional)">{{old('notes')}}</textarea>
                                                    @error('notes')
                                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </form>
                                        <a href="#approve-requisition" id="requisition-approve-btn"
                                           data-target-url="{{route('request.approve',$requisition->id)}}">
                                            <button class="btn btn-primary" style="margin: 2px">
                                                <i class="fa fa-vote-yea"></i> Approve Requisition
                                            </button>
                                        </a>
                                        <a href="#remove-requisition" id="requisition-cancel-btn"
                                           data-target-url="{{route('request.cancel',$requisition->id)}}">
                                            <button class="btn btn-danger" style="margin: 2px">
                                                <i class="fa fa-times-circle"></i> Cancel Requisition
                                            </button>
                                        </a>
                                    @endif
                                @endif
                            @if($requisition->status=='pending')
                                    <form action="" id="approve-form" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="id" value="{{$requisition->id}}">
                                        <input type="hidden" name="status" value="{{$requisition->status}}">
                                        <div class="form-group">
                                            <label for="">Notes</label>
                                            <div class="form-group">
                                     <textarea name="notes" rows="2"
                                               class="form-control @error('notes') is-invalid @enderror" placeholder="Notes Before Approval (Optional)">{{old('notes')}}</textarea>
                                                @error('notes')
                                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </form>
                                    <a href="#approve-requisition" id="requisition-approve-btn"
                                       data-target-url="{{route('request.approve',$requisition->id)}}">
                                        <button class="btn btn-primary" style="margin: 2px">
                                            <i class="fa fa-vote-yea"></i> Approve Requisition
                                        </button>
                                    </a>
                                    <a href="#remove-requisition" id="requisition-cancel-btn"
                                       data-target-url="{{route('request.cancel',$requisition->id)}}">
                                        <button class="btn btn-danger" style="margin: 2px">
                                            <i class="fa fa-times-circle"></i> Cancel Requisition
                                        </button>
                                    </a>
                            @endif
                            @else
                                @if($requisition->status=='Cancelled')
                                    <a href="#re-requisition" id="requisition-request-btn"
                                       data-target-url="{{route('request.request',$requisition->id)}}">
                                        <button class="btn btn-primary" style="margin: 2px">
                                        Re~Request Requisition
                                        </button>
                                    </a>
                                    @endif
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 mb-4 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Requisition Items
                        </div>
                        @if(count($requisition->departmentRequisition) === 0)
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
                                                        <th>DESCRIPTION</th>
                                                        <th>AMOUNT (MK)</th>
                                                        <th>REASONS</th>
                                                        <th>SUPPLIER</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  $c= 1;?>
                                                    @foreach($requisition->departmentRequisition  as $list_item)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($list_item->description) }}</td>
                                                            <td>{{number_format($list_item->amount) }}</td>
                                                            <td>{{ucwords($list_item->reason) }}</td>
                                                            <td>{{ucwords($list_item->personale) }}</td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="card " style="min-height: 30em;">
                                                <div class="card-body px-1">

                                                    <div style="overflow-x:auto;">
                                                        <table class="table table-bordered table-primary table-hover table-striped" id="data-table">
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>SPECIFICATIONS</th>
                                                                <th>SUPPLIER</th>
                                                                <th>REASON</th>
                                                                <th>AMOUNT (MK)</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $c = 1; $sum = 0?>
                                                            @foreach($requisition->departmentRequisition  as $list_item)
                                                                <tr>
                                                                    <td>{{$c++}}</td>
                                                                    <td style="text-align: left">{{$list_item->description}}</td>
                                                                    <td style="text-align: left"> {{$list_item->personale}}</td>
                                                                    <td style="text-align: left"> {{$list_item->reason}}</td>
                                                                    <td class="d-none"> {{$sum = $sum+$list_item->amount}}</td>
                                                                    <td style="text-align: left">{{number_format($list_item->amount)}}</td>
                                                                    <td class="pt-1">
                                                                        @if($requisition->user_id==request()->user()->id)
                                                                            @if($requisition->status=='pending' || $requisition->status=='Cancelled')                                                                            <div class="d-flex">
                                                                                <button
                                                                                    class="btn btn-danger delete-btn quantity"
                                                                                    data-target-url="{{route('request.trash',$list_item->id)}}"
                                                                                    title="Remove item" style="margin:2px"> <i
                                                                                        class="fa fa-minus-circle"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                        @endif
                                                                        @if(request()->user()->level>1)
                                                                            @if($requisition->checkProjectUser($requisition->id)!=request()->user()->level)
                                                                                @if($requisition->status=='pending' || $requisition->status=='in-order')
                                                                                    <div class="d-flex">
                                                                                        <button
                                                                                            class="btn btn-danger delete-btn quantity"
                                                                                            data-target-url="{{route('request.trash',$list_item->id)}}"
                                                                                            title="Remove item" style="margin:2px"> <i
                                                                                                class="fa fa-minus-circle"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <th style="text-align: left">Total</th>
                                                                <td></td>
                                                                <td></td>
                                                                <th style="text-align: left">{{number_format($sum)}}</th>
                                                                <td class="pt-1">
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        <form action="" id="request-form" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" value="{{$requisition->id}}">
        </form>
        <form action="" id="acknowledge-form" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" value="{{$requisition->id}}">
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

                    //requisition-cancel-btn
                    $("#requisition-cancel-btn").on('click', function () {
                        $url = $(this).attr('data-target-url');

                        $("#delete-form").attr('action', $url);
                        confirmationWindow("Confirm Cancelling", "Are you sure you want to cancel this requisition?", "Yes,Continue", function () {
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

                    $("#requisition-request-btn").on('click', function () {
                        $url = $(this).attr('data-target-url');
                        $("#request-form").attr('action', $url);
                        confirmationWindow("Confirm Request", "Are you sure you want to Request Again this requisition?", "Yes,Continue", function () {
                            $("#request-form").submit();
                        })
                    });
                    $("#requisition-acknowledge-btn").on('click', function () {
                        $url = $(this).attr('data-target-url');
                        $("#acknowledge-form").attr('action', $url);
                        confirmationWindow("Confirm Request", "Are you sure you want to acknowledge the generation of Voucher of this requisition?", "Yes,Continue", function () {
                            $("#acknowledge-form").submit();
                        })
                    });
                })
            </script>
@stop
