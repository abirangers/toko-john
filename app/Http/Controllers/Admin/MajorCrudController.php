<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Major\MajorRequest;
use App\Models\Major;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MajorCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::all();
        return Inertia::render('Admin/Major/Index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Major/Manage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MajorRequest $request)
    {
        Major::create($request->validated());
        return redirect()->route('admin.majors.index')->with('success', 'Major created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $major = Major::find($id);
        if (!$major) {
            return redirect()->route('admin.major.index')->with('error', 'Major not found');
        }
        return Inertia::render('Admin/Major/Show', compact('major'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $major = Major::find($id);
        if (!$major) {
            return redirect()->route('admin.major.index')->with('error', 'Major not found');
        }
        return Inertia::render('Admin/Major/Manage', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MajorRequest $request, string $id)
    {
        $major = Major::find($id);
        if (!$major) {
            return redirect()->route('admin.major.index')->with('error', 'Major not found');
        }
        $major->slug = null;
        $major->update($request->validated());
        
        return redirect()->route('admin.majors.index')->with('success', 'Major updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $major = Major::find($id);
        if (!$major) {
            return redirect()->route('admin.major.index')->with('error', 'Major not found');
        }
        $major->delete();
        return redirect()->route('admin.majors.index')->with('success', 'Major deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        $majors = Major::whereIn('id', $ids)->get();
        foreach ($majors as $major) {
            $major->delete();
        }
        return redirect()->route('admin.majors.index')->with('success', 'Majors deleted successfully');
    }
}