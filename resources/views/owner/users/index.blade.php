<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      ユーザー一覧
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font">
            <div class="container md:px-5 mx-auto">
              <x-flash-message status="session('status')" />
              <div class="w-full mx-auto overflow-auto">
                <!-- 検索フォームstart -->
                <form method="get" action="{{ route('owner.users.index') }}" class="flex">
                  <div class="flex items-center w-full sm:w-auto">
                    <input type="text" name="search" placeholder="ユーザーを検索" class="flex-grow h-8 mt-1 mr-0 sm:mr-2 w-full sm:w-80 lg:w-96 rounded-md border-gray-300 shadow-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" value="検索" class="h-8 mt-2 sm:mt-0 bg-gray-400 hover:bg-gray-300 text-white rounded px-2 py-1"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </form>
                <!-- 検索フォームend -->
                <div class="flex justify-end mb-4 mt-3">
                  <button onclick="location.href='{{ route('owner.users.create') }}'" class="text-white  bg-default border-0 py-1 px-6 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">新規登録</button>
                </div>
                <table class="table-auto w-full text-left whitespace-no-wrap mt-3 mb-5">
                  <thead>
                    <tr>
                      <th class="px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                      <th class="hidden md:table-cell px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">ニックネーム</th>
                      <th class="hidden md:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">部屋番号</th>
                      <th class="hidden md:table-cell px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">メールアドレス</th>
                      <th class="px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">作成日</th>
                      <th class="hidden md:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr class="sm:hidden">
                      <td class="px-2 md:px-4 py-3 cursor-pointer" onclick="location.href='{{ route('owner.users.show', ['user' => $user->id]) }}'">{{ $user->name }}</td>
                      <td class="px-2 md:px-4 py-3 cursor-pointer" onclick="location.href='{{ route('owner.users.show', ['user' => $user->id]) }}'">{{ $user->created_at->format('m/d/H:i') }}</td>
                    </tr>
                    <tr class="hidden sm:table-row">
                      <td class="px-2 md:px-4 py-3">{{ $user->name }}</td>
                      <td class="hidden md:table-cell px-2 md:px-4 py-3">{{ $user->nickname ?? $user->name }}</td>
                      <td class="hidden md:table-cell px-2 md:px-4 py-3">{{ $user->room_number }}</td>
                      <td class="hidden md:table-cell px-2 md:px-4 py-3">{{ $user->email }}</td>
                      <td class="px-2 md:px-4 py-3">{{ $user->created_at->format('m/d/H:i') }}</td>
                      <td class="hidden md:table-cell px-2 sm:px-4 py-3 text-center">
                        <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.users.destroy', ['user' => $user->id]) }}">
                          @csrf
                          @method('DELETE')
                          <button data-id="{{ $user->id }}" onclick="return deletePost(this)" class="text-white bg-alert border-0 py-2 px-4 focus:outline-none hover:bg-opacity-90 rounded-xl text-sm">削除</button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $users->links() }}
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<script src="{{ asset('/js/deleteButton.js') }}"></script>