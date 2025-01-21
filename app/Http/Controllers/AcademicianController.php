<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AcademicianController extends Controller
{
    public function index(Request $request)
    {
        $query = Academician::with(['user', 'leadingGrants']);

        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('college', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $academicians = $query->orderBy('name')
                             ->paginate(10)
                             ->withQueryString(); // This preserves the search parameter in pagination links

        return view('academicians.index', compact('academicians'));
    }

    public function create()
    {
        return view('academicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'college' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Academician'
        ]);

        // Create academician profile
        $user->academician()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'college' => $validated['college'],
            'department' => $validated['department'],
            'position' => $validated['position']
        ]);

        return redirect()->route('academicians.index')
            ->with('success', 'Academician created successfully.');
    }

    public function edit(Academician $academician)
    {
        return view('academicians.edit', compact('academician'));
    }

    public function update(Request $request, Academician $academician)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $academician->user->id,
            'college' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'password' => 'nullable|min:8|confirmed'
        ]);

        // Update user details
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email']
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $academician->user->update($userData);

        // Update academician details
        $academician->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'college' => $validated['college'],
            'department' => $validated['department'],
            'position' => $validated['position']
        ]);

        return redirect()->route('academicians.index')
            ->with('success', 'Academician updated successfully.');
    }

    public function destroy(Academician $academician)
    {
        // Check if academician has any grants
        if ($academician->leadingGrants()->exists()) {
            return back()->with('error', 'Cannot delete academician who is leading research grants.');
        }

        // Delete associated user account and academician profile
        $academician->user->delete();
        $academician->delete();

        return redirect()->route('academicians.index')
            ->with('success', 'Academician deleted successfully.');
    }
}
