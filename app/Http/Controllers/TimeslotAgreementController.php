<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Timeslot;
use App\Models\TimeslotAgreement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeslotAgreementController extends Controller
{

    /**
     * returns a status of 200 and all services if successful
     */
    public function getAllTimeslotAgreements() : JsonResponse {
        $timeslotagreements = TimeslotAgreement::with(['user', 'timeslot.service.subject'])->where('timeslot.service.user_id', 1)->get();
        return response()->json($timeslotagreements, 200);
    }

    /**
     * returns a status of 200 and all services if successful
     */
    public function getSpecificTimeslotAgreement(string $id) : TimeslotAgreement  {
        $timeslotagreement = TimeslotAgreement::where('timeslot_id', $id)
            ->first();
        return $timeslotagreement;
    }

    /**
     * returns a status of 200 and all services if successful
     */
    public function getAllTimeslotAgreementsOfUser(string $id) : JsonResponse {
        $timeslotagreements = TimeslotAgreement::where('user_id', $id)->where('accepted', true)->with('timeslot.service.subject')->orderBy('timeslot.date', 'DESC')->orderBy('timeslot.from', 'DESC')->get();
        return response()->json($timeslotagreements, 200);
    }

    /**
     * returns a status of 200 and all services if successful
     */
    public function getAllTimeslotAgreementsOfUserWithPending(string $id) : JsonResponse {
        $timeslotagreements = TimeslotAgreement::where('user_id', $id)->where('accepted', false)->with('timeslot.service.subject')->get();
        return response()->json($timeslotagreements, 200);
    }

    /**
     * returns 200 if User could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslotAgreement = TimeslotAgreement::create($request->all());
            DB::commit();
            return response()->json($timeslotAgreement, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving TimeslotAgreement failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if User updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslotAgreement = TimeslotAgreement::where('id', $id)->first();
            if ($timeslotAgreement != null) {
                $timeslotAgreement->update($request->all());
                $timeslotAgreement->save();
            }
            DB::commit();
            $timeslotAgreement = TimeslotAgreement::where('id', $id)->first();
            // return a valid http response
            return response()->json($timeslotAgreement, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating TimeslotAgreement failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if Service was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $timeslotAgreement = TimeslotAgreement::where('id', $id)->first();
        if ($timeslotAgreement != null) {
            $timeslotAgreement->delete();
        }
        else
            throw new \Exception("TimeslotAgreement couldn't be deleted - it does not exist");
        return response()->json('TimeslotAgreement (' . $id . ') successfully deleted', 200);
    }
}
