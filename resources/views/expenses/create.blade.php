@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Expenses
        </h4>
        <p>
            Expenses
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('expenses.index')}}">Expenses</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Expenses</li>
            </ol>
        </nav>
            <hr>
        <div class="mt-2">
            <form action="{{route('expenses.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                            <div class="form-group">
                                <label>Expenses Name</label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}"
                                       placeholder="Expenses name" >
                                @error('name')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Account</label>
                                <select name="account_id"
                                        class="form-select @error('account_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}"
                                            {{old('account_id')===$account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        <div class="form-group">
                            <label>Transaction Type</label>
                            <select name="t_type" class="form-select t_type @error('t_type') is-invalid @enderror">{{old('t_type')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Cash</option>
                                <option value="2">Credit</option>
                            </select>
                            @error('t_type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="supplier d-none">
                            <div class="form-group">
                                <label>Suppliers</label>
                                <select name="supplier_id"
                                        class="form-select @error('supplier_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}"
                                            {{old('supplier_id')===$supplier->id ? 'selected' : ''}}>{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Total Amount Charged</label>
                                <input type="number" name="total_amount"
                                       class="form-control @error('total_amount') is-invalid @enderror"
                                       value="{{old('total_amount')}}"
                                       placeholder="Amount Charged" >
                                @error('total_amount')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                            <div class="form-group">
                                <label>Bank Account</label>
                                <select name="bank_id"
                                        class="form-select @error('bank_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}"
                                            {{old('bank_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' '.$bank->account_number}}</option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-md btn-primary rounded-0">
                                    <i class="fa fa-paper-plane"></i>Save
                                </button>
                            </div>

                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount Paid" >
                            <input type="hidden" name="transaction_type" value="1"
                                   class="form-control @error('transaction_type') is-invalid @enderror"
                            >
                            @error('transaction_type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Cheque Number</label>
                            <input type="number" name="cheque_number"
                                   class="form-control @error('cheque_number') is-invalid @enderror"
                                   value="{{old('cheque_number')}}"
                                   placeholder="Cheque Number" >
                            @error('cheque_number')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="1" placeholder="Optional"
                                      class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                            @error('description')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-select type @error('type') is-invalid @enderror">{{old('type')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Project</option>
                                <option value="2">Admin</option>
                                <option value="3">Suppliers</option>
                                <option value="4">Labourer/Sub-contractor</option>
                            </select>
                            @error('gender')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="suppliers d-none">
                            <div class="form-group">
                                <label>Suppliers</label>
                                <select name="supplier_id"
                                        class="form-select @error('supplier_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}"
                                            {{old('supplier_id')===$supplier->id ? 'selected' : ''}}>{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="labourers  d-none">
                            <div class="form-group">
                                <label>Labourers</label>
                                <select name="labourer_id"
                                        class="form-select @error('labourer_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($labourers as $labourer)
                                        <option value="{{$labourer->id}}"
                                            {{old('labourer_id')===$labourer->id ? 'selected' : ''}}>{{$labourer->name}}</option>
                                    @endforeach
                                </select>
                                @error('labourer_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script src="{{asset('vendor/jquery-3.4.1.min.js')}}"></script>
    <script>
    $(document).ready(function () {
        $('.type').on('change', function () {
            let status = $(this).val();
            if(status==='1'){
                $('.suppliers').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('show').removeClass('d-none');
            }
            if(status==='2'){
                $('.suppliers').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('d-none').removeClass('show');
            }
            if(status==='3'){
                $('.suppliers').addClass('show').removeClass('d-none');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('show').removeClass('d-none');
            }
            if(status==='4'){
                $('.suppliers').addClass('d-none').removeClass('show');
                $('.members').addClass('show').removeClass('d-none');
                $('.projects').addClass('show').removeClass('d-none');
            }
        });
        $('.t_type').on('change', function () {
            let check = $(this).val();
            if(check==='1'){
                $('.supplier').addClass('d-none').removeClass('show');
            }
            if(check==='2'){
                $('.supplier').addClass('show').removeClass('d-none');
            }
        });
    });
    </script>
@endsection

