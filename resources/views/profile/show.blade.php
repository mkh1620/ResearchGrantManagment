@extends('layouts.app')

@section('breadcrumbs')
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>My Profile</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">Edit Profile</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $user->name }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $user->email }}</dd>

                        <dt class="col-sm-3">Role</dt>
                        <dd class="col-sm-9">{{ $user->role }}</dd>

                        @if($user->academician)
                            <dt class="col-sm-3">College</dt>
                            <dd class="col-sm-9">{{ $user->academician->college }}</dd>

                            <dt class="col-sm-3">Department</dt>
                            <dd class="col-sm-9">{{ $user->academician->department }}</dd>

                            <dt class="col-sm-3">Position</dt>
                            <dd class="col-sm-9">{{ $user->academician->position }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
