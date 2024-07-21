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
        if($request->user()->userable_type == 'App\Models\Med') {
            return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('med.dashboard', absolute: false))
                    : view('auth.verify-email');
        } elseif($request->user()->userable_type == 'App\Models\TestMed') {
            return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('testmed.dashboard', absolute: false))
                    : view('auth.verify-email');
        } elseif($request->user()->userable_type == 'App\Models\Admin') {
            return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('admin.dashboard', absolute: false))
                    : view('auth.verify-email');
        } else {
            return back();
        }
    }
}
