<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // プロフィール編集画面の表示
    public function edit(Request $request)
    {
        $user = $request->user(); 
        return view('profile.profile', compact('user'));
    }

    // プロフィール更新処理
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        // 画像アップロード
        $avatarPath = $user->avatar_path;
        if ($request->hasFile('avatar')) {
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); 
        }

       // バリデーション済みデータで更新
        $data = $request->validated();
        $data['avatar_path']       = $avatarPath;
        $data['profile_completed'] = true;

        $user->update($data);

        return redirect()->route('items.index')->with('status', 'プロフィールを更新しました。');
    }
}

