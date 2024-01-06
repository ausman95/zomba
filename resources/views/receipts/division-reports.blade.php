@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Division Reports
        </h4>
        <p>
            Manage Division Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('receipts.index')}}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Division Reports</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="mb-2  col-lg-3">
                        <hr>
                        <form action="{{route('division-report.generate')}}" method="POST">
                            @csrf
                            @if(request()->user()->division_id==1)
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Division</label>
                                    <div class="col-sm-8">
                                        <select name="division_id"
                                                class="form-select select-relation @error('division_id') is-invalid @enderror" style="width: 100%">
                                            <option value=""></option>
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
                                </div>
                            @else
                                <input type="hidden" name="division_id"
                                       class="form-control @error('division_id') is-invalid @enderror"
                                       value="{{request()->user()->division_id}}">
                            @endif
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Account</label>
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
                                <label for="inputEmail3" class="col-sm-4 col-form-label">From</label>
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
                                <label for="inputEmail3" class="col-sm-4 col-form-label">To</label>
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
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Desc</label>
                                <div class="col-sm-8">
                                    <select name="description"
                                            class="form-select select-relation @error('description') is-invalid @enderror" style="width: 100%">
                                        <option value="4">Church</option>
                                        <option value="1">District</option>
                                        <option value="2">Section</option>
                                        <option value="3">Pastor</option>
                                    </select>
                                    @error('description')
                                    <span class="invalid-feedback">
                               {{$message}}
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Type</label>
                                <div class="col-sm-8">
                                    <select name="type"
                                            class="form-select select-relation @error('type') is-invalid @enderror" style="width: 100%">
                                        <option value="1">Default</option>
                                        <option value="2">Descending</option>
                                    </select>
                                    @error('type')
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
                                            Receipt not available at the moment!.
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
                                                @if($description ==='3' || $account_id ==='2' || $account_id ==='7' )
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">{{$division_name.' DIVISION '.$account_name}}  PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}}  TO {{strtoupper($from_month_name)}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>PASTOR</th>
                                                            <th>CHURCH</th>
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
                                                                <td>{{$church->pastor}}</td>
                                                                <td>{{$church->church}}</td>
                                                            @foreach($getMonths as $month)
                                                                    <td>
                                                                        {{number_format($month->getPastorAmount($church->pastor_id,$month->month_id,$church->account_id))}}
                                                                    </td>
                                                                @endforeach
                                                                <td>{{number_format($church->amount)}}</td>
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

