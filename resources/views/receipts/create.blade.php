@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Church Receipts
        </h4>
        <p>
            Church Receipts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('receipts.index')}}">Church Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create  Receipt</li>
            </ol>
        </nav>
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
        <div class="modal " id="supplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Adding Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('suppliers.store')}}" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}"
                                       placeholder="Supplier's name"
                                >
                                @error('name')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="number" name="phone_number"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       value="{{old('phone_number')}}"
                                       placeholder="Supplier's phone number"
                                >
                                @error('phone_number')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location"
                                       class="form-control @error('location') is-invalid @enderror"
                                       value="{{old('location')}}"
                                       placeholder="Enter location i.e Lilongwe"
                                >
                                @error('location')
                                <span class="invalid-feedback">
                               {{$message}}
                            </span>
                                @enderror
                            </div>
                            <hr style="height: .3em;" class="border-theme">
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
            <form action="{{route('receipts.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Transactor</label>
                            <select name="type" required class="form-select select-relation type @error('type') required is-invalid @enderror" style="width: 100%">{{old('type')}} >
                                <option value="">-- Select ---</option>
                                <option value="5">Members</option>
                                <option value="6">Home Churches</option>
                                <option value="7">Ministries</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="employees d-none">
                            <div class="form-group">
                                <label>Members</label>
                                <select name="member_id"
                                        class="form-select select-relation @error('member_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($members as $member)
                                        <option value="{{$member->id}}"
                                            {{old('member_id')===$member->id ? 'selected' : ''}}>{{$member->name}}</option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="churches d-none">
                            <div class="form-group">
                                <label>Home Churches</label>
                                <select name="church_id"
                                        class="form-select select-relation @error('church_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($churches as $church)
                                        <option value="{{$church->id}}"
                                            {{old('church_id')===$church->id ? 'selected' : ''}}>{{$church->name}}</option>
                                    @endforeach
                                </select>
                                @error('church_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="ministries d-none">
                            <div class="form-group">
                                <label>Ministries</label>
                                <select name="ministry_id"
                                        class="form-select select-relation @error('ministry_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($ministries as $ministry)
                                        <option value="{{$ministry->id}}"
                                            {{old('ministry_id')===$ministry->id ? 'selected' : ''}}>{{$ministry->name}}</option>
                                    @endforeach
                                </select>
                                @error('ministry_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Account</label>
                            <select name="account_id" required
                                    class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
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

                        <div class="form-group ">
                            <label>Bank Account</label>
                            <select name="bank_id" required
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
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="hidden" name="cheque_number"
                                   class="form-control @error('cheque_number') is-invalid @enderror"
                                   value="0"
                                   placeholder="Cheque Number">
                            <input type="number" name="amount" required
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount" >
                            @error('amount')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" required
                                    class="form-select select-relation payment_method @error('payment_method') is-invalid @enderror" style="width: 100%">{{old('payment_method')}}>
                                <option value="">-- Select ---</option>
                                <option value="1">Cash</option>
                                <option value="3">Cheque</option>
                                <option value="4">Online Transfer </option>
                                <option value="5">Mobile Money Transfer</option>
                            </select>
                            @error('payment_method')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" name="reference"
                                   class="form-control @error('reference') is-invalid @enderror"
                                   placeholder="Optional" >
                            @error('reference')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="t_date" required
                                   class="form-control @error('t_date') is-invalid @enderror">
                            @error('t_date')
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
        @if(@$_GET['id']=='success')
                <script>
                    $(document).ready(function () {
                        window.open('../libs/receipt.pdf', "_blank", "scrollbars=yes,width=700,height=600,top=30");
                    });
                </script>
        @endif
    <script>
        $(document).ready(function () {
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');

                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('d-none').removeClass('show');
                }
                if(status==='2'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('d-none').removeClass('show');
                }
                if(status==='3'){
                    $('.suppliers').addClass('show').removeClass('d-none');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('d-none').removeClass('show');
                }
                if(status==='4'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('show').removeClass('d-none');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('d-none').removeClass('show');
                }
                if(status==='5'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('show').removeClass('d-none');
                }
                if(status==='6'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('show').removeClass('d-none');
                    $('.ministries').addClass('d-none').removeClass('show');
                    $('.employees').addClass('d-none').removeClass('show');
                }
                if(status==='7'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.ministries').addClass('show').removeClass('d-none');
                    $('.employees').addClass('d-none').removeClass('show');
                }
            });
        });
    </script>
@endsection

