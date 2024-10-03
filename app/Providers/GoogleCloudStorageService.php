<?php

namespace App\Providers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;

class GoogleCloudStorageService
{
    protected $storage;

    public function __construct()
    {
        $this->storage = new StorageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
        ]);
    }

    public function uploadImage($bucketName, $filePath, $fileName)
    {
        // Get the bucket
        $bucket = $this->storage->bucket($bucketName);

        // Upload the file
        $file = fopen($filePath, 'r');
        $object = $bucket->upload($file, [
            'name' => $fileName,
        ]);

        // Return the public URL
        return $object->info()['mediaLink'];
    }

    public function deleteImage($bucketName, $fileName)
    {
        // Get the bucket
        $bucket = $this->storage->bucket($bucketName);

        // Delete the object
        $bucket->object($fileName)->delete();
    }
}
