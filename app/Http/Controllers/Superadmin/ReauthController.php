<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ReauthController extends Controller
{
    /**
     * Show the password re-authentication form.
     */
    public function show(Request $request): View
    {
        return view('superadmin.auth.reauth');
    }

    /**
     * Validate the Superadmin's password for re-authentication.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            SecurityLog::record(
                eventType: 'superadmin_reauth_failed',
                severity: 'high',
                endpoint: $request->path(),
                method: 'POST',
                payload: ['message' => 'Failed re-authentication attempt'],
                responseStatus: 401
            );

            return redirect()->back()->withErrors([
                'password' => 'Kata sandi tidak sesuai dengan otorisasi Superadmin Anda.',
            ]);
        }

        // Set reauth timestamp (valid for 15 minutes)
        $request->session()->put('superadmin_reauth_at', time());

        SecurityLog::record(
            eventType: 'superadmin_reauth_success',
            severity: 'low',
            endpoint: $request->path(),
            method: 'POST',
            payload: ['status' => 're-authenticated'],
            responseStatus: 200
        );

        $targetUrl = $request->session()->pull('superadmin_reauth_target', route('superadmin.maintenance.index'));

        return redirect()->to($targetUrl)->with('success', 'Otorisasi Zero Trust berhasil diverifikasi. Anda memiliki akses operasi tingkat tinggi.');
    }
}
