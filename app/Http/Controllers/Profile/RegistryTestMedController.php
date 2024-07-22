<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistryTestMedUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegistryTestMedController extends Controller
{
    /**
     * Display the user's registry form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit-testmed-registry', [
            'testmed' => $request->user()->userable,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(RegistryTestMedUpdateRequest $request): RedirectResponse
    {
        $med = $request->user()->userable;
        $med->fill($request->validated());

        $med->save();

        return Redirect::route('testmed.registry.edit')->with('status', 'registry-updated');
    }

}
