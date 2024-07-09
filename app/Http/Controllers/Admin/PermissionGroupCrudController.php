<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use App\Models\PermissionGroup;
use Spatie\Permission\Middleware\PermissionMiddleware;

class PermissionGroupCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-permission-groups'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-permission-group'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('find-permission-group'), only: ['show']),
            new Middleware(PermissionMiddleware::using('update-permission-group'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-permission-group'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-permission-groups'), only: ['bulkDestroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissionGroups = PermissionGroup::all();
        return Inertia::render('Admin/PermissionGroup/Index', [
            'permissionGroups' => $permissionGroups
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/PermissionGroup/Manage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        PermissionGroup::create([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.permission-groups.index')->with('success', 'Permission group created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        return Inertia::render('Admin/PermissionGroup/Show', compact('permissionGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        return Inertia::render('Admin/PermissionGroup/Manage', compact('permissionGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $permissionGroup = PermissionGroup::findOrFail($id);
        $permissionGroup->update([
            'name' => $request->name,
        ]);
        
        return redirect()->route('admin.permission-groups.index')->with('success', 'Permission group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        PermissionGroup::findOrFail($id)->delete();
        return redirect()->route('admin.permission-groups.index')->with('success', 'Permission group deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        PermissionGroup::whereIn('id', $ids)->delete();

        return redirect()->route('admin.permission-groups.index')->with('success', 'Bulk delete permission groups successfully');
    }
}