@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Pledges
        </h4>
        <p>
            Member Pledges
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('pledges.index')}}">Pledges</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Pledges</li>
            </ol>
        </nav>
        <a href="{{route('members.create')}}" class="btn btn-primary btn-md rounded-0">
            <i class="fa fa-plus-circle"></i>New Member
        </a>
        <hr>
        <div class="mt-2">
            <form action="{{route('pledges.store')}}" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                            <div class="form-group">
                                <label>Member</label>
                                <select name="member_id"
                                        class="form-select select-relation @error('member_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">-- Select ---</option>
                                    @foreach($members as $member)
                                        <option value="{{$member->id}}"
                                            {{old('member_id')===$member->id ? 'selected' : ''}}>{{$member->name.' '.$member->church->church->name}}</option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                @enderror
                            </div>
                        <div class="form-group">
                            <label>Year</label>
                            <select name="year_id"
                                    class="form-select select-relation @error('year_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($years as $year)
                                    <option value="{{$year->id}}"
                                        {{old('year_id')===$year->id ? 'selected' : ''}}>{{$year->name.' - '.$year->start_date.' to '.$year->end_date}}</option>
                                @endforeach
                            </select>
                            @error('year_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Pledge Amount / Month</label>
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

