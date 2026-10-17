<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try 
        {
            $google = Socialite::driver('google')->user();
        } 
        catch (\Throwable $e) {

            return redirect()->route('login')
                ->withErrors(['email' => 'Google sign-in failed. Please try again.']);
        }

        $user = User::where('google_id', $google->getId())
            ->orWhere('email', $google->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $google->getId(),
                'avatar'    => $google->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name'      => $google->getName(),
                'email'     => $google->getEmail(),
                'google_id' => $google->getId(),
                'avatar'    => $google->getAvatar(),
                'password'  => Str::random(32),
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}