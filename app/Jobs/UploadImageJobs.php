<?php

namespace App\Jobs;

use App\Models\PreviewImage;
use App\Services\GoogleDriveService; // Adjust this path if needed
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class UploadImageJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $productId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, $productId)
    {
        $this->filePath = $filePath;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GoogleDriveService $googleDriveService)
    {
        // Retrieve the file from storage
        $file = Storage::get($this->filePath);
        $fileName = basename($this->filePath);
        $tempPath = sys_get_temp_dir() . '/' . $fileName;

        // Save the file temporarily to process it
        file_put_contents($tempPath, $file);

        // Create an UploadedFile instance from the temporary file
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempPath,
            $fileName,
            null,
            null,
            true
        );

        // Upload the file to Google Drive
        $data = $googleDriveService->uploadFile($uploadedFile);

        // Store the image information in the database
        PreviewImage::create([
            'product_id' => $this->productId,
            'image' => $data['url'],
            'file_id' => $data['id'],
            'width' => 0,
            'height' => 0,
            'created_at' => now(),
        ]);

        // Clean up the temporary file
        unlink($tempPath);
        Storage::delete($this->filePath);
    }
}
