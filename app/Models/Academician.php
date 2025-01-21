<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Academician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'college',
        'department',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leadingGrants()
    {
        return $this->hasMany(ResearchGrant::class);
    }

    public function researchGrants()
    {
        return $this->belongsToMany(ResearchGrant::class, 'academician_research_grant');
    }
}
