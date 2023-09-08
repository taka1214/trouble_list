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
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                      <div id="title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->title }}
                      </div>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="body" class="leading-7 text-sm text-gray-600">本文</label>
                      <div id="body" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        {{ $post->body }}
                      </div>
                    </div>
                  </div>
                  @if($post->images)
                  <div class="p-2 w-full">
                    @foreach($post->images as $image)
                    <div class="relative">
                      <label for="image_file" class="leading-7 text-sm text-gray-600"></label>
                      <img id="image_file" src="{{ $image->file_path }}" class="w-full rounded border border-gray-300 text-base outline-none py-1 px-3 leading-8">
                    </div>
                    @endforeach
                  </div>
                  @endif

                  <div class="p-2 w-full">
                    <div class="relative">
                      <p class="text-left text-xs text-gray-400">
                        既読： {{ $post->readCount }}
                      </p>
                      <!-- 投稿した人&最終更新日を表示start -->
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
                      <!-- 投稿した人&最終更新日を表示end -->
                    </div>
                  </div>

                  <div class="p-2 w-full mt-4 flex flex-col lg:justify-center sm:flex-row justify-around">
                    <!-- <button type="button" onclick="location.href='{{ route('owner.posts.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg mb-2 sm:mb-0">一覧に戻る</button> -->
                    <!-- ログインユーザーが投稿者と同じなら表示start -->
                    @if(Auth::id() === $post->owner_id)
                    <div class="flex justify-around mb-2 sm:mb-0">
                      <form method="get" action="{{ route('owner.posts.edit', ['post' => $post->id ]) }}">
                        <button type="submit" class="text-white bg-default border-0 py-2 px-8 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">編集</button>
                      </form>
                      <form id="delete_{{ $post->id }}" method="post" action="{{ route('owner.posts.destroy', ['post' => $post->id ]) }}" class="lg:ml-5 md:ml-5">
                        @csrf
                        @method('DELETE')
                        <button data-id="{{ $post->id }}" onclick="return deletePost(this)" class="text-white bg-alert border-0 py-2 px-8 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">削除</button>
                      </form>
                    </div>
                    @endif
                    <!-- ログインユーザーが投稿者と同じなら表示end -->
                  </div>

                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="body" class="leading-7 text-sm text-gray-600">返信</label>
                      <ul>
                        <!-- もし投稿に返信があるなら表示start -->
                        @forelse ($replies as $reply)
                        <li id="body{{ $reply->id }}" class="w-full rounded bg-gray-100 bg-opacity-50 border-b-2 border-gray-100 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out list-none">
                          <div class="show_reply">
                            <div id="reply{{ $reply->id }}" class="reply-message">
                              {{ $reply->message }}
                            </div>
                            @foreach($reply->images as $image)
                            <img id="image_file" src="{{ $image->file_path }}" class="w-full rounded border border-gray-300 text-base outline-none py-1 px-3 leading-8">
                            @endforeach

                            <!-- ログインしている人と返信の投稿者が同じならstart -->
                            @if(Auth::id() === $reply->owner_id)
                            <div class="flex justify-end mt-2">
                              <button onclick="editReply({{ $reply->id }})" class="text-white bg-default border-0 py-1 px-2 focus:outline-none hover:bg-opacity-90 rounded text-sm mr-2">編集</button>
                              <form id="destroy_{{ $reply->id }}" method="post" action="{{ route('owner.replies.destroy', ['reply' => $reply->id ]) }}" class="text-white bg-alert border-0 py-1 px-2 focus:outline-none hover:bg-opacity-90 rounded text-sm mr-2">
                                @csrf
                                @method('DELETE')
                                <button data-id="{{ $reply->id }}" onclick="return deletePost(this)">削除</button>
                              </form>
                            </div>
                            @endif
                            <!-- ログインしている人と返信の投稿者が同じならend -->

                            <div>
                              <!-- replyした人を表示start -->
                              @if(isset($reply->owner))
                              <p class="text-right text-xs text-gray-400">{{ $reply->owner->nickname ? $reply->owner->nickname : $reply->owner->name }}</p>
                              @elseif (isset($reply->user))
                              <p class="text-right text-xs text-gray-400">{{ $reply->user->nickname ? $reply->user->nickname : $reply->user->name }}</p>
                              @endif
                              <!-- replyした人を表示end -->

                              <!-- replyの最終更新日start -->
                              <p class="text-right text-xs text-gray-400">最終更新日時 {{ $reply->updated_at ? $reply->updated_at->format('Y年m月d日 H時i分') : $reply->created_at->format('Y年m月d日 H時i分') }}</p>
                              <!-- replyの最終更新日end -->
                            </div>
                          </div>

                          <!-- 返信の更新フォームstart -->
                          <div id="edit{{ $reply->id }}" class="hidden">
                            <form method="post" action="{{ route('owner.replies.update', ['reply' => $reply->id ]) }}" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
                              <textarea name="message" class="w-full rounded bg-gray-100 bg-opacity-50 border-b-2 border-gray-100 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $reply->message }}</textarea>

                              <!-- もしreplyに画像があれば表示start -->
                              <!-- 既存の画像を表示 -->
                              @if ($reply->images->count() > 0)
                              @foreach ($reply->images as $image)
                              <label for="image_file" class="leading-7 text-sm text-gray-600">画像</label>
                              <img id="image_file" src="{{ $image->file_path }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              <div class="mt-2">
                                <input type="checkbox" id="delete_image_{{ $image->id }}" name="delete_image[{{ $image->id }}]" value="1">
                                <label for="delete_image_{{ $image->id }}" class="text-sm text-gray-600">現在の画像を削除する</label>
                              </div>
                              @endforeach
                              @endif
                              <!-- もしreplyに画像があれば表示end -->
                              <!-- 新しい画像を追加するためのファイル入力フィールド -->
                              <label for="image_file" class="leading-7 text-sm text-gray-600">新しい画像を追加</label>
                              <input type="file" name="new_image_file[]" multiple>
                              <x-input-error :messages="$errors->get('new_image_file')" class="mt-2" />
                              <div class="flex justify-center mt-2">
                                <button type="submit" class="text-white bg-default border-0 py-1 px-2 focus:outline-none hover:bg-opacity-90 rounded text-sm">更新</button>
                              </div>
                            </form>
                            <div class="flex justify-center mt-2">
                              <button id="cancelEditReply{{ $reply->id }}" onclick="cancelEditReply({{ $reply->id }})" class="bg-gray-300 border-0 py-1 px-2 focus:outline-none hover:bg-opacity-90 rounded text-sm">キャンセル</button>
                            </div>
                          </div>
                          <!-- 返信の更新フォームend -->
                        </li>

                        @empty
                        <p>まだ返信はありません</p>
                        @endforelse
                        <!-- もし投稿に返信があるなら表示end -->
                      </ul>
                    </div>
                  </div>

                  <!-- 返信フォームstart -->
                  <form method="post" action="{{ route('owner.replies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="message" class="leading-7 text-sm text-gray-600">返信を書く</label>
                        <textarea id="message" name="message" rows="5" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                      </div>
                      <div class="relative">
                        <label for="image_files" class="leading-7 text-sm text-gray-600">画像</label>
                        <input type="file" id="image_files" name="image_files[]" multiple>
                        <x-input-error :messages="$errors->get('image_files.*')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-full mt-4 flex justify-around">
                      <button type="submit" class="text-white bg-default border-0 py-2 px-8 mt-3 focus:outline-none  hover:bg-opacity-90 rounded-xl text-md shadow-md">返信する</button>
                    </div>
                  </form>
                  <!-- 返信フォームend -->

                  <div class="p-2 w-full mt-4 flex justify-around">
                    <button type="button" onclick="location.href='{{ route('owner.posts.index') }}'" class="bg-gray-300 border-0 py-2 px-8 focus:outline-none hover:bg-opacity-90 rounded-xl text-md shadow-md">一覧に戻る</button>
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
<script src="{{ asset('/js/editReply.js') }}"></script>
<script src="{{ asset('/js/cancelEditReply.js') }}"></script>