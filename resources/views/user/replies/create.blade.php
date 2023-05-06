<!-- このファイル未使用 -->

<form method="POST" action="{{ route('user.replies.store') }}">
  @csrf
  <input
      name="post_id"
      type="hidden"
      value="{{ $post->id }}"
  >
  <div>
    <label for="message">返信</label>
    <textarea name="message" required>{{ old('message') }}</textarea>
    @error('content')
    <div>{{ $message }}</div>
    @enderror
  </div>

  <div>
    <button type="submit">投稿</button>
  </div>
</form>