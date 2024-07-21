<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestMed;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredTestMedController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register-testmed');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'min:8', 'max:11', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'birthdate' => ['required', 'date', 'before:now'],
        ]);

        $med = TestMed::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'telephone' => $request->telephone,
            'birthdate' => $request->birthdate,
        ]);

        User::where('username', $request->user()->username)->update(['userable_id' => $med->id]);

        return redirect(route('testmed.dashboard', absolute: false));
    }
}
