<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      ユーザー登録
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">ユーザー登録</h1>
              </div>
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form method="post" action="{{ route('owner.users.store') }}">
                  @csrf
                  <div class="-m-2">
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="name" class="leading-7 text-sm text-gray-600">名前</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="room_number" class="leading-7 text-sm text-gray-600">部屋番号</label>
                        <input type="text" id="room_number" name="room_number" value="{{ old('room_number') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"  required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="password" class="leading-7 text-sm text-gray-600">パスワード</label>
                        <input type="password" id="password" name="password" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="password_confirmation" class="leading-7 text-sm text-gray-600">パスワード確認</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full mt-4 flex justify-around">
                      <button type="button" onclick="location.href='{{ route('owner.users.index') }}'" class="bg-gray-300 border-0 py-2 px-8 mt-3 focus:outline-none hover:bg-opacity-90 rounded-xl text-md shadow-md">戻る</button>
                      <button type="submit" class="text-white bg-default border-0 py-2 px-8 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">登録</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>