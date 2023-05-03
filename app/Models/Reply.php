<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'post_id',
        'user_id',
        'owner_id',
        'image_file',
    ];

    private function getS3Client()
    {
        return new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function post()
    {
        return $this->belongsTo(App\Models\Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function images()
    {
        return $this->hasMany(ReplyImage::class);
    }

    public function uploadImagesToS3($image_files, $prefix)
    {
        $s3Client = $this->getS3Client();

        $images = [];
        foreach ($image_files as $image_file) {
            try {
                $result = $s3Client->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $prefix . '/replyImages/' . $image_file->getClientOriginalName(),
                    'SourceFile' => $image_file->getRealPath(),
                ]);

                $path = $result['ObjectURL'];
                $images[] = [
                    'reply_id' => $this->id,
                    'file_path' => $path,
                ];
            } catch (Aws\S3\Exception\S3Exception $e) {
                // エラーが発生した場合、詳細をログに記録
                Log::error($e->getMessage());
            }
        }

        return $images;
    }

    public function deleteImagesFromS3($image_ids, $prefix)
    {
        $s3Client = $this->getS3Client();

        foreach ($image_ids as $image_id) {
            $image = ReplyImage::find($image_id);
            if ($image) {
                try {
                    $s3Client->deleteObject([
                        'Bucket' => env('AWS_BUCKET'),
                        'Key' => $prefix . '/images/' . basename($image->file_path),
                    ]);

                    $image->delete();
                } catch (Aws\S3\Exception\S3Exception $e) {
                    // エラーが発生した場合、詳細をログに記録
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
