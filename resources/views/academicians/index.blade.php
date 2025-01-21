@extends('layouts.app')

@section('breadcrumbs')
<li class="breadcrumb-item active">Manage Academicians</li>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('academicians.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" 
                            value="{{ request('search') }}" placeholder="Search academicians...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('academicians.create') }}" class="btn btn-primary">Add New Academician</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Leading Grants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($academicians as $academician)
                        <tr>
                            <td>{{ $academician->name }}</td>
                            <td>{{ $academician->user->email }}</td>
                            <td>{{ $academician->college }}</td>
                            <td>{{ $academician->department }}</td>
                            <td>{{ $academician->position }}</td>
                            <td>{{ $academician->leadingGrants->count() }}</td>
                            <td>
                                <a href="{{ route('academicians.edit', $academician) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('academicians.destroy', $academician) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this academician?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $academicians->links('pagination::bootstrap-5') }}
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
