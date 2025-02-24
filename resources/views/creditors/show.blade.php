@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-user"></i> Creditor Details
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('creditors.index') }}">Creditors</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $creditor->name }}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="row">
            <div class="col-md-6">  {{-- Creditor Information --}}
                <h5>
                    <i class="fas fa-info-circle"></i> Creditor Information
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $creditor->name }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $creditor->phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $creditor->email }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $creditor->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td>{{ $creditor->created_at->format('d F Y') }}</td>  {{-- Use Carbon --}}
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ $creditor->creator?->name ?? '-' }}</td>  {{-- Use Relationship and Null Coalescing --}}
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{{ $creditor->updater?->name ?? '-' }}</td>  {{-- Use Relationship and Null Coalescing --}}
                            </tr>
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('creditors.edit', $creditor) }}" class="btn btn-primary rounded-0">
                                <i class="fas fa-edit"></i> Update
                            </a>
                            @if(request()->user()->designation == 'administrator')
                                <button class="btn btn-danger rounded-0" id="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                <form action="{{ route('creditors.destroy', $creditor) }}" method="POST" id="delete-form" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>  {{-- End Creditor Information --}}

            <div class="col-md-6">  {{-- Creditor Statements --}}
                <h5>
                    <i class="fas fa-file-alt"></i> Creditor Statements
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if ($creditor->statements->isEmpty())
                            <p>No statements found for this creditor.</p>
                        @else
                            <div class="table-responsive">  {{-- Add table responsive --}}
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($creditor->statements as $statement)
                                        <tr>
                                            <td>{{ $statement->created_at->format('d F Y') }}</td> {{-- Use Carbon --}}
                                            <td>{{ $statement->description }}</td>
                                            <td>{{ $statement->type }}</td>
                                            <td>{{ number_format($statement->amount, 2) }}</td>
                                            <td>{{ number_format($statement->balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>  {{-- End Creditor Statements --}}
        </div>  {{-- End Row --}}

    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#delete-btn").on('click', function () {
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete this creditor?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#delete-form").submit();
                    }
                })
            });
        });
    </script>
@stop
