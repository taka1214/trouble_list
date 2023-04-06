<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      期限切れユーザー一覧
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="md:p-6 text-gray-900">
          <section class="text-gray-600 body-font">
            <div class="container px-5 mx-auto">
              <x-flash-message status="session('status)" />
              {{-- 検索フォームstart --}}
              <form method="get" action="{{ route('owner.expired-users.index') }}" class="flex">
                <input type="text" name="search" placeholder="ユーザーを検索" class="mt-1 mr-2 block w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <button type="submit" value="検索" class="bg-gray-400 hover:bg-gray-300 text-white rounded px-2 py-1">探す</button>
              </form>
              {{-- 検索フォームend --}}
              <div class="lg:w-2/3 w-full mx-auto mt-2 overflow-auto">
                <table class="table-auto w-full text-left whitespace-no-wrap">
                  <thead>
                    <tr>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">ニックネーム</th>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">期限が切れた日</th>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                      <th class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($expiredUsers as $user)
                    <tr>
                      <td class="md:px-4 py-3">{{ $user->name }}</td>
                      <td class="md:px-4 py-3">{{ $user->nickname }}</td>
                      <td class="md:px-4 py-3">{{ $user->email }}</td>
                      <td class="md:px-4 py-3">{{ $user->deleted_at->diffForHumans() }}</td>
                      <td class="md:px-4 py-3 text-center">
                        <form id="delete_{{ $user->id }}" method="post" action="{{ route('owner.expired-users.destroy', ['user' => $user->id]) }}">
                          @csrf
                          <button data-id="{{ $user->id }}" onclick="deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded text-sm">完全に削除</button>
                        </form>
                      </td>
                      <td class="px-4 py-3 text-center">
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

  <script>
    function deletePost(e) {
      'use strict';
      if (confirm('Are you sure?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
      }
    }
  </script>
</x-app-layout>