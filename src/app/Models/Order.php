<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   use HasFactory;

   protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'payment_method',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
    ];

    protected $casts = [
        'amount' => 'int',
    ];

    public function buyer()  
     { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function product()
     {
         return $this->belongsTo(Product::class);
         } 



}
