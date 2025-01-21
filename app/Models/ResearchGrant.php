<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class ResearchGrant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'grant_amount', 'grant_provider', 'duration', 'academician_id', 'start_date'
    ];

    protected $casts = [
        'start_date' => 'date'
    ];

    public function projectLeader()
    {
        return $this->belongsTo(Academician::class, 'academician_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(Academician::class, 'academician_research_grant');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Get the expected completion date based on the last milestone
     */
    public function getCompletionDate()
    {
        $lastMilestone = $this->milestones()
            ->orderBy('target_completion_date', 'desc')
            ->first();
            
        return $lastMilestone ? Carbon::parse($lastMilestone->target_completion_date) : null;
    }
}
