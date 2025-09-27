<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order;                          
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Support\Facades\Schema;

class MyPageController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
{
   
    return redirect()->route('mypage.purchases');
}

   public function purchases(Request $request)
    {
         $user = $request->user();

          
        $tab = $request->query('tab');
        $tab = in_array($tab, ['sell','buy'], true) ? $tab : 'sell';

       
    $tab = $request->query('tab');
    $tab = in_array($tab, ['sell','buy'], true) ? $tab : 'sell';

    
    $buyerColumn = \Schema::hasColumn('orders','buyer_id') ? 'buyer_id' : 'user_id';
    $orders = \App\Models\Order::with('product')
        ->where($buyerColumn, $user->id)
        ->latest('id')
        ->paginate(12, ['*'], 'buys_page');

    $productsBought = $orders->getCollection()->pluck('product')->filter()->values();
    $items = new \Illuminate\Pagination\LengthAwarePaginator(
        $productsBought, $orders->total(), $orders->perPage(), $orders->currentPage(),
        ['path'=>$request->url(),'query'=>$request->query()]
    );

    $myProducts = \App\Models\Product::where('user_id', $user->id)
        ->latest('id')
        ->paginate(12, ['*'], 'sells_page');

    return view('mypage.purchases', compact('user','items','myProducts','tab'));
    }
}