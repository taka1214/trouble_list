<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      投稿
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">投稿詳細</h1>
              </div>
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="-m-2">
                  <div class="p-2 w-1/2 mx-auto">
                    <div class="relative">
                      <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                      <div id="title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->title }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-1/2 mx-auto">
                    <div class="relative">
                      <label for="body" class="leading-7 text-sm text-gray-600">本文</label>
                      <div id="body" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->body }}
                      </div>
                    </div>
                  </div>
                  @if($post['image_file'])
                  <div class="p-2 w-1/2 mx-auto">
                    <div class="relative">
                      <label for="image_file" class="leading-7 text-sm text-gray-600">画像</label>
                      <img id="image_file" src="{{ Storage::url($post['image_file']) }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                  </div>
                  @endif
                  <div class="p-2 w-full mt-4 flex justify-around">
                    <button type="button" onclick="location.href='{{ route('user.posts.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                    @if(Auth::id() === $post->user_id)
                    <form method="get" action="{{ route('user.posts.edit', ['post' => $post->id ]) }}">
                      <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                    </form>
                    <form id="delete_{{ $post->id }}" method="post" action="{{ route('user.posts.destroy', ['post' => $post->id ]) }}">
                      @csrf
                      @method('DELETE')
                      <a href="#" data-id="{{ $post->id }}" onclick="deletePost(this)" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">削除</a>
                    </form>
                    @endif
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

<script>
  function deletePost(e) {
    'use strict'
    if (confirm('Are you sure?')) {
      document.getElementById('delete_' + e.dataset.id).submit()
    }
  }

  function deleteReply(e) {
    'use strict'
    if (confirm('Are you sure?')) {
      document.getElementById('destroy_' + e.dataset.id).submit()
    }
  }
</script>