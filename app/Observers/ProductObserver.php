<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\GoogleDriveService;

class ProductObserver
{
    protected $googleDriveService;


    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // Collect all file IDs including the product's file ID and preview images' file IDs
        $fileIds = collect([$product->file_id]);

        // Add preview images' file IDs to the collection
        $fileIds = $fileIds->merge(
            $product->previewImages()->pluck('file_id')->filter()
        );

        if($product->option_type == 0){
            $fileIds = $fileIds->merge(
                $product->variations()->plunk('file_id')->filler()
            );
            $product->variations()->delete();
        }


        // Delete all associated preview images in one query
        $product->previewImages()->delete();
        $product->relation()->attributeValue()->delete();
        $product->relation()->delete();
        $product->carts()->delete();
        $product->favourites()->delete();
        $product->delete();

        $fileIds->each(function($fileId) {
            $this->googleDriveService->deleteFile($fileId);
        });
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
