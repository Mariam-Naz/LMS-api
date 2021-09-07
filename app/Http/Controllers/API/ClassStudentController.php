<?php

namespace App\Http\Controllers\API;

use App\Models\ClassStudent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassStudentResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ClassStudentController extends Controller
{

    /** Display a listing of ClassStudents **/
    public function index()
    {
        $classStudent = ClassStudent::all()->sortBy('id');
        return ClassStudentResource::collection($classStudent);
    }

    /** Create a ClassStudent **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'class_id' => 'required|integer',
            'student_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $classStudent = new ClassStudent($data);
        $classStudent->save();
        $classStudent = new ClassStudentResource($classStudent);
        $response = ['ClassStudent' => $classStudent];
        return self::success("ClassStudent is created Successfully", ['data' => $response]);
    }

    /** show a specific ClassStudent **/
    public function show($id)
    {
        $classStudent = ClassStudent::find($id);
        if (!$classStudent) {
            return response()->json([
                'message' => 'Could not find the ClassStudent',
                'code' => 404
            ], 404);
        }
        return new ClassStudentResource($classStudent);
    }

    /** Update the specified ClassStudent **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $classStudent = ClassStudent::find($id);

        if (!$classStudent) {
            return response()->json([
                'message' => 'Could not find the ClassStudent',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($classStudent->update($data)) {
            $classStudent = new ClassStudentResource($classStudent);
            $response = ['ClassStudent' => $classStudent];
            return self::success("ClassStudent is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified ClassStudent **/
    public function delete($id)
    {
        $classStudent = ClassStudent::find($id);

        if (!$classStudent) {
            return response()->json([
                'message' => 'Could not find the ClassStudent',
                'code' => 404
            ], 404);
        }

        if ($classStudent->delete()) {
            return response()->json([
                'message' => 'ClassStudent deleted successsfully',
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

        if (array_key_exists('class_id', $data)) {
            $rules['class_id'] = 'required|integer';
        }
        if (array_key_exists('student_id', $data)) {
            $rules['student_id'] = 'required|integer';
        }
        if (array_key_exists('status', $data)) {
            $rules['status'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
