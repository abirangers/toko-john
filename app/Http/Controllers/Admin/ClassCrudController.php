<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Major;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassModel::with('major')->get();
        return Inertia::render('Admin/Class/Index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $majors = Major::all();
        return Inertia::render('Admin/Class/Manage', compact('majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'required|exists:majors,id',
        ]);

        $class = ClassModel::create($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $majors = Major::all();
        $classData = ClassModel::with('major')->find($id);
        
        if (!$classData) {
            return redirect()->route('admin.classes.index')->with('error', 'Class not found');
        }
        
        return Inertia::render('Admin/Class/Show', compact('classData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $majors = Major::all();
        $classData = ClassModel::with('major')->find($id);
        if (!$classData) {
            return redirect()->route('admin.classes.index')->with('error', 'Class not found');
        }
        return Inertia::render('Admin/Class/Manage', compact('classData', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'required|exists:majors,id',
        ]);
        $class = ClassModel::find($id);
        if (!$class) {
            return redirect()->route('admin.classes.index')->with('error', 'Class not found');
        }
        $class->update($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = ClassModel::find($id);
        if (!$class) {
            return redirect()->route('admin.classes.index')->with('error', 'Class not found');
        }
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:classes,id',
        ]);
        $ids = $request->input('ids');
        $classes = ClassModel::whereIn('id', $ids)->get();
        foreach ($classes as $class) {
            $class->delete();
        }
        return redirect()->route('admin.classes.index')->with('success', 'Classes deleted successfully');
    }
}