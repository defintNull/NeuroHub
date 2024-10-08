<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('username', $request->user()->username)->get()[0];
        if($user->userable_type == 'App\Models\Med' && $user->userable_id == null) {
            return redirect(route('med.register', absolute:false));
        } elseif($user->userable_type == 'App\Models\TestMed' && $user->userable_id == null) {
            return redirect(route('testmed.register', absolute:false));
        }

        return $next($request);
    }
}
