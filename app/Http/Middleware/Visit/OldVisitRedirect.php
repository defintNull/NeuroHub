<?php

namespace App\Http\Middleware\Visit;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class OldVisitRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $visits = $request->user()->userable->visits()->where('status', '0')->get();
        if($visits->count() != 0) {
            $visit = $visits[0];
            //Putting param on session
            $request->session()->put('activevisit', $visit->id);
            $interviews = $visit->Interviews()->where('status', '0')->get();
            if($interviews->count() != 0) {
                $interview = $interviews[0];
                $request->session()->put('activeinterview', $interview->id);
                if($interview->testresult->status == 0) {

                    //Creating Progressive
                    $progressive = [];
                    $rec = function($sectionresult) use (&$rec, &$progressive) {
                        $progressive[] = $sectionresult->progressive;
                        if($sectionresult->sections->count() != 0) {
                            for($i=0; $i<$sectionresult->sections->count(); $i++) {
                                if($sectionresult->sections[$i]->status == 0) {
                                    $rec($sectionresult->sections[$i]);
                                    return true;
                                }
                            }
                        } else {
                            for($i=0; $i<$sectionresult->questionresults->count(); $i++) {
                                if(!$sectionresult->questionresults[$i]->questionable) {
                                    $progressive[] = $sectionresult->questionresults[$i]->progressive;
                                    return true;
                                }
                            }
                            $progressive[] = $sectionresult->questionresults->count()+1;
                            return true;
                        }
                    };

                    for($i=0; $i<$interview->testresult->sectionresults->count(); $i++) {
                        $sectionresult = $interview->testresult->sectionresults[$i];
                        if($sectionresult->status == 0) {
                            $rec($sectionresult);
                            break;
                        }
                    }

                    $request->session()->put('progressive', implode('-', $progressive));

                    return Redirect::route('med.visitadministration.testcompilation')->with('status', 'exit-status');
                } else {
                    return Redirect::route('med.visitadministration.endinterview');
                }
            } else {
                return Redirect::route('med.visitadministration.controlpanel')->with('status', 'exit-status');
            }
        } else {
            return $next($request);
        }
    }
}
