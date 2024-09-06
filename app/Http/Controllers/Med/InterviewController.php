<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\Cast\String_;

class InterviewController extends Controller
{
    /**
     * Display a list of .
     */
    public function index(Visit $visit)
    {
        $interviews = $visit->interviews;
        return view('med.interviewlist', ['interviews' => $interviews]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeInterview(Request $request): RedirectResponse
    {
        $request->validate([
            'interview' => ['required', 'integer'],
        ]);
        $interview = Interview::where('id', $request->interview)->get();
        if($interview->count() != 0) {
            $interview = $interview[0];
            return redirect(route('med.visits.interviewdetail.interview.show', ['visit'=> $interview->visit->id, 'interview' => $interview->id]));
        }
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function showInterview(int $visit, int $interview): View
    {
        $interview = Interview::findorfail($interview);
        return view('med.interviewdetail.interviewdetail', ['interview' => $interview]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
