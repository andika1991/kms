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
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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
            'photo_profil' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();
        $hadPhoto = filled($user->photo_profil); // ada foto sebelumnya?

        // Hapus yang lama jika ada
        if ($hadPhoto && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        // Simpan yang baru
        $path = $request->file('photo_profil')->store('profile_photos', 'public');
        $user->photo_profil = $path;
        $user->save();

        // URL publik + info untuk cache busting
        $url = Storage::disk('public')->url($path);
        $status = $hadPhoto ? 'updated' : 'added';

        // Balas JSON untuk request AJAX/fetch
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status'     => $status,            // 'added' | 'updated'
                'url'        => $url,
                'updated_at' => optional($user->updated_at)->timestamp,
            ]);
        }

        // Fallback bila form disubmit biasa
        return back()->with(
            'status',
            $status === 'added' ? 'profile-photo-added' : 'profile-photo-updated'
        );
    }
    /**
     * Delete the user's account.
     */
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
