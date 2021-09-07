<?php

namespace App\Http\Controllers\API;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{

    /** Display a listing of Schedules **/
    public function index()
    {
        $schedule = Schedule::all()->sortBy('id');
        return ScheduleResource::collection($schedule);
    }

    /** Create a Schedule **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'date_range' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $schedule = new Schedule($data);
        $schedule->save();
        $schedule = new ScheduleResource($schedule);
        $response = ['schedule' => $schedule];
        return self::success("Schedule is created Successfully", ['data' => $response]);
    }

    /** show a specific Schedule **/
    public function show($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Could not find the Schedule',
                'code' => 404
            ], 404);
        }
        return new ScheduleResource($schedule);
    }

    /** Update the specified Schedule **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Could not find the Schedule',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($schedule->update($data)) {
            $schedule = new ScheduleResource($schedule);
            $response = ['schedule' => $schedule];
            return self::success("Schedule is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Schedule **/
    public function delete($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Could not find the Schedule',
                'code' => 404
            ], 404);
        }

        if ($schedule->delete()) {
            return response()->json([
                'message' => 'Schedule deleted successsfully',
                'code' => 204
            ], 204);
        } else {
            return response()->json([
                'message' => 'Internal Error',
                'code' => 500,
            ], 500);
        }
    }

    /** Validator update **/
    private function validator_update($data)
    {
        $rules = array();

        if (array_key_exists('title', $data)) {
            $rules['title'] = 'required|string';
        }
        if (array_key_exists('date_range', $data)) {
            $rules['date_range'] = 'required|string';
        }
        if (array_key_exists('start_date', $data)) {
            $rules['start_date'] = 'required|date';
        }
        if (array_key_exists('end_date', $data)) {
            $rules['end_date'] = 'required|date';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
