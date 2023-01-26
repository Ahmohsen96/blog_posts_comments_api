<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;

// use Illuminate\Database\Eloquent\Model\Comment;


class Post extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'posts';
    protected $fillable = [
        'title',
        'content',
        'image',
        'author',
    ];

    // public function comments()
    // {
    //     return $this->belongsTo(Category::class, 'cat_id');
    // }

    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id');
    }
}
