<?php

namespace App\Http\Controllers\Auth;

use App\Models\SocialProfile;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function сallback(Request $request, $provider)
    {
        // retrieve user profile using OAuth
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error(sprintf('%s login error: %s', $provider, $e->getMessage()));
            return redirect()->route('login');
        }

        // check if user with given email exists locally. If not - create it
        $userEmail = $providerUser->getEmail() ?: $providerUser->getId() . '_' . $providerUser->getNickname();

        if (strpos($userEmail, '@') === FALSE)
            $userEmail .= '@' . $provider . '.com';

        $user = User::firstOrCreate(
            ['email' => $userEmail],
            [
                'name'              => $providerUser->getName() ?: $providerUser->getNickname(),
                'role'              => User::ROLE_USER,
                'status'            => User::STATUS_ACTIVE,
                'last_login_from'   => $request->ip(),
                'last_login_at'     => Carbon::now(),
                'password'          => bcrypt($providerUser->token),
                'email_verified_at' => Carbon::now(),
            ]
        );

        // throw Registered event if user just signed up
        if ($user->wasRecentlyCreated) {
            event(new Registered($user));
        }

        // check if social profile exists and create a new one if not. User can have multiple social profiles linked (Facebook, Google etc)
        $socialProfile = SocialProfile::firstOrCreate(
            ['provider_name' => $provider, 'provider_user_id' => $providerUser->getId()],
            ['user_id' => $user->id]
        );

        // authenticate user
        auth()->login($user, true);
        return redirect()->route('frontend.leaderboard');
    }
}
