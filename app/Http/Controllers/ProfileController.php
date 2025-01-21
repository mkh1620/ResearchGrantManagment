<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update user record
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        // Update academician record if it exists
        if ($user->academician) {
            $academicianData = array_merge(
                [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ],
                $request->validate([
                    'college' => 'required|string|max:255',
                    'department' => 'required|string|max:255',
                    'position' => 'required|string|max:255',
                ])
            );
            
            $user->academician->update($academicianData);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
