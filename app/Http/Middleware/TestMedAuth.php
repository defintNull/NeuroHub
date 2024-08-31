<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMedAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('username', $request->user()->username)->get();
        if($user->count() != 0) {
            $user = $user[0];
        } else {
            return back();
        }

        if($user->userable_type == 'App\Models\TestMed') {
            return $next($request);
        }

        if($request->user()->userable_type == 'App\Models\Med') {
            return redirect()->intended(route('med.dashboard', absolute: false));
        } elseif($request->user()->userable_type == 'App\Models\Admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } else {
            return back();
        }
    }
}
