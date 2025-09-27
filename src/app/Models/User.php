<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code','address','building',
        'avatar_path','profile_completed',
        'profile_image_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_completed' => 'boolean',
        
    ];

 public function getAvatarUrlAttribute(): string
{
   $p = $this->avatar_path;

    if (empty($p)) {
       
        return asset('images/avatar-default.png');
        
    }

    
    if (Str::startsWith($p, ['http://', 'https://', '//'])) {
        return $p;
    }

    
    if (Str::startsWith($p, ['/storage/', 'storage/'])) {
        return asset(ltrim($p, '/')); 
    }

    
    if (Str::startsWith($p, ['/images/', 'images/'])) {
        return asset(ltrim($p, '/')); 
    }

   
    return Storage::disk('public')->url($p);
}
}