<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'image_file' => $request->hasFile('image_file') ? $request->file('image_file')->store('public/images') : null,
        ]);

        return to_route('user.posts.index')
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

        return redirect()->back();
    }

    public function unlike($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', Auth::user()->id)->first();

        if ($like) {
            $like->delete();
        }

        return redirect()->back();
    }
}
