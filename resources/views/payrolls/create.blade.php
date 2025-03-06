@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-file-contract"></i> Labourer Contracts</h4>
        <p>Create Labourer Contracts</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Payroll</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-2">
            <form action="{{ route('payrolls.store') }}" method="POST">
                @csrf
                <div class="row-cols-3">
                    <div class="mb-3">
                        <label for="month_id">Select Month</label>
                        <select name="month_id" id="month_id" class="form-control select-relation">
                            @foreach ($months as $month)
                                <option value="{{ $month->id }}">{{ $month->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Payroll Date</label>
                        <input type="date" name="payroll_date"
                               class="form-control @error('payroll_date') is-invalid @enderror"
                               placeholder="Date">
                        @error('payroll_date')
                        <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="big-checkbox-label">
                        <input type="checkbox" name="generate_all" id="generate_all" class="big-checkbox-input">
                        <span class="big-checkbox-text">Generate Payroll for All Labourers</span>
                    </label>


                </div>
                <div class="row-cols-3">
                    <div class="mb-3" id="labourer_select" style="display: none;">
                        <label for="labourer_id">Select Labourer</label>
                        <select name="labourer_id" id="labourer_id" class="select-relation form-control" style="width: 100%">
                            @foreach ($labourers as $labourer)
                                <option value="{{ $labourer->id }}">{{ $labourer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generate Payroll</button>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#generate_all').change(function() {
                if (this.checked) {
                    $('#labourer_select').hide();
                    $('#labourer_id').removeAttr('name');
                } else {
                    $('#labourer_select').show();
                    $('#labourer_id').attr('name', 'labourer_id');
                }
            });
        });
    </script>
@endsection
