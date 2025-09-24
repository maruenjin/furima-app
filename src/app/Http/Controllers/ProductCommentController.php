<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductComment;
use App\Http\Requests\StoreProductCommentRequest; 
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function store(StoreProductCommentRequest $request, Product $product) 
    {
        $data = $request->validated(); 

       
        $product->comments()->create([
            'user_id' => auth()->id(),   
            'body'    => $data['body'],
        ]);

        return redirect()->route('products.show', $product);
    }
}

