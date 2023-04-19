<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      期限切れユーザー一覧
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
                <form method="get" action="{{ route('owner.expired-users.index') }}" class="flex">
                  <div class="lg:w-2/3 w-full">
                    <input type="text" name="search" placeholder="ユーザーを検索" class="flex-grow h-8 mt-1 mr-0 sm:mr-2 w-5/8 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" value="検索" class="h-8 mt-2 sm:mt-0 bg-gray-400 hover:bg-gray-300 text-white rounded px-2 py-1"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </form>
                <!-- 検索フォームend -->
                <table class="table-auto w-full text-left whitespace-no-wrap mt-3">
                  <thead>
                    <tr>
                      <th class="px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                      <th class="hidden md:table-cell px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">ニックネーム</th>
                      <th class="hidden md:table-cell px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">メールアドレス</th>
                      <th class="px-2 md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">期限切れ日</th>
                      <th class="px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br md:hidden sm:table-cell"></th>
                      <th class="hidden md:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                      <th class="px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($expiredUsers as $user)
                    <tr>
                      <td class="px-2 md:px-4 py-3">{{ $user->name }}</td>
                      <td class="hidden md:table-cell px-2 md:px-4 py-3">{{ $user->nickname ?? $user->name }}</td>
                      <td class="hidden md:table-cell px-2 md:px-4 py-3">{{ $user->email }}</td>
                      <td class="px-2 md:px-4 py-3">{{ $user->deleted_at->format('m/d/H:i') }}</td>
                      <td class="px-2 sm:px-4 py-3 text-center md:hidden sm:table-cell">
                        <div class="flex justify-center sm:justify-start">
                          <button onclick="location.href='{{ route('owner.expired-users.show', ['user' => $user->id]) }}'" class="sm:hidden bg-gray-400 hover:bg-gray-300 text-white rounded px-2 py-1">詳細</button>
                        </div>
                      </td>
                      <td class="hidden md:table-cell px-2 sm:px-4 py-3 text-center">
                        <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.expired-users.destroy', ['user' => $user->id]) }}">
                          @csrf
                          @method('delete')
                          <button data-id="{{ $user->id }}" onclick="return deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded text-sm">完全に削除</button>
                        </form>
                      </td>
                      <td class="px-2 sm:px-4 py-3 text-center">
                        <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.expired-users.restore', ['user' => $user->id]) }}">
                          @csrf
                          @method('put')
                          <button data-id="{{ $user->id }}" class="text-white bg-blue-400 border-0 py-2 px-4 focus:outline-none hover:bg-blue-500 rounded text-sm">戻す</ぶ>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $expiredUsers->links() }}
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<script src="{{ asset('/js/deleteButton.js') }}"></script>