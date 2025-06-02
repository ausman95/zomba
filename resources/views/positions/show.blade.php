@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-sitemap"></i> Positions {{-- Consistent icon for positions --}}
        </h4>
        <p>
            Manage Position Details
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                {{-- FIX: Changed 'labours.index' to 'positions.index' for consistency --}}
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('positions.index')}}">Positions</a></li>
                {{-- FIX: Using $position->name directly for the active breadcrumb --}}
                <li class="breadcrumb-item active" aria-current="page">{{ucwords($position->name)}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="position-details-table">
                                            {{-- FIX: Consistent caption with $position->name --}}
                                            <caption style="caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">{{ucwords($position->name)}} INFORMATION</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ucwords($position->name)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                                {{-- FIX: Access creator relationship --}}
                                                <td>{{ucwords($position->creator->name ?? 'N/A')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                {{-- FIX: Format Carbon instance --}}
                                                <td>{{ \Carbon\Carbon::parse($position->created_at)->format('d F Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated By</td>
                                                {{-- FIX: Access updater relationship --}}
                                                <td>{{ucwords($position->updater->name ?? 'N/A')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated On</td>
                                                {{-- FIX: Format Carbon instance --}}
                                                <td>{{ \Carbon\Carbon::parse($position->updated_at)->format('d F Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($position->soft_delete == 1)
                                                        <span class="badge bg-danger">Deleted (Reserved for Audit)</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start flex-wrap"> {{-- Use flex-wrap for responsiveness --}}
                                            <a href="{{route('positions.edit',$position->id)}}"
                                               class="btn btn-primary btn-md rounded-0 me-2 mb-2"> {{-- Added me-2 mb-2 for spacing --}}
                                                <i class="fa fa-edit"></i> Update Position
                                            </a>
                                            {{-- Delete Button triggers Bootstrap Modal --}}
                                            <button type="button" class="btn btn-danger btn-md rounded-0 mb-2" data-bs-toggle="modal" data-bs-target="#deletePositionModal{{ $position->id }}">
                                                <i class="fa fa-trash"></i> Delete Position
                                            </button>

                                            {{-- Restore Button (only if soft-deleted) --}}
                                            @if($position->soft_delete == 1)
                                                <button type="button" class="btn btn-success btn-md rounded-0 mb-2 ms-2" data-bs-toggle="modal" data-bs-target="#restorePositionModal{{ $position->id }}">
                                                    <i class="fa fa-undo"></i> Restore Position
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-users"></i> Members with this Position {{-- Changed icon and text for clarity --}}
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            {{-- FIX: Check $members (plural) collection, not $labourers --}}
                            @if($members->isEmpty())
                                <div class="alert alert-info" role="alert">
                                    <i class="fa fa-info-circle"></i> There are no members assigned to this position yet.
                                </div>
                            @else
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped" id="members-table"> {{-- Changed ID for clarity --}}
                                        {{-- FIX: Consistent caption --}}
                                        <caption style="caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">MEMBERS IN {{ucwords($position->name)}}</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>GENDER</th>
                                            <th>DEPARTMENT</th>
                                            <th>PHONE</th>
                                            <th>EMPLOYMENT TYPE</th> {{-- Changed PROFESSIONAL to EMPLOYMENT TYPE --}}
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{-- FIX: Correct numbering for pagination --}}
                                        @php $c = $members->firstItem(); @endphp
                                        @foreach($members as $member) {{-- FIX: Changed $labourer to $member --}}
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td>{{ucwords($member->name)}}</td>
                                            <td>{{ucwords($member->gender)}}</td>
                                            {{-- FIX: Access department relationship --}}
                                            <td>{{ucwords($member->department->name ?? 'N/A')}}</td>
                                            <td>{{$member->phone_number}}</td>
                                            <td>
                                                @if($member->type==1)
                                                    {{'Employed'}}
                                                @elseif($member->type==2)
                                                    {{'Sub-Contractor'}}
                                                @else
                                                    {{'Temporary Worker'}} {{-- FIX: Corrected syntax --}}
                                                @endif
                                            </td>
                                            <td class="pt-1">
                                                {{-- FIX: Correct route for member show --}}
                                                <a href="{{route('members.show',$member->id)}}"
                                                   class="btn btn-md btn-primary rounded-0">
                                                    <i class="fa fa-list-ol"></i> View Details
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- Pagination Links for Members --}}
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $members->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE POSITION MODAL --}}
    <div class="modal" id="deletePositionModal{{ $position->id }}" tabindex="-1" aria-labelledby="deletePositionModalLabel{{ $position->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePositionModalLabel{{ $position->id }}">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the position: <strong>{{ ucwords($position->name) }}</strong>?
                    <p class="text-danger"><small>This action will soft-delete the position. It can be restored later.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-0">Delete Position</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- RESTORE POSITION MODAL (only if soft-deleted) --}}
    @if($position->soft_delete == 1)
        <div class="modal" id="restorePositionModal{{ $position->id }}" tabindex="-1" aria-labelledby="restorePositionModalLabel{{ $position->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restorePositionModalLabel{{ $position->id }}">Confirm Restoration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to restore the position: <strong>{{ ucwords($position->name) }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('positions.restore', $position->id) }}" method="POST" class="d-inline">
                            @csrf
                            {{-- Assuming you have a POST route for restore, or use @method('PUT') if it's a PUT request --}}
                            <button type="submit" class="btn btn-success rounded-0">Restore Position</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop

@section('scripts')
    {{-- No need for custom Swal.fire if using Bootstrap modals directly --}}
    {{-- Ensure Bootstrap's JS is loaded for modals to work --}}
    <script>
        // If you had any other specific JS for this page, put it here.
        // The delete/restore modals are handled by Bootstrap's data attributes.
    </script>
@stop
