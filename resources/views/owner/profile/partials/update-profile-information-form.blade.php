<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            <!-- {{ __('Profile Information') }} -->
            プロフィール情報
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            <!-- {{ __("Update your account's profile information and email address.") }} -->
            アカウントのプロフィール情報とメールアドレスを更新してください。
        </p>

    </header>

    <form id="send-verification" method="post" action="{{ route('owner.verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('owner.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <!-- <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" /> -->
            <x-input-label for="name" value="名前" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <!-- <x-input-label for="nickname" :value="__('Nickname')" />
            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $user->nickname)" required autocomplete="nickname" />
            <x-input-error class="mt-2" :messages="$errors->get('nickname')" /> -->
            <x-input-label for="nickname" value="ニックネーム" /><span class="text-xs text-gray-500">ニックネームが空欄の場合、お名前が投稿者として表示されます。</span>
            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $user->nickname)" autocomplete="nickname" />
            <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>