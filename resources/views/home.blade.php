@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('admin-executive')
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Admin Executive Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Grants</h5>
                                        <h2 class="mb-0">{{ $totalGrants ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Funding</h5>
                                        <h2 class="mb-0">RM {{ number_format($totalFunding ?? 0, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Researchers</h5>
                                        <h2 class="mb-0">{{ $totalResearchers ?? 0 }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5>Quick Actions</h5>
                            <div class="list-group">
                                <a href="{{ route('grants.create') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-plus"></i> Add New Research Grant
                                </a>
                                <a href="{{ route('academicians.create') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-user-plus"></i> Add New Academician
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('project-leader')
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Project Leader Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h5>My Research Grants</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myGrants ?? [] as $grant)
                                        <tr>
                                            <td>{{ $grant->title }}</td>
                                            <td>RM {{ number_format($grant->grant_amount, 2) }}</td>
                                            <td>{{ $grant->duration }} months</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>
                                                <a href="{{ route('grants.show', $grant) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No research grants found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <h5>Upcoming Milestones</h5>
                            <div class="list-group">
                                @forelse($upcomingMilestones ?? [] as $milestone)
                                    <div class="list-group-item">
                                        <h6 class="mb-1">{{ $milestone->title }}</h6>
                                        <p class="mb-1">Grant: {{ $milestone->researchGrant->title }}</p>
                                        <small>Due: {{ $milestone->target_completion_date }}</small>
                                    </div>
                                @empty
                                    <div class="list-group-item">No upcoming milestones.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('irmc-staff')
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">iRMC Staff Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Recent Applications</h5>
                                        <div class="list-group">
                                            @forelse($recentGrants ?? [] as $grant)
                                                <div class="list-group-item">
                                                    <h6 class="mb-1">{{ $grant->title }}</h6>
                                                    <p class="mb-1">Leader: {{ $grant->projectLeader->name }}</p>
                                                    <small>Submitted: {{ $grant->created_at->diffForHumans() }}</small>
                                                </div>
                                            @empty
                                                <div class="list-group-item">No recent applications.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Quick Actions</h5>
                                        <div class="list-group">
                                            <a href="{{ route('grants.index') }}" class="list-group-item list-group-item-action">
                                                <i class="fas fa-list"></i> View All Grants
                                            </a>
                                            <a href="{{ route('academicians.index') }}" class="list-group-item list-group-item-action">
                                                <i class="fas fa-users"></i> View All Academicians
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection
