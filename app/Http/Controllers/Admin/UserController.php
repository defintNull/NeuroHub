<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(?Request $request)
    {
        if ($request->input('search')) {
            $validated = $request->validate([
                'search' => 'max:255',
            ]);
            $users = User::where('userable_type', '<>', 'App\Models\Admin')
                ->where('username', 'like', '%' . $validated["search"] . '%')
                ->paginate(3);
        } else {
            $users = User::where('userable_type', '<>', 'App\Models\Admin')
                ->where('username', '<>', '')
                ->paginate(3);
        }
        return view('admin.users', ['users' => $users]);
    }

    public function show(User $user): View
    {
        if ($user->userable_id) {
            return view('admin.profile', [
                'user' => $user,
                'info' => $user->userable,
            ]);
        } else {
            $users = User::where('userable_type', '<>', 'App\Models\Admin')
                ->where('username', '<>', '')
                ->paginate(3);
            return view('admin.users',['users' => $users]);
        }
    }

    public function destroy(User $user)/* : RedirectResponse */
    {
        if ($user->userable_type != 'App\Models\Admin') {
            $user->delete();
        }
        return redirect(route('admin.users'));
    }
}
