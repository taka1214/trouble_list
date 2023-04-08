<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // 返信の日時を気にせずに投稿だけを見る場合の一覧順序(更新が新しい順)
        // $posts = Post::select('id', 'title', 'body', 'image_file', 'created_at', 'updated_at')
        // ->orderByDesc('updated_at')
        // ->orderByDesc('created_at')
        // ->paginate(10);
        // return view('owner.posts.index', compact('posts'));

        // 返信の日時も含めた場合の一覧順序(更新が新しい順)
        $query = Post::leftJoin('replies', 'posts.id', '=', 'replies.post_id')
            ->select('posts.id', 'posts.title', 'posts.body', 'posts.image_file', 'posts.created_at', 'posts.updated_at')
            ->selectRaw('GREATEST(posts.updated_at, COALESCE(MAX(replies.updated_at), \'2000-01-01\')) as sort_date')
            ->groupBy('posts.id', 'posts.title', 'posts.body', 'posts.image_file', 'posts.created_at', 'posts.updated_at')
            ->orderByDesc('sort_date');

        if ($request->has('search')) {
            $query->where('posts.title', 'like', '%' . $request->input('search') . '%')
                ->orWhere('posts.body', 'like', '%' . $request->input('search') . '%')
                ->orWhere('replies.message', 'like', '%' . $request->input('search') . '%');
        }

        $posts = $query->paginate(10);
        return view('owner.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('owner.posts.create');
    }

    public function store(PostRequest $request)
    {
        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'owner_id' => $request->user()->id,
            'image_file' => $request->hasFile('image_file') ? $request->file('image_file')->store('public/images') : null,
        ]);

        return to_route('owner.posts.index')
            ->with([
                'message' => '投稿が完了しました',
                'status' => 'info',
            ]);
    }

    public function show($id)
    {
        $post = Post::find($id);
        $replies = Post::find($id)->replies;
        $postUser = Post::find($id)->user;
        $postOwner = Post::find($id)->owner;
        return view('owner.posts.show', compact('post', 'replies', 'postUser', 'postOwner'));
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('owner.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);

        if ($request->input('delete_image')) {
            Storage::delete($post->image_file);
            $post->image_file = null;
        }

        if ($request->hasFile('image_file')) {
            $image_file = $request->file('image_file')->store('public/images');
            $post->image_file = str_replace('public/', '', $image_file);
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return redirect()
            ->route('owner.posts.index')
            ->with([
                'message' => '投稿を更新しました',
                'status' => 'info',
            ]);
    }

    public function destroy($id)
    {
        Post::find($id)->delete();

        return redirect()
            ->route('owner.posts.index')
            ->with([
                'message' => '投稿を削除しました',
                'status' => 'alert',
            ]);
    }
}
