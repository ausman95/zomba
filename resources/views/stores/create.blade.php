@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Purchases
        </h4>
        <p>
            Purchases
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('purchases.index')}}">Purchases</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Purchases</li>
            </ol>
        </nav>
            <hr>
        <div class="mt-2">
            <form action="{{route('purchases.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                        <div class="form-group">
                            <label>Material Name</label>
                            <select name="material_id"
                                    class="form-select @error('material_id') is-invalid @enderror">
                                <option value="">-- Select ---</option>
                                @foreach($materials as $material)
                                    <option value="{{$material->id}}"
                                        {{old('material_id')===$material->id ? 'selected' : ''}}>{{$material->name.' '.$material->specifications}}</option>
                                @endforeach
                            </select>
                            @error('material_id')
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
                            <label>Purchase Method</label>
                            <select name="payment_type" class="form-select payment_type @error('payment_type') is-invalid @enderror">{{old('payment_type')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Cash</option>
                                <option value="2">Credit</option>
                            </select>
                            @error('payment_type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
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
                        <div class="bank  d-none">
                            <div class="form-group ">
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
                        </div>

                        <div class="form-group">
                                <button class="btn btn-md btn-success me-2">
                                    <i class="fa fa-save"></i>Save
                                </button>
                            </div>
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Quantity in KGs</label>
                            <input type="number" name="quantity_kg"
                                   class="form-control @error('quantity_kg') is-invalid @enderror"
                                   value="{{old('quantity_kg')}}"
                                   placeholder="Quantity in KGs" >
                            @error('quantity_kg')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Quantity in Number</label>
                            <input type="number" name="quantity_number"
                                   class="form-control @error('quantity_number') is-invalid @enderror"
                                   value="{{old('quantity_number')}}"
                                   placeholder="Quantity in Number" >
                            @error('quantity_number')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Material Total Amount" >
                            @error('amount')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Purchase Type</label>
                            <select name="t_type" class="form-select t_type @error('t_type') is-invalid @enderror">{{old('t_type')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Stores</option>
                                <option value="2">Project-Direct</option>
                            </select>
                            @error('t_type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="projects  d-none">
                            <div class="form-group">
                                <label>Projects</label>
                                <select name="project_id"
                                        class="form-select @error('project_id') is-invalid @enderror">
                                    <option value="">-- Select ---</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}"
                                            {{old('project_id')===$project->id ? 'selected' : ''}}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
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
        $('.t_type').on('change', function () {
            let status = $(this).val();
            if(status==='2'){
                $('.projects').addClass('show').removeClass('d-none');
            }
            if(status==='1'){
                $('.projects').addClass('d-none').removeClass('show');
            }
        });
        $('.payment_type').on('change', function () {
            let status = $(this).val();
            if(status==='1'){
                $('.bank').addClass('show').removeClass('d-none');
            }
            if(status==='2'){
                $('.bank').addClass('d-none').removeClass('show');
            }
        });
    });
    </script>
@endsection

