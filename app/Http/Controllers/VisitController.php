<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function create(string $patient_id)
    {
        return view('med.visitcreate', ['patient_id' => $patient_id]);
    }
}
