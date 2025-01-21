<?php

namespace App\Http\Controllers;

use App\Models\ResearchGrant;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ResearchGrant $grant)
    {
        $this->authorize('manage-milestones', $grant);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deliverable' => 'required|string',
            'target_completion_date' => 'required|date',
        ]);

        $grant->milestones()->create($validated + ['status' => 'pending']);
        return back()->with('success', 'Milestone created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResearchGrant $grant, Milestone $milestone)
    {
        $this->authorize('manage-milestones', $grant);

        $validated = $request->validate([
            'status' => 'required|in:pending,in progress,completed',
            'remark' => 'nullable|string',
        ]);

        $milestone->update($validated);
        return back()->with('success', 'Milestone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchGrant $grant, Milestone $milestone)
    {
        $milestone->delete();
        return redirect()->route('grants.show', $grant)
            ->with('success', 'Milestone deleted successfully.');
    }
}
