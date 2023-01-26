<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

// use Illuminate\Database\Eloquent\Model\Post;



class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'post_id',
        'comment'
    ];

     public function posts()
    {
        return $this->belongsTo(Post::class,'post_id');
    }
}
