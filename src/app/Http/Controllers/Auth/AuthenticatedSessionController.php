<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LinkedAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Redirect the user to the external provider.
     */
    public function initialise(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * The user has authenticated with the external provider. If the user is already authenticated, link the
     */
    public function store(string $provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (InvalidStateException $exception) {
            // An error has occurred.
            return redirect(route('auth.login'));
        }

        // Determine if we are working with OAuth 2.0 or 1.0 (Socialite does not appear to have this functionality).
        $refreshToken = [];
        if (property_exists($providerUser, 'refreshToken')) {
            $refreshToken = [
                'refresh_token' => $providerUser->refreshToken,
                'expires_in' => $providerUser->expiresIn,
            ];
        }

        // Check if the provider user has already been authenticated before.
        $linkedAccount = LinkedAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        // If a user is logged in, we link the GH account
        if (auth()->check()) {
            /** @var ?User $user */
            $user = auth()->user();

            if (null !== $user) {
                if (null !== $linkedAccount) {
                    $linkedAccount->update(
                        array_merge(
                            ['token' => $providerUser->token],
                            $refreshToken,
                        )
                    );
                } else {
                    $user->accounts()->create(
                        array_merge(
                            [
                                'provider_name' => $provider,
                                'provider_id' => $providerUser->getId(),
                                'token' => $providerUser->token,
                            ],
                            $refreshToken,
                        )
                    );
                }

                return redirect(route('dashboard'));
            }
        }

        // If a user is not logged in, but exists, we log him in
        if (null !== $linkedAccount) {
            // Update token details.
            $linkedAccount->update(
                array_merge(
                    ['token' => $providerUser->token],
                    $refreshToken,
                )
            );
            Auth::login($linkedAccount->user, true);

            return redirect(route('dashboard'));
        }

        // If no user is found, we create a new account
        $user = User::create();
        $user->accounts()->create(
            array_merge(
                [
                    'provider_name' => $provider,
                    'provider_id' => $providerUser->getId(),
                    'token' => $providerUser->token,
                ],
                $refreshToken,
            )
        );

        Auth::login($user, true);

        return redirect(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
