<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            // Check if Team Member and redirect accordingly
            if ($user->hasRole(env('ROLE_TEAM_MEMBER'))) {
                return redirect()->intended(route('bid-recommendations.index').'?verified=1');
            }
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Check if Team Member and redirect accordingly after verification
        if ($user->hasRole(env('ROLE_TEAM_MEMBER'))) {
            return redirect()->intended(route('bid-recommendations.index').'?verified=1');
        }
        
        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
