<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\TestMed;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()) {
            $usertype = get_class($request->user()->userable);
            if($usertype == Admin::class) {
                return redirect(route('admin.dashboard', absolute:false));
            } elseif($usertype == TestMed::class) {
                return redirect(route('testmed.dashboard', absolute:false));
            } else {
                return redirect(route('med.dashboard', absolute:false));
            }
        } else {
            return $next($request);
        }
        if($request->session()->get('activevisit') !== null) {
            return $next($request);
        } else {
            return redirect(route('med.dashboard', absolute:false));
        }
    }
}
