<?php

namespace App\Http\Controllers;

use App\Models\TimeslotAgreement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeslotAgreementController extends Controller
{

    /**
     * returns a timeslotAgreement if successful
     */
    public function getSpecificTimeslotAgreementByTimeslotId(string $id) : TimeslotAgreement  {
        $timeslotagreement = TimeslotAgreement::where('timeslot_id', $id)
            ->first();
        return $timeslotagreement;
    }

    /**
     * returns 200 if timeslotAgreement could be created successfully, throws excpetion if not
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
     * returns 200 if timeslotAgreement updated successfully, throws excpetion if not
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
     * returns 200 if timeslotAgreement was deleted successfully, throws excpetion if not
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
