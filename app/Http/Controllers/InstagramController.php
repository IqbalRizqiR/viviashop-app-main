<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;

class InstagramController extends Controller
{
    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = config('instagram.access_token');
    }


    public function webhook(Request $request)
    {
        // 1. Verification
        if ($request->isMethod('get')) {
            $mode      = $request->query('hub_mode');
            $token     = $request->query('hub_verify_token');
            $challenge = (int)$request->query('hub_challenge');

            // dd(config('instagram.verify_token'));

            // dd(Log::info('Instagram Webhook GET', [
            //     'mode'      => $mode,
            //     'token'     => $token,
            //     'challenge' => $challenge,
            // ]));

            if ($mode === 'subscribe' && $token === config('instagram.verify_token')) {
                return response($challenge, 200);
            }

            return response('Forbidden', 403);
        }

        // 2. Handle incoming POST updates
        $data = $request->all();
        // Log::info('Instagram Webhook POST', $data);

        // TODO: react to the update (e.g. new comment, mention, story, etc.)
        return response('OK', 200);
    }

    /**
     * Step 1: Redirect the user to Instagram's OAuth screen.
     */
    public function redirectToInstagram()
    {
        return Socialite::driver('instagram-basic')
                        ->scopes(['user_profile','user_media'])
                        ->redirect();
    }

    /**
     * Step 2: Handle Instagram's redirect back to your app.
     */
    public function handleCallback(Request $request)
    {
        // Socialite will exchange the code for a short‑lived token
        $instagramUser = Socialite::driver('instagram-basic')->user();

        // Exchange for long‑lived token if you want (60 days)
        $response = Http::asForm()->post('https://graph.instagram.com/access_token', [
            'grant_type'    => 'ig_exchange_token',
            'client_secret' => config('instagram.client_secret'),
            'access_token'  => $instagramUser->token,
        ]);

        $longLived = $response->json()['access_token'];

        // Store $longLived in your DB or config for later API calls
        auth()->user()->updateInstagramToken($longLived);

        return redirect()->route('admin.instagram.index')
                         ->with('success','Instagram connected!');
    }

    public function create()
    {
        return view('instagram.create');
    }

    public function postToInstagram(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'required|string|max:2200',
        ]);

        $image  = $request->file('image');
        $result = CloudinaryController::upload($image->getRealPath(), $image->getClientOriginalName());
        $url = $result;
        $urlIG= 'https://graph.instagram.com/v22.0/' . config('instagram.client_id') . '/media?access_token=' . $this->accessToken;

        // dd($this->accessToken);


        $igUserId    = env('INSTAGRAM_CLIENT_ID');
        $createUrl   = "https://graph.instagram.com/v22.0/{$igUserId}/media";

        $createResp = Http::withToken($this->accessToken)
                        ->asForm()
                        ->post($createUrl, [
                            'image_url'    => $url,
                            'caption'      => $request->caption,
                            'access_token' => $this->accessToken,
                        ]);

        if (! $createResp->successful()) {
            dd($createResp->json());  // you’ll now see the real error
        }

        $creationId = $createResp->json()['id'];

        // 3) Publish the container
        $publishUrl   = "https://graph.instagram.com/v22.0/{$igUserId}/media_publish";
        $publishResp  = Http::withToken($this->accessToken)
                        ->asForm()
                        ->post($publishUrl, [
                            'creation_id' => $creationId,
                            'access_token' => $this->accessToken,
                        ]);

        if (! $publishResp->successful()) {
            dd($publishResp->json());
        }
        Alert::success('Success', 'Post published successfully!');
        return redirect()->route('admin.instagram.index');
    }

    public function getInstagramData()
    {
        $response = Http::withToken($this->accessToken)->get('https://graph.instagram.com/me/media', [
            'fields' => 'id,caption,media_type,media_url,thumbnail_url,timestamp',
            'access_token' => $this->accessToken,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $posts = $result['data'];
            // dd($posts);
            return view('instagram.index', compact('posts'));
        }

        return redirect()->back()->with('error', 'Failed to retrieve data.');
    }
}
