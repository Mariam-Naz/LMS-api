<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class CourseController extends Controller
{

    /** Display a listing of Courses **/
    public function index()
    {
        $course = Course::all()->sortBy('id');
        return CourseResource::collection($course);
    }

    /** Create a Course **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'hours' => 'required|string',
            'price_per_hour' => 'required|string'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $course = new Course($data);
        $course->save();
        $course = new CourseResource($course);
        $response = ['course' => $course];
        return self::success("Course is created Successfully", ['data' => $response]);
    }

    /** show a specific Course **/
    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Could not find the Course',
                'code' => 404
            ], 404);
        }
        return new CourseResource($course);
    }

    /** Update the specified Course **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Could not find the company Wallet',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($course->update($data)) {
            $course = new CourseResource($course);
            $response = ['course' => $course];
            return self::success("Course is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Course **/
    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Could not find the Course',
                'code' => 404
            ], 404);
        }

        if ($course->delete()) {
            return response()->json([
                'message' => 'Course deleted successsfully',
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

        if (array_key_exists('name', $data)) {
            $rules['name'] = 'required|string';
        }
        if (array_key_exists('hours', $data)) {
            $rules['hours'] = 'required|string';
        }
        if (array_key_exists('price_per_hour', $data)) {
            $rules['price_per_hour'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
