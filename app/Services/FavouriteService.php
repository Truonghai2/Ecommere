<?php 
namespace App\Services;

use App\Models\Favourite;

class FavouriteService{

    /**
     * check favourite user
     *
     * @param int $id
     * @return bool
     */
    public function checkFavourite($id){
        $exists = Favourite::where('product_id', $id)->where('user_id', auth()->id())->exists();

        if($exists){
            return true;
        }
        return false;
    }


    public function getAllUserFavouriteProduct(int $product_id):array 
    {
        $favourites = Favourite::where('product_id', $product_id)->with('user')->get();
        if($favourites){
            $userIds = [];
            foreach ($favourites as $favourite) {
                $userIds[] = $favourite->user->id;
            }

            return $userIds;
        }
        
    }
}