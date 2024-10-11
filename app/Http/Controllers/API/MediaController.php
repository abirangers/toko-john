<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $mediaQuery = Media::query();

            $mediaQuery->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            });

            $media = $mediaQuery->get();
            foreach ($media as $m) {
                $m->url = asset('storage/' . $m->path);
            }

            return $this->sendResponse($media, 'Media retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:jpeg,png,jpg,bmp|max:1024',
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $fileName = Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;

            $filePath = $file->storeAs('public/media', $fileName, 'public');

            $media = Media::create([
                'name' => $originalFileName,
                'path' => $filePath,
                'user_id' => $request->user_id
            ]);

            $media->url = asset('storage/' . $media->path);

            return $this->sendResponse($media, 'Media uploaded successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    public function bulkStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array',
                'files.*' => 'required|file|mimes:jpeg,png,jpg,bmp|max:5120',
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            $media = [];
            $files = $request->file('files');

            foreach ($files as $file) {
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public/media', $fileName, 'public');

                $media[] = Media::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'user_id' => $request->user_id
                ]);
            }

            foreach ($media as $m) {
                $m->url = asset('storage/' . $m->path);
            }

            return $this->sendResponse($media, 'Media uploaded successfully.');

        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $media = Media::find($id);

            if (!$media) {
                return $this->sendError('Media not found');
            }

            // delete file based on path
            $filePath = $media->path;
            $filePath = str_replace('storage/', '', $filePath);
            $filePath = storage_path('app/public/' . $filePath);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $media->delete();

            return $this->sendResponse(null, 'Media deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }
}