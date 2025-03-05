<?php

namespace App\Jobs;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\PreviewImage;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use App\Models\RelationProductCategory;
use App\Services\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productData;

    public function __construct($productData)
    {
        $this->productData = $productData; // Fixing the assignment
    }

    public function handle()
    {
        try {

            $googleDriveService = app(GoogleDriveService::class);
            $productId = $this->createProduct($googleDriveService);
            $this->handleCategories($productId);
            $this->handleImages($productId, $googleDriveService);

        } catch (\Exception $e) {
            Log::error('Failed to process product', [
                'productData' => $this->productData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \Exception('product_option_poster is missing');
        }
    }

    protected function createProduct($googleDriveService)
    {
        try {
            $posterArray = $googleDriveService->uploadBase64EncodedImage($this->productData['product_poster']);

            $product = Product::create([
                'title' => $this->productData['product_name'],
                'poster' => $posterArray['url'],
                'file_id' => $posterArray['id'],
                'description' => $this->productData['product_description'],
                'brand' => $this->productData['product_brand'],
                'option_type' => intval($this->productData['product_type']),
                'country' => $this->productData['product_make'],
                'guarantee' => $this->productData['product_guarantee'],
            ]);

            if ($this->productData['product_type'] == 1) {
                $product->update([
                    'material' => $this->productData['product_material'],
                    'price' => $this->productData['product_price'] ?? 0,
                    'sale' => $this->productData['product_sale'] ?? 0,
                    'quantity' => $this->productData['product_quantity'] ?? 0,
                    'weight' => $this->productData['product_weight'] ?? 0,
                    'width' => $this->productData['product_width'] ?? 0,
                    'height' => $this->productData['product_height'] ?? 0,
                    'length' => $this->productData['product_length'] ?? 0,
                ]);
            } else if ($this->productData['product_type'] == 0 && isset($this->productData['options'])) {
                array_map(function($option) use ($googleDriveService, $product) {
                    $optionArray = $googleDriveService->uploadBase64EncodedImage($option['product_option_poster']);
                    $variation = ProductVariation::create([
                        'product_id' => $product->id,
                        'poster' => $optionArray['url'],
                        'file_id' => $optionArray['id'],
                        'price' => $option['product_price'] ?? 0,
                        'sale' => $option['product_sale'] ?? 0,
                        'quantity' => $option['product_quantity'] ?? 0,
                        'weight' => $option['product_weight'] ?? 0,
                        'width' => $option['product_width'] ?? 0,
                        'height' => $option['product_height'] ?? 0,
                        'length' => $option['product_length'] ?? 0,
                        'material' => $option['product_material'] ?? '',
                    ]);
    
                    $attributeId = $this->handleAttribute($option['product_option_type'] ?? '');
                    $attributeValueId = $this->createAttributeValue($attributeId, $option['product_option_name']);
                    ProductVariationAttribute::create([
                        'product_variation_id' => $variation->id,
                        'attribute_id' => $attributeId,
                        'attribute_value_id' => $attributeValueId,
                    ]);
                }, $this->productData['options']);
            }

            return $product->id;
        } catch (\Exception $e) {
            Log::error('Failed to create product', [
                'productData' => $this->productData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    protected function handleCategories($productId)
    {
        try {
            if (isset($this->productData['product_categories']) && !empty($this->productData['product_categories'])) {
                RelationProductCategory::create([
                    'product_id' => $productId,
                    'category_id' => $this->productData['product_categories'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to handle categories', [
                'productData' => $this->productData,
                'productId' => $productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
    /**
     * 
     *
     * @param integer $productId
     * @param [type] $googleDriveService
     * @return void
     */
    protected function handleImages(int $productId, $googleDriveService): void
    {
        try {
            if (isset($this->productData['product_images']) && !empty($this->productData['product_images'])) {
                $list = array_map(function ($item) use ($googleDriveService, $productId) {
                    $image = $googleDriveService->uploadBase64EncodedImage($item);
                    return [
                        'product_id' => $productId,
                        'image' => $image['url'],
                        'file_id' => $image['id'],
                        'width' => $image['width'],
                        'height' => $image['height'],
                        'created_at' => now(),
                    ];
                }, $this->productData['product_images']);

                PreviewImage::insert($list);
            }
        } catch (\Exception $e) {
            Log::error('Failed to handle images', [
                'productData' => $this->productData,
                'productId' => $productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * create a Attribute Option
     *
     * @param string $name
     * @return integer
     */
    protected function handleAttribute(string $name): int
    {
        try {
            $attribute = Attribute::firstOrCreate([
                'name' => $name,
            ]);

            return $attribute->id;
        } catch (\Exception $e) {
            Log::error('Failed to handle attribute', [
                'attributeName' => $name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
    

    /**
     * function create a attribute value
     *
     * @param integer $attributeId
     * @param string $value
     * @return integer
     */
    protected function createAttributeValue(int $attributeId, string $value): int
    {
        $attributeValue = AttributeValue::create([
            'attribute_id' => $attributeId,
            'value' => $value,
        ]);

        return $attributeValue->id;
    }

}
