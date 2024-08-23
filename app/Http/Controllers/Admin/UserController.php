<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Retrieves a paginated list of users for the admin panel.
     *
     * @param Request|null $request The HTTP request object.
     * @return \Illuminate\Contracts\View\View The view for the admin users page.
     */
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
        return view('admin.users', [
            'users' => $users,
            'search' => ($request->input('search') ? $validated["search"] : false),
        ]);
    }

    /**
     * Displays a list of users if the user has finished registration.
     *
     * @param User $user The user object to display the profile for.
     * @return View The view for the user's profile or the list of users.
     */
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
            return view('admin.users', ['users' => $users]);
        }
    }

    /**
     * Deletes a user from the database if the user is not an admin.
     *
     * @param User $user The user object to be deleted.
     * @return RedirectResponse Redirects to the admin users page.
     */
    public function destroy(User $user)/* : RedirectResponse */
    {
        if ($user->userable_type != 'App\Models\Admin') {
            $user->delete();
        }
        return redirect(route('admin.users'));
    }
}
