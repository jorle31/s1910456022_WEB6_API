<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;

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
     * finds and returns a Subject based on it's id
     */
    public function subjectExists(int $id) : JsonResponse
    {
        $subject =  Subject::where('id', $id)->first();
        return $subject != null ?
            response()->json(true, 200) :
            response()->json(false, 200);
    }
}
