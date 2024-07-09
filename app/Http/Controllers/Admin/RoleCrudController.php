<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RoleCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-roles'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-role'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('find-role'), only: ['show']),
            new Middleware(PermissionMiddleware::using('update-role'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-role'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-roles'), only: ['bulkDestroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return Inertia::render("Admin/Role/Index", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Admin/Role/Manage");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "display_name" => "required|string|max:255",
            "name" => "required|string|max:255|unique:roles",
        ]);

        Role::create([
            "name" => $request->name,
            "display_name" => $request->display_name,
        ]);

        return redirect()->route("admin.roles.index")->with("success", "Role created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissionGroups = PermissionGroup::with('permissions')->get();

        return Inertia::render("Admin/Role/Show", compact("role", "rolePermissions", "permissionGroups"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $role = Role::findOrFail($id);
        return Inertia::render("Admin/Role/Manage", compact("role"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            "display_name" => "nullable|string|max:255",
            "name" => "nullable|string|max:255|unique:roles,name," . $id,
            "permissions" => "nullable|array",
            "permissions.*" => "exists:permissions,name",
        ]);

        $data = [];
        if ($request->has('display_name')) {
            $data['display_name'] = $request->display_name;
        }
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        $role = Role::findOrFail($id);
        $role->update($data);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        if ($request->has('fromShowPage')) {
            return redirect()->route("admin.roles.show", $role->id)->with("success", "Role updated successfully");
        }

        return redirect()->route("admin.roles.index")->with("success", "Role updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route("admin.roles.index")->with("success", "Role deleted successfully");
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        Role::whereIn('id', $ids)->delete();

        return redirect()->route("admin.roles.index")->with("success", "Bulk roles deleted successfully.");
    }
}
