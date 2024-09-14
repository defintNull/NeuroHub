<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * Display the admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index(?Request $request)
    {
        if ($request->input("datemin")){
            $request->validate([
                'datemin' => 'date|before_or_equal:' . now()->format('Y-m-d'),
            ]);
        }
        if ($request->input("datemax")){
            $request->validate([
                'datemax' => 'date|after:datemin|after_or_equal:' .$request->input("datemin"),
            ]);
        }
        if ($request->input('test')) {
            if ($request->input("test") != "all") {
                $request->validate([
                    'test' => 'required|exists:tests,id',
                ]);

                $data = [
                    '11/01/2022' => 9,
                    '12/01/2022' => 10,
                    '13/01/2022' => 7,
                    '14/01/2022' => 4,
                    '15/01/2022' => 3,
                    '16/01/2022' => 6,
                    '17/01/2022' => 9,
                ];
            }
            if ($request->input("test") == "all") {
                $data = [
                    'Vinerland' => 9,
                    'Adi-R'=> 13,
                    'Trog'=> 50,
                    'Ados-2'=> 10,
                ];
            }
        } else{
            $data = [
                'Vinerland' => 9,
                'Adi-R'=> 13,
                'Trog'=> 50,
                'Ados-2'=> 10,
            ];
        }
        $test = Test::all();
        return view("admin.dashboard", [
            'tests' => $test,
            'sel' => $request->test,
            'data' => $data,
            'datemax' => $request->datemax,
            'datemin' => $request->datemin,
        ]);
    }
}
