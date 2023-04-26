<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      期限切れユーザー
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">期限切れユーザー詳細</h1>
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
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="deleted_at" class="leading-7 text-sm text-gray-600">期限が切れた日</label>
                      <div id="deleted_at" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8">
                        {{ $user->deleted_at->format('Y年m月d日 H時i分') }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full mt-4 flex flex-col lg:flex-row lg:justify-center sm:justify-around">
                    <div class="w-full lg:w-1/2 mb-2 lg:mb-0 lg:pr-1">
                      <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.expired-users.destroy', ['user' => $user->id]) }}" class="text-white bg-alert border-0 py-2 px-8 mt-3 focus:outline-none hover:bg-opacity-90 rounded-xl text-center text-md shadow-md">
                        @csrf
                        @method('DELETE')
                        <button data-id="{{ $user->id }}" onclick="return deletePost(this)" >完全削除</button>
                      </form>
                    </div>
                    <div class="w-full lg:w-1/2 lg:pl-1 text-white bg-default border-0 py-2 px-8 focus:outline-none hover:bg-opacity-90 rounded-xl text-center text-md shadow-md">
                      <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.expired-users.restore', ['user' => $user->id]) }}">
                        @csrf
                        @method('put')
                        <button data-id="{{ $user->id }}">戻す</button>
                      </form>
                    </div>
                    <button type="button" onclick="location.href='{{ route('owner.expired-users.index') }}'" class="bg-gray-300 border-0 py-2 px-8 mt-3 focus:outline-none hover:bg-opacity-90 rounded-xl text-md shadow-md">一覧に戻る</button>
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