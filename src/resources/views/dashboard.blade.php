<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach ($linkedAccounts as $key => $accounts)
                        <p><strong>{{ $key }}</strong></p>

                        @foreach ($accounts as $account)
                            <p>{{ $account->provider_id }}</p>
                        @endforeach
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">
                        {{ __('Add accounts') }}
                    </h2>
                    <x-button href="{{ route('auth.initialise', ['provider' => 'facebook']) }}" class="ml-3">
                        {{ __('Facebook') }}
                    </x-button>
                    <x-button href="{{ route('auth.initialise', ['provider' => 'twitter']) }}" class="ml-3">
                        {{ __('Twitter') }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
