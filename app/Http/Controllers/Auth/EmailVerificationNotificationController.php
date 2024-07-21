<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if($request->user()->userable_type == 'App\Models\Med') {
                return redirect()->intended(route('med.dashboard', absolute: false));
            } elseif($request->user()->userable_type == 'App\Models\TestMed') {
                return redirect()->intended(route('testmed.dashboard', absolute: false));
            } elseif($request->user()->userable_type == 'App\Models\Admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } else {
                return back();
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
