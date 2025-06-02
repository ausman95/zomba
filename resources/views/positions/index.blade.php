@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
    {{-- You might also need Bootstrap 5 CSS if you're using its modal classes (btn-close, data-bs-*) --}}
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i> Positions
        </h4>
        <p>
            Manage Positions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">Positions</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('positions.create')}}" class="btn btn-primary btn-md rounded-0 mb-3"> {{-- Added mb-3 for spacing --}}
                <i class="fa fa-plus-circle"></i> New Position
            </a>
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                    <div class="card" style="min-height: 30em;">
                        <div class="card-body px-1">
                            {{-- FIX: Check $positions (plural) collection, not $position (singular) --}}
                            @if($positions->isEmpty())
                                <div class="alert alert-info" role="alert">
                                    <i class="fa fa-info-circle"></i> There are no positions created yet.
                                </div>
                            @else
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style="caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">POSITIONS</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>NO. MEMBERS</th>
                                            <th>CREATED ON</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{-- FIX: Correct numbering for pagination --}}
                                        @php $c = $positions->firstItem(); @endphp
                                        @foreach($positions as $position)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($position->name) }}</td>
                                                {{-- FIX: Access members_count from withCount() --}}
                                                <td>{{ $position->members_count }}</td>
                                                {{-- FIX: Format Carbon instance for display --}}
                                                <td>{{ \Carbon\Carbon::parse($position->created_at)->format('d F Y H:i') }}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('positions.show',$position->id)}}"
                                                       class="btn btn-primary btn-sm rounded-0">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{route('positions.edit',$position->id)}}"
                                                       class="btn btn-info btn-sm rounded-0">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $position->id }}">
                                                        <i class="fa fa-trash-alt"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- Delete Confirmation Modal --}}
                                            <div class="modal" id="deleteModal{{ $position->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $position->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $position->id }}">Confirm Deletion</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete the position: <strong>{{ ucwords($position->name) }}</strong>?
                                                            <p class="text-danger"><small>This action will soft-delete the position, meaning it can be restored later.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger rounded-0">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- Pagination Links --}}
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $positions->links() }}
                                </div>
                            @endif
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
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Simple-DataTables if you intend to use it.
            // WARNING: Simple-DataTables can interfere with Laravel's server-side pagination.
            // If you are using Laravel's paginate() method, you likely DON'T need simple-datatable.
            // If you want client-side search/sort/pagination, then remove Laravel's paginate()
            // from the controller and let simple-datatable handle it entirely.
            // For now, I'm commenting it out as you're using Laravel's pagination.
            // const dataTable = new simpleDatatables.DataTable("#data-table");
        });
    </script>
@stop
