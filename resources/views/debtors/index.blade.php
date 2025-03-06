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
{{--            <a href="{{ route('debtors.create') }}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fas fa-plus-circle"></i> New Debtor--}}
{{--            </a>--}}

            <div class="card container-fluid" style="min-height: 30em;">
                <div class="card" style="min-height: 30em;">
                    <div class="card-body px-1">
                        @if (empty($memberData))
                            <i class="fas fa-info-circle"></i> There are no Members!
                        @else
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">Members</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAME</th>
                                        <th>PHONE</th>
                                        <th>HOME</th>
                                        <th>BALANCE</th>
                                        <th>CREATED ON</th>
                                        <th>CREATED BY</th>
                                        <th>UPDATED BY</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1; ?>
                                    @foreach ($memberData as $data)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ $data['member']->name }}</td>
                                            <td>{{ $data['member']->phone_number }}</td>
                                            <td>{{ \App\Models\Church::find($data['member']->church_id)->name }}</td>
                                            <td>{{ number_format($data['balance'], 2) }}</td>
                                            <td>{{ date('d F Y', strtotime($data['member']->created_at)) }}</td>
                                            <td>{{ \App\Models\User::find($data['member']->created_by)?->name ?? '-' }}</td>
                                            <td>{{ \App\Models\User::find($data['member']->updated_by)?->name ?? '-' }}</td>
                                            <td class="pt-1">
                                                <a href="{{ route('members.show', $data['member']) }}"
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
