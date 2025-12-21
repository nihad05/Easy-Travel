<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Messages;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Messages;

    public function index(): Application|Factory|View
    {
        $users = User::query()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show($id): Application|Factory|View|RedirectResponse
    {
        $guide = User::query()->findOrFail($id);

        if ($guide->role == 'guide' && $guide) {
            return view('admin.users.guide', compact('guide'));
        }

        return back()->with('error', self::$WRONG);
    }

    public function edit($id): Application|Factory|View|RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        if (! $user) {
            return back()->with('error', self::$USER_NOT_FOUND);
        }

        return view('admin.users.editRole', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        if (! $user) {
            return back()->with('error', self::$USER_NOT_FOUND);
        }

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', self::$ROLE_UPDATED);
    }

    /**
     * @return RedirectResponse|void
     */
    public function destroy($id)
    {
        $user = User::query()->findOrFail($id);

        if (! $user) {
            return back()->with('error', self::$USER_NOT_FOUND);
        }

        $delete = $user->delete();

        if ($delete) {
            return redirect()->route('admin.users.index')->with('success', self::$USER_BLOCKED);
        }

        return back()->with('error', self::$WRONG);
    }
}
