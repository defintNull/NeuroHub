<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Test;
use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(?Request $request)
    {

        if ($request->order == null && $request->date == null)
            $visits = Visit::where('med_id', Auth::user()->userable->id)->paginate(3);

        if ($request->order != null && $request->date == null)
            $visits = Visit::where('med_id', Auth::user()->userable->id)->orderBy('date',$request->order)->paginate(3);

        if ($request->order == null && $request->date != null)
            $visits = Visit::where('med_id', Auth::user()->userable->id)->whereDate('date', $request->date)->paginate(3);

        if ($request->order != null && $request->date != null)
            $visits = Visit::where('med_id', Auth::user()->userable->id)->whereDate('date', $request->date)->orderBy('date',$request->order)->paginate(3);
        return view('med.visitlist', ['visits' => $visits, 'order' => $request->order, 'date' => $request->date]);
    }

    public function create(int $patient_id, ?Request $request)
    {
        $patient = Patient::find($patient_id);
        if($patient != null && $patient->active == 1) {
            if ($request->type==null) {
                return view('med.visittype', ['patient_id' => $patient_id]);
            }
            elseif ($request->type=="test") {
                $visit = Visit::create([
                    'patient_id' => $patient->id,
                    'med_id' => Auth::user()->userable->id,
                    'date' => now(),
                    'type' => 'test',
                ]);
                session(['activevisit' => $visit->id]);
                return redirect(route('med.visitadministration.controlpanel'));
            }
            elseif ($request->type=="simple") {
                return view('med.visitcreate', ['patient_id' => $patient_id, 'type' => $request->type]);
            }
        }
        return back();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|integer|min:0',
            'date' => 'required|date|after_or_equal:' . now()->subDays(5)->format('Y-m-d') . '|before_or_equal:' . now()->format('Y-m-d'),
            'diagnosis' => 'max:1000',
            'treatment' => 'max:1000',
            'type' => 'string|required|in:simple,test',
        ]);

        $patient = Patient::find($request->patient_id);
        if($patient != null) {
            Visit::create([
                'patient_id' => $validated["patient_id"],
                'date' => $validated["date"],
                'status' => 1,
                'diagnosis' => ($validated["diagnosis"]),
                'treatment' => ($validated["treatment"]),
                'med_id' => Auth::user()->userable->id,
                'type' => $validated["type"],
            ]);

            return redirect(route('med.visits.show', ['patient' => $validated["patient_id"]]));
        }
        return back();
    }

    public function show(Patient $patient, ?Request $request)
    {
        if ($request->order == null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->where('status', '1')->orderBy('date', 'desc')->paginate(5);

    if ($request->order != null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->where('status', '1')->orderBy('date',$request->order)->paginate(5);

    if ($request->order == null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->where('status', '1')->whereDate('date', $request->date)->paginate(5);

    if ($request->order != null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->where('status', '1')->whereDate('date', $request->date)->orderBy('date',$request->order)->paginate(5);
        return view('med.visitlist', ['visits' => $visits, 'order' => $request->order, 'date' => $request->date]);
    }

    public function interviews(String $visit_id){

        $visit = Visit::findorfail($visit_id);
        return view('med.showinterviews', ['visit' => $visit]);
    }
}
