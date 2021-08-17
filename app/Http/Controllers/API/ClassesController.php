<?php

namespace App\Http\Controllers\API;

use App\Models\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassesResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ClassesController extends Controller
{

    /** Display a listing of Classes **/
    public function index()
    {
        $classes = Classes::all()->sortBy('id');
        return ClassesResource::collection($classes);
    }

    /** Create a Class **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'teacher_id' => 'required|integer',
            'title' => 'required|string',
            'course_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $classes = new Classes($data);
        $classes->save();
        $classes = new ClassesResource($classes);
        $response = ['classes' => $classes];
        return self::success("Class is created Successfully", ['data' => $response]);
    }

    /** show a specific Classes **/
    public function show($id)
    {
        $classes = Classes::find($id);

        if (!$classes) {
            return response()->json([
                'message' => 'Could not find the class',
                'code' => 404
            ], 404);
        }
        return new ClassesResource($classes);
    }

    /** Update the specified Class **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $classes = Classes::find($id);

        if (!$classes) {
            return response()->json([
                'message' => 'Could not find the Class',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($classes->update($data)) {
            $classes = new ClassesResource($classes);
            $response = ['classes' => $classes];
            return self::success("Class is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Classes **/
    public function delete($id)
    {
        $classes = Classes::find($id);

        if (!$classes) {
            return response()->json([
                'message' => 'Could not find the Class',
                'code' => 404
            ], 404);
        }

        if ($classes->delete()) {
            return response()->json([
                'message' => 'Class deleted successsfully',
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

        if (array_key_exists('teacher_id', $data)) {
            $rules['teacher_id'] = 'required|integer';
        }
        if (array_key_exists('title', $data)) {
            $rules['title'] = 'required|string';
        }
        if (array_key_exists('course_id', $data)) {
            $rules['course_id'] = 'required|integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
