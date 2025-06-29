<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->paginate(10);

        return view('admin.users.index', compact(['users']));
    }
    public function edit($id): View
    {
        $user = User::query()->findOrFail($id);

        return view('admin.users.editRole', compact('user'));
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $role = $request->role;
        $user = User::query()->findOrFail($id);

        $user->update([
            'role' => $role
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Role updated successfully!');
    }
    public function block($id): RedirectResponse|null
    {
        $user = User::query()->findOrFail($id);
        $delete = $user->delete();

        if ($delete) {
            return redirect()->route('admin.users.index')->with('success', 'User blocked!');
        }
    }
    public function guideInfo($id): View
    {
        $guide = User::query()->findOrFail($id);
        $languages = json_decode($guide->guides->languages);
        $places = json_decode($guide->guides->aviable_for);

        if ($guide->role == 'guide') {
            return view('admin.users.guide', compact('guide', 'places', 'languages'));
        }
    }
}
