@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Charts Of Accounts
        </h4>
        <p>
            Manage Account information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Chart of Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $account->name }}</li>
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
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style="caption-side: top; text-align: center">{{ $account->name }} CHART OF ACCOUNT</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $account->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Type</td>
                                                <td>
                                                    @if($account->type == 1)
                                                        {{"CR"}}
                                                    @else
                                                        {{"DR"}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Category</td>
                                                <td>{{ @$account->category->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{ $account->created_at }}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated On</td>
                                                <td>{{ $account->updated_at }}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated By</td>
                                                <td>{{ @$account->userName($account->updated_by) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                                <td>{{ @$account->userName($account->created_by) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($account->soft_delete == 1)
                                                        <p style="color: red">Deleted, and Reserved for Audit</p>
                                                    @else
                                                        Active
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div>
                                                <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-primary rounded-0" style="margin: 2px">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{ $account->id }}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Transactions
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            @if($transactions->count() === 0)
                                <i class="fa fa-info-circle"></i>There are no Transactions!
                            @else
                                <div style="overflow-x:auto;">
                                <table class="table table1 table-bordered table-hover table-striped">
                                    <caption style="caption-side: top; text-align: center">{{ $account->name }} STATEMENT</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>AMOUNT</th>
                                        <th>DESCRIPTION</th>
                                        <th>DATE</th>
                                        <th>TYPE</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1; ?>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ ucwords($transaction->name) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}</td>
                                            <td>{{ ucwords($transaction->transaction_type == 1 ? 'CR' : 'DR') }}</td>
                                            <td>
                                                <a href="{{route('account.change',$transaction->id)}}"
                                                   class="btn btn-primary rounded-0">
                                                    <i class="fa fa-edit"></i> Update
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

    <!-- Modal for Transaction Details -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Transaction Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Amount:</strong> <span id="modalAmount"></span></p>
                    <p><strong>Description:</strong> <span id="modalName"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Type:</strong> <span id="modalType"></span></p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {

            // Function to show confirmation window for deletion
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
                });
            }

            // Event listener for delete button
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this account?", "Yes, Delete", function () {
                    $("#delete-form").submit();
                });
            });

            // Show modal with transaction details on update button click
            $('.update-btn').on('click', function () {
                var row = $(this).closest('.transaction-row');
                $('#modalAmount').text(row.data('amount'));
                $('#modalName').text(row.data('name'));
                $('#modalDate').text(row.data('date'));
                $('#modalType').text(row.data('type'));
                $('#updateModal').modal('show');
            });
        });
    </script>
@stop
