<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Reply;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    // public function index()
    // {
    //     $replies = Post::find(1)->replies;
    //     $posts = Reply::find(1)->Post;
    // }

    // public function create(Post $Post)
    // {
    //     return view('owner.replies.create', compact('post'));
    // }

    public function store(ReplyRequest $request)
    {
        // 返信を作成する処理
        $reply = Reply::create([
            'post_id' => $request->post_id,
            'owner_id' => Auth::user()->id,
            'message' => $request->message,
        ]);
        $reply->save();

        return to_route('owner.posts.show', [$reply['post_id']])
            ->with([
                'message' => '返信を投稿しました。',
                'status' => 'info',
            ]);
    }

    public function edit($id)
    {
        $reply = Reply::find($id);
        return view('owner.replies.edit', compact('reply'));
    }

    public function update(ReplyRequest $request, $id)
    {
        $reply = Reply::find($id);
        $reply->message = $request->message;
        $reply->save();

        return to_route('owner.posts.show', [$reply['post_id']])
        ->with([
            'message' => '返信を更新しました。',
            'status' => 'info',
        ]);
    }

    public function destroy($id)
    {
        $reply = Reply::find($id);
        $reply->delete();

        return to_route('owner.posts.show', [$reply['post_id']])
        ->with([
            'message' => '返信を削除しました。',
            'status' => 'alert',
        ]);
    }
}
