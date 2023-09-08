<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'body',
        'admin_id',
        'owner_id',
        'user_id',
        'image_file',
        'is_pinned'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function reads()
    {
        return $this->hasMany(Read::class);
    }

    public function is_liked_by_auth_user()
    {
        $id = Auth::id();

        $likers = array();
        foreach ($this->likes as $like) {
            array_push($likers, $like->user_id);
        }

        if (in_array($id, $likers)) {
            return true;
        } else {
            return false;
        }
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function uploadImagesToS3($image_files, $prefix)
    {
        $s3Client = $this->getS3Client();

        $images = [];
        foreach ($image_files as $image_file) {
            try {
                $result = $s3Client->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $prefix . '/images/' . $image_file->getClientOriginalName(),
                    'SourceFile' => $image_file->getRealPath(),
                ]);

                $path = $result['ObjectURL'];
                $images[] = [
                    'post_id' => $this->id,
                    'file_path' => $path,
                ];
            } catch (Aws\S3\Exception\S3Exception $e) {
                // エラーが発生した場合、詳細をログに記録
                Log::error($e->getMessage());
            }
        }

        return $images;
    }

    public function deleteImagesFromS3($image_ids, $prefix = 'user')
    {
        $s3Client = $this->getS3Client();

        foreach ($image_ids as $image_id) {
            $image = Image::find($image_id);
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

    public function getReadCountAttribute()
    {
        return $this->reads->count();
    }
}
