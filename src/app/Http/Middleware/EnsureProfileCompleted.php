<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
          $user = $request->user();

        // プロフィール未完了の条件を決める
        // 例: 郵便番号や住所が空なら未設定扱い
        $incomplete = empty($user->postal_code) || empty($user->address);

        // プロフィール未設定かつ、いま profile.edit 以外にアクセスした場合
        if ($incomplete && !$request->routeIs('profile.edit')) {
            return redirect()->route('profile.edit');
        }
        return $next($request);
    }
}
