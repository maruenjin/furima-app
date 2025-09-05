<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        
        if (!$user) {
            return $next($request);
        }

        
        $incomplete = !$user->profile_completed;

        
        $allowed = [
            'profile.edit',
            'profile.update',
            'logout',
            'verification.notice',
            'verification.send',
            'verification.verify',
        ];

        if (
            $incomplete &&
            !$request->routeIs($allowed) &&  // ルート名で許可
            !$request->is('email/*')         // パスでも保険
        ) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}

