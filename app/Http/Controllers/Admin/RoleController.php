<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): Factory|View|Application
    {
        $roles = Role::query()->select('id', 'name', 'created_at')->get();
        $permissions = Permission::all();

        return view('admin.role.index', compact(['roles', 'permissions']));
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

    public function store(StoreRequest $request): RedirectResponse
    {
        $role = Role::query()->create([
            'name' => $request->name,
            'guard_name' => 'admin',
        ]);

        $arr = [];
        foreach ($request->permissions as $permission) {
            $arr[] = [
                'permission_id' => $permission,
                'role_id' => $role->id,
            ];
        }

        DB::table('role_has_permissions')
            ->insert($arr);

        return back()->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit(int $id): Factory|View|Application
    {
        $role = Role::query()->findOrFail($id);
        $permissions = Permission::query()->with(['roles'])->get();

        return view('admin.role.edit', compact(['role', 'permissions']));
    }

    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $role = Role::query()->findOrFail($id);

        $role->update($request->validated());

        return back()->with('success', 'Role updated successfully');
    }

    public function destroy(int $id): RedirectResponse
    {
        $role = Role::query()->findOrFail($id);
        $role->delete();

        return back()->with('success', 'Role deleted successfully');
    }
}
