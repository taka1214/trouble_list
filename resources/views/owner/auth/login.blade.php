<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <p class="text-right">オーナー用</p>
    <form method="POST" action="{{ route('owner.login') }}">
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

        <div class="flex justify-between mt-8">
            <div class="flex flex-col mr-3">
                @if (Route::has('owner.register'))
                <a href="{{ route('owner.register') }}" class="mt-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-500">オーナーアカウントの新規登録</a>
                @endif

                @if (Route::has('owner.password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-default" href="{{ route('owner.password.request') }}">
                    <!-- {{ __('Forgot your password?') }} -->
                    パスワードをお忘れですか？
                </a>
                @endif
            </div>

            <x-primary-button>
                <!-- {{ __('Log in') }} -->
                ログイン
            </x-primary-button>
        </div>

    </form>

</x-guest-layout>