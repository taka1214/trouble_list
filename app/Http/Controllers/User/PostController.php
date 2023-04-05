<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function index(Request $request)
    {
        // $posts = User::find(1)->posts;
        // $user = Post::find(3)->user;
        // dd($posts, $user);
        $posts = Post::select('id', 'title', 'body', 'image_file', 'created_at')->get();
        return view('user.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function store(PostRequest $request)
    {
        if($request->hasFile('image_file')) {
            Post::create([
                'title' => $request->title,
                'body' => $request->body,
                'image_file' => $request->file('image_file')->store('public/images'),
            ]);
        } else {
            Post::create([
                'title' => $request->title,
                'body' => $request->body,
            ]);
        }
        return to_route('user.posts.index');
    }

    public function show($id)
    {
        $post = Post::find($id);
        $replies = Post::find($id)->replies;
        return view('user.posts.show', compact('post', 'replies'));
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('user.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
        if (!empty($post->image_file)) {
            Storage::delete($post->image_file);
        }
        if($request->hasFile('image_file')) {
            $image_file = $request->file('image_file')->store('public/images');
            $post->image_file = str_replace('public/', '', $image_file);
        }        
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return redirect()
        ->route('user.posts.index')
        ->with([
            'message' =>'投稿を更新しました',
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
}
