@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i> &nbsp; Edit Asset
        </h4>
        <p>
            Update the asset details below.
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Asset</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-8">
                    <form action="{{ route('assets.update', $asset->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="updated_by" value="{{ request()->user()->id }}">
                        <input type="hidden"  name="created_at" value="{{request()->user()->id}}" required>

                        <h4 class="mb-3">Asset Information</h4>

                        <div class="row">
                            <!-- Asset Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Asset Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}" placeholder="Enter asset name">
                                    @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Purchase Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="depreciation">Depreciation % <span class="text-danger">*</span></label>
                                    <input type="number" name="depreciation" required class="form-control @error('depreciation') is-invalid @enderror" value="{{ old('depreciation', $asset->depreciation) }}" placeholder="Enter depreciation percentage">
                                    @error('depreciation')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <!-- Serial / Reg Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Serial / Reg Number <span class="text-danger">*</span></label>
                                    <input type="text" name="serial_number" required class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $asset->serial_number) }}" placeholder="Serial Number">
                                    @error('serial_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" required class="form-select select-relation @error('category_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            @if($category->status == 1)
                                                <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" required class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $asset->quantity) }}" placeholder="Enter quantity">
                                    @error('quantity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date Bought -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="t_date">Date Bought <span class="text-danger">*</span></label>
                                    <input type="date" name="t_date" required class="form-control @error('t_date')
                                     is-invalid @enderror" value="{{\Carbon\Carbon::parse($asset->t_date)->format('Y-m-d') }}">
                                    @error('t_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Cost Amount -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost">Cost Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="cost" required class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $asset->cost) }}" placeholder="Enter cost amount">
                                    @error('cost')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Asset Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="life">Expected Life <span class="text-danger">*</span></label>
                                    <input type="number" name="life" required class="form-control @error('life') is-invalid @enderror" value="{{ old('life', $asset->life) }}" placeholder="Enter Expected life">
                                    @error('life')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Payment Method Section -->
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Asset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePaymentMethod() {
            const purchaseType = document.getElementById('purchase_type').value;
            const paymentMethodSection = document.getElementById('payment_method_section');
            if (purchaseType === 'new') {
                paymentMethodSection.style.display = 'block';
            } else {
                paymentMethodSection.style.display = 'none';
            }
        }

        function toggleFields() {
            const paymentMethod = document.getElementById('payment_method').value;
            const banksField = document.getElementById('banks_field');
            const suppliersField = document.getElementById('suppliers_field');
               banksField.style.display = 'block';
                suppliersField.style.display = 'block';

        }

        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentMethod();
            toggleFields();
        });
    </script>
@endsection
