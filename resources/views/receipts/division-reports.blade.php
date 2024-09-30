@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Church Reports
        </h4>
        <p>
            Manage Church Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('analytics')}}">Analytics</a></li>
                <li class="breadcrumb-item active" aria-current="page">Accounts Reports</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="mb-2  col-lg-3">
                        <hr>
                        <form action="{{route('church-report.generate')}}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">ACCOUNT</label>
                                <div class="col-sm-8">
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
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">FROM</label>
                                <div class="col-sm-8">
                                    <select name="from_month_id"
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
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">TO</label>
                                <div class="col-sm-8">
                                    <select name="to_month_id"
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
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">DESC</label>
                                <div class="col-sm-8">
                                    <select name="description"
                                            class="form-select select-relation @error('description') is-invalid @enderror" style="width: 100%">
                                        <option value="3">Members</option>
{{--                                        <option value="4">Home Churches</option>--}}
{{--                                        <option value="2">Ministries</option>--}}
                                    </select>
                                    @error('description')
                                    <span class="invalid-feedback">
                               {{$message}}
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        Generate
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="mb-2 col-lg-9">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if(!@$receipts)
                                    <div class="text-center">
                                        <div class="alert alert-danger">
                                            Select ACCOUNT, FROM and TO then click Generate.
                                        </div>
                                    </div>
                                @else
                                    <div class="ul list-group list-group-flush">
                                        @if($receipts->count() === 0)
                                            <div class="text-center">
                                                <div class="alert alert-danger">
                                                    <i class="fa fa-info-circle"></i>There are no Transactions!
                                                </div>
                                            </div>
                                        @else
                                            <div style="overflow-x:auto;">
                                                @if($description ==='3')
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center"> PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>MEMBER</th>
                                                            <th>HOME CHURCH</th>
                                                            @foreach($getMonths as $month)
                                                                <th>{{$month->name}}
                                                                    <br>
                                                                    (MK)
                                                                </th>
                                                            @endforeach
                                                            <th>TOTAL
                                                                <br>
                                                                (MK)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1; $sum = 0;?>
                                                        @foreach($churches  as $church)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{$church->pastor}}</td>
                                                                <td>{{$church->church}}</td>
                                                            @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getMemberAmount($church->member_id,$month->month_id,$church->account_id),2)}}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{number_format($church->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @elseif($account_id ==='1' && $description ==='4' )
                                                    {{$churches->links()}}
                                                    @if($type==1)
                                                    <table class="table table-bordered table-hover  table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}} PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <?php  $z = 1;  $total= 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>
                                                            <th>NO</th>
                                                            <th>-</th>
                                                            <th>-</th>
                                                            @foreach($getMonths as $month)
                                                                <th>-</th>
                                                            @endforeach
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($districts as $district)
                                                        <tr>
                                                            <th>{{$z++}}</th>
                                                            <th>{{$district->name}} District</th>
                                                            <th>-</th>
                                                            @foreach($getMonths as $month)
                                                                <th>-</th>
                                                            @endforeach
                                                        </tr>
                                                        @foreach(\App\Models\Receipt::getSection($district->id) as $section)
                                                        <tr>
                                                            <th>{{$z++}}</th>
                                                            <th>{{$section->name}} Section</th>
                                                            <th>-</th>
                                                            @foreach($getMonths as $month)
                                                                <th>-</th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <th>{{$z++}}</th>
                                                            <th>CHURCH</th>
                                                            <th>PASTOR</th>
                                                            @foreach($getMonths as $month)
                                                                <th>{{$month->name}}</th>
                                                            @endforeach
                                                        </tr>
                                                        @foreach(\App\Models\Receipt::getChurch($section->id) as $church)
                                                            <tr>
                                                                <td>{{$z++}}</td>
                                                                <td>{{$church->name}}</td>
                                                                <td>{{$church->pastor}}</td>
                                                                @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getChurchGeneralFunds($church->id,$month->id)*10/15)}}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                        @endforeach
                                                        @endforeach

                                                        </tbody>
                                                    </table>
                                                    @else
                                                        <table class="table table-bordered table-hover  table-striped">
                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}} PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                            <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>CHURCH</th>
                                                                <th>PASTOR</th>
                                                                @foreach($getMonths as $month)
                                                                    <th>{{$month->month->name}}
                                                                        <br>
                                                                        (MK)
                                                                    </th>
                                                                @endforeach
                                                                <th>TOTAL
                                                                    <br>
                                                                    (MK)
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php  $z = 1;  $total= 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>
                                                            @foreach($churches  as $church)
                                                                <tr>
                                                                    <td>{{$z++}}</td>
                                                                    <td>{{$church->church}}</td>
                                                                    <td>{{$church->pastor}}</td>
                                                                    @foreach($getMonths as $month)
                                                                        <td>
                                                                            {{number_format($month->getChurchGeneralFunds($church->church_id,$month->month_id)*10/15)}}
                                                                        </td>
                                                                    @endforeach
                                                                    <td>{{number_format($church->general_amount*10/15)}}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                @elseif($description ==='1' || $account_id ==='160')
                                                    {{$churches->links()}}
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' DISTRICT '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>DISTRICT</th>
                                                            <th>PASTOR</th>
                                                            @foreach($getMonths as $month)
                                                                <th>{{$month->month->name}}
                                                                    <br>
                                                                    (MK)
                                                                </th>
                                                            @endforeach
                                                            <th>TOTAL
                                                                <br>
                                                                (MK)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1; $sum = 0;?>
                                                        @foreach($churches  as $church)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{$church->district}}</td>
                                                                <td>{{$church->pastor}}</td>
                                                                @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getDistrictAmount($church->district_id,$month->month_id,$church->account_id))}}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{number_format($church->amount)}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @elseif($description ==='2')
                                                    {{$churches->links()}}
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' SECTION '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>SECTION</th>
                                                            <th>PRESBYTER</th>
                                                            @foreach($getMonths as $month)
                                                                <th>{{$month->month->name}}
                                                                    <br>
                                                                    (MK)
                                                                </th>
                                                            @endforeach
                                                            <th>TOTAL
                                                                <br>
                                                                (MK)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1; $sum = 0;?>
                                                        @foreach($churches  as $church)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{$church->sections}}</td>
                                                                <td>{{$church->pastor}}</td>
                                                                @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getSectionAmount($church->section_id,$month->month_id,$church->account_id))}}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{number_format($church->amount)}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    {{$churches->links()}}
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>CHURCH</th>
                                                            <th>PASTOR</th>
                                                            @foreach($getMonths as $month)
                                                                <th>{{$month->month->name}}
                                                                    <br>
                                                                    (MK)
                                                                </th>
                                                            @endforeach
                                                            <th>TOTAL
                                                                <br>
                                                                (MK)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1; $sum = 0;?>
                                                        @foreach($churches  as $church)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{$church->church}}</td>
                                                                <td>{{$church->pastor}}</td>
                                                                @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getChurchAmount($church->church_id,$month->month_id,$church->account_id))}}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{number_format($church->amount)}}</td>
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

