<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Providers\RouteServiceProvider;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function sendFailedLoginResponse(Request $request)
    // {
    //     return redirect()->back()
    //         ->withInput($request->only($this->username(), 'remember'))
    //         ->withErrors([
    //             $this->username() => [trans('auth.failed')],
    //         ]);
    // }

    public function showLoginForm()
	{
		if (view()->exists('auth.authenticate')) {
			return view('auth.authenticate');
		}
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
		view()->share('countCart', $cart);

		return view('frontend.auth.login');
	}
}
