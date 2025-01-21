<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'research_grant_id', 'name', 'target_completion_date', 
        'deliverable', 'status', 'remark', 'date_updated'
    ];

    public function researchGrant()
    {
        return $this->belongsTo(ResearchGrant::class);
    }
}
