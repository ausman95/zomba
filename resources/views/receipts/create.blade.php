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
        <hr>
        <div class="mt-2">
            <form action="{{route('receipts.store')}}" method="POST" autocomplete="off" onsubmit="return disableSubmitButton(this);">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        @csrf
                        <div class="form-group">
                            <label>Transactor</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="type" required class="form-select select-relation type @error('type') required is-invalid @enderror" style="width: 100%">{{old('type')}} >
                                <option value="">-- Select ---</option>
                                <option value="5">Members</option>
{{--                                <option value="2">Debtors</option>--}}
                                <option value="1">Church</option>
                                <option value="6">Home Churches</option>
                                <option value="7">Ministries</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="debtors d-none">
                            <div class="form-group">
                                <label>Debtors</label>
                                <select name="debtor_id"
                                        class="form-select select-relation @error('debtor_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($debtors as $debtor)
                                        <option value="{{$debtor->id}}"
                                            {{old('debtor_id')===$debtor->id ? 'selected' : ''}}>{{$debtor->name}}</option>
                                    @endforeach
                                </select>
                                @error('debtor_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="members d-none">
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
                            <input type="text" name="amount" required
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
                            <label>Description</label>
                            <textarea name="specification" rows="2"
                                      class="form-control @error('specification')
                                      is-invalid @enderror" placeholder="Optional">{{old('specification')}}</textarea>
                            @error('specification')
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
                        <div class="form-group">
                            <label>Type</label>
                            <select name="pledge" required class="form-select select-relation @error('pledge') required is-invalid @enderror" style="width: 100%">{{old('pledge')}} >
                                <option value="1">Normal</option>
                                <option value="2">Pledge</option>

                            </select>
                            @error('pledge')
                            <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-md btn-primary rounded-0 submit-button">
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
        function disableSubmitButton(form) {
            const submitButton = form.querySelector('.submit-button');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
            }
            return true; // Allow form submission
        }

        $(document).ready(function () {
            $('.type').on('change', function () {
                let status = $(this).val();
                $('.debtors, .suppliers, .members, .projects, .churches, .ministries, .employees').addClass('d-none').removeClass('show');

                switch (status) {
                    case '1':
                        // Church Selected
                        break; // No specific related field is showed
                    case '2':
                        $('.debtors').removeClass('d-none').addClass('show');
                        break;
                    case '5':
                        $('.members').removeClass('d-none').addClass('show');
                        break;
                    case '6':
                        $('.churches').removeClass('d-none').addClass('show');
                        break;
                    case '7':
                        $('.ministries').removeClass('d-none').addClass('show');
                        break;
                    default:
                        // Handle other cases if needed
                        break;
                }
            });
        });
    </script>
@endsection

