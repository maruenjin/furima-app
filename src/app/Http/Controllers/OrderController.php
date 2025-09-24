<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB; 

class OrderController extends Controller
{
     public function create(Product $product)
    {
        $user = auth()->user()->fresh(); 

        if ($product->user_id === $user->id) {
        return redirect()
            ->route('products.show', $product)
            ->withErrors('自分の商品は購入できません。');
        }
        if (!is_null($product->buyer_id)) {
        return redirect()
            ->route('products.show', $product)
            ->withErrors('この商品は売り切れです。');
        }

        
        $addr = [
            
            'postal_code' => $user->postal_code ?? '',
            'address'     => $user->address ?? '',
            'building'    => $user->building ?? '',
        ];

        return view('orders.create', compact('product','addr'));
    }

    

public function store(\App\Http\Requests\StoreOrderRequest $request, \App\Models\Product $product)
{
    $user = auth()->user();

   
   if ($product->user_id === $user->id) {
        return redirect()
            ->route('products.show', ['product' => $product->id])
            ->withErrors('自分の商品は購入できません。');
    
    }
    if (!is_null($product->buyer_id)) {
        return redirect()->route('products.show', $product)->withErrors('この商品は売り切れです。');
    }

    $data = $request->validate([
        'payment_method' => ['required', 'in:card,convenience'],
    ]);


    \DB::transaction(function () use ($request, $product, $user) {
        
        $zip = $user->postal_code ?? '';

        \App\Models\Order::create([
            'user_id'           => $user->id,
            'product_id'        => $product->id,
            'amount'            => $product->price,
            'payment_method'    => $request->validated()['payment_method'],
            'shipping_postcode' => $zip,
            'shipping_address'  => $user->address ?? '',
            'shipping_building' => $user->building ?? '',
        ]);

        
        $product->forceFill(['buyer_id' => $user->id])->save();
    });

    
    return redirect()->route('products.index')
         ->with('flash_purchase', [
            'pm'        => $data['payment_method'],
            'productId' => $product->id,
        ]);
}



}



    
  

