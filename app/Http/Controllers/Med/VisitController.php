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
                Visit::create([
                    'patient_id' => $patient->id,
                    'med_id' => Auth::user()->userable->id,
                    'date' => now(),
                    'type' => 'test',
                ]);
                return redirect(route('med.visitadministration'));
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
            'patient_id' => 'required|integer',
            'date' => 'required|date',
            'diagnosis' => 'max:1000',
            'treatment' => 'max:1000',
            'type' => 'string|required|in:simple,test',
        ]);

        $patient = Patient::find($request->patient_id);
        if($patient != null) {
            Visit::create([
                'patient_id' => $validated["patient_id"],
                'date' => $validated["date"],
                'diagnosis' => ($validated["diagnosis"]),
                'treatment' => ($validated["treatment"]),
                'med_id' => Auth::user()->userable->id,
                'type' => $validated["type"],
            ]);

            return (response("<h1>Caricamento effettuato</h2>"));
        }
        return back();
    }

    public function edit(Visit $visit)
    {
        return view('med.visitedit', ['visit' => $visit, 'patient_id' => $visit->patient_id]);
    }

    public function update(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'patient_id' => 'required|integer',
            'date' => 'required|date',
            'diagnosis' => 'max:1000',
            'treatment' => 'max:1000',
        ]);

        $visit->update($validated);

        return (redirect(route('med.visits.index')));
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return (redirect(route('med.visits.index')));
    }

    public function show(Patient $patient, ?Request $request)
    {
        if ($request->order == null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->paginate(3);

    if ($request->order != null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->orderBy('date',$request->order)->paginate(3);

    if ($request->order == null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->whereDate('date', $request->date)->paginate(3);

    if ($request->order != null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->whereDate('date', $request->date)->orderBy('date',$request->order)->paginate(3);
        return view('med.visitlist', ['visits' => $visits, 'order' => $request->order, 'date' => $request->date]);
    }
}
