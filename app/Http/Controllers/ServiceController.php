<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Service;
use App\Models\Timeslot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{

    /**
     * returns a status of 200 and all services if successful
     */
    public function index() : JsonResponse {
        $services = Service::with(['subject', 'timeslots.timeslotAgreement', 'comments', 'images', 'user'])->latest()->get();
        return response()->json($services, 200);
    }

    /**
     * returns a status of 200 and the 5 latest services if successful
     */
    public function getLatest() : JsonResponse {
        $services = Service::with(['subject', 'timeslots.timeslotAgreement', 'comments', 'images', 'user'])->latest()->take(5)->get();
        return response()->json($services, 200);
    }

    /**
     * finds and returns a service based on it's id
     */
    public function getSpecificServiceById(string $id) : Service {
        $service = Service::where('id', $id)
            ->with(['subject', 'timeslots.timeslotAgreement.user', 'images', 'comments', 'user'])
            ->first();
        return $service;
    }

    /**
     * finds and returns an array of services based on their user_id
     */
    public function getSpecificUsersServiceById(string $id) : JsonResponse {
        $services = Service::where('user_id', $id)->with(['subject', 'timeslots.timeslotAgreement', 'comments', 'images', 'user'])->latest()->get();
        return response()->json($services, 200);
    }

    /**
     * returns an array of services with a pending timeslot_agreement status
     */
    public function getServicesWithPending(string $id) : JsonResponse {
        $services = Service::where('user_id', $id)->whereHas('timeslots', function ($query) {
            $query->where('is_booked', '=', true);
        })->whereHas('timeslots.timeslotAgreement', function($query) {
            $query->where('accepted','=', false);
        })->with(['subject', 'timeslots' => function($q){
            $q->has('timeslotAgreement');
        }, 'comments', 'images', 'user', 'timeslots.timeslotAgreement.user'])->get();
        return response()->json($services, 200);
    }

    /**
     * returns an array of services with an accepted timeslot_agreement status
     */
    public function getServicesWithAccepted(string $id) : JsonResponse {
        $services = Service::where('user_id', $id)->whereHas('timeslots', function ($query) {
            $query->where('is_booked', '=', true);
        })->whereHas('timeslots.timeslotAgreement', function($query) {
            $query->where('accepted','=', true);
        })->with(['subject', 'timeslots' => function($q){
            $q->has('timeslotAgreement');
        }, 'comments', 'images', 'user', 'timeslots.timeslotAgreement.user'])->get();
        return response()->json($services, 200);
    }

    /**
     * returns 200 if Service was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $Service = Service::where('id', $id)->first();
        if ($Service != null) {
            $Service->delete();
        }
        else
            throw new \Exception("Service couldn't be deleted - it does not exist");
        return response()->json('Service (' . $id . ') successfully deleted', 200);
    }

    /**
     * returns 200 if Service created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        $request = $this->parseRequest($request);

        DB::beginTransaction();
        try {
            $service = Service::create($request->all());

            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    $image = Image::firstOrNew([
                        'url' => $img['url'],
                        'title' => $img['title']
                    ]);
                    $service->images()->save($image);
                }
            }
            if(isset($request['timeslots']) && is_array($request['timeslots'])){
                foreach ($request['timeslots'] as $timeslot){
                    $timeslots = Timeslot::firstOrNew([
                        'from' => $timeslot['from'],
                        'until' => $timeslot['until'],
                        'date' => $timeslot['date']
                    ]);
                    $service->timeslots()->save($timeslots);
                }
            }

            DB::commit();
            return response()->json($service, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving Service failed:" . $e->getMessage(), 420);
        }
    }

    private function parseRequest(Request $request) : Request {
        if (isset($request['date'])) {
            $date = new \DateTime($request->date);
            $request['date'] = $date;
        }
        if (isset($request['from'])) {
            $request['from'] = Carbon::createFromFormat('H:i:s', $request->from, "Europe/Vienna");
        }
        if (isset($request['until'])) {
            $request['until'] = Carbon::createFromFormat('H:i:s', $request->until, "Europe/Vienna");
        }
        return $request;
    }

    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $service = Service::with(['timeslots', 'images', 'user'])
                ->where('id', $id)->first();
            if ($service != null) {
                $request = $this->parseRequest($request);
                $service->update($request->all());
                //delete all old Timeslots
                $service->images()->delete();
                //delete all old Timeslots
                $timeslots = Timeslot::where('service_id', $service['id'])->where('is_booked', false);
                $timeslots->delete();
                // save images
                if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $img) {
                        $image = Image::firstOrNew([
                            'url' => $img['url'],
                            'title' => $img['title']
                        ]);
                        $service->images()->save($image);
                    }
                }
                // save Timeslots
                if(isset($request['timeslots']) && is_array($request['timeslots'])){
                    foreach ($request['timeslots'] as $timeslot){
                        $timeslots = Timeslot::firstOrNew([
                            'from' => $timeslot['from'],
                            'until' => $timeslot['until'],
                            'date' => $timeslot['date']
                        ]);
                        $service->timeslots()->save($timeslots);
                    }
                }
            }
            DB::commit();
            $c = Service::with(['timeslots', 'images', 'user'])
                ->where('id', $id)->first();
            // return a vaild http response
            return response()->json($c, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Service failed: " . $e->getMessage(), 420);
        }
    }
}
