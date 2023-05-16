<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      投稿一覧
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font">
            <div class="container mx-auto">
              <x-flash-message status="session('status)" />
              <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                <!-- 検索フォームstart -->
                <form method="get" action="{{ route('user.posts.index') }}" class="flex flex-wrap">
                  <div class="flex items-center w-full sm:w-auto">
                    <input type="text" name="search" placeholder="同じような投稿がないか検索" class="flex-grow h-8 mt-1 mr-0 sm:mr-2 w-full sm:w-80 lg:w-96 rounded-md border-gray-300 shadow-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" value="検索" class="h-8 mt-2 sm:mt-0 bg-gray-400 hover:bg-gray-300 text-white rounded px-2 py-1"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </form>
                <!-- 検索フォームend -->
                <div class="flex justify-end mb-4 mt-3">
                  <button onclick="location.href='{{ route('user.posts.create') }}'" class="text-white  bg-default border-0 py-1 px-6 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">
                    新規登録
                  </button>
                </div>
                <table class="table-auto w-full mb-5 text-left whitespace-no-wrap">
                  <thead>
                    <tr>
                      <th class="px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">タイトル</th>
                      <th class="hidden sm:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">本文</th>
                      <th class="hidden md:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">投稿日時</th>
                      <th class="px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                      <th class="hidden md:table-cell px-2 sm:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($posts as $post)
                    @php
                    $bgColor = $post->is_pinned ? 'bg-default-10' : '';
                    @endphp
                    <tr class="{{ $bgColor }}">
                      <td class="px-2 sm:px-4 py-3" onclick="location.href='{{ route('user.posts.show', ['post' => $post->id]) }}'">
                        <div class="">{{ Str::limit($post->title, 30, '…' ) }}</div>
                        <div class="sm:hidden text-xs ml-2">{{ Str::limit($post->body, 80, '…' ) }}</div>
                      </td>
                      <td class="hidden sm:table-cell px-2 sm:px-4 py-3" onclick="location.href='{{ route('user.posts.show', ['post' => $post->id]) }}'">{{ Str::limit($post->body, 80, '…' ) }}</td>
                      <td class="hidden md:table-cell text-sm px-2 sm:px-4 py-3">{{ $post->created_at->format('m/d/H:i') }}</td>
                      <td class="px-2 sm:px-4 py-3 text-center md:block hidden">
                        <button onclick="location.href='{{ route('user.posts.show', ['post' => $post->id]) }}'" class="text-white bg-default border-0 py-1 px-3 focus:outline-none hover:bg-opacity-90 rounded-xl text-md shadow-md">詳細</button>
                      </td>
                      <td class="px-2 sm:px-4 py-3 text-center">
                        @if($post->is_liked_by_auth_user())
                        <a href="#" class="btn btn-success btn-sm toggle-like" data-post-id="{{ $post->id }}" data-liked="true">
                          <p><i class="fa-solid fa-hand" style="color:#c8ad85;"></i></p>
                          <span class="badge text-xs">{{ $post->likes->count() }}</span>
                        </a>
                        @else
                        <a href="#" class="btn btn-secondary btn-sm toggle-like" data-post-id="{{ $post->id }}" data-liked="false">
                          <p><i class="fa-solid fa-hand" style="color:silver;"></i></p>
                          <span class="badge text-xs">{{ $post->likes->count() }}</span>
                        </a>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>

                </table>
                {{ $posts->links() }}
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('/js/deleteButton.js') }}"></script>
  <script>
    window.likeUrl = "{{ route('user.posts.like', ['id' => '__id']) }}";
    window.unlikeUrl = "{{ route('user.posts.unlike', ['id' => '__id']) }}";
  </script>
  <script src="{{ asset('js/like.js') }}"></script>
  <script>
    $(document).ready(function() {
      window.toggleLike();
    });
  </script>
</x-app-layout>