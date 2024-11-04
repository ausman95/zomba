@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i>Church Receipt
        </h4>
        <p>
            Manage Church Receipt
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>

                @if(request()->has('verified') && request()->input('verified') == 0)
                    <li class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></li>
                @endif

                @if(request()->has('verified') && request()->input('verified') == 2)
                    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                @endif

                @if(request()->has('verified') && request()->input('verified') == 1)
                    <li class="breadcrumb-item"><a href="{{ route('receipt.unverified') }}">Un~Verified Transactions</a></li>
                @endif

                <li class="breadcrumb-item active" aria-current="page">{{ $transaction->id }}</li>
            </ol>
        </nav>


        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-5 mb-2 ">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Date</td>
                                        <td>{{date('d F Y', strtotime($transaction->t_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ref</td>
                                        <td>{{$transaction->reference}}</td>
                                    </tr>
                                    <tr>
                                        <td>Other</td>
                                        <td>{{$transaction->specification}}</td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td>{{$transaction->name}}</td>
                                    </tr>
                                    @if($transaction->type==5)
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{\App\Models\MemberPayment::where(['member_payments.payment_id'=>$transaction->id])
                                                    ->join('members', 'members.id', '=', 'member_payments.member_id')
                                                    ->first()->phone_number}}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Amount</td>
                                        <td>MK {{number_format($transaction->amount,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Account</td>
                                        <td>{{$transaction->account->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <td>{{$transaction->bank->bank_name.' - '.$transaction->bank->account_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>
                                            @if($transaction->type==5)
                                                MEMBERS
                                            @elseif($transaction->type==6)
                                                HOME CHURCH
                                            @elseif($transaction->type==7)
                                                MINISTRIES
                                            @else
                                                OTHERS
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Method</td>
                                        <td>
                                            @if($transaction->payment_method==1)
                                                CASH
                                            @elseif($transaction->payment_method==3)
                                                CHEQUE
                                            @else
                                                ONLINE TRANSFER
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <th>{{ucwords($transaction->status == 1 ? "VERIFIED" : "UN~VERIFIED") }}</th>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{date('d F Y', strtotime($transaction->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Update ON</td>
                                        <td>{{date('d F Y', strtotime($transaction->updated_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Update By</td>
                                        <td>{{\App\Models\Budget::userName($transaction->updated_by)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{@\App\Models\Budget::userName($transaction->created_by)}}</td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <div>
                                        <a href="{{route('payments.edit',$transaction->id).'?verified='.$_GET['verified']}}"
                                           class="btn btn-warning rounded-0" style="margin: 2px">
                                            <i class="fa fa-edit"></i>Update
                                        </a>
                                        @if($transaction->status==0)
                                            @if(request()->user()->id !=$transaction->created_by)
                                        <button class="btn btn-success  rounded-0" id="delete-btn" style="margin: 5px">
                                            <i class="fa fa-check-circle"></i>Verify
                                        </button>
                                            @endif
                                        <form action="{{route('payments.destroy',$transaction->id)}}" method="POST" id="delete-form">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="id" value="{{$transaction->id}}">
                                            <input type="hidden" name="type" value="{{$transaction->account_id}}">
                                            <input type="hidden" name="amount" value="{{$transaction->amount}}">
                                            <input type="hidden" name="account" value="{{$transaction->account->name}}">
                                        </form>
                                        @endif
                                        @if($transaction->account_id==1)
                                            <a href="{{route('member-receipt.generate')."?id=".$transaction->id}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px" target="_blank">
                                                <i class="fa fa-print"></i>Receipt
                                            </a>
{{--                                        @elseif($transaction->type==6)--}}
{{--                                            <a href="{{route('church-receipt.generate')."?id=".$transaction->id}}"--}}
{{--                                               class="btn btn-primary rounded-0" style="margin: 2px" target="_blank">--}}
{{--                                                <i class="fa fa-print"></i>Receipt--}}
{{--                                            </a>--}}
{{--                                        @elseif($transaction->type==7)--}}
{{--                                            <a href="{{route('ministry-receipt.generate')."?id=".$transaction->id}}"--}}
{{--                                               class="btn btn-primary rounded-0" style="margin: 2px" target="_blank">--}}
{{--                                                <i class="fa fa-print"></i>Receipt--}}
{{--                                            </a>--}}
                                        @endif

                                    </div>
                                </div>
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
                confirmationWindow("Confirm Verification", "Are you sure you want Verify this Record ?", "Yes,Continue", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
