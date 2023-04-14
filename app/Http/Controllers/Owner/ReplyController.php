<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\ReplyImage;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // $reply->save();

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image_file) {
                $file_path = $image_file->store('public/images');

                ReplyImage::create([
                    'reply_id' => $reply->id,
                    'file_path' => $file_path,
                ]);
            }
        }

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

        // 既存の画像の削除
        if ($request->input('delete_image')) {
            foreach ($request->input('delete_image') as $image_id => $value) {
                $image = ReplyImage::find($image_id);
                Storage::delete($image->file_path);
                $image->delete();
            }
        }

        // 新しい画像の追加
        if ($request->hasFile('new_image_file')) {
            foreach ($request->file('new_image_file') as $file) {
                $file_path = $file->store('public/images');
                $reply->images()->create([
                    'file_path' => str_replace('public/', '', $file_path),
                ]);
            }
        }

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
