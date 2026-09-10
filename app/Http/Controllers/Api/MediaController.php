<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload a file into a structured directory (avatars, campaigns, questions, general).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,svg,pdf,csv,doc,docx|max:10240',
            'folder' => 'nullable|string|in:avatars,campaigns,questions,documents,general',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $folder = $request->input('folder', 'general');

        // Structured directory hierarchy: uploads/{folder}/YYYY/MM
        $subDirectory = 'uploads/' . $folder . '/' . date('Y/m');

        $originalExtension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $fileName = $folder . '_' . time() . '_' . Str::random(8) . '.' . $originalExtension;

        // Store file in storage/app/public/uploads/{folder}/YYYY/MM
        $storedPath = $file->storeAs($subDirectory, $fileName, 'public');

        if (!$storedPath) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save uploaded file',
            ], 500);
        }

        $fullPath = storage_path('app/public/' . $storedPath);
        if (file_exists($fullPath)) {
            @chmod($fullPath, 0644);
            @chmod(dirname($fullPath), 0755);
            @chmod(dirname(dirname($fullPath)), 0755);
            @chmod(dirname(dirname(dirname($fullPath))), 0755);
        }

        // Return secure direct API media URL to prevent LiteSpeed/Apache 403 storage block
        $publicUrl = url('api/media/file/' . $storedPath);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'data' => [
                'url' => $publicUrl,
                'relative_path' => $storedPath,
                'file_name' => $fileName,
                'folder' => $folder,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
            ]
        ], 201);
    }

    /**
     * Publicly serve media file with cross-origin headers.
     */
    public function showFile($path)
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/' . $path);
        }

        if (!file_exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Media file not found: ' . $path,
            ], 404);
        }

        $mime = mime_content_type($fullPath) ?: 'image/jpeg';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    /**
     * List uploaded files in a specific category.
     */
    public function index(Request $request)
    {
        $folder = $request->query('folder', 'all');
        $baseDirectory = 'uploads';

        if ($folder !== 'all') {
            $baseDirectory .= '/' . $folder;
        }

        $allFiles = Storage::disk('public')->allFiles($baseDirectory);
        $mediaList = [];

        foreach ($allFiles as $filePath) {
            $mediaList[] = [
                'url' => asset('storage/' . $filePath),
                'relative_path' => $filePath,
                'file_name' => basename($filePath),
                'size_bytes' => Storage::disk('public')->size($filePath),
                'last_modified' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($filePath)),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $mediaList,
        ]);
    }
}
