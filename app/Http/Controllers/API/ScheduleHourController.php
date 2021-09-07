<?php

namespace App\Http\Controllers\API;

use App\Models\Schedulehour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleHourResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ScheduleHourController extends Controller
{

    /** Display a listing of ScheduleHours **/
    public function index()
    {
        $scheduleHour = ScheduleHour::all()->sortBy('id');
        return ScheduleHourResource::collection($scheduleHour);
    }

    /** Create a ScheduleHour **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'day_number' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $scheduleHour = new ScheduleHour($data);
        $scheduleHour->save();
        $scheduleHour = new ScheduleHourResource($scheduleHour);
        $response = ['scheduleHour' => $scheduleHour];
        return self::success("ScheduleHour is created Successfully", ['data' => $response]);
    }

    /** show a specific ScheduleHour **/
    public function show($id)
    {
        $scheduleHour = ScheduleHour::find($id);

        if (!$scheduleHour) {
            return response()->json([
                'message' => 'Could not find the Schedule',
                'code' => 404
            ], 404);
        }
        return new ScheduleHourResource($scheduleHour);
    }

    /** Update the specified ScheduleHour **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $scheduleHour = ScheduleHour::find($id);

        if (!$scheduleHour) {
            return response()->json([
                'message' => 'Could not find the ScheduleHour',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($scheduleHour->update($data)) {
            $scheduleHour = new ScheduleHourResource($scheduleHour);
            $response = ['scheduleHour' => $scheduleHour];
            return self::success("ScheduleHour is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified ScheduleHour **/
    public function delete($id)
    {
        $scheduleHour = ScheduleHour::find($id);

        if (!$scheduleHour) {
            return response()->json([
                'message' => 'Could not find the ScheduleHour',
                'code' => 404
            ], 404);
        }

        if ($scheduleHour->delete()) {
            return response()->json([
                'message' => 'ScheduleHour deleted successsfully',
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

        if (array_key_exists('day_number', $data)) {
            $rules['day_number'] = 'required|string';
        }
        if (array_key_exists('start_time', $data)) {
            $rules['start_time'] = 'required';
        }
        if (array_key_exists('end_time', $data)) {
            $rules['end_time'] = 'required';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
