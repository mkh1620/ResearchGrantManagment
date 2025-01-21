@extends('layouts.app')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('grants.index') }}">Research Grants</a></li>
<li class="breadcrumb-item"><a href="{{ route('grants.show', $grant) }}">{{ $grant->title }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Research Grant</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('grants.update', $grant) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title', $grant->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="grant_amount" class="form-label">Grant Amount (RM)</label>
                            <input type="number" step="0.01" class="form-control @error('grant_amount') is-invalid @enderror" 
                                id="grant_amount" name="grant_amount" value="{{ old('grant_amount', $grant->grant_amount) }}" required>
                            @error('grant_amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="grant_provider" class="form-label">Grant Provider</label>
                            <input type="text" class="form-control @error('grant_provider') is-invalid @enderror" 
                                id="grant_provider" name="grant_provider" value="{{ old('grant_provider', $grant->grant_provider) }}" required>
                            @error('grant_provider')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (months)</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                id="duration" name="duration" value="{{ old('duration', $grant->duration) }}" required>
                            @error('duration')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="academician_id" class="form-label">Project Leader</label>
                            <select class="form-select @error('academician_id') is-invalid @enderror" 
                                id="academician_id" name="academician_id" required>
                                <option value="">Select Project Leader</option>
                                @foreach($academicians as $academician)
                                    <option value="{{ $academician->id }}" 
                                        {{ old('academician_id', $grant->academician_id) == $academician->id ? 'selected' : '' }}>
                                        {{ $academician->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academician_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Research Grant</button>
                            <a href="{{ route('grants.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
