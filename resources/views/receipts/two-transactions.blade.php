@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Division Receipts
        </h4>
        <p>
           Churches with more than two Transactions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('receipts.index')}}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">More Transactions</li>
            </ol>
        </nav>
        <hr>

            <div class="mt-3">
                <div class="card container-fluid" style="min-height: 30em;">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                            <hr>
                            <form action="{{route('more.generate')}}" method="POST">
                                @csrf
                                @if(request()->user()->division_id==1)
                                    <div class="form-group">
                                        <select name="division_id"
                                                class="form-select select-relation @error('division_id') is-invalid @enderror" style="width: 100%">
                                            @foreach($divisions as $division)
                                                <option value="{{$division->id}}"
                                                    {{old('division_id')===$division->id ? 'selected' : ''}}>{{$division->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                        <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="division_id"
                                           class="form-control @error('division_id') is-invalid @enderror"
                                           value="{{request()->user()->division_id}}">
                                @endif
                                <div class="form-group">
                                    <select name="account_id"
                                            class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}"
                                                {{old('account_id')===$account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <select name="month_id"
                                            class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($months as $month)
                                            <option value="{{$month->id}}"
                                                {{old('month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('month_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <select name="description"
                                            class="form-select select-relation @error('description') is-invalid @enderror" style="width: 100%">
                                        <option value="0">Optional</option>
                                        <option value="1">Districts</option>
                                        <option value="2">Sections</option>
                                        <option value="3">Pastors</option>
                                        <option value="4">Churches</option>
                                        <option value="5">Members</option>
                                    </select>
                                    @error('description')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        Generate
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-11 col-lg-10">
                            <br>
                            <div class="card container-fluid" style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if(!@$receipts)
                                        <div class="text-center">
                                            <div class="alert alert-danger">
                                                Receipt not available at the moment!.
                                            </div>
                                        </div>
                                    @else
                                        <div class="ul list-group list-group-flush">
                                            @if($receipts->count() === 0)
                                                <div class="text-center">
                                                    <div class="alert alert-danger">
                                                        <i class="fa fa-info-circle"></i>There are no Transactions!                                                    </div>
                                                </div>
                                            @else
                                                <div style="overflow-x:auto;">
                                                    @if($description==3 || $account_id ==='2' || $account_id ==='7' )
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION DISTRICT'.$account_name}}  TRANSACTIONS FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>PASTOR</th>
                                                                <th>#</th>
                                                                <th>AMOUNT</th>
                                                                @if($account_id ==='2')
                                                                    <th>NATIONAL (10%)</th>
                                                                @endif

                                                                <th>PHONE</th>
                                                                <th>CHURCH</th>
                                                                <th class="hidden"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $c= 1; $sum = 0;?>
                                                            @foreach($receipts as $receipt)
                                                                <tr>
                                                                    <td>{{$c++}}</td>
                                                                    <td class="hidden">{{$sum = $sum+$receipt->total}}</td>
                                                                    <td>{{ucwords(@$receipt->pastor) }}</td>
                                                                    <td>{{$receipt->pastor_id_number}}</td>
                                                                    <td>{{number_format($receipt->total) }}</td>
                                                                    @if($account_id ==='2')
                                                                        <td>{{number_format(0.1*$receipt->total) }}</td>
                                                                    @endif
                                                                    <td>{{ucwords(@$receipt->phone_number) }}</td>
                                                                    <td>{{ucwords(@$receipt->church) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @elseif($account_id=='1')
                                                        <table class="table table-bordered table-hover  table-striped">
                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION '.$account_name}}  BATCH FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>CHURCH</th>
                                                                <th>PASTOR</th>
                                                                <th>#</th>
                                                                <th>GF AMOUNT</th>
                                                                <th>SUNDAY SCHOOL</th>
                                                                <th>AGREDS</th>
                                                                <th>TOTAL</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $z = 1;  $gf_amount = 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>
                                                            @foreach($receipts as $transaction)
{{--                                                                @if($transaction->church_id_number>1)--}}
                                                                <tr>
                                                                    <td>{{$z++}}</td>
                                                                    <td>{{$transaction->church}}</td>
                                                                    <td>{{$transaction->pastor}}</td>
                                                                    <td>{{$transaction->church_id_number}}</td>
                                                                    <td class="{{$gf_amount = $gf_amount+($transaction->general_amount)}}">{{number_format($transaction->general_amount) }}</td>
                                                                    <td class="{{$sunday_amount = $sunday_amount+$transaction->sunday_amount}}">{{number_format($transaction->sunday_amount) }}</td>
                                                                    <td class="{{$agreds_amount = $agreds_amount+$transaction->agreds_amount}}">{{number_format($transaction->agreds_amount) }}</td>
                                                                    <td>{{number_format($transaction->general_amount+$transaction->sunday_amount+$transaction->agreds_amount) }}</td>
                                                                </tr>
{{--                                                                @endif--}}
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @elseif($description==1 || $account_id ==='160')
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION DISTRICT'.$account_name}}  TRANSACTIONS FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>DISTRICT</th>
                                                                <th>#</th>
                                                                <th>AMOUNT</th>
                                                                @if($account_id ==='160')
                                                                    <th>NATIONAL (10%)</th>
                                                                @endif
                                                                <th>DS</th>
                                                                <th>PHONE</th>
                                                                <th class="hidden"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $c= 1; $sum = 0;$tithe = 0?>
                                                            @foreach($receipts as $receipt)
                                                                <tr>
                                                                    <td>{{$c++}}</td>

                                                                    <td class="hidden">{{$sum = $sum+$receipt->total}}</td>
                                                                    <td>{{ucwords(@$receipt->district) }}</td>
                                                                    <td>{{ucwords(@$receipt->id_number) }}</td>
                                                                    <td>{{number_format($receipt->total) }}</td>
                                                                    @if($account_id ==='160')
                                                                    <td class="{{$tithe = $tithe+(0.1*$receipt->total)}}">{{number_format(0.1*$receipt->total) }}</td>
                                                                    @endif
                                                                    <td>{{ucwords(@$receipt->pastor) }}</td>
                                                                    <td>{{ucwords(@$receipt->phone) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @elseif($description==4)
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION '.$account_name}}  TRANSACTIONS FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>CHURCH</th>
                                                                <th>#</th>
                                                                <th>AMOUNT</th>
                                                                <th>SNR PASTOR</th>
                                                                <th>PHONE</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $c= 1; $sum = 0;?>
                                                            @foreach($receipts as $receipt)
                                                                <tr>
                                                                    <td>{{$c++}}</td>
                                                                    <td>{{ucwords(@$receipt->church) }}</td>
                                                                    <td>{{number_format($receipt->id_number) }}</td>
                                                                    <td>{{number_format($receipt->total) }}</td>
                                                                    <td>{{ucwords(@$receipt->pastor) }}</td>
                                                                    <td>{{ucwords(@$receipt->phone) }}</td>

                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @elseif($description==2)
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption style=" caption-side: top; text-align: center"> SECTION {{$account_name}}  TRANSACTIONS FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>SECTION</th>
                                                                <th>PRESBYTER</th>
                                                                <th>PHONE</th>
                                                                <th>#</th>
                                                                <th>AMOUNT</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $c= 1; $sum = 0;?>
                                                            @foreach($receipts as $receipt)
                                                                <tr>
                                                                    <td>{{$c++}}</td>
                                                                    <td>{{ucwords(@$receipt->section) }}</td>
                                                                    <td>{{ucwords(@$receipt->pastor) }}</td>
                                                                    <td>{{ucwords(@$receipt->phone) }}</td>
                                                                    <td>{{number_format($receipt->id_number) }}</td>
                                                                    <td>{{number_format($receipt->total) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @elseif($description==5)
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption style=" caption-side: top; text-align: center"> MEMBER {{$account_name}}  TRANSACTIONS FOR THE MONTH OF {{$month_name}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>MEMBER</th>
                                                                <th>CHURCH</th>
                                                                <th>PHONE</th>
                                                                <th>#</th>
                                                                <th>AMOUNT</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $c= 1; $sum = 0;?>
                                                            @foreach($receipts as $receipt)
                                                                <tr>
                                                                    <td>{{$c++}}</td>
                                                                    <td>{{ucwords(@$receipt->member) }}</td>
                                                                    <td>{{ucwords(@$receipt->church) }}</td>
                                                                    <td>{{ucwords(@$receipt->phone) }}</td>
                                                                    <td>{{number_format($receipt->id_number) }}</td>
                                                                    <td>{{number_format($receipt->total) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            @endif
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

