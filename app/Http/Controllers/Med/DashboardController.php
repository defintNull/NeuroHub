<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;


class DashboardController extends Controller
{
    public function index()
    {
        return view('med.dashboard');
    }

    public function getData(Request $request){
        if ($request->input('date1') || $request->input('date2'))
        {
            try {
                $request->validate([
                    'date1' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                    'date2' => 'required|date|after_or_equal:' . $request->input("date1"),
                ]);
            } catch (\Exception $e) {
                return false;
            }
            $data = [];
            $date1 = new \DateTime($request->input("date1"));
            $date2 = new \DateTime($request->input("date2"));
            $data = [];
            while ($date1 <= $date2) {
                $date = $date1->format('Y-m-d');
                $count = Visit::where('med_id', auth()->user()->userable->id)->whereBetween('date', [$date . " 00:00:00", $date . " 23:59:59"])->count();
                $d = ['date' => $date, 'visitcount' => $count];
                $data[] = $d;
                $date1->modify('+1 day');
            }
            return $data;
        }else{
            $data = [];
            $date1 = new \DateTime(auth()->user()->created_at->format('Y-m-d'));
            $date2 = new \DateTime(now()->format('Y-m-d'));
            $data = [];
            while ($date1 <= $date2) {
                $date = $date1->format('Y-m-d');
                $count = Visit::where('med_id', auth()->user()->userable->id)->whereBetween('date', [$date . " 00:00:00", $date . " 23:59:59"])->count();
                $d = ['date' => $date, 'visitcount' => $count];
                $data[] = $d;
                $date1->modify('+1 day');
            }
            return $data;
        }
    }
}
