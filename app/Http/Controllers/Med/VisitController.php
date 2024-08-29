<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    public function index(?Request $request)
    {

        if ($request->order == null && $request->date == null)
            $visits = Visit::where('med_id', auth()->user()->userable->id)->paginate(3);

        if ($request->order != null && $request->date == null)
            $visits = Visit::where('med_id', auth()->user()->userable->id)->orderBy('date',$request->order)->paginate(3);

        if ($request->order == null && $request->date != null)
            $visits = Visit::where('med_id', auth()->user()->userable->id)->whereDate('date', $request->date)->paginate(3);

        if ($request->order != null && $request->date != null)
            $visits = Visit::where('med_id', auth()->user()->userable->id)->whereDate('date', $request->date)->orderBy('date',$request->order)->paginate(3);
        return view('med.visitlist', ['visits' => $visits, 'order' => $request->order, 'date' => $request->date]);
    }

    public function create(string $patient_id)
    {
        return view('med.visitcreate', ['patient_id' => $patient_id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|integer',
            'date' => 'required|date',
            'diagnosis' => 'max:1000',
            'treatment' => 'max:1000',
        ]);

        Visit::create([
            'patient_id' => $validated["patient_id"],
            'date' => $validated["date"],
            'diagnosis' => ($validated["diagnosis"] == null ? '' : $validated["diagnosis"]),
            'treatment' => ($validated["treatment"] == null ? '' : $validated["treatment"]),
            'med_id' => auth()->user()->userable->id,
        ]);

        return (redirect(route('med.visits.index')));
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
