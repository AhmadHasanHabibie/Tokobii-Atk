<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display Owner Profile.
     */
    public function index(Request $request): View
    {
        return view('owner.profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display Edit Profile Form.
     */
    public function edit(Request $request): View
    {
        return view('owner.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update Profile Information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('owner.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete Owner Account.
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