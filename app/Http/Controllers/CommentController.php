<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * finds and returns a comment based on it's id
     */
    public function getSpecificCommentById(string $id) : Comment {
        $comment = Comment::where('id', $id)
            ->first();
        return $comment;
    }

    /**
     * returns 200 if comment could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $comment = Comment::create($request->all());
            DB::commit();
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving Comment failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if comment updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $comment = Comment::where('id', $id)->first();
            if ($comment != null) {
                $comment->update($request->all());
                $comment->save();
            }
            DB::commit();
            $comment1 = Comment::where('id', $id)->first();
            // return a vaild http response
            return response()->json($comment1, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Comment failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if comment was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $comment = Comment::where('id', $id)->first();
        if ($comment != null) {
            $comment->delete();
        }
        else
            throw new \Exception("Comment couldn't be deleted - it does not exist");
        return response()->json('Comment (' . $id . ') successfully deleted', 200);
    }
}
