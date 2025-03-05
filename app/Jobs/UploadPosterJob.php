<?php

namespace App\Jobs;

use App\Services\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadPosterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $variation;
    protected $poster;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($variation, $poster)
    {
        $this->variation = $variation;
        $this->poster = $poster;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GoogleDriveService $googleDriveService)
    {
        // Xóa file cũ trên Google Drive
        $googleDriveService->deleteFile($this->variation->file_id);

        // Tải lên hình ảnh mới và lấy thông tin URL và file ID
        $data = $googleDriveService->uploadBase64EncodedImage($this->poster);
        $this->variation->poster = $data['url'];
        $this->variation->file_id = $data['id'];
        
        // Lưu các thay đổi
        $this->variation->save();
    }
}
