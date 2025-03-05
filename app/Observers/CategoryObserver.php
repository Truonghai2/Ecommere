<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Product;
use App\Services\GoogleDriveService;

class CategoryObserver
{
    protected $googleDriveService;


    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Category $Category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $Category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $Category): void
    {
        $this->googleDriveService->deleteFile($Category->file_id);
        $Category->relation()->delete();
        $Category->delete();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $Category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $Category): void
    {
        //
    }
}
