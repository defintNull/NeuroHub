<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    /**
     * Display the admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index(){
        return view("admin.dashboard");
    }
}
