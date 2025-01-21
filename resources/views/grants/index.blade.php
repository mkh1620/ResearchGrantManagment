@extends('layouts.app')

@section('breadcrumbs')
<li class="breadcrumb-item active">Research Grants</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <form action="{{ route('grants.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                    value="{{ request('search') }}" placeholder="Search grants...">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            @can('admin-executive')
                                <a href="{{ route('grants.create') }}" class="btn btn-primary">Add New Grant</a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('grants.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Provider</label>
                                <select name="provider" class="form-select">
                                    <option value="">All Providers</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                                            {{ $provider }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Minimum Amount</label>
                                <input type="number" name="min_amount" class="form-control" 
                                    value="{{ request('min_amount') }}" placeholder="Min Amount">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Maximum Amount</label>
                                <input type="number" name="max_amount" class="form-control" 
                                    value="{{ request('max_amount') }}" placeholder="Max Amount">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('grants.index') }}" class="btn btn-secondary">Clear Filters</a>
                                </div>
                            </div>
                        </div>

                        <!-- Include any existing search parameter -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <!-- Include any existing sort parameters -->
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                    </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ route('grants.index', ['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                            Title
                                            @if(request('sort') === 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('grants.index', ['sort' => 'grant_amount', 'direction' => request('sort') === 'grant_amount' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                            Amount
                                        </a>
                                    </th>
                                    <th>Provider</th>
                                    <th>Duration (months)</th>
                                    <th>Start Date</th>
                                    <th>Expected Completion</th>
                                    <th>Project Leader</th>
                                    @if(auth()->user()->role === 'Academician')
                                        <th>Your Role</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grants as $grant)
                                <tr>
                                    <td>{{ $grant->title }}</td>
                                    <td>RM {{ number_format($grant->grant_amount, 2) }}</td>
                                    <td>{{ $grant->grant_provider }}</td>
                                    <td>{{ $grant->duration }}</td>
                                    <td>{{ $grant->start_date ? $grant->start_date->format('d M Y') : '-' }}</td>
                                    <td>{{ $grant->getCompletionDate() ? $grant->getCompletionDate()->format('d M Y') : '-' }}</td>
                                    <td>{{ $grant->projectLeader->name }}</td>
                                    @if(auth()->user()->role === 'Academician')
                                        <td>
                                            @if($grant->academician_id === optional(auth()->user()->academician)->id)
                                                Project Leader
                                            @elseif($grant->teamMembers->contains(optional(auth()->user()->academician)->id))
                                                Team Member
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('grants.show', $grant) }}" class="btn btn-sm btn-info">View</a>
                                        @can('manage-grant', $grant)
                                            <a href="{{ route('grants.edit', $grant) }}" class="btn btn-sm btn-primary">Edit</a>
                                            @can('admin-executive')
                                                <form action="{{ route('grants.destroy', $grant) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endcan
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $grants->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        padding-left: 1rem;
        --bs-pagination-font-size: 0.875rem;
    }
    .page-link {
        min-width: 36px;
        height: 36px;
        padding: 0.375rem 0.75rem;
    }
    .page-link i {
        font-size: 1rem;
    }
</style>
@endpush