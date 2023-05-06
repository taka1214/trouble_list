<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Image;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // 返信の日時を気にせずに投稿だけの日時を見る場合の一覧順序(更新が新しい順)
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
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'owner_id' => $request->user()->id,
        ]);

        if ($request->hasFile('image_files')) {
            $images = $post->uploadImagesToS3($request->file('image_files'), 'owner');
            Image::insert($images);
        }

        return redirect()->route('owner.posts.index')
            ->with([
                'message' => '投稿が完了しました',
                'status' => 'info',
            ]);
    }

    public function show($id)
    {
        $post = Post::with('replies.user', 'replies.owner')->find($id);
        $replies = $post->replies;
        $postUser = $post->user;
        $postOwner = $post->owner;
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

        // 画像の削除処理
        if ($request->input('delete_images')) {
            $post->deleteImagesFromS3($request->input('delete_images'), 'owner');
        }

        // 画像の追加処理
        if ($request->hasFile('image_files')) {
            $images = $post->uploadImagesToS3($request->file('image_files'), 'owner');
            Image::insert($images);
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

    public function like($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', Auth::user()->id)->first();

        if (!$like) {
            Like::create([
                'post_id' => $id,
                'user_id' => Auth::user()->id,
            ]);
        }

        $post = Post::findOrFail($id);

        return response()->json([
            'likes_count' => $post->likes->count(),
        ]);
    }

    public function unlike($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', Auth::user()->id)->first();

        if ($like) {
            $like->delete();
        }

        $post = Post::findOrFail($id);

        return response()->json([
            'likes_count' => $post->likes->count(),
        ]);
    }
}
