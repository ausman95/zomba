@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i> Creditors
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Creditors</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{ route('creditors.create') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Creditor
            </a>

            <div class="card container-fluid" style="min-height: 30em;">
                <div class="card">  {{-- Removed extra row and card --}}
                    <div class="card-body px-1">
                        @if ($creditors->isEmpty())  {{-- Use $creditors->isEmpty() for cleaner check --}}
                        <div class="text-center">  {{-- Center the message --}}
                            <i class="fa fa-info-circle fa-2x"></i>  {{-- Make icon bigger --}}
                            <p class="mt-2">There are no Creditors!</p>  {{-- Add some margin --}}
                        </div>
                        @else
                            <div class="table-responsive"> {{-- Add table-responsive for better responsiveness --}}
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">Creditors</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAME</th>
                                        <th>PHONE</th>
                                        <th>EMAIL</th>
                                        <th>ADDRESS</th>
                                        <th>BALANCE</th>
                                        <th>CREATED ON</th>
                                        <th>CREATED BY</th>
                                        <th>UPDATED BY</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $c = 1; @endphp
                                    @foreach ($creditors as $creditor)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ $creditor->name }}</td>
                                            <td>{{ $creditor->phone_number }}</td>
                                            <td>{{ $creditor->email }}</td>
                                            <td>{{ $creditor->address ?? '-' }}</td>
                                            <td>
                                                {{ number_format($creditor->latestStatement?->balance ?? 0, 2) }}
                                            </td>
                                            <td>{{ $creditor->created_at->format('d F Y') }}</td>
                                            <td>{{ $creditor->creator?->name ?? '-' }}</td>
                                            <td>{{ $creditor->updater?->name ?? '-' }}</td>
                                            <td class="pt-1">
                                                <a href="{{ route('creditors.show', $creditor) }}"
                                                   class="btn btn-primary btn-md rounded-0">
                                                    <i class="fa fa-list-ol"></i> Manage
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>  {{-- Close table-responsive --}}
                        @endif
                    </div>
                </div>  {{-- Removed extra card --}}
            </div>
        </div>
    </div>
@stop
