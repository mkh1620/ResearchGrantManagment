<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResearchGrantController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\AcademicianController;

Route::get('/', function () {
    $totalGrants = \App\Models\ResearchGrant::count();
    $totalFunding = \App\Models\ResearchGrant::sum('grant_amount');
    $totalResearchers = \App\Models\Academician::count();
    $recentGrants = \App\Models\ResearchGrant::with('projectLeader')
        ->latest()
        ->take(5)
        ->get();

    return view('welcome', compact(
        'totalGrants',
        'totalFunding',
        'totalResearchers',
        'recentGrants'
    ));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Admin only routes
    Route::middleware(['can:admin-executive'])->group(function () {
        Route::resource('academicians', AcademicianController::class);
    });

    // Admin and Staff only routes
    Route::middleware(['can:manage-grant'])->group(function () {
        Route::resource('grants', ResearchGrantController::class)->except(['index', 'show']);
    });

    // Academician specific routes
    Route::get('/my-grants', [ResearchGrantController::class, 'myGrants'])->name('grants.my');

    // Routes accessible by all authenticated users
    Route::get('/grants', [ResearchGrantController::class, 'index'])->name('grants.index');
    Route::get('/grants/{grant}', [ResearchGrantController::class, 'show'])->name('grants.show');

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Grant team and milestone management routes
    Route::middleware(['can:manage-members,grant'])->group(function () {
        Route::post('/grants/{grant}/members', [ResearchGrantController::class, 'updateMembers'])
            ->name('grants.members.update');
        Route::delete('/grants/{grant}/members', [ResearchGrantController::class, 'removeMember'])
            ->name('grants.members.remove');
    });

    Route::middleware(['can:manage-milestones,grant'])->group(function () {
        Route::post('/grants/{grant}/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::put('/grants/{grant}/milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
        Route::delete('/grants/{grant}/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
    });
});
