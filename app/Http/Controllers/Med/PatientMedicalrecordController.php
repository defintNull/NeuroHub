<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;

class PatientMedicalrecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient, ?Request $request)
    {
        if ($request->order == null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->paginate(3);

    if ($request->order != null && $request->date == null)
        $visits = Visit::where('patient_id', $patient->id)->orderBy('date',$request->order)->paginate(3);

    if ($request->order == null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->whereDate('date', $request->date)->paginate(3);

    if ($request->order != null && $request->date != null)
        $visits = Visit::where('patient_id', $patient->id)->whereDate('date', $request->date)->orderBy('date',$request->order)->paginate(3);
        return view('med.medicalrecordshow', ["patient" => $patient, "visits" => $visits]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
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
