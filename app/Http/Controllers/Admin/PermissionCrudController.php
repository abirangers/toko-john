<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class PermissionCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return Inertia::render('Admin/Permission/Index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionGroups = PermissionGroup::all();
        return Inertia::render('Admin/Permission/Manage', [
            'permissionGroups' => $permissionGroups
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
        ]);
        $permission = Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_name' => $request->group_name,
        ]);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::find($id);
        if(!$permission) {
            return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
        }
        return Inertia::render('Admin/Permission/Show', [
            'permission' => $permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::find($id);
        if(!$permission) {
            return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
        }
        
        $permissionGroups = PermissionGroup::all();
        
        return Inertia::render('Admin/Permission/Manage', [
            'permission' => $permission,
            'permissionGroups' => $permissionGroups
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
        ]);
        
        $permission = Permission::find($id);
        if(!$permission) {
            return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
        }
        
        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        if(!$permission) {
            return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
        }
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:permissions,id',
        ]);

        $ids = $request->input('ids');
        $permissions = Permission::whereIn('id', $ids)->get();
        foreach ($permissions as $permission) {
            $permission->delete();
        }

        return redirect()->route('admin.permissions.index')->with('success', 'Permissions deleted successfully.');
    }
}