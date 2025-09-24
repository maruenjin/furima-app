<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    
    public function edit(Request $request)
    {
        $user = $request->user();

        $addr = [
            'postal_code' => $user->postal_code ?? '',
            'address'     => $user->address ?? '',
            'building'    => $user->building ?? '',
        ];

        return view('profile.edit', compact('user', 'addr'));
    }

    
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        
        if (!empty($data['postal_code'])) {
            $zip = preg_replace('/\D/', '', $data['postal_code']); 
            if (preg_match('/^\d{7}$/', $zip)) {
                $data['postal_code'] = substr($zip, 0, 3) . '-' . substr($zip, 3);
            }
        }

        
        if ($request->hasFile('avatar')) {
            if (!empty($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['profile_completed'] = true;
        $user->update($data);

        return back()->with('status', 'プロフィールを更新しました。');
    }

  
    public function editAddress(Request $request, Product $product)
    {
        $u = $request->user();
        $addr = [
            'postal_code' => $u->postal_code ?? '',
            'address'     => $u->address ?? '',
            'building'    => $u->building ?? '',
        ];
        return view('profile.address', compact('addr','product'));
    }

   
    public function updateAddress(UpdateAddressRequest $request, Product $product)
    {
        
        $request->user()->update($request->normalized());

        return redirect()
            ->route('orders.create', $product)
            ->with('status', '住所を更新しました。');
    }
}




