<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            // Check if Team Member and redirect accordingly
            if ($user->hasRole(env('ROLE_TEAM_MEMBER'))) {
                return redirect()->intended(route('bid-recommendations.index'));
            }
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
        return view('auth.verify-email');
    }
}
