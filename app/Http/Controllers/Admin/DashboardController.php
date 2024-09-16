<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Display the admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index(?Request $request)
    {
        $test = Test::all();
        $data = $this->getData($request);
        return view("admin.dashboard", [
            'tests' => $test,
            'sel' => $request->test,
            'data' => $data,
            'datemax' => $request->datemax,
            'datemin' => $request->datemin,
        ]);
    }

    public function getData(Request $request)
    {


        //demo logic
        if ($request->input("datemin")) {
            try {
                $request->validate([
                    'datemin' => 'date|before_or_equal:' . now()->format('Y-m-d'),
                ]);
            } catch (\Exception $e) {
                return false;
            }
        }
        if ($request->input("datemax")) {
            try {
                $request->validate([
                    'datemax' => 'date|after:datemin|after_or_equal:' . $request->input("datemin"),
                ]);
            } catch (\Exception $e) {
                return false;
            }
        }
        if ($request->input('test')) {
            if ($request->input("test") != "all") {
                try {
                    $request->validate([
                        'test' => 'required|exists:tests,id',
                    ]);
                } catch (\Exception $e) {
                    return false;
                }

                $data = [
                    ['data' => '11/01/2022', 'subministration' => 9],
                    ['data' => '12/01/2022', 'subministration' => 13],
                    ['data' => '13/01/2022', 'subministration' => 7],
                    ['data' => '14/01/2022', 'subministration' => 4],
                    ['data' => '15/01/2022', 'subministration' => 3],
                    ['data' => '16/01/2022', 'subministration' => 6],
                    ['data' => '17/01/2022', 'subministration' => 9],
                ];
            }
            if ($request->input("test") == "all") {
                $data = [
                    [
                        'test' => "Vinerland",
                        "subministration" => 9
                    ],
                    [
                        'test' => "Vinerland",
                        "subministration" => 13
                    ],
                    [
                        'test' => "Vinerland",
                        "subministration" => 50
                    ],
                    [
                        'test' => "Vinerland",
                        "subministration" => 10
                    ],
                ];
            }
        } else {
            $data = [
                [
                    'test' => "Vinerland",
                    "subministration" => 9
                ],
                [
                    'test' => "Adi-R",
                    "subministration" => 13
                ],
                [
                    'test' => "Trog",
                    "subministration" => 50
                ],
                [
                    'test' => "Ados",
                    "subministration" => 10
                ],
            ];
        }
        return $data;
    }
}
