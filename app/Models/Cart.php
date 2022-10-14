<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Cart extends Collection
{
    /**
     * Collection untuk penjualan yang di cache
     */

    public function __construct($items = null)
    {
        if($items == null && !is_array($items)){
            # secara default mengambil semua cache penjualan
            if(Cache::has('cart-item')){
                $this->items = Cache::get('cart-item');
            }else{
                $this->items = Cache::rememberForever('cart-item', function() { return []; });
            }
        }else{
            $this->items = $items;
        }
    }

    /**
     * mencari penjualan ke dalam cart berdasarkan nomor penjualan
     * @return App\Modules\Pos\Models\Penjualan
     */
    public static function find($cart_id)
    {
        $newInstance = new static();
        return $newInstance->where('cart_id', $cart_id)->first();
    }

    /**
     * bila penjualan tidak ditemukan maka buat baru
     * @return this->find() || this->createPenjualan()
     */
    public static function findOrCreate($cart_id)
    {
        $newInstance = new static();
        if ($newInstance->find($cart_id)) {
            return $newInstance->find($cart_id);
        }

        return $newInstance->createItem($cart_id);
        
    }
    
    /**
     * buat penjualan baru ke dalam cart
     * @return this->find()
     */
    public function createItem($cart_id)
    {
        $this->items[] = new CartAction($cart_id);
        $this->syncCart();
        
        $newInstance = new Static;
        return $newInstance->find($cart_id);
    }

    /**
     * update cart
     * @param Penjualan $cart_action data penjualan yang di ingin di cart
     * @return void
     */
    public static function updateItem(CartAction $cart_action)
    {
        $newInstance = new static;
        $itemIndex = $newInstance->search(function ($item, $key) use($cart_action){
            return $item->cart_id == $cart_action->cart_id;
        });
        $newInstance->put($itemIndex, $cart_action);
        $newInstance->syncCart();
    }

    public static function destroyItem($cart_id)
    {
        $newInstance = new static;
        $itemIndex = $newInstance->search(function ($item, $key) use($cart_id){
            return $item->cart_id == $cart_id;
        });
        $newInstance->pull($itemIndex);
        $newInstance->syncCart();

        return true;
    }

    /**
     * mensinkronkan data cart
     * @return void
     */
    private function syncCart()
    {
        $cart_penjualan = $this->items;
        Cache::pull('cart-item');
        Cache::rememberForever('cart-item', function() use($cart_penjualan){
            return $cart_penjualan;
        });
    }

    public static function generateCartId()
    {
        $newInstance = new static;
        return ($newInstance->max('cart_id')+1) ?? 1;
    }
}
