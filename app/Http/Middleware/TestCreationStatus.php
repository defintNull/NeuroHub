<?php

namespace App\Http\Middleware;

use App\Models\Test;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class TestCreationStatus
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
                return Redirect::route('testmed.createteststructure.testscore')->with('status', 'exit-status');
            } else {
                return Redirect::route('testmed.createteststructure')->with('status', 'exit-status');
            }
        } else {
            return $next($request);
        }
    }
}
