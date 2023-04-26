<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Like;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Likes;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:users');
        // only()の引数内のメソッドはログイン時のみ有効
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    public function index(Request $request)
    {
        // 使い方の例
        // $posts = User::find(1)->posts;
        // $user = Post::find(3)->user;
        // dd($posts, $user);
        $query = Post::leftJoin('replies', 'posts.id', '=', 'replies.post_id')
            ->select('posts.id', 'posts.title', 'posts.body', 'posts.image_file', 'posts.created_at')
            ->selectRaw('GREATEST(posts.updated_at, COALESCE(MAX(replies.updated_at), \'2000-01-01\')) as sort_date')
            ->groupBy('posts.id', 'posts.title', 'posts.body', 'posts.image_file', 'posts.created_at')
            ->orderByDesc('sort_date');

        if ($request->has('search')) {
            $query->where('posts.title', 'like', '%' . $request->input('search') . '%')
                ->orWhere('posts.body', 'like', '%' . $request->input('search') . '%');
        }

        $posts = $query->paginate(10);
        return view('user.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function store(PostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image_file) {
                $file_path = $image_file->store('public/images');
                Image::create([
                    'post_id' => $post->id,
                    'file_path' => $file_path,
                ]);
            }
        }

        return redirect()->route('user.posts.index')
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
        return view('user.posts.show', compact('post', 'replies', 'postUser', 'postOwner'));
    }


    public function edit($id)
    {
        $post = Post::find($id);
        return view('user.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);

        // 画像の削除処理
        if ($request->input('delete_images')) {
            foreach ($request->input('delete_images') as $image_id) {
                $image = Image::find($image_id);
                if ($image) {
                    Storage::delete($image->file_path);
                    $image->delete();
                }
            }
        }

        // 画像の追加処理
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image_file) {
                $file_path = $image_file->store('public/images');
                $post->images()->create([
                    'file_path' => str_replace('public/', '', $file_path),
                ]);
            }
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return redirect()
            ->route('user.posts.index')
            ->with([
                'message' => '投稿を更新しました',
                'status' => 'info',
            ]);
    }

    public function destroy($id)
    {
        Post::find($id)->delete();

        return redirect()
            ->route('user.posts.index')
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
