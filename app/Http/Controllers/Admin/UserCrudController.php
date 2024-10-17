<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Log;

class UserCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-users'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-user'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('update-user'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-user'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-users'), only: ['bulkDestroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->whereHas('roles', function (Builder $query) {
            $query->where('name', '!=', 'admin');
        })->get();
        return Inertia::render('Admin/User/Index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Admin/User/Manage', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->assignRole($validatedData['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return Inertia::render('Admin/User/Manage', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return Inertia::render('Admin/User/Manage', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $validatedData = $request->validated();

        $user = User::with('roles')->findOrFail($id);
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        $user->syncRoles($validatedData['role']);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        $users = User::whereIn('id', $ids)->get();
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself');
            }

            $user->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'Bulk users deleted successfully');
    }
}