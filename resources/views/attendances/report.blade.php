@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-file-archive"></i>Attendance Report
        </h4>
        <p>
            Report
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                        <hr>
                        <form action="{{route('member.reports')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <select name="ministry_id"
                                        class="form-select select-relation @error('ministry_id') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
                                @foreach($ministries as $ministry)
                                        <option value="{{$ministry->id}}"
                                            {{old('ministry_id')===$ministry->id ? 'selected' : ''}}>{{$ministry->name}}</option>
                                    @endforeach
                                </select>
                                @error('ministry_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <select name="service_id"
                                        class="form-select select-relation @error('service_id') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
                                @foreach($services as $service)
                                        <option value="{{$service->id}}"
                                            {{old('service_id')===$service->id ? 'selected' : ''}}>{{$service->name}}</option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <select name="gender"
                                        class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
{{--                                <button class="btn btn-primary rounded-0" type="submit">--}}
{{--                                    View &rarr;--}}
{{--                                </button>--}}
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-11 col-lg-10">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
{{--                                @if(!@$receipts)--}}
                                    <div class="text-center">
                                        <div class="alert alert-danger">
                                            THIS IS COMING SOON
{{--                                            Select ACCOUNT, FROM and TO then click Generate.--}}
                                        </div>
                                    </div>
{{--                                @else--}}
{{--                                    <div class="ul list-group list-group-flush">--}}
{{--                                        @if($receipts->count() === 0)--}}
{{--                                            <div class="text-center">--}}
{{--                                                <div class="alert alert-danger">--}}
{{--                                                    <i class="fa fa-info-circle"></i>There are no Transactions!--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            <div style="overflow-x:auto;">--}}
{{--                                                @if($description ==='3')--}}
{{--                                                    <table class="table table-bordered table-hover table-striped">--}}
{{--                                                        <caption style=" caption-side: top; text-align: center"> PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                        <thead>--}}
{{--                                                        <tr>--}}
{{--                                                            <th>NO</th>--}}
{{--                                                            <th>MEMBER</th>--}}
{{--                                                            <th>HOME CHURCH</th>--}}
{{--                                                            @foreach($getMonths as $month)--}}
{{--                                                                <th>{{$month->name}}--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            @endforeach--}}
{{--                                                            <th>TOTAL--}}
{{--                                                                <br>--}}
{{--                                                                (MK)--}}
{{--                                                            </th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                            <?php  $c= 1; $sum = 0;?>--}}
{{--                                                        @foreach($churches  as $church)--}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$c++}}</td>--}}
{{--                                                                <td>{{$church->pastor}}</td>--}}
{{--                                                                <td>{{$church->church}}</td>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <td>--}}
{{--                                                                        {{number_format($month->getMemberAmount($church->member_id,$month->month_id,$church->account_id),2)}}--}}
{{--                                                                    </td>--}}
{{--                                                                @endforeach--}}
{{--                                                                <td>{{number_format($church->amount,2)}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                @elseif($account_id ==='1' && $description ==='4' )--}}
{{--                                                    {{$churches->links()}}--}}
{{--                                                    @if($type==1)--}}
{{--                                                        <table class="table table-bordered table-hover  table-striped">--}}
{{--                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}} PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                            <thead>--}}
{{--                                                            <tr>--}}
{{--                                                                    <?php  $z = 1;  $total= 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>--}}
{{--                                                                <th>NO</th>--}}
{{--                                                                <th>-</th>--}}
{{--                                                                <th>-</th>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <th>-</th>--}}
{{--                                                                @endforeach--}}
{{--                                                            </tr>--}}
{{--                                                            </thead>--}}
{{--                                                            <tbody>--}}
{{--                                                            @foreach($districts as $district)--}}
{{--                                                                <tr>--}}
{{--                                                                    <th>{{$z++}}</th>--}}
{{--                                                                    <th>{{$district->name}} District</th>--}}
{{--                                                                    <th>-</th>--}}
{{--                                                                    @foreach($getMonths as $month)--}}
{{--                                                                        <th>-</th>--}}
{{--                                                                    @endforeach--}}
{{--                                                                </tr>--}}
{{--                                                                @foreach(\App\Models\Receipt::getSection($district->id) as $section)--}}
{{--                                                                    <tr>--}}
{{--                                                                        <th>{{$z++}}</th>--}}
{{--                                                                        <th>{{$section->name}} Section</th>--}}
{{--                                                                        <th>-</th>--}}
{{--                                                                        @foreach($getMonths as $month)--}}
{{--                                                                            <th>-</th>--}}
{{--                                                                        @endforeach--}}
{{--                                                                    </tr>--}}
{{--                                                                    <tr>--}}
{{--                                                                        <th>{{$z++}}</th>--}}
{{--                                                                        <th>CHURCH</th>--}}
{{--                                                                        <th>PASTOR</th>--}}
{{--                                                                        @foreach($getMonths as $month)--}}
{{--                                                                            <th>{{$month->name}}</th>--}}
{{--                                                                        @endforeach--}}
{{--                                                                    </tr>--}}
{{--                                                                    @foreach(\App\Models\Receipt::getChurch($section->id) as $church)--}}
{{--                                                                        <tr>--}}
{{--                                                                            <td>{{$z++}}</td>--}}
{{--                                                                            <td>{{$church->name}}</td>--}}
{{--                                                                            <td>{{$church->pastor}}</td>--}}
{{--                                                                            @foreach($getMonths as $month)--}}
{{--                                                                                <td>--}}
{{--                                                                                    {{number_format($month->getChurchGeneralFunds($church->id,$month->id)*10/15)}}--}}
{{--                                                                                </td>--}}
{{--                                                                            @endforeach--}}
{{--                                                                        </tr>--}}
{{--                                                                    @endforeach--}}
{{--                                                                @endforeach--}}
{{--                                                            @endforeach--}}

{{--                                                            </tbody>--}}
{{--                                                        </table>--}}
{{--                                                    @else--}}
{{--                                                        <table class="table table-bordered table-hover  table-striped">--}}
{{--                                                            <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}} PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                            <thead>--}}
{{--                                                            <tr>--}}
{{--                                                                <th>NO</th>--}}
{{--                                                                <th>CHURCH</th>--}}
{{--                                                                <th>PASTOR</th>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <th>{{$month->month->name}}--}}
{{--                                                                        <br>--}}
{{--                                                                        (MK)--}}
{{--                                                                    </th>--}}
{{--                                                                @endforeach--}}
{{--                                                                <th>TOTAL--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            </tr>--}}
{{--                                                            </thead>--}}
{{--                                                            <tbody>--}}
{{--                                                                <?php  $z = 1;  $total= 0;$local_amount = 0; $general_amount = 0;$agreds_amount = 0; $other_amount = 0; $sunday_amount = 0;$missions = 0;$construction = 0; $pastor = 0; $education = 0 ?>--}}
{{--                                                            @foreach($churches  as $church)--}}
{{--                                                                <tr>--}}
{{--                                                                    <td>{{$z++}}</td>--}}
{{--                                                                    <td>{{$church->church}}</td>--}}
{{--                                                                    <td>{{$church->pastor}}</td>--}}
{{--                                                                    @foreach($getMonths as $month)--}}
{{--                                                                        <td>--}}
{{--                                                                            {{number_format($month->getChurchGeneralFunds($church->church_id,$month->month_id)*10/15)}}--}}
{{--                                                                        </td>--}}
{{--                                                                    @endforeach--}}
{{--                                                                    <td>{{number_format($church->general_amount*10/15)}}</td>--}}
{{--                                                                </tr>--}}
{{--                                                            @endforeach--}}
{{--                                                            </tbody>--}}
{{--                                                        </table>--}}
{{--                                                    @endif--}}
{{--                                                @elseif($description ==='1' || $account_id ==='160')--}}
{{--                                                    {{$churches->links()}}--}}
{{--                                                    <table class="table table-bordered table-hover table-striped">--}}
{{--                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' DISTRICT '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                        <thead>--}}
{{--                                                        <tr>--}}
{{--                                                            <th>NO</th>--}}
{{--                                                            <th>DISTRICT</th>--}}
{{--                                                            <th>PASTOR</th>--}}
{{--                                                            @foreach($getMonths as $month)--}}
{{--                                                                <th>{{$month->month->name}}--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            @endforeach--}}
{{--                                                            <th>TOTAL--}}
{{--                                                                <br>--}}
{{--                                                                (MK)--}}
{{--                                                            </th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                            <?php  $c= 1; $sum = 0;?>--}}
{{--                                                        @foreach($churches  as $church)--}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$c++}}</td>--}}
{{--                                                                <td>{{$church->district}}</td>--}}
{{--                                                                <td>{{$church->pastor}}</td>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <td>--}}
{{--                                                                        {{number_format($month->getDistrictAmount($church->district_id,$month->month_id,$church->account_id))}}--}}
{{--                                                                    </td>--}}
{{--                                                                @endforeach--}}
{{--                                                                <td>{{number_format($church->amount)}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                @elseif($description ==='2')--}}
{{--                                                    {{$churches->links()}}--}}
{{--                                                    <table class="table table-bordered table-hover table-striped">--}}
{{--                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' SECTION '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                        <thead>--}}
{{--                                                        <tr>--}}
{{--                                                            <th>NO</th>--}}
{{--                                                            <th>SECTION</th>--}}
{{--                                                            <th>PRESBYTER</th>--}}
{{--                                                            @foreach($getMonths as $month)--}}
{{--                                                                <th>{{$month->month->name}}--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            @endforeach--}}
{{--                                                            <th>TOTAL--}}
{{--                                                                <br>--}}
{{--                                                                (MK)--}}
{{--                                                            </th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                            <?php  $c= 1; $sum = 0;?>--}}
{{--                                                        @foreach($churches  as $church)--}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$c++}}</td>--}}
{{--                                                                <td>{{$church->sections}}</td>--}}
{{--                                                                <td>{{$church->pastor}}</td>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <td>--}}
{{--                                                                        {{number_format($month->getSectionAmount($church->section_id,$month->month_id,$church->account_id))}}--}}
{{--                                                                    </td>--}}
{{--                                                                @endforeach--}}
{{--                                                                <td>{{number_format($church->amount)}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                @else--}}
{{--                                                    {{$churches->links()}}--}}
{{--                                                    <table class="table table-bordered table-hover table-striped">--}}
{{--                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' CHURCH '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                        <thead>--}}
{{--                                                        <tr>--}}
{{--                                                            <th>NO</th>--}}
{{--                                                            <th>CHURCH</th>--}}
{{--                                                            <th>PASTOR</th>--}}
{{--                                                            @foreach($getMonths as $month)--}}
{{--                                                                <th>{{$month->month->name}}--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            @endforeach--}}
{{--                                                            <th>TOTAL--}}
{{--                                                                <br>--}}
{{--                                                                (MK)--}}
{{--                                                            </th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                            <?php  $c= 1; $sum = 0;?>--}}
{{--                                                        @foreach($churches  as $church)--}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$c++}}</td>--}}
{{--                                                                <td>{{$church->church}}</td>--}}
{{--                                                                <td>{{$church->pastor}}</td>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <td>--}}
{{--                                                                        {{number_format($month->getChurchAmount($church->church_id,$month->month_id,$church->account_id))}}--}}
{{--                                                                    </td>--}}
{{--                                                                @endforeach--}}
{{--                                                                <td>{{number_format($church->amount)}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @endif--}}
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

