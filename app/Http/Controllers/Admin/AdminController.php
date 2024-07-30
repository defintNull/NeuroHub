<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(?Request $request)
    {
        if ($request->input('search')) {
            $validated = $request->validate([
                'search' => 'max:255',
            ]);
            $users = User::where('userable_type', '<>', 'App\Models\Admin')
            ->where('username','like', '%'.$validated["search"].'%')
            ->paginate(3);
        } else {
            $users = User::where('userable_type', '<>', 'App\Models\Admin')
                ->where('username', '<>', '')
                ->paginate(3);
        }
        return view('admin.dashboard', ['users' => $users]);
    }

    public function show(string $id): View
    {
        $user = User::findOrFail($id);
        return view('admin.profile', [
            'user' => $user,
            'info' => $user->userable,
        ]);
    }

    public function del(string $id): RedirectResponse
    {
        $user = User::where('userable_type', '<>', 'App\Models\Admin')
        ->where('id', $id)->get();
        if ($user->count()!=0){
        $affected = DB::table('users')
            ->where('id', $id)
            ->delete();
        }
        return redirect(route('admin.dashboard'));
    }
}
