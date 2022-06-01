<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeslotController extends Controller
{
    /**
     * returns a status of 200 and all timeslots of user if successful
     */
    public function getAllPendingTimeslotAgreementsOfTutor(int $id) : JsonResponse {
        $timeslots = Timeslot::whereHas('service', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('timeslotAgreement', function ($query) {
            $query->where('accepted', '=', false);
        })->with(['service.images', 'timeslotAgreement.user'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($timeslots, 200);
    }

    /**
     * returns a status of 200 and all timeslots of user if successful
     */
    public function getAllAcceptedTimeslotAgreementsOfTutor(int $id) : JsonResponse {
        $timeslots = Timeslot::whereHas('service', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('timeslotAgreement', function ($query) {
            $query->where('accepted', '=', true);
        })->with(['service.images', 'timeslotAgreement.user'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($timeslots, 200);
    }

    /**
     * returns a status of 200 and all services if successful
     */
    public function getAllAcceptedTimeslotAgreementsOfStudent(int $id) : JsonResponse {
        $timeslots = Timeslot::whereHas('timeslotAgreement', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('timeslotAgreement', function ($query) {
            $query->where('accepted', '=', true);
        })->with(['service.user', 'service.images', 'timeslotAgreement'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($timeslots, 200);
    }

    /**
     * returns a status of 200 and all services if successful
     */
    public function getAllPendingTimeslotAgreementsOfStudent(int $id) : JsonResponse {
        $timeslots = Timeslot::whereHas('timeslotAgreement', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('timeslotAgreement', function ($query) {
            $query->where('accepted', '=', false);
        })->with(['service.user', 'service.images', 'timeslotAgreement'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($timeslots, 200);
    }

    /**
     * finds and returns a User based on their id
     */
    public function getSpecificTimeslotById(string $id) : Timeslot {
        $timeslot = Timeslot::where('id', $id)
            ->with(['timeslotAgreement'])
            ->first();
        return $timeslot;
    }

    /**
     * returns 200 if timeslot could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslot = Timeslot::create($request->all());
            DB::commit();
            return response()->json($timeslot, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving Timeslot failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if timeslot updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslot = Timeslot::where('id', $id)->first();
            if ($timeslot != null) {
                $timeslot->update($request->all());
                $timeslot->save();
            }
            DB::commit();
            $timeslot1 = Timeslot::where('id', $id)->first();
            // return a valid http response
            return response()->json($timeslot1, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Timeslot failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if timeslot was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $timeslot = Timeslot::where('id', $id)->first();
        if ($timeslot != null) {
            $timeslot->delete();
        }
        else
            throw new \Exception("Timeslot couldn't be deleted - it does not exist");
        return response()->json('Timeslot (' . $id . ') successfully deleted', 200);
    }
}
