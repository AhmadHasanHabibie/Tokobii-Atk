<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            $user = $request->user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isOwner()) {
                return redirect()->route('owner.dashboard');
            }
            return redirect()->route('customer.dashboard')
                ->with('success', 'Email Anda sudah terverifikasi. Mengarahkan ke Dashboard...');
        }

        if ($request->has('refresh')) {
            return back()->with('error', 'Email Anda belum terverifikasi. Silakan klik link verifikasi dari email Anda.');
        }

        return view('auth.verify-email');
    }
}
