<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Results\TestResult;
use App\Models\Test;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

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
        if ($request->input("datemin") || $request->input("datemax")) {
            try {
                $request->validate([
                    'datemin' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                    'datemax' => 'required|date|after_or_equal:' . $request->input("datemin"),
                ]);
            } catch (\Exception $e) {
                return false;
            }
        }
        if ($request->input('test')) {
            if ($request->input("test") != "all" && $request->input("datemin") != null && $request->input("datemax") != null) {
                try {
                    $request->validate([
                        'test' => 'required|exists:tests,id',
                        'type' => 'required|in:line,doughnut',
                    ]);
                } catch (\Exception $e) {
                    return false;
                }
                if ($request->input("type") == "line") {
                    $date1 = new \DateTime($request->input("datemin"));
                    $date2 = new \DateTime($request->input("datemax"));
                    $data = [];
                    while ($date1 <= $date2) {
                        $date = $date1->format('Y-m-d');
                        $count = TestResult::where('test_id', $request->input("test"))->whereBetween('created_at', [$date . " 00:00:00", $date . " 23:59:59"])->count();
                        $d = ['date' => $date, 'subministration' => $count];
                        $data[] = $d;
                        $date1->modify('+1 day');
                    }
                    return $data;
                }
                if ($request->input("type") == "doughnut") {
                    $data = [];
                    $results = FacadesDB::table('test_results')
                    ->select('result')
                    ->where('test_id', $request->input("test"))
                    ->groupBy('result')->get();

                    foreach ($results as $result) {
                        $count = TestResult::where('test_id', $request->input("test"))->where('result', $result->result)
                        ->whereBetween('created_at', [$request->input("datemin"), $request->input("datemax")." 23:59:59"])->count();
                        $d = ['score' => $result->result, 'scorecount' => $count];
                        $data[] = $d;
                    }
                    return $data;
                }
            } elseif ($request->input("test") == "all" && $request->input("datemin") == null && $request->input("datemax") == null) {
                $tests = Test::all();
                $data = [];
                foreach ($tests as $test) {
                    $d = ['test' => $test->name, 'subministration' => TestResult::where('test_id', $test->id)->count()];
                    $data[] = $d;
                }
            }elseif ($request->input("test") == "all" && $request->input("datemin") && $request->input("datemax")){
                $tests = Test::all();
                $data = [];
                foreach ($tests as $test) {
                    $count = TestResult::where('test_id', $test->id)->whereBetween('created_at', [$request->input("datemin"), $request->input("datemax")." 23:59:59"])->count(); //da controllare
                    $d = ['test' => $test->name, 'subministration' => $count];
                    $data[] = $d;
                }
            }else {
                return false;
            }
        } else {
            return false;
        }
        return $data;
    }
}
