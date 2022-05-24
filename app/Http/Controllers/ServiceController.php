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
        $services = Service::with(['subject', 'timeslots', 'comments', 'images', 'user'])->get();
        return response()->json($services, 200);
    }

    /**
     * finds and returns a service based on it's id
     */
    public function getSpecificServiceById(string $id) : Service {
        $service = Service::where('id', $id)
            ->with(['subject', 'timeslots', 'images', 'comments', 'user'])
            ->first();
        return $service;
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

        //if (isset($request['is_coach']) && $request['is_coach'] === true) {

            DB::beginTransaction();
            try {
                $service = Service::create($request->all());

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

                DB::commit();
                return response()->json($service, 201);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json("saving Service failed:" . $e->getMessage(), 420);
            }
        /*}
        else{
            return response()->json("Users with the role of student do not possess the rights do publish a service offer.", 403);
        }*/
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
                $service->timeslots()->delete();
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
