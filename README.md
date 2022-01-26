## About Mass Social Authenticator
Mass Social Authenticator (MSA) is a proof-of-concept Laravel web application for an idea by Matt Zagursky ([@sevadus](https://twitter.com/sevadus)).

It utilises [laravel/socialite](https://github.com/laravel/socialite) to interface with OAuth implementations of popular services (e.g., Facebook, Google, and Twitter). You can easily login with any of your favourite services. Once logged in, you can add even more accounts since you are not limited in any capacity.

This proof-of-concept only shows basic functionality: authentication and linking of accounts from external providers.

## Initial setup
To use the application you first need to create a `.env` file with your own values. After that you can just `docker-compose up -d --build` to create the containers.

Laravel needs an app key to perform cryptographic operations, you can generate this key using

```php
php artisan key:generate
```

Assuming similar Docker container names you would actually run

```shell
docker exec mass-social-authenticator_app_1 php artisan key:generate
```

To create the proper tables in your database, run

```php
php artisan migrate
```

## Adding more OAuth providers
For all supported OAuth providers see [Socialite Providers](https://socialiteproviders.com) for more information. You can install extra providers by running

```php
php composer require socialiteproviders/google
```

In your `.env` you need the required variables (the redirect URL is the callback URL, which should look like https://example.com/auth/google/callback):

```dotenv
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URL=...
```

Then in `./src/config/services.php` add the following

```php
'google' => [    
    'client_id' => env('GOOGLE_CLIENT_ID'),  
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),  
    'redirect' => env('GOOGLE_REDIRECT_URI') 
],
```

You can configure an event listener for this provider in the `EventServiceProvider`:

```php
protected $listen = [
    SocialiteWasCalled::class => [
        // ...
        GoogleExtendSocialite::class.'@handle',
    ],
];
```

Finally, the routes are configured to only use specific providers, to prevent people from misusing any non-configured providers. Change `./src/routes/auth.php` to add thew new provider:

```php
Route::get('auth/{provider}', [AuthenticatedSessionController::class, 'initialise'])
    ->where('provider', '(facebook|google|twitter)')
//                                 ^^^^^^
    ->name('auth.initialise');

Route::get('auth/{provider}/callback', [AuthenticatedSessionController::class, 'store'])
    ->where('provider', '(facebook|google|twitter)')
//                                 ^^^^^^
    ->name('auth.store');
```

Rebuild the container and you are good to go.

## License
MSA, like the Laravel framework, is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
