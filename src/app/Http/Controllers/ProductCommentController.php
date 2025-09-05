<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductCommentRequest;
use Illuminate\Support\Facades\Auth;


class ProductCommentController extends Controller
{
    public function store(Product $product, StoreProductCommentRequest $request)
    {
        $product->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->input('body'),
        ]);

        return back(); 
    }
}
