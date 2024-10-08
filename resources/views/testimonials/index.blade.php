@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bxs-comment-detail"></i>&nbsp; Testimonials
        </h4>
        <p>
            Manage Testimonials
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('testimonials.index')}}">Testimonials</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Testimonials</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('testimonials.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> Add New Testimonial
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($testimonials->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no testimonials available!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">TESTIMONIALS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>MEMBER</th>
                                                <th>STATEMENT</th>
                                                <th>CREATED ON</th>
                                                <th>CREATED BY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 1; ?>
                                            @foreach($testimonials as $testimonial)
                                                <tr>
                                                    <td>{{ $count++ }}</td>
                                                    <td>{{ $testimonial->member->name }}</td>
                                                    <td>{{ Str::limit($testimonial->statement, 50) }}</td>
                                                    <td>{{ $testimonial->created_at->format('d M, Y') }}</td>
                                                    <td>{{ $testimonial->created_by }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{route('testimonials.show', $testimonial->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="{{route('testimonials.edit', $testimonial->id)}}"
                                                           class="btn btn-warning btn-md rounded-0">
                                                            <i class="fa fa-pencil-alt"></i> Edit
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

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
@stop
