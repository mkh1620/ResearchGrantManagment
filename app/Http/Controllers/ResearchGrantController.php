<?php

namespace App\Http\Controllers;

use App\Models\ResearchGrant;
use App\Models\Academician;
use Illuminate\Http\Request;

class ResearchGrantController extends Controller
{
    public function index(Request $request)
    {
        $query = ResearchGrant::with(['projectLeader', 'teamMembers', 'milestones']);  // Add milestones to eager loading
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('grant_provider', 'like', "%{$search}%")
                  ->orWhereHas('projectLeader', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('provider')) {
            $query->where('grant_provider', $request->provider);
        }

        if ($request->filled('min_amount')) {
            $query->where('grant_amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('grant_amount', '<=', $request->max_amount);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // User role based filtering
        $user = auth()->user();
        if (!in_array($user->role, ['Admin', 'Staff'])) {
            $academician = $user->academician;
            $query->where(function($q) use ($academician) {
                $q->where('academician_id', $academician->id)
                  ->orWhereHas('teamMembers', function($q) use ($academician) {
                      $q->where('academician_id', $academician->id);
                  });
            });
        }

        $grants = $query->paginate(10)->withQueryString();
        $providers = ResearchGrant::distinct('grant_provider')->pluck('grant_provider');
        
        return view('grants.index', compact('grants', 'providers'));
    }

    public function create()
    {
        $academicians = Academician::all();
        return view('grants.create', compact('academicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'grant_amount' => 'required|numeric|min:0',
            'grant_provider' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'academician_id' => 'required|exists:academicians,id',
            'start_date' => 'required|date'  // Add this line
        ]);

        ResearchGrant::create($validated);
        return redirect()->route('grants.index')->with('success', 'Research grant created successfully.');
    }

    public function show(ResearchGrant $grant)
    {
        $grant->load(['projectLeader', 'teamMembers', 'milestones']);
        $availableAcademicians = Academician::whereNotIn('id', $grant->teamMembers->pluck('id'))
            ->where('id', '!=', $grant->academician_id)
            ->get();
        return view('grants.show', compact('grant', 'availableAcademicians'));
    }

    public function edit(ResearchGrant $grant)
    {
        $academicians = Academician::all();
        return view('grants.edit', compact('grant', 'academicians'));
    }

    public function update(Request $request, ResearchGrant $grant)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'grant_amount' => 'required|numeric|min:0',
            'grant_provider' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'academician_id' => 'required|exists:academicians,id',
            'start_date' => 'required|date'  // Add this line
        ]);

        $grant->update($validated);
        return redirect()->route('grants.index')->with('success', 'Research grant updated successfully.');
    }

    public function myGrants()
    {
        $grants = ResearchGrant::where('academician_id', auth()->user()->academician->id)
            ->with('projectLeader')
            ->latest()
            ->paginate(10);

        $providers = ResearchGrant::distinct('grant_provider')->pluck('grant_provider');

        return view('grants.index', compact('grants', 'providers'));
    }

    public function updateMembers(Request $request, ResearchGrant $grant)
    {
        $this->authorize('manage-members', $grant);
        
        $validated = $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:academicians,id'
        ]);

        $grant->teamMembers()->sync($validated['member_ids']);
        return redirect()->route('grants.show', $grant)
            ->with('success', 'Team members updated successfully.');
    }

    public function removeMember(Request $request, ResearchGrant $grant)
    {
        $this->authorize('manage-members', $grant);
        
        $validated = $request->validate([
            'member_id' => 'required|exists:academicians,id'
        ]);

        if ($grant->teamMembers->count() <= 1) {
            return back()->with('error', 'Cannot remove the last team member.');
        }

        $grant->teamMembers()->detach($validated['member_id']);
        return back()->with('success', 'Team member removed successfully.');
    }

    public function destroy(ResearchGrant $grant)
    {
        $grant->delete();
        return redirect()->route('grants.index')->with('success', 'Research grant deleted successfully.');
    }
}
