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
              <x-flash-message status="session('status)" />
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">投稿詳細</h1>
              </div>
              <div class="lg:w-1/2 md:w-3/4 mx-auto">
                <div class="-m-2">
                  <div class="p-2 w-2/3 mx-auto">
                    <div class="relative">
                      <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                      <div id="title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->title }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-2/3 mx-auto">
                    <div class="relative">
                      <label for="body" class="leading-7 text-sm text-gray-600">本文</label>
                      <div id="body" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->body }}
                      </div>
                    </div>
                  </div>
                  @if($post['image_file'])
                  <div class="p-2 w-2/3 mx-auto">
                    <div class="relative">
                      <label for="image_file" class="leading-7 text-sm text-gray-600">画像</label>
                      <img id="image_file" src="{{ Storage::url($post['image_file']) }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                  </div>
                  @endif

                  <div class="p-2 w-2/3 mx-auto">
                    <div class="relative">
                      {{-- 投稿した人&最終更新日を表示start --}}
                      <p class="text-right text-xs text-gray-400">
                        @if($postUser)
                        {{ $postUser->nickname ? 'posted by ' . $postUser->nickname : 'posted by ' . $postUser->name }}
                        @elseif($postOwner)
                        {{ $postOwner->nickname ? 'posted by ' . $postOwner->nickname : 'posted by ' . $postOwner->name }}
                        @endif
                      </p>
                      <p class="text-right text-xs text-gray-400">
                        最終更新日時 {{ $post->updated_at ? $post->updated_at->format('Y年m月d日 H時i分') : $post->created_at->format('Y年m月d日 H時i分') }}
                      </p>
                      {{-- 投稿した人&最終更新日を表示end --}}
                    </div>
                  </div>

                  <div class="p-2 w-full mt-4 flex justify-around">
                    <button type="button" onclick="location.href='{{ route('owner.posts.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">一覧に戻る</button>
                    {{-- ログインユーザーが投稿者と同じなら表示start --}}
                    @if(Auth::id() === $post->owner_id)
                    <form method="get" action="{{ route('owner.posts.edit', ['post' => $post->id ]) }}">
                      <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                    </form>
                    <form id="delete_{{ $post->id }}" method="post" action="{{ route('owner.posts.destroy', ['post' => $post->id ]) }}">
                      @csrf
                      @method('DELETE')
                      <a href="#" data-id="{{ $post->id }}" onclick="deletePost(this)" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">削除</a>
                    </form>
                    @endif
                    {{-- ログインユーザーが投稿者と同じなら表示end --}}
                  </div>

                  <div class="p-2 w-2/3 mx-auto">
                    <div class="relative">
                      <label for="body" class="leading-7 text-sm text-gray-600">返信</label>
                      <ul>
                        {{-- もし投稿に返信があるなら表示start --}}
                        @forelse ($replies as $reply)
                        <li id="body{{ $reply->id }}" class="w-full rounded bg-gray-100 bg-opacity-50 border-b-2 border-gray-100 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          <div class="show_reply">
                            <div id="reply{{ $reply->id }}" class="reply-message w-full bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 mb-1 leading-8 transition-colors duration-200 ease-in-out">
                              {{ $reply->message }}
                            </div>
                            @if ($reply['image_file'])
                            <img id="image_file" src="{{ Storage::url($reply['image_file']) }}" class="w-full mb-3 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            @endif

                            @if(Auth::id() === $reply->owner_id)
                            <div class="flex justify-end">
                              <button onclick="editReply({{ $reply->id }})" class="text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded text-lg mr-2">編集</button>
                              <form id="destroy_{{ $reply->id }}" method="post" action="{{ route('owner.replies.destroy', ['reply' => $reply->id ]) }}">
                                @csrf
                                @method('DELETE')
                                <a href="#" data-id="{{ $reply->id }}" onclick="deleteReply(this)" class="text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-600 rounded text-lg">削除</a>
                              </form>
                            </div>

                            <div>
                              {{-- replyした人を表示start --}}
                              @php
                              $user = \App\Models\User::find($reply->user_id);
                              $owner = \App\Models\Owner::find($reply->owner_id);
                              @endphp
                              @if ($user)
                              <p class="text-right text-xs text-gray-400">{{ $user->nickname ?? $user->name }}</p>
                              @elseif ($owner)
                              <p class="text-right text-xs text-gray-400">{{ $owner->nickname ?? $owner->name }}</p>
                              @endif
                              {{-- replyした人を表示end --}}

                              {{-- replyの最終更新日start --}}
                              <p class="text-right text-xs text-gray-400">最終更新日時 {{ $reply->updated_at ? $reply->updated_at->format('Y年m月d日 H時i分') : $reply->created_at->format('Y年m月d日 H時i分') }}</p>
                              {{-- replyの最終更新日end --}}
                            </div>
                          </div>

                          {{-- 返信の更新フォームstart --}}
                          <div id="edit{{ $reply->id }}" class="hidden">
                            <form method="post" action="{{ route('owner.replies.update', ['reply' => $reply->id ]) }}" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
                              <textarea name="message" class="w-full rounded bg-gray-100 bg-opacity-50 border-b-2 border-gray-100 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $reply->message }}</textarea>

                              {{-- もしreplyに画像があれば表示start --}}
                              @if ($reply['image_file'])
                              <label for="image_file" class="leading-7 text-sm text-gray-600"></label>
                              <img src="{{ Storage::url($reply['image_file']) }}">
                              <div class="mt-2">
                                <input type="checkbox" id="delete_image" name="delete_image" value="1">
                                <label for="delete_image" class="text-sm text-gray-600">現在の画像を削除する</label>
                              </div>
                              @endif
                              {{-- もしreplyに画像があれば表示end --}}

                              <input type="file" name="image_file">
                              <x-input-error :messages="$errors->get('image_file')" class="mt-2" />
                              <div class="flex justify-center mt-2">
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
                              </div>
                            </form>
                            <div class="flex justify-center mt-2">
                              <button id="cancelEditReply{{ $reply->id }}" onclick="cancelEditReply({{ $reply->id }})" class="text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded text-lg">キャンセル</button>
                            </div>
                          </div>
                          @endif
                          {{-- 返信の更新フォームend --}}
                        </li>

                        @empty
                        <p>まだ返信はありません</p>
                        @endforelse
                        {{-- もし投稿に返信があるなら表示end --}}
                      </ul>
                    </div>
                  </div>

                  <form method="post" action="{{ route('owner.replies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div class="p-2 w-2/3 mx-auto">
                      <div class="relative">
                        <label for="message" class="leading-7 text-sm text-gray-600">返信を書く</label>
                        <textarea id="message" name="message" rows="5" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                      </div>
                      <div class="relative">
                        <label for="image_file" class="leading-7 text-sm text-gray-600">画像</label>
                        <input type="file" name="image_file">
                        <x-input-error :messages="$errors->get('image_file')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full mt-4 flex justify-around">
                      <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">返信する</button>
                    </div>
                  </form>

                  <div class="p-2 w-full mt-4 flex justify-around">
                    <button type="button" onclick="location.href='{{ route('owner.posts.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">一覧に戻る</button>
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

  function editReply(replyId) {
    'use strict'
    document.getElementById('reply' + replyId).classList.add('hidden');
    document.getElementById('edit' + replyId).classList.remove('hidden');

    let targetShowReply = document.getElementById('body' + replyId).querySelector('.show_reply');
    targetShowReply.classList.add('hidden');
  }

  function cancelEditReply(replyId) {
    'use strict'
    document.getElementById('reply' + replyId).classList.remove('hidden');
    document.getElementById('edit' + replyId).classList.add('hidden');

    let targetShowReply = document.getElementById('body' + replyId).querySelector('.show_reply');
    targetShowReply.classList.remove('hidden');
  }

  function deleteReply(e) {
    'use strict'
    if (confirm('Are you sure?')) {
      document.getElementById('destroy_' + e.dataset.id).submit()
    }
  }
</script>