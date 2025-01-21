<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchGrant;
use App\Models\User;
use App\Models\Milestone;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Stats for admin
        $totalGrants = ResearchGrant::count();
        $totalFunding = ResearchGrant::sum('grant_amount');
        $totalResearchers = User::where('role', 'Academician')->count();
        $recentGrants = ResearchGrant::latest()->take(5)->get();

        // Data for academician (both leader and member)
        $myGrants = null;
        $memberGrants = null;
        $upcomingMilestones = null;
        $myStats = null;

        if ($user->role === 'Academician' && $user->academician) {
            // Grants where user is leader
            $myGrants = $user->academician->leadingGrants;
            logger($myGrants);
            // Grants where user is a team member
            $memberGrants = $user->academician->researchGrants;
            
            // Calculate academician stats
            $myStats = [
                'total_leading' => $myGrants->count(),
                'total_member' => $memberGrants->count(),
                'total_active' => $memberGrants->where('status', 'active')->count(),
                'total_funding' => $myGrants->sum('grant_amount'),
                'total_milestones' => Milestone::whereIn('research_grant_id', 
                    $memberGrants->pluck('id')->concat($myGrants->pluck('id'))->unique()
                )->count(),
                'completed_milestones' => Milestone::whereIn('research_grant_id', 
                    $memberGrants->pluck('id')->concat($myGrants->pluck('id'))->unique()
                )->where('status', 'completed')->count()
            ];
            logger($myStats);

            // Get upcoming milestones for all involved grants
            $upcomingMilestones = Milestone::whereIn('research_grant_id', 
                $memberGrants->pluck('id')->concat($myGrants->pluck('id'))->unique()
            )
                ->where('status', '!=', 'completed')
                ->orderBy('target_completion_date')
                ->take(5)
                ->get();
        }

        return view('home', compact(
            'totalGrants',
            'totalFunding',
            'totalResearchers',
            'recentGrants',
            'myGrants',
            'memberGrants',
            'upcomingMilestones',
            'myStats'
        ));
    }
}
