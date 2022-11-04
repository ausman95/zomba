@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Payments
        </h4>
        <p>
            Payments
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.subcontractors')}}">Subcontractors</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Payments</li>
            </ol>
        </nav>
        <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#account">
            <i class="fa fa-plus-circle"></i> New Chart of Account
        </button>
        <div class="modal " id="account" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Adding Chart of Accounts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('accounts.store')}}" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}"
                                       placeholder="Account's name" >
                                @error('name')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <hr style="height: .3em;" class="border-theme">
                            <div class="form-group">
                                <label>Account Type</label>
                                <select name="type" class="form-control select-relation @error('type') is-invalid @enderror" style="width: 100%">{{old('type')}}>
                                    <option value="1">Cr</option>
                                    <option value="2">Dr</option>
                                </select>
                                @error('type')
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-2">
            <form action="{{route('transactions.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Payment Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="Payment Name" >
                            @error('name')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Account</label>
                            <select name="account_id"
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
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-select select-relation payment_method @error('payment_method') is-invalid @enderror" style="width: 100%">{{old('payment_method')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Cash</option>
                                <option value="3">Cheque</option>
                                <option value="4">Online Transfer</option>
                            </select>
                            @error('payment_method')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="hidden" name="cheque_number"
                                   class="form-control @error('cheque_number') is-invalid @enderror"
                                   value="0"
                                   placeholder="Cheque Number">
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount" >
                            @error('amount')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" name="reference"
                                   class="form-control @error('reference') is-invalid @enderror"
                                   placeholder="Reference Number" >
                            @error('reference')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf

                        <div class="form-group">
                            <label>Subcontractor</label>
                            <select name="labourer_id"
                                    class="form-select select-relation @error('labourer_id') is-invalid @enderror" style="width: 100%">
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
                        <div class="form-group">
                            <label>Projects / Site</label>
                            <select name="project_id"
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
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="description" class="form-select select-relation description @error('description') required is-invalid @enderror" style="width: 100%">{{old('description')}} >
                                <option value="0">Select Type</option>
                                <option value="4">Opening Balance</option>
                                <option value="1">Normal</option>
                                <option value="2">Advance</option>
                            </select>
                            @error('description')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="bank d-none">
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
        $('.description').on('change', function () {
            let status = $(this).val();
            if(status!='4'){
                $('.bank').addClass('show').removeClass('d-none');
            }
            else{
                $('.bank').addClass('d-none').removeClass('show');
            }
        });
    });
    </script>
@endsection

