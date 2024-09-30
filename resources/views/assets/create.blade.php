@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i> &nbsp; Assets
        </h4>
        <p>
            Assets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Asset</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-8">
                    <form action="{{ route('assets.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="created_by" value="{{ request()->user()->id }}">
                        <input type="hidden" name="updated_by" value="{{ request()->user()->id }}">

                            <h4 class="mb-3">Asset Information</h4>

                            <div class="row">
                                <!-- Purchase Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purchase_type">Purchase Type <span class="text-danger">*</span></label>
                                        <select name="purchase_type" id="purchase_type" required class="form-select @error('purchase_type') is-invalid @enderror" onchange="togglePaymentMethod()">
                                            <option value="">-- Select Purchase Type --</option>
                                            <option value="new" {{ old('purchase_type') == 'new' ? 'selected' : '' }}>Newly Bought</option>
                                            <option value="previous" {{ old('purchase_type') == 'previous' ? 'selected' : '' }}>Previously Bought</option>
                                        </select>
                                        @error('purchase_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Asset Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Asset Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter asset name">
                                        @error('name')
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
                                        <input type="text" name="serial_number" required
                                               class="form-control @error('serial_number') is-invalid @enderror"
                                               value="{{ old('serial_number') }}"
                                               placeholder="Serial Number">
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
                                                    <option value="{{ $category->id }}" {{ old('category_id') === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                        <input type="number" name="quantity" required class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Enter quantity">
                                        @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date Bought -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="t_date">Date Bought <span class="text-danger">*</span></label>
                                        <input type="date" name="t_date" required class="form-control @error('t_date') is-invalid @enderror" value="{{ old('t_date') }}">
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
                                        <input type="number" name="cost" required class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost') }}" placeholder="Enter cost amount">
                                        @error('cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Payment Method Section -->
                                <div class="col-md-6" id="payment_method_section" style="display: none;">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        <select name="payment_method" id="payment_method" required class="form-select @error('payment_method') is-invalid @enderror" onchange="toggleFields()">
                                            <option value="">-- Select Payment Method --</option>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="credit" {{ old('payment_method') == 'credit' ? 'selected' : '' }}>Credit</option>
                                        </select>
                                        @error('payment_method')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Bank Field -->
                                <div class="col-md-6" id="banks_field" style="display: none;">
                                    <div class="form-group">
                                        <label for="bank_id">Bank <span class="text-danger">*</span></label>
                                        <select name="bank_id" id="bank_id" class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                            <option value="">-- Select Bank --</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                                    {{ $bank->bank_name . ' ' . $bank->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Suppliers Field -->
                                <div class="col-md-6" id="suppliers_field" style="display: none;">
                                    <div class="form-group">
                                        <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                            <option value="">-- Select Supplier --</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Cheque Number Field -->
                                <div class="col-md-6" id="cheque_number_field" style="display: none;">
                                    <div class="form-group">
                                        <label for="cheque_number">Cheque Number</label>
                                        <input type="text" name="cheque_number" id="cheque_number" class="form-control @error('cheque_number') is-invalid @enderror" value="{{ old('cheque_number') }}" placeholder="Enter cheque number">
                                        @error('cheque_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Depreciation -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="depreciation">Depreciation % <span class="text-danger">*</span></label>
                                        <input type="number" name="depreciation" required class="form-control @error('depreciation') is-invalid @enderror" value="{{ old('depreciation') }}" placeholder="Enter depreciation percentage">
                                        @error('depreciation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Condition -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condition">Condition <span class="text-danger">*</span></label>
                                        <select name="condition" required class="form-select @error('condition') is-invalid @enderror">
                                            <option value="">-- Select Condition --</option>
                                            <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                                            <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Used</option>
                                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                        </select>
                                        @error('condition')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Location -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                        <input type="text" name="location" required class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Enter location">
                                        @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remarks">Life Span</label>
                                        <input type="text" name="life" required class="form-control @error('life') is-invalid @enderror" value="{{ old('life') }}" placeholder="Asset Life span">
                                        @error('life')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Save Asset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        function togglePaymentMethod() {
            const purchaseType = document.getElementById("purchase_type").value;
            const paymentMethodSection = document.getElementById("payment_method_section");

            if (purchaseType === "new") {
                paymentMethodSection.style.display = "block";
            } else {
                paymentMethodSection.style.display = "none";
                document.getElementById("payment_method").value = ""; // Reset payment method
                toggleFields(); // Reset related fields
            }
        }

        function toggleFields() {
            const paymentMethod = document.getElementById("payment_method").value;
            const banksField = document.getElementById("banks_field");
            const suppliersField = document.getElementById("suppliers_field");
            const chequeNumberField = document.getElementById("cheque_number_field");

            banksField.style.display = paymentMethod === "cash" ? "block" : "none";
            suppliersField.style.display = paymentMethod === "credit" ? "block" : "none";
            chequeNumberField.style.display = paymentMethod === "cash" ? "block" : "none";
        }
    </script>
@endsection
