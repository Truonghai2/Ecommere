<?php

namespace App\Services;

use App\Client\config;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    protected $client;
    protected $drive;
    protected $config;

    public function __construct(config $config)
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        $this->drive = new Drive($this->client);

        $this->config = $config;
    }

    public function uploadBase64EncodedImage($base64Image)
    {
        // Decode the base64 string
        $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
        $image = base64_decode($base64Data);
        // Get the image size
        $imageInfo = getimagesizefromstring($image);
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Upload to Google Drive
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $this->createFileName(),
            'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')]
        ]);

        $file = $this->drive->files->create($fileMetadata, [
            'data' => $image,
            'mimeType' => $mimeType,
            'uploadType' => 'media',
            'fields' => 'id'
        ]);

        // Make the file publicly accessible
        $permission = new \Google\Service\Drive\Permission([
            'type' => 'anyone',
            'role' => 'reader'
        ]);

        $this->drive->permissions->create($file->id, $permission);

        // Return the file URL and size
        return [
            'url' => 'https://lh3.googleusercontent.com/d/' . $file->id,
            'id' => $file->id,
            'width' => $width,
            'height' => $height
        ];
    }

    /**
     * upload image on google drive not base 64
     *
     * @param UploadedFile $file
     * @return void
     */
    public function uploadFile(UploadedFile $file)
    {
        try {
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $filePath = $file->getPathname();

            // Upload to Google Drive
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => $this->createFileName(),
                'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')]
            ]);


            $content = file_get_contents($filePath);

            $createdFile = $this->drive->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id',
            ]);

            return [
                'id' => $createdFile->id,
                'url' => 'https://lh3.googleusercontent.com/d/' . $createdFile->id,
            ];

        } catch (\Exception $e) {
            // Handle exceptions here
            throw new \Exception('Failed to upload attachment to Google Drive: ' . $e->getMessage());
        }
    }

    private function CreateFileName(){
        $fileName = 'product_image_' . time() . '.png';

        return $fileName;
    }

    /**
     * delete image in google drive by file_id
     * @param string $fileId
     * @return bool
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->drive->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
