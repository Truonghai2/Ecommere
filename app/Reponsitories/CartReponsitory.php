<?php 
namespace App\Reponsitories;

use App\Models\Cart;

class CartReponsitory
{

    /**
     * create a cart
     *
     * @param [type] $product_id
     * @param [type] $quantity
     * @param [type] $option_id
     * @return void
     */
    public function addCart($product_id, $quantity ,$option_id):void 
    {
        $cart = new Cart();
        $cart->user_id = auth()->id();
        $cart->product_id = $product_id;
        $cart->option_id = $option_id ?? null;
        $cart->quantity = $quantity;
        $cart->save();
    }

    /**
     * update a cart
     *
     * @param [type] $product_id
     * @param [type] $option_id
     * @param [type] $quantity
     * @return boolean
     */
    public function updateCart($product_id, $option_id, $quantity):bool
    {
        $cart = Cart::where('product_id', $product_id)->where('option_id', $option_id ?? null)->first();

        if($cart) {
            $cart->quantity = $cart->quantity + $quantity;
            $cart->save();
            return true;
        }
        return false;
    }

    /**
     * search a cart
     *
     * @param [type] $product_id
     * @param [type] $option_id
     * @return boolean
     */
    public function selectCart($product_id, $option_id)
    {
        $cart = Cart::where('product_id', $product_id)->where('option_id', $option_id ?? null)->first();
        if($cart){
            return $cart;
        }

        return null;
    }
}