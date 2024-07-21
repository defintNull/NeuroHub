<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'username' => $request->user()->username,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

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
}
