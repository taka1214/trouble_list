<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Owner;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // $posts = User::find(1)->posts;
        // $user = Post::find(1)->user;
        // dd($posts, $user);
        $posts = Post::select('id', 'title', 'body', 'image_file', 'created_at')->get();
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
            'message' =>'投稿が完了しました',
            'status' => 'info',
        ]);
    }

    public function show($id)
    {
        $post = Post::find($id);
        $replies = Post::find($id)->replies;
        return view('owner.posts.show', compact('post', 'replies'));
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('owner.posts.edit', compact('post'));
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
        ->route('owner.posts.index')
        ->with([
            'message' =>'投稿を更新しました',
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
