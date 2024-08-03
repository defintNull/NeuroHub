<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
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
     * Display the add section button view.
     */
    public function createaddsectionbutton(): View
    {
        return view('testmed.creationcomponents.add-section-button');
    }

    /**
     * Display the add section button view.
     */
    public function createsection(): View
    {
        return view('testmed.creationcomponents.add-section');
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
     * Handle an incoming create section request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storesection(Request $request): JsonResponse
    {

        $request->validate([
            'sectionname' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer', 'max:255'],
        ]);

        //Create section object
        if($request->type == 'test') {
            $section = Section::create([
                'name' => $request->sectionname,
                'test_id' => $request->id,
            ]);
        } elseif($request->type == 'section') {
            $section = Section::create([
                'name' => $request->sectionname,
                'test_id' => $request->session()->get('testidcreation'),
                'section_id' => $request->id,
            ]);
        }


        return response()->json([
            'status' => 200
        ]);
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
