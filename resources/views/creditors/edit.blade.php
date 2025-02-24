@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-user-edit"></i> Edit Creditor
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('creditors.index') }}">Creditors</a></li>
                <li class="breadcrumb-item"><a href="{{ route('creditors.show', $creditor) }}">{{ $creditor->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="mt-2">
            <div class="row">
                <div class="col-md-8 col-lg-6"> {{-- Adjust column size as needed --}}
                    <form action="{{ route('creditors.update', $creditor) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PATCH')  {{-- Important for updates --}}
                        <input type="hidden" name="updated_by" value="{{ request()->user()->id }}" required>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') ?? $creditor->name }}" required
                                   placeholder="Creditor name">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{ old('phone_number') ?? $creditor->phone_number }}" required
                                   placeholder="Phone number">
                            @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') ?? $creditor->email }}" required
                                   placeholder="Email address">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address (Optional)</label>
                            <textarea name="address" id="address"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Address">{{ old('address') ?? $creditor->address }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-md btn-primary rounded-0">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
