<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
	{
		$user = auth()->user();
		
        // Use RajaOngkirKomerce class directly
        require_once base_path('rajaongkir_komerce.php');
        $rajaOngkir = new \RajaOngkirKomerce();
        
        $provinces = $rajaOngkir->getProvinces();
        
        // Pre-load cities if user has a valid province_id
        $cities = [];
        if (isset($user->province_id) && $user->province_id) {
            try {
                $cities = $rajaOngkir->getCities($user->province_id);
            } catch (\Exception $e) {
                Log::warning('Failed to load cities for province: ' . $user->province_id);
                $cities = [];
            }
        }
        
        // Always start with empty districts - let JavaScript handle this
        $districts = [];
        
		$cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
		view()->share('countCart', $cart);

		return view('frontend.auth.profile', compact('user','provinces','cities','districts'));
    }

    public function update(Request $request)
	{
		$params = $request->except('_token');
		// dd($params);
		$user = auth()->user();
		$user->update([
            'name' => $params['name'],
			'email' => $params['email'],
			'province_id' => $params['province_id'],
			'city_id' => $params['city_id'],
			'district_id' => $params['district_id'],
			'postcode' => $params['postcode'],
			'address1' => $params['address1'],
			'address2' => $params['address2'],
			'phone' => $params['phone'],
		]);
		if ($user) {
			// dd($user);
			return redirect('profile');
		}
	}
}
