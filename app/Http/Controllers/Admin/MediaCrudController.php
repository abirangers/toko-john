<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class MediaCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-media'), only: ['index']),
            new Middleware(PermissionMiddleware::using('delete-media'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-media'), only: ['bulkDestroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = Media::with('user')->get();
        return Inertia::render('Admin/Media/Index', ['medias' => $medias]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $media = Media::findOrFail($id);
        if ($media->path) {
            Storage::delete("public/images/products/" . $media->path);
        }
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        $medias = Media::whereIn('id', $ids)->get();

        foreach ($medias as $media) {
            if ($media->path) {
                Storage::delete("public/images/products/" . $media->path);
            }
            
            $media->delete();
        }

        return redirect()->route('admin.media.index')->with('success', 'Bulk media deleted successfully');
    }
}