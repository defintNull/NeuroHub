<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CreateTestController extends Controller
{
    /**
     * Display the create test view.
     */
    public function create(): View
    {
        return view('testmed.createtest');
    }

    /**
     * Display the create test structure view.
     */
    public function createtest(Request $request): View
    {
        $testid = $request->session()->get('testidcreation');
        $test = Test::where('id', $testid)->get()[0];
        if($request->session()->get('status') == 'exit-status') {
            $status = $request->session()->get('status');
            return view('testmed.createteststructure', ['testname' => $test->name, 'status' => $status]);
        } elseif($request->status == 'exit-status') {
            return view('testmed.createteststructure', ['testname' => $test->name, 'status' => $request->status]);
        } else {
            return view('testmed.createteststructure', ['testname' => $test->name]);
        }

    }

    /**
     * Handle an incoming create test request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'testname' => ['required', 'string', 'max:255', 'unique:'.Test::class.",name"],
        ]);

        $test = Test::create([
            'name' => $request->testname,
        ]);

        $request->session()->put('testidcreation', $test->id);
        return Redirect::route('testmed.createteststructure');
    }

    /**
     * Delete the creation test.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //Procedura per cancelazione del test

        $request->session()->forget('testidcreation');

        return Redirect::route('testmed.createteststructure');
    }
}
