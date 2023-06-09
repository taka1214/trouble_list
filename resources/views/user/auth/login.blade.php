<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <p class="text-right">入居者用</p>
    <form method="POST" action="{{ route('user.login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <!-- <x-input-label for="email" :value="__('Email')" /> -->
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password" :value="__('Password')" /> -->
            <x-input-label for="password" value="パスワード" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <!-- <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span> -->
                <span class="ml-2 text-sm text-gray-600">ログイン情報記憶する</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('user.password.request'))
            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-default" href="{{ route('user.password.request') }}">
                <!-- {{ __('Forgot your password?') }} -->
                パスワードをお忘れですか？
            </a>
            @endif

            <x-primary-button class="ml-3">
                <!-- {{ __('Log in') }} -->
                ログイン
            </x-primary-button>
        </div>
    </form>
    <!-- @if (Route::has('user.register'))
    <a href="{{ route('user.register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
    @endif -->
</x-guest-layout>