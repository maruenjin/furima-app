<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $fillable = [
        'user_id','name','brand','price','description','condition','image_path','buyer_id','categories'
    ];

    protected $casts = [
       'categories' => 'array',
    ];
    public function getIsSoldAttribute(): bool
    {
        return ! is_null($this->buyer_id);
    }

    public function seller() { return $this->belongsTo(User::class, 'user_id'); }
    public function buyer()  { return $this->belongsTo(User::class, 'buyer_id'); }

    public function likes(){ return $this->hasMany(\App\Models\ProductLike::class); }

    public function isLikedBy(?\App\Models\User $user): bool
    {
    return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }

    public function comments(){ return $this->hasMany(\App\Models\ProductComment::class); }

}
