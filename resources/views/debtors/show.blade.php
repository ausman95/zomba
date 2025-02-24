@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-user"></i> Debtor Details
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('debtors.index') }}">Debtors</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $debtor->name }}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="row">
            <div class="col-md-4">  {{-- Debtor Information --}}
                <h5>
                    <i class="fas fa-info-circle"></i> Debtor Information
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $debtor->name }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $debtor->phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $debtor->email }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $debtor->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td>{{ $debtor->created_at->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ \App\Models\User::find($debtor->created_by)?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{{ \App\Models\User::find($debtor->updated_by)?->name ?? '-' }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('debtors.edit', $debtor) }}" class="btn btn-primary rounded-0">
                                <i class="fas fa-edit"></i> Update
                            </a>
                            @if(request()->user()->designation == 'administrator')
                                <button class="btn btn-danger rounded-0" id="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                <form action="{{ route('debtors.destroy', $debtor) }}" method="POST" id="delete-form" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>  {{-- End Debtor Information --}}

            <div class="col-md-8">  {{-- Debtor Statements --}}
                <h5>
                    <i class="fas fa-file-alt"></i> Debtor Statements
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if ($debtor->statements->isEmpty())
                            <p>No statements found for this debtor.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $c = 1; @endphp
                                    @foreach ($debtor->statements as $statement)
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td>{{ $statement->created_at->format('d F Y') }}</td>
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
            </div>  {{-- End Debtor Statements --}}
        </div>  {{-- End Row --}}

    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#delete-btn").on('click', function () {
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete this debtor?',
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
