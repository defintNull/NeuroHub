<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('username', $request->user()->username)->get()[0];
        if($user->userable_type == 'App\Models\Admin') {
            return $next($request);
        }

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
