<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = Media::with('user')->get();
        return Inertia::render('Admin/Media/Index', ['medias' => $medias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Media/Manage');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $media = Media::find($id);
        if (!$media) {
            return redirect()->route('admin.media.index')->with('error', 'Media not found');
        }
        $media->delete();
        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        $media = Media::whereIn('id', $ids)->get();
        foreach ($media as $media) {
            $media->delete();
        }
        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully');
    }
}