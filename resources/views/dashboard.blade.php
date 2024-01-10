@extends('layouts.app')
@section('content')
    <div class="container-fluid pt-4 ps-1">
        <h4>
            <i class="fa fa-th-large"></i>&nbsp;Dashboard
        </h4>
        <p>
            Site Overview
        </p>
        <hr>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12">
{{--                    <div class="card shadow-sm my-2">--}}
{{--                        <div class="card-body">--}}
{{--                            <h5>Church Finances</h5>--}}
{{--                            <div>--}}
{{--                                My Tithe--}}
{{--                            </div>--}}
{{--                            <div class="d-flex my-2 justify-content-between">--}}
{{--                                <h3 class="text-primary">--}}
{{--                                    <a href="{{route('members.show',request()->user()->member_id)}}"--}}
{{--                                       class="btn btn-md btn-primary rounded-0">--}}
{{--                                        Check &rarr;--}}
{{--                                    </a></h3>--}}
{{--                                <div>--}}
{{--                                    <i class="fa fa-file-archive fa-2x"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div><!-- ./ analytic -->--}}
                    <div class="card shadow-sm my-2">
                        <div class="card-body">
                            <h5>Church Events</h5>
                            <div>
                                Announcements
                            </div>
                            <div class="d-flex my-2 justify-content-between">
                                <h3 class="text-primary">{{count($announcements)}}
                                    <a href="{{route('announcements.index')}}"
                                       class="btn btn-md btn-primary rounded-0">
                                        Check &rarr;
                                    </a></h3>
                                <div>
                                    <i class="fa fa-list-ol fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div><!-- ./ analytic -->

                </div>
                <div class="col-sm-12">
                    <div class="card shadow-sm my-2">
                        <div class="card-body">
                            <h5>Members</h5>
                            <div>
                                Church Members
                            </div>
                            <div class="d-flex my-2 justify-content-between">
                                <h3 class="text-primary"> {{count($members)}}
                                    <a href="{{route('members.index')}}"
                                       class="btn btn-md btn-primary rounded-0">
                                        Check &rarr;
                                    </a></h3>
                                <div>
                                    <i class="fa fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div><!-- ./ analytic -->
                </div>
                <div class="col-sm-12">
                    <div class="card shadow-sm my-2">
                        <div class="card-body">
                            <h5>Home Cells</h5>
                            <div>
                                Home Churches
                            </div>
                            <div class="d-flex my-2 justify-content-between">
                                <h3 class="text-primary"> {{count($churches)}}
                                    <a href="{{route('churches.index')}}"
                                       class="btn btn-md btn-primary rounded-0">
                                        Check &rarr;
                                    </a></h3>
                                <div>
                                    <i class="fa fa-file-archive fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div><!-- ./ analytic -->
                </div>
{{--                <div class="col-lg-4">--}}
{{--                    @if(request()->user()->designation=='administrator')--}}
{{--                        <div class="card shadow-sm my-2">--}}
{{--                            <div class="card-body">--}}
{{--                                <h5>Receipts</h5>--}}
{{--                                <div>--}}
{{--                                    Church Incomes--}}
{{--                                </div>--}}
{{--                                <div class="d-flex my-2 justify-content-between">--}}
{{--                                    <h3 class="text-primary">--}}
{{--                                        <a href="{{route('payments.index')}}"--}}
{{--                                           class="btn btn-primary rounded-0">--}}
{{--                                            Manage &rarr;--}}
{{--                                        </a>--}}
{{--                                    </h3>--}}
{{--                                    <div>--}}
{{--                                        <i class="fa fa-file-archive fa-2x"></i>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div><!-- ./ analytic -->--}}
{{--                        <div class="card shadow-sm my-2">--}}
{{--                            <div class="card-body">--}}
{{--                                <h5>Requisitions</h5>--}}
{{--                                <div>--}}
{{--                                    Church Requisitions--}}
{{--                                </div>--}}
{{--                                <div class="d-flex my-2 justify-content-between">--}}
{{--                                    <h3 class="text-primary">--}}
{{--                                        <a href="{{route('requisitions.index')}}"--}}
{{--                                           class="btn btn-md btn-primary rounded-0">--}}
{{--                                            Manage &rarr;--}}
{{--                                        </a></h3>--}}
{{--                                    <div>--}}
{{--                                        <i class="fa fa-file-archive fa-2x"></i>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div><!-- ./ analytic -->--}}
{{--                    @endif--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
