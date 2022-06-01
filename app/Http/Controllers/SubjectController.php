<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * returns a status of 200 and all Subjects if successful
     */
    public function getSubjects() : JsonResponse {
        $Subjects = Subject::with(['services'])->get();
        return response()->json($Subjects, 200);
    }

    /**
     * finds and returns a Subject based on it's id
     */
    public function getSpecificSubjectsById(string $id) : Subject {
        $Subject = Subject::where('id', $id)
            ->with(['services'])
            ->first();
        return $Subject;
    }

    /**
     * returns 200 if subject could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $subject = Subject::create($request->all());
            DB::commit();
            return response()->json($subject, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving Subject failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if User updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $subject = Subject::where('id', $id)->first();
            if ($subject != null) {
                $subject->update($request->all());
                $subject->save();
            }
            DB::commit();
            $subject = Subject::where('id', $id)->first();
            // return a valid http response
            return response()->json($subject, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Subject failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if Service was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $subject = Subject::where('id', $id)->first();
        if ($subject != null) {
            $subject->delete();
        }
        else
            throw new \Exception("Subject couldn't be deleted - it does not exist");
        return response()->json('Subject (' . $id . ') successfully deleted', 200);
    }
}
