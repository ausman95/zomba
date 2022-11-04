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
            Requisitions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Requisitions</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('requisitions.determine')}}" class="btn btn-primary rounded-0">
                <i class="fa fa-plus-circle"></i>  Create a New Requisition
            </a>
            @if(request()->user()->designation==='project' || request()->user()->designation==='other' || request()->user()->designation==='administrator' || request()->user()->designation==='accountant')
            <a href="{{route('request.archive')}}" class="btn btn-primary rounded-0">
                <i class="fa fa-archive"></i>  Out Tray
            </a>
            @endif
                @if(request()->user()->designation==='accountant')
                    <a href="{{route('request.pending')}}" class="btn btn-primary rounded-0">
                        <i class="fa fa-archive"></i>  Pending
                    </a>
                @endif
            <div class="mt-3">

                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if( $dept_requisitions ->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no requisitions!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered  table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">IN TRAY</caption>
                                            <thead>
                                            <tr>
                                                <th>REG NO.</th>
                                                <th>PREPARED BY</th>
                                                <th>ITEMS</th>
                                                <th>DEPARTMENT</th>
                                                <th>STATUS</th>
                                                <th>SENT DATE</th>
                                                <th>NEXT DESTINATION</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @if(request()->user()->level<3)
                                                @if(request()->user()->designation==='administrator' || request()->user()->designation==='project')
                                                    @foreach($dept_requisitions as $item)
                                                    @if($item->status=='pending')
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{$item->user->name}}</td>
                                                        <td>{{$item->departmentRequisition()->count()}}</td>
                                                        <td>{{$item->department->name}}</td>
                                                        <td>
                                                            @if($item->status=='in-order')
                                                                <P style="color: red !important;">
                                                                    {{$item->findUser($item->id)}} CHECKED & APPROVED <br> (ONE FINAL APPROVAL REMAINING)
                                                                </P>
                                                            @elseif($item->status==='closed')
                                                                APPROVED by {{$item->findUser($item->id)}}
                                                            @elseif($item->status==='pending')
                                                                <P style="color: red !important;">NEW REQUISITION WAITING TO BE CHECKED & APPROVED</P>
                                                            @elseif($item->status==='Cancelled')
                                                                CANCELLED by {{$item->findUser($item->id)}}
                                                            @else
                                                                ACKNOWLEDGED by {{$item->findUser($item->id)}}
                                                            @endif
                                                        </td>
                                                        <td>{{date('d F Y', strtotime($item->created_at))}}</td>
                                                      <td>  @if($item->status=='in-order')
                                                              @foreach($item->getUser(3) as $user)
                                                                  <P style="color: red !important;">
                                                                      {{$user->name}}                                                                    </P>
                                                              @endforeach
                                                          @elseif($item->status==='closed')
                                                              @foreach($item->getUserFinal() as $user)
                                                                  <P style="color: red !important;">
                                                                      {{$user->name}}                                                                    </P>
                                                              @endforeach
                                                          @elseif($item->status==='pending')
                                                              @foreach($item->getUser(2) as $user)
                                                                  <P style="color: red !important;">
                                                                      {{$user->name}}                                                                    </P>
                                                              @endforeach
                                                          @else
                                                              -
                                                          @endif</td>
                                                        <td class="pt-1">
                                                            <a href="{{route('requisitions.check',$item->id)}}"
                                                               class="btn btn-primary rounded-0">
                                                                <i class="fa fa-list-ol"></i> Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @endif
                                            @if(request()->user()->level==3)
                                                @foreach($dept_requisitions as $item)
                                                    @if($item->status=='in-order')
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{$item->user->name}}</td>
                                                            <td>{{$item->departmentRequisition()->count()}}</td>
                                                            <td>{{$item->department->name}}</td>
                                                            <td>
                                                                @if($item->status=='in-order')
                                                                    <P style="color: red !important;">
                                                                        {{$item->findUser($item->id)}} CHECKED & APPROVED <br> (ONE FINAL APPROVAL REMAINING)
                                                                    </P>
                                                                @elseif($item->status==='closed')
                                                                    APPROVED by {{$item->findUser($item->id)}}
                                                                @elseif($item->status==='pending')
                                                                 <P style="color: red !important;">NEW REQUISITION WAITING TO BE CHECKED & APPROVED</P>
                                                                @elseif($item->status==='Cancelled')
                                                                    CANCELLED by {{$item->findUser($item->id)}}
                                                                @else
                                                                    ACKNOWLEDGED by {{$item->findUser($item->id)}}
                                                                @endif
                                                            </td>
                                                            <td>{{date('d F Y', strtotime($item->created_at))}}</td>
                                                            <td>
                                                                @if($item->status=='in-order')
                                                                    @foreach($item->getUser(3) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='closed')
                                                                    @foreach($item->getUserFinal() as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='pending')
                                                                    @foreach($item->getUser(2) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="pt-1">
                                                                <a href="{{route('requisitions.check',$item->id)}}"
                                                                   class="btn btn-primary rounded-0">
                                                                    <i class="fa fa-list-ol"></i> Manage
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(request()->user()->designation==='accountant')
                                                @foreach($dept_requisitions as $item)
                                                    @if($item->status=='closed')
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{$item->user->name}}</td>
                                                            <td>{{$item->departmentRequisition()->count()}}</td>
                                                            <td>{{$item->department->name}}</td>
                                                            <td>
                                                                <P style="color: red !important;">
                                                                    APPROVED by {{$item->findUser($item->id)}}, OPEN IT, GENERATE THE VOUCHER THEN ACKNOWLEDGE
                                                                </P>
                                                            </td>
                                                            <td>{{date('d F Y', strtotime($item->created_at))}}</td>
                                                            <td>
                                                                @if($item->status=='in-order')
                                                                    @foreach($item->getUser(3) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='closed')
                                                                    @foreach($item->getUserFinal() as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='pending')
                                                                    @foreach($item->getUser(2) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="pt-1">
                                                                <a href="{{route('requisitions.check',$item->id)}}"
                                                                   class="btn btn-primary rounded-0">
                                                                    <i class="fa fa-list-ol"></i> Manage
                                                                    </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(request()->user()->designation==='other')
                                                @foreach($dept_requisitions as $item)
                                                    @if($item->status=='pending' || $item->status=='in-order')
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{$item->user->name}}</td>
                                                            <td>{{$item->departmentRequisition()->count()}}</td>
                                                            <td>{{$item->department->name}}</td>
                                                            <td>
                                                                @if($item->status=='in-order')
                                                                    <P style="color: red !important;">
                                                                        {{$item->findUser($item->id)}} CHECKED & APPROVED <br> (ONE FINAL APPROVAL REMAINING)
                                                                    </P>
                                                                @elseif($item->status==='closed')
                                                                    APPROVED by {{$item->findUser($item->id)}}
                                                                @elseif($item->status==='pending')
                                                                    <P style="color: red !important;">NEW REQUISITION WAITING TO BE CHECKED & APPROVED</P>
                                                                @elseif($item->status==='Cancelled')
                                                                    CANCELLED by {{$item->findUser($item->id)}}
                                                                @else
                                                                    ACKNOWLEDGED by {{$item->findUser($item->id)}}
                                                                @endif
                                                            </td>
                                                            <td>{{date('d F Y', strtotime($item->created_at))}}</td>
                                                            <td>  @if($item->status=='in-order')
                                                                    @foreach($item->getUser(3) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='closed')
                                                                    @foreach($item->getUserFinal() as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @elseif($item->status==='pending')
                                                                    @foreach($item->getUser(2) as $user)
                                                                        <P style="color: red !important;">
                                                                            {{$user->name}}                                                                    </P>
                                                                    @endforeach
                                                                @else
                                                                    -
                                                                @endif</td>
                                                            <td class="pt-1">
                                                                <a href="{{route('requisitions.check',$item->id)}}"
                                                                   class="btn btn-primary rounded-0">
                                                                    <i class="fa fa-list-ol"></i> Details
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif

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
