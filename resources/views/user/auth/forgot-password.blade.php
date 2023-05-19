<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <!-- {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }} -->
        パスワードリセット用のリンクをメールで送信いたします。
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('user.password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                <!-- {{ __('Email Password Reset Link') }} -->
                メールを送る
            </x-primary-button>
        </div>

        @if (Route::has('owner.password.request'))
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('user.login') }}">
            ログイン画面に戻る
        </a>
        @endif
    </form>
</x-guest-layout>
