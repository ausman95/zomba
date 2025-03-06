@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-file-contract"></i> Labourer Contracts</h4>
        <p>Create Labourer Contracts</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Labourer Contracts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Contract</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-2">
            <form action="{{ route('contracts.store') }}" method="POST" onsubmit="return disableSubmitButton(this);">
                @csrf

                <div class="card-body">
                    <div class="row-cols-3">
                        <div class="form-group">
                            <label for="labourer_id" class="form-label">Labourer</label>
                            <select name="labourer_id" class="select-relation form-select @error('labourer_id') is-invalid @enderror" required>
                                <option value="">-- Select Labourer --</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{ $labourer->id }}">{{ $labourer->name }}</option>
                                @endforeach
                            </select>
                            @error('labourer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <h5 class="card-title mt-4">Labourer Benefits</h5>
                    <div id="benefits-container">
                        <div class="benefit-row">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="account_id" class="form-label">Account</label>
                                    <select name="benefits[0][account_id]" class="select-relation form-select account-select @error('benefits.0.account_id') is-invalid @enderror" required>
                                        <option value="">-- Select Account --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('benefits.0.account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" step="0.01" name="benefits[0][amount]" class="form-control amount @error('benefits.0.amount') is-invalid @enderror" required>
                                    @error('benefits.0.amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-lg remove-benefit">
                                        <i class="fa fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg rad-0 mt-3" id="add-benefit">
                        <i class="fa fa-plus"></i> Add Benefit
                    </button>

                    <button type="submit" class="btn btn-success btn-lg submit mt-4">
                        <i class="fa fa-save"></i> Save Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('add-benefit').addEventListener('click', function () {
            const index = document.querySelectorAll('#benefits-container .benefit-row').length;
            const container = document.getElementById('benefits-container');
            const row = document.createElement('div');
            row.className = 'benefit-row';
            row.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label for="account_id" class="form-label">Account</label>
                        <select name="benefits[${index}][account_id]" class="select-relation form-select account-select" required>
                            <option value="">-- Select Account --</option>
                            @foreach($accounts as $account)
            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="benefits[${index}][amount]" class="form-control amount" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-lg remove-benefit">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>`;
            container.appendChild(row);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-benefit')) {
                e.target.closest('.benefit-row').remove();
            }
        });
    </script>
@endsection
