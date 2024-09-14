<?php

namespace App\Http\Middleware;

use App\Models\Test;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestScoreRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->get('testidcreation') !== null) {
            $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
            if($test->operationOnScore) {
                return $next($request);
            }
        }
        return redirect(route('testmed.dashboard', absolute:false));
    }
}
