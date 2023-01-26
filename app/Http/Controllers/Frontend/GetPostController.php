<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class GetPostController extends Controller
{
    // all posts
    public function index()
    {
        try {
            $posts = Post::orderBy('id', 'desc')->with('comments')->get();
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }




    public function getPostById($id)
    {
        try {
            $posts = Post::with('comments')->findOrFail($id);
            $posts->save();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function searchPost($search)
    {
        try {
            $posts = Post::with('comments')->where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
