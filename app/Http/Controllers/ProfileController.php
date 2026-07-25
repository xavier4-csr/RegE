<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'phone_no'   => ['nullable', 'string', 'max:20'],
        ]);
        Auth::user()->update($data);
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);
        $user = Auth::user();
        if ($user->profile_picture_url && !str_starts_with($user->profile_picture_url, 'http')) {
            Storage::disk('public')->delete($user->profile_picture_url);
        }
        $path = $request->file('photo')->store('avatars', 'public');
        $user->update(['profile_picture_url' => $path]);
        return back()->with('success', 'Profile photo updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);
        Auth::user()->update(['password' => Hash::make($data['password'])]);
        return back()->with('success', 'Password changed successfully.');
    }
}