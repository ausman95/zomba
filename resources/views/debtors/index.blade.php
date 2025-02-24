@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-users"></i> Debtors
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li> {{-- Adjust as needed --}}
                <li class="breadcrumb-item active" aria-current="page">Debtors</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>

        <div class="mt-3">
            <a href="{{ route('debtors.create') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-plus-circle"></i> New Debtor
            </a>

            <div class="card container-fluid" style="min-height: 30em;">
                <div class="card" style="min-height: 30em;">
                    <div class="card-body px-1">
                        @if ($debtors->count() === 0)
                            <i class="fas fa-info-circle"></i> There are no Debtors!
                        @else
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">Debtors</caption>
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
                                        <?php $c = 1; ?>
                                    @foreach ($debtors as $debtor)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ $debtor->name }}</td>
                                            <td>{{ $debtor->phone_number }}</td>
                                            <td>{{ $debtor->email }}</td>
                                            <td>{{ $debtor->address ?? '-' }}</td>
                                            <td>
                                                {{ number_format($debtor->latestStatement?->balance ?? 0, 2) }}
                                            </td>
                                            <td>{{ date('d F Y', strtotime($debtor->created_at)) }}</td>
                                            <td>{{ \App\Models\User::find($debtor->created_by)?->name ?? '-' }}</td>
                                            <td>{{ \App\Models\User::find($debtor->updated_by)?->name ?? '-' }}</td>
                                            <td class="pt-1">
                                                <a href="{{ route('debtors.show', $debtor) }}"
                                                   class="btn btn-primary btn-md rounded-0">
                                                    <i class="fas fa-list-ol"></i> Manage
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
