<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
     // プロフィール編集画面の表示
    public function edit()
    {
       $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // プロフィール更新処理
    public function update(ProfileUpdateRequest  $request)
    {
        $user = auth()->user();

         // 画像アップロード
        $avatarPath = $user->avatar_path;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public'); 
            $avatarPath = $path;
        }

        $user->update([
            'name'       => $request->name,
            'zip_code'   => $request->zip_code,
            'address'    => $request->address,
            'building'   => $request->building,
            'avatar_path'=> $avatarPath,
        ]);

        return redirect()->route('profile.edit')->with('status', 'プロフィールを更新しました。');
    }
 }

