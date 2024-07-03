<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PermissionGroup;

class PermissionGroupCrudController extends Controller
{
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
    public function show(string $id)
    {
        $permissionGroup = PermissionGroup::find($id);
        if (!$permissionGroup) {
            return redirect()->route('admin.permission-groups.index')->with('error', 'Permission group not found');
        }
        return Inertia::render('Admin/PermissionGroup/Show', [
            'permissionGroup' => $permissionGroup
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permissionGroup = PermissionGroup::find($id);
        if (!$permissionGroup) {
            return redirect()->route('admin.permission-groups.index')->with('error', 'Permission group not found');
        }
        return Inertia::render('Admin/PermissionGroup/Manage', [
            'permissionGroup' => $permissionGroup
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $permissionGroup = PermissionGroup::find($id);
        if (!$permissionGroup) {
            return redirect()->route('admin.permission-groups.index')->with('error', 'Permission group not found');
        }
        $permissionGroup->update([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.permission-groups.index')->with('success', 'Permission group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permissionGroup = PermissionGroup::find($id);
        if (!$permissionGroup) {
            return redirect()->route('admin.permission-groups.index')->with('error', 'Permission group not found');
        }
        $permissionGroup->delete();
        return redirect()->route('admin.permission-groups.index')->with('success', 'Permission group deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:permission_groups,id',
        ]);

        $ids = $request->input('ids');

        $permissionGroups = PermissionGroup::whereIn('id', $ids)->get();
        foreach ($permissionGroups as $permissionGroup) {
            $permissionGroup->delete();
        }

        return redirect()->route('admin.permission-groups.index')->with('success', 'Bulk delete permission groups successfully');
    }
}