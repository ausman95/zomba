@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Division Remittances
        </h4>
        <p>
            Manage Division Remittances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('receipts.index')}}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Division Remittances</li>
            </ol>
        </nav>
        <div class="mt-3">
            <div class="mt-3">
                <div class="card container-fluid" style="min-height: 30em;">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                            <hr>
                            <form action="{{route('remittance.generate')}}" method="POST">
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
                                                    <table class="table table-bordered table-hover  table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION '}}  REMITTANCE SUMMARY FOR THE MONTH OF {{$month_name}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>ACCOUNT</th>
                                                            <th>AMOUNT</th>
                                                            <th>BANK NAME</th>
                                                            <th>ACCOUNT NAME</th>
                                                            <th>ACCOUNT NUMBER</th>
                                                            <th>SERVICE CENTRE</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $z = 1;  $gf_amount = 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>
                                                        @foreach($receipts as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>GENERAL FUND</td>
                                                                <td class="{{$general_amount = $general_amount+(0.73*$transaction->general_amount*10/15)}}">
                                                                    {{number_format(0.73*$transaction->general_amount*10/15,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Malawi Assemblies of God main account</td>
                                                                <td>1229095</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>MISSION SUPPORT</td>
                                                                <td class="{{$missions = $missions+($transaction->general_amount*2/15)}}">{{number_format($transaction->general_amount*2/15,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>AOG Division Missions</td>
                                                                <td>1052721</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>EDUCATION SUPPORT</td>
                                                                <td class="{{$education = $education+($transaction->general_amount*1/15)}}">{{number_format($transaction->general_amount*1/15,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Malawi Assemblies of God Education Fund</td>
                                                                <td>3397513</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>CONSTRUCTION SUPPORT</td>
                                                                <td class="{{$construction = $construction+($transaction->general_amount*1/15)}}">{{number_format($transaction->general_amount*1/15,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Malawi Assemblies of God Construction</td>
                                                                <td>2676788</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>PASTOR SUPPORT</td>
                                                                <td class="{{$pastor = $pastor+($transaction->general_amount*1/15)}}">{{number_format($transaction->general_amount*1/15,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Malawi Assemblies of God main account</td>
                                                                <td>1229095</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>SUNDAY SCHOOL</td>
                                                                <td class="{{$sunday_amount = $sunday_amount+$transaction->sunday_amount}}">{{number_format($transaction->sunday_amount,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Assemblies of God National Sunday School</td>
                                                                <td>2380738</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>AGREDS SCHOOL</td>
                                                                <td class="{{$agreds_amount = $agreds_amount+$transaction->agreds_amount}}">{{number_format($transaction->agreds_amount,2) }}</td>
                                                                <td>STANDARD BANK</td>
                                                                <td>AG CARE GENEVA GLOBAL</td>
                                                                <td>0140002052402</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>OTHER AMOUNT</td>
                                                                <td>{{number_format($transaction->other_amount,2) }}</td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>Malawi Assemblies of God main account</td>
                                                                <td>1229095</td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach($pastors as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>PASTOR {{$transaction->account}}</td>
                                                                <td>
                                                                    @if($transaction->key==2)
                                                                    {{number_format(0.1*$transaction->total,2) }}
                                                                    @else
                                                                        {{number_format($transaction->total,2) }}
                                                                    @endif
                                                                </td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>
                                                                    @if($transaction->key==2)
                                                                        Malawi Assemblies of God main account
                                                                    @else
                                                                        MAOG Ministers Benefit Scheme
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($transaction->key==2)
                                                                        1229095
                                                                    @else
                                                                        3965899
                                                                    @endif
                                                                </td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach($districts as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>DISTRICT {{$transaction->account}}</td>
                                                                <td>
                                                                    @if($transaction->key==160)
                                                                        {{number_format(0.1*$transaction->total,2) }}
                                                                    @else
                                                                        {{number_format($transaction->total,2) }}
                                                                    @endif
                                                                </td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>
                                                                    @if($transaction->key==160)
                                                                        Malawi Assemblies of God main account
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($transaction->key==160)
                                                                        1229095
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach($churches as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>CHURCH {{$transaction->account}}</td>
                                                                <td>
                                                                    {{number_format($transaction->total,2) }}
                                                                </td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>
                                                                        -
                                                                </td>
                                                                <td>
                                                                        -
                                                                </td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach($sections as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>SECTION {{$transaction->account}}</td>
                                                                <td>
                                                                    {{number_format($transaction->total,2) }}
                                                                </td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>
                                                                    -
                                                                </td>
                                                                <td>
                                                                    -
                                                                </td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach($members as $transaction)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>MEMBER {{$transaction->account}}</td>
                                                                <td>
                                                                    {{number_format($transaction->total,2) }}
                                                                </td>
                                                                <td>National Bank of Malawi</td>
                                                                <td>
                                                                    -
                                                                </td>
                                                                <td>
                                                                    -
                                                                </td>
                                                                <td>Capital City</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
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

