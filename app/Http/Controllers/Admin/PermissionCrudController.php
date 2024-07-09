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

class PermissionCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-permissions'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-permission'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('find-permission'), only: ['show']),
            new Middleware(PermissionMiddleware::using('update-permission'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-permission'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-permissions'), only: ['bulkDestroy']),
        ];
    }
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
        
        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group_name' => $request->group_name,
        ]);
        
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $permission = Permission::findOrFail($id);
        return Inertia::render('Admin/Permission/Show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $permission = Permission::findOrFail($id);
        $permissionGroups = PermissionGroup::all();
        
        return Inertia::render('Admin/Permission/Manage', compact('permission', 'permissionGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
        ]);
        
        $permission = Permission::findOrFail($id);
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
    public function destroy(int $id)
    {
        Permission::findOrFail($id)->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        Permission::whereIn('id', $ids)->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permissions deleted successfully.');
    }
}