<?php

namespace App\Http\Controllers;

use App\Providers\GoogleCloudStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    protected $gcs;

    public function __construct(GoogleCloudStorageService $gcs)
    {
        $this->gcs = $gcs;
    }

    public function upload(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the uploaded file
        $file = $request->file('image');
        $filePath = $file->getPathname();
        $fileName = time() . '-' . $file->getClientOriginalName();

        // Upload the file to GCS
        $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
        $imageUrl = $this->gcs->uploadImage($bucketName, $filePath, $fileName);

        return response()->json(['url' => $imageUrl], 200);
    }
}
