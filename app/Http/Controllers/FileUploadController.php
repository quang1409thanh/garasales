<?php

namespace App\Http\Controllers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadFileToCloud(Request $request)
    {

        try {
            $file = $request->file('file');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $storeFile = $file->storeAs("test", $file_name, "gcs");
            $disk = Storage::disk('gcs');
            // Tạo Signed URL
            $storage = new StorageClient();
            $bucket = $storage->bucket('my_datab');
            $object = $bucket->object($file_name);
            $disk = Storage::disk('gcs');
            $url = $disk->temporaryUrl($url_image, now()->addMinutes(30));

            $signedUrl = $object->signedUrl(new \DateTime('tomorrow'), [
                'version' => 'v4',
                'method' => 'GET',
            ]);

            // Trả về URL để hiển thị
            return response()->json([
                'url' => $url,
            ]);

        } catch(\UnableToWriteFile|UnableToSetVisibility $e) {
            throw_if($this->throwsExceptions(), $e);
            return false;
        }

        return response()->json([
            'data' => $fetchFile,
        ], 201);
    }
}
