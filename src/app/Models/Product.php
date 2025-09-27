<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','brand','price','description',
        'condition','image_path','categories','buyer_id',
    ];

    
    protected $casts = [
        'categories' => 'array',
    ];

    
    public function getIsSoldAttribute(): bool
    {
        return !is_null($this->buyer_id);
    }

     public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(): BelongsToMany
{
   
   return $this->belongsToMany(\App\Models\User::class, 'product_likes', 'product_id', 'user_id')
                ->withTimestamps();

   
}

 public function comments(): HasMany
    {
        return $this->hasMany(ProductComment::class);
    }

 public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->whereKey($user->id)->exists();
    }


public function getImageUrlAttribute(): string
{
    $p = $this->image_path;

    if (!$p) return asset('images/noimage.svg');


    if (Str::startsWith($p, ['http://','https://','//'])) {
        return $p;
    }

    
    if (Str::startsWith($p, ['images/', '/images/', 'public/images/'])) {
        
        $p = ltrim(preg_replace('#^public/#', '', $p), '/');
        return asset($p); 
    }

    
    return Storage::disk('public')->url($p); 
}


public function scopeExceptMine($query, ?int $userId)
{
    if ($userId) {
        $query->where('user_id', '!=', $userId);
    }
    return $query;
}

}


