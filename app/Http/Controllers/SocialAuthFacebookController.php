<?php

namespace App\Http\Controllers;

use App\Services\SocialFacebookAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthFacebookController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirect()
    {
//        return Socialite::driver('facebook')->redirect();
        return Socialite::with('facebook')->redirect();

    }


    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback(SocialFacebookAccountService $service)
    {
        $user = $service->createOrGetMember(Socialite::driver('facebook')->member());
        auth()->login($user);
        return redirect()->to('/home');
    }
}
