<?php 

namespace App\Services;

use App\Client\config;
use App\Models\Favourite;

class SelectItemService {


    protected $config;

    public function __construct(config $config)
    {
        $this->config = $config;
    }
    /**
     * get favourite use pagination
     *
     * @param integer $page
     * @return object
     */
    public function getFavourite(int $page): \Illuminate\Http\JsonResponse
    {
        $favourite = Favourite::where('user_id', auth()->id())->orderByDesc('created_at')->paginate(15, ['*'], 'page', $page);

        if ($favourite->count() > 0) {
            $favourite->load('product');

            foreach ($favourite as $item) {
                if ($item->product->option_type == 0) {
                    $item->product->load(['variations' => function ($query) {
                        $query->select('id', 'product_id', 'price', 'sale', 'quantity');
                    }]);

                    $minVariation = $item->product->variations->sortBy('price')->first();
                    $item->product->setRelation('variations', collect([$minVariation]));
                }
            }

            return response()->json([
                'success' => true, 
                'data' => $favourite->items(),
                'last_page' => $favourite->lastPage()
            ]);
        }

        return response()->json(['success' => true, 'data' => null]);
    }

}