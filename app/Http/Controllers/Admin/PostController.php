<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
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

    public function getTotalPost()
    {
        try {
            $posts = Post::count();
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

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:100', 'min:10', 'unique:posts'],
                'content' => ['required', 'string', 'max:1000', 'min:20'],
                'image' => ['required','image','mimes:jpg,png,jpeg','max:2048'],
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all(),
                ]);
            } else {
                $filename = "";
                if ($request->file('image')) {
                    $filename = $request->file('image')->store('posts', 'public');
                } else {
                    $filename = "null";
                }
                $result = Post::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $filename,
                ]);
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Post Add Successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Some Problem"
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $posts = Post::findOrFail($id);
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $posts = Post::findOrFail($request->id);
            $validation = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:100', 'min:10'],
                'content' => ['required', 'string', 'max:1000', 'min:10'],
                'image' => [ 'required','image','mimes:jpg,png,jpeg','max:2048'],

            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all(),

                ]);
            } else {
                $filename = "";
                $destination = public_path('storage\\' . $posts->image);
                if ($request->file('new_image')) {
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }
                    $filename = $request->file('new_image')->store('posts', 'public');
                } else {
                    $filename = $request->old_image;
                }
                $posts->title = $request->title;
                $posts->content = $request->content;
                $posts->image = $filename;
                $result = $posts->save();
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "Post Update Successfully",
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Some Problem",
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $posts = Post::findOrFail($id);
        $destination = public_path('storage\\' . $posts->image);

        if (File::exists($destination)) {
            File::delete($destination);
        }

        $result = $posts->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Post Delete Successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Some Problem",
            ]);
        }
    }

    public function search($search)
    {
        try {
            $posts = Post::where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
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
}
