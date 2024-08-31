<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class VisitAdministrationController extends Controller {

    /**
     * Show the page for visualizing the visit administration.
     */
    public function create(): View
    {
        return view('med.testadministration.visitadministration');
    }

}
