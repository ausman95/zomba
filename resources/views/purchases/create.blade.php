@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Purchases
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

        <!-- Modal -->
            <hr>
        <div class="mt-2">
            <form action="{{route('purchases.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                        <div class="form-group">
                            <label>Material Name</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="material_id" required
                                    class="form-select select-relation @error('material_id') is-invalid @enderror" style="width: 100%">
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
                                <select name="account_id" required
                                        class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
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
                            <select name="payment_type" required class="form-select select-relation payment_type @error('payment_type') is-invalid @enderror" style="width: 100%">{{old('payment_type')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Cash</option>
                                <option value="2">Credit</option>
                                <option value="4">Online Transfer</option>
                            </select>
                            @error('payment_type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" required
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{old('quantity')}}"
                                   placeholder="Quantity" >
                            @error('quantity')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" name="amount" required
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="amount" >
                            @error('amount')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="bank  d-none">
                            <div class="form-group ">
                                <label>Bank Account</label>
                                <select name="bank_id"
                                        class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
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
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Suppliers</label>
                            <select name="supplier_id" required
                                    class="form-select select-relation @error('supplier_id') is-invalid @enderror" style="width: 100%">
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
                            <label>Reference (is Optional)</label>
                            <input type="text" name="reference"
                                   class="form-control @error('reference') is-invalid @enderror"
                                   placeholder="Reference Number" >
                            @error('reference')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="projects">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="project_id" required
                                        class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
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
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" required
                                   class="form-control @error('date') is-invalid @enderror"
                                   value="{{old('date')}}"
                                   placeholder="Purchase Date" >
                            @error('date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-md btn-primary rounded-0">
                        <i class="fa fa-paper-plane"></i>Save
                    </button>
                </div>
            </form>
        </div>

    </div>
@stop
@section('scripts')
    <script>
    $(document).ready(function () {
        $('.stores').on('change', function () {
            let status = $(this).val();
            if(status==='3'){
                $('.projects').addClass('show').removeClass('d-none');
            }
            else{
                $('.projects').addClass('d-none').removeClass('show');
            }
        });
        $('.payment_type').on('change', function () {
            let status = $(this).val();
            if(status==='2'){
                $('.bank').addClass('d-none').removeClass('show');
            }
            else{
                $('.bank').addClass('show').removeClass('d-none');
            }
        });
    });
    </script>
@endsection

