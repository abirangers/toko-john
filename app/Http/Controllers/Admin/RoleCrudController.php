<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class RoleCrudController extends Controller
{
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
    public function show(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route("admin.roles.index")->with("error", "Role not found");
        }

        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissionGroups = PermissionGroup::with('permissions')->get();

        return Inertia::render("Admin/Role/Show", compact("role", "rolePermissions", "permissionGroups"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route("admin.roles.index")->with("error", "Role not found");
        }

        return Inertia::render("Admin/Role/Manage", compact("role"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "display_name" => "nullable|string|max:255",
            "name" => "nullable|string|max:255|unique:roles,name," . $id,
        ]);

        $role = Role::find($id);
        if (!$role) {
            return redirect()->route("admin.roles.index")->with("error", "Role not found");
        }

        $role->update($request->all());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        if ($request->has('fromShowPage')) {
            return redirect()->route("admin.roles.show", $role->id)->with("success", "Role updated successfully");
        }

        return redirect()->route("admin.roles.index")->with("success", "Role updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route("admin.roles.index")->with("error", "Role not found");
        }

        $role->delete();
        return redirect()->route("admin.roles.index")->with("success", "Role deleted successfully");
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            "ids" => "required|array",
            "ids.*" => "required|exists:roles,id",
        ]);

        $ids = $request->input('ids');
        $roles = Role::whereIn('id', $ids)->get();
        foreach ($roles as $role) {
            $role->delete();
        }

        return redirect()->route("admin.roles.index")->with("success", "Roles deleted successfully");
    }
}