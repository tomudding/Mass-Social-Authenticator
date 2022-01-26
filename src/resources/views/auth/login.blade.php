<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">
            {{ __('Login') }}
        </h2>

        <div class="flex items-center justify-center mt-4">
            <x-button href="{{ route('auth.initialise', ['provider' => 'facebook']) }}" class="ml-3">
                {{ __('Log in with Facebook') }}
            </x-button>
            <x-button href="{{ route('auth.initialise', ['provider' => 'twitter']) }}" class="ml-3">
                {{ __('Log in with Twitter') }}
            </x-button>
        </div>
    </x-auth-card>
</x-guest-layout>
