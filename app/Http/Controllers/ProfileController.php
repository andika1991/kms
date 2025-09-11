<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
public function edit(Request $request): View
{
    $user = $request->user();

    return view('profile.edit', compact('user'));
}
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

public function updatePhoto(Request $request)
{
    $request->validate([
        'photo_profil' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    $user = $request->user();

    if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
        Storage::disk('public')->delete($user->photo_profil);
    }

    $path = $request->file('photo_profil')->store('profile_photos', 'public');

    $user->photo_profil = $path;
    $user->save();

    // Refresh Auth agar Auth::user() langsung update
    Auth::setUser($user->fresh());

    return back()->with('status', 'profile-photo-updated');
}


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
