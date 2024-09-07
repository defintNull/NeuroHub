<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $validated = $request->validate([
                'search' => 'max:255',
            ]);
            $patients = Patient::where(DB::raw("concat(name, ' ', surname)"), 'LIKE', "%" . $validated["search"] . "%")
                ->where('active', true)
                ->paginate(3);
        } else {
            $patients = Patient::where('active', true)
            ->paginate(3);
        }
        return view('med.patitentslist', [
            'patients' => $patients,
            'search' => ($request->input('search') ? $validated["search"] : false),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("med.createpatient");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'telephone' => ['required', 'string', 'min:8', 'max:11', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'birthdate' => 'required|date|before:now',
        ]);

        $patient = Patient::create([
            'name' => $validated["name"],
            'surname' => $validated["surname"],
            'telephone' => $validated["telephone"],
            'birthdate' => $validated["birthdate"],
            'active' => true,
        ]);

        return (redirect(route('med.patients.show',['patient' => $patient->id])));
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
         return view('med.patitentslist', [
            'patients' => Patient::where('id', $id)->paginate(3),
            'search' => false,
        ])->with('redirect', '1');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('med.patientsedit', ['patient' => $patient]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'telephone' => ['required', 'string', 'min:8', 'max:11', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'birthdate' => 'required|date|before:now',
        ]);

        $patient->update($validated);

        return (redirect(route('med.patients.index')));
    }


    public function confirmDelete(Patient $patient){
        return view('med.patientconfirm', ['patient' => $patient]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->update(['active' => false]);
        return redirect(route('med.patients.index'));
    }
}
