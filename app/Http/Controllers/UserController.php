<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * returns a status of 200 and all Users if successful
     */
    public function getUsers() : JsonResponse {
        $users = User::with(['services', 'timeslotAgreements', 'comments'])->get();
        return response()->json($users, 200);
    }

    /**
     * finds and returns a User based on their id
     */
    public function getSpecificUserById(string $id) : User {
        $user = User::where('id', $id)
            ->with(['services', 'timeslotAgreements', 'comments'])
            ->first();
        return $user;
    }

    /**
     * returns 200 if User could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $User = User::create($request->all());
            DB::commit();
            return response()->json($User, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving User failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if User updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $User = User::where('id', $id)->first();
            if ($User != null) {
                $User->update($request->all());
                $User->save();
            }
            DB::commit();
            $User1 = User::where('id', $id)->first();
            // return a vaild http response
            return response()->json($User1, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating User failed: " . $e->getMessage(), 420);
        }
    }
}
