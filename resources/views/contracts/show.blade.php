@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-file-contract"></i> Labourer Contracts</h4>
        <p>Manage Labourer Contracts</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Labourer Contracts</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary btn-md rounded-0" data-bs-toggle="modal" data-bs-target="#createBenefitModal">
                <i class="fa fa-plus-circle"></i> New Benefit
            </button>
            <div class="modal" id="createBenefitModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createBenefitModalLabel">New Benefit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('labourer-contract-benefits.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                                    <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                                    <label for="account_id" class="form-label">Account</label>
                                    <select class="form-select" id="account_id" name="account_id">
                                        @foreach(\App\Models\Accounts::where(['type'=>2])->get() as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount">
                                </div>
                                <input type="hidden" name="labourer_contract_id" value="{{$contract->id}}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            </div>

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if ($benefits->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no Benefits!
                                @else
                                    <div style="overflow-x: auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">Benefits for {{ $contract->labourer->name }}</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>ACCOUNT NAME</th>
                                                <th>AMOUNT</th>
                                                <th>CREATED AT</th>
                                                <th>CREATED BY</th>
                                                <th>UPDATED AT</th>
                                                <th>UPDATED BY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = 1; @endphp
                                            @foreach ($benefits as $benefit)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{ $benefit->account->name }}</td>
                                                    <td>{{ number_format($benefit->amount, 2) }}</td>
                                                    <td>{{ date('j F Y', strtotime($benefit->created_at)) }}</td>
                                                    <td>
                                                        @if ($benefit->created_by)
                                                            {{ optional(\App\Models\User::find($benefit->created_by))->name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ date('j F Y', strtotime($benefit->updated_at)) }}</td>
                                                    <td>
                                                        @if ($benefit->updated_by)
                                                            {{ optional(\App\Models\User::find($benefit->updated_by))->name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBenefitModal{{ $benefit->id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-target-url="{{ route('labourer-contract-benefits.destroy', $benefit->id) }}">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                                <div class="modal" id="editBenefitModal{{ $benefit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editBenefitModalLabel{{ $benefit->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editBenefitModalLabel{{ $benefit->id }}">Edit Benefit</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('labourer-contract-benefits.update', $benefit->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="account_id" class="form-label">Account</label>
                                                                        <select class="form-select" id="account_id" name="account_id">
                                                                            @foreach(\App\Models\Accounts::all() as $account)
                                                                                <option value="{{ $account->id }}" {{ $benefit->account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="amount" class="form-label">Amount</label>
                                                                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $benefit->amount }}">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
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

        $(document).ready(function() {
            $(".delete-btn").on('click', function() {
                let $url = $(this).attr('data-target-url');
                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this benefit?", "Yes, Delete", function() {
                    $("#delete-form").submit();
                });
            });
        });
    </script>

    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@stop
