@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-car"></i>Driver Allowances
        </h4>
        <p>
            Manage Driver Allowances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('drivers.index')}}">Driver Allowances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Driver Allowance</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('drivers.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> Driver </label>
                            <select name="labourer_id"
                                    class="form-select select-relation @error('labourer_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}"
                                        {{old('labourer_id')===$driver->id ? 'selected' : ''}}>{{$driver->name}}</option>
                                @endforeach
                            </select>
                            @error('labourer_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Allowance For </label>
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
                            <label>Amount</label>
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
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date')}}"
                                   placeholder="Start Date" >
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date')}}"
                                   placeholder="End Date" >
                            @error('end_date')
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
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            // $('.btn-success').click(function () {
            //     let check = $('.form-control').val();
            //     if(check){
            //         $(this).removeClass("fa fa-save").html('<span class="fa fa-spin fa-spinner"></span> Please Wait').attr('disabled',true);
            //     }
            // });
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.labourers').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='2'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.labourers').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                }
                if(status==='3'){
                    $('.suppliers').addClass('show').removeClass('d-none');
                    $('.labourers').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='4'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.labourers').addClass('show').removeClass('d-none');
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
