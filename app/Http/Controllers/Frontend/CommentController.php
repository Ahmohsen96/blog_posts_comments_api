<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => ['required'],
            // 'post_id' => ['required'],
            'comment' => ['required']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => true,
                'message' => $validation->errors()->all(),
            ]);
        } else {
            $result = Comment::create([
                'post_id' => $id,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
            ]);
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Some Problem'
                ]);
            }
        }
    }

    public function getComments()
    {
        try {
            $comments = Comment::orderBy('id', 'desc')->with('posts')->get();
            return response()->json([
                'success' => true,
                'contects' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'contects' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $result = Comment::findOrFail($id)->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Comment Delete Successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Some Problem'
            ]);
        }
    }

}
