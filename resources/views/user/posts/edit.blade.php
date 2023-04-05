<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      編集
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">投稿編集</h1>
              </div>
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form method="post" action="{{ route('user.posts.update', ['post' => $post->id ]) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="-m-2">
                    <div class="p-2 w-1/2 mx-auto">
                      <div class="relative">
                        <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                        <input type="text" id="title" name="title" value="{{ $post->title }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-1/2 mx-auto">
                      <div class="relative">
                        <label for="body" class="leading-7 text-sm text-gray-600">本文</label>
                        <textarea id="body" name="body" rows="5" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $post->body }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                      </div>
                    </div>
                    <div class="p-2 w-1/2 mx-auto">
                      <div class="relative">
                        <label for="image_file" class="leading-7 text-sm text-gray-600">画像</label>
                        @if ($post['image_file'])
                        <img src="{{ Storage::url($post['image_file']) }}">
                        @endif
                        <input type="file" name="image_file">
                        <x-input-error :messages="$errors->get('image_file')" class="mt-2" />
                      </div>
                    </div>

                    <div class="p-2 w-full mt-4 flex justify-around">
                      <button type="button" onclick="location.href='{{ route('user.posts.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                      <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">投稿</button>
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