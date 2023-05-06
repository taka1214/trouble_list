<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
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
    //     return view('user.replies.create', compact('Post'));
    // }

    public function store(ReplyRequest $request)
    {
        // 返信を作成する処理
        $reply = Reply::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::user()->id,
            'message' => $request->message,
        ]);

        // $reply->save();

        if ($request->hasFile('image_files')) {
            // 画像をS3にアップロード
            $images = $reply->uploadImagesToS3($request->file('image_files'), 'user');
            // アップロードされた画像をReplyImageモデルに保存
            foreach ($images as $image) {
                ReplyImage::create([
                    'reply_id' => $reply->id,
                    'file_path' => $image['file_path'],
                ]);
            }
        }

        return to_route('user.posts.show', [$reply['post_id']])
            ->with([
                'message' => '返信を投稿しました。',
                'status' => 'info',
            ]);
    }


    public function edit($id)
    {
        $reply = Reply::find($id);
        return view('user.replies.edit', compact('reply'));
    }

    public function update(ReplyRequest $request, $id)
    {
        $reply = Reply::find($id);

        // 既存の画像の削除
        if ($request->input('delete_image')) {
            $reply->deleteImagesFromS3(array_keys($request->input('delete_image')), 'user');
        }

        // 新しい画像の追加
        if ($request->hasFile('new_image_file')) {
            $uploaded_images = $reply->uploadImagesToS3($request->file('new_image_file'), 'user');
            $reply->images()->createMany($uploaded_images);
        }

        $reply->message = $request->message;
        $reply->save();

        return to_route('user.posts.show', [$reply['post_id']])
            ->with([
                'message' => '返信を更新しました。',
                'status' => 'info',
            ]);
    }


    public function destroy($id)
    {
        $reply = Reply::find($id);

        // 画像の削除
        $image_ids = $reply->images->pluck('id')->toArray();
        $reply->deleteImagesFromS3($image_ids, 'user');

        $reply->delete();

        return to_route('user.posts.show', [$reply['post_id']])
            ->with([
                'message' => '返信を削除しました。',
                'status' => 'alert',
            ]);
    }
}
