<?php

namespace App\Http\Middleware\Visit;

use App\Models\Interview;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EndInterviewBlockRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $interview = Interview::where('id', $request->session()->get('activeinterview'))->get()[0];
        if($interview->testresult->status == 0) {
            return $next($request);
        } else {
            return Redirect::route('med.visitadministration.endinterview');
        }
    }
}
