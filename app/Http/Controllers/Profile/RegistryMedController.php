<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistryMedUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegistryMedController extends Controller
{
    /**
     * Display the user's registry form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit-med-registry', [
            'med' => $request->user()->userable,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(RegistryMedUpdateRequest $request): RedirectResponse
    {
        $med = $request->user()->userable;
        $med->fill($request->validated());

        $med->save();

        return Redirect::route('med.registry.edit')->with('status', 'registry-updated');
    }

}
