@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Church Information
        </h4>
        <p>
            Manage Church Information
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Church Information</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('informations.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Information
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($informations->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no data!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">INFORMATIONS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>VISION</th>
                                                <th>MISSION</th>
                                                <th>GOAL</th>
                                                <th>WHAT WE DO</th>
                                                <th>WHO WE ARE</th>
                                                <th>CREATED DATE</th>
                                                <th>CREATED BY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php  $c= 1;?>
                                            @foreach($informations as $information)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{ucwords(substr($information->vision,0,50)) }}</td>
                                                    <td>{{ucwords(substr($information->mission,0,50)) }}</td>
                                                    <td>{{ucwords(substr($information->goal,0,50)) }}</td>
                                                    <td>{{ucwords(substr($information->what_we_do,0,50)) }}</td>
                                                    <td>{{ucwords(substr($information->who_we_are,0,50)) }}</td>
                                                    <td>{{date('d F Y', strtotime($information->created_at)) }}</td>
                                                    <td>{{\App\Models\Budget::userName($information->updated_by)}}</td>
                                                    <td class="pt-1">
                                                        <a href="{{route('informations.show',$information->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i>  Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
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

