<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      ユーザー詳細
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">ユーザー詳細</h1>
              </div>
              <div class="lg:w-1/2 md:w-2/3 mx-auto">

                <div class="-m-2">
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600">名前</label>
                      <div id="name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8">
                        {{ $user->name }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="nickname" class="leading-7 text-sm text-gray-600">ニックネーム</label>
                      <div id="nickname" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8">
                        {{ $user->nickname?? $user->name }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                      <div id="email" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8">
                        {{ $user->email }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="created_at" class="leading-7 text-sm text-gray-600">作成日</label>
                      <div id="created_at" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8">
                        {{ $user->created_at }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full mt-4 flex flex-col lg:flex-row lg:justify-center sm:justify-around">
                    <div class="w-full lg:w-1/2 mb-2 lg:mb-0 lg:pr-1">
                      <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.users.destroy', ['user' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button data-id="{{ $user->id }}" onclick="deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded text-sm w-full">削除</button>
                      </form>
                    </div>
                    <button type="button" onclick="location.href='{{ route('owner.users.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg mt-2 sm:mt-0 w-full">一覧に戻る</button>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<script src="{{ asset('/js/deleteButton.js') }}"></script>