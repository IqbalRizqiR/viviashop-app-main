<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProfileController extends Controller
{
    public function show()
    {
        $cart = Cart::content()->count();
        $setting = Setting::first();
		view()->share('countCart', $cart);
		view()->share('setting', $setting);
        return view('auth.profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        if ($request->password) {
            auth()->user()->update(['password' => Hash::make($request->password)]);
        }

        // dd($request);
        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'postcode' => $request->postcode,
        ]);

        return redirect()->route('admin.profile.show')->with([
            'message' => 'berhasil di ubah !'
        ]);
    }
}
