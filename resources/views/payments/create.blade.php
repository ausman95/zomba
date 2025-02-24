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
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('payments.index')}}">Payments</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Payments</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-2">
            <form action="{{route('payments.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                        <div class="form-group">
                            <label>Payee</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="type"
                                    required
                                    class="form-select select-relation type @error('type') required is-invalid @enderror" style="width: 100%">{{old('type')}} >
                                <option value="">-- Select ---</option>
                                <option value="2">Admin</option>
                                <option value="4">Employees</option>
                                <option value="3">Creditors</option>
                                <option value="5">Members</option>
                                <option value="6">Home Churches</option>
                                <option value="7">Ministries</option>
                                <option value="1">Department</option>

                                <option value="8">Others</option>
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
                        <div class="creditors d-none">
                            <div class="form-group">
                                <label>Creditors</label>
                                <select name="creditor_id"
                                        class="form-select select-relation @error('creditor_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($creditors as $creditor)
                                        <option value="{{$creditor->id}}"
                                            {{old('creditor_id')===$creditor->id ? 'selected' : ''}}>{{$creditor->name}}</option>
                                    @endforeach
                                </select>
                                @error('creditor_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="members  d-none">
                            <div class="form-group">
                                <label>Employees</label>
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
                                <label>Payment Type</label>
                                <select name="description" class="form-select select-relation description @error('description') required is-invalid @enderror" style="width: 100%">{{old('description')}} >
                                    <option value="1">Normal</option>
                                    <option value="2">Advance</option>
                                    <option value="3">Allowances</option>
                                    <option value="4">Other</option>
                                </select>
                                @error('description')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="projects  d-none">
                            <div class="form-group">
                                <label>Department</label>
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
                            <select name="payment_method" required class="form-select select-relation payment_method @error('payment_method') is-invalid @enderror" style="width: 100%">{{old('payment_method')}}>
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
                            <label>Description</label>
                            <textarea name="specification" rows="2"
                                      class="form-control @error('specification')
                                      is-invalid @enderror" placeholder="Description">{{old('specification')}}</textarea>
                            @error('specification')
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
{{--    @if(@$_GET['id']=='success')--}}
{{--            <script>--}}
{{--                $(document).ready(function () {--}}
{{--                    window.open('../libs/receipt.pdf', "_blank", "scrollbars=yes,width=700,height=600,top=30");--}}
{{--                });--}}
{{--            </script>--}}
{{--    @endif--}}
    <script>
    $(document).ready(function () {
        $('.type').on('change', function () {
            let status = $(this).val();
            if(status==='1'){
                $('.creditors').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('show').removeClass('d-none');

                $('.churches').addClass('d-none').removeClass('show');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('d-none').removeClass('show');
            }
            if(status==='2'){
                $('.creditors').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('d-none').removeClass('show');
                $('.churches').addClass('d-none').removeClass('show');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('d-none').removeClass('show');
            }
            if(status==='3'){
                $('.creditors').addClass('show').removeClass('d-none');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('d-none').removeClass('show');
                $('.churches').addClass('d-none').removeClass('show');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('d-none').removeClass('show');
            }
            if(status==='4'){
                $('.creditors').addClass('d-none').removeClass('show');
                $('.members').addClass('show').removeClass('d-none');
                $('.projects').addClass('d-none').removeClass('show');
                $('.churches').addClass('d-none').removeClass('show');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('d-none').removeClass('show');
            }
            if(status==='5'){
                $('.creditors').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('d-none').removeClass('show');
                $('.churches').addClass('d-none').removeClass('show');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('show').removeClass('d-none');
            }
            if(status==='6'){
                $('.creditors').addClass('d-none').removeClass('show');
                $('.members').addClass('d-none').removeClass('show');
                $('.projects').addClass('d-none').removeClass('show');
                $('.churches').addClass('show').removeClass('d-none');
                $('.ministries').addClass('d-none').removeClass('show');
                $('.employees').addClass('d-none').removeClass('show');
            }
            if(status==='7'){
                $('.creditors').addClass('d-none').removeClass('show');
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

