<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if($request->user()->userable_type == 'App\Models\Med') {
                return redirect()->intended(route('med.dashboard', absolute: false).'?verified=1');
            } elseif($request->user()->userable_type == 'App\Models\TestMed') {
                return redirect()->intended(route('testmed.dashboard', absolute: false).'?verified=1');
            } elseif($request->user()->userable_type == 'App\Models\Admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
            } else {
                return back();
            }

        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if($request->user()->userable_type == 'App\Models\Med') {
            return redirect()->intended(route('med.dashboard', absolute: false).'?verified=1');
        } elseif($request->user()->userable_type == 'App\Models\TestMed') {
            return redirect()->intended(route('testmed.dashboard', absolute: false).'?verified=1');
        } elseif($request->user()->userable_type == 'App\Models\Admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        } else {
            return back();
        }
    }
}
