<?php

namespace App\Http\Controllers\API;

use App\Models\CoursePackage;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CoursePackageResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class CoursePackageController extends Controller
{

    /** Display a listing of CoursePackages **/
    public function index()
    {
        $coursePackage = CoursePackage::all()->sortBy('id');
        return CoursePackageResource::collection($coursePackage);
    }

    /** Create a CoursePackage **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'package_id' => 'required|integer',
            'course_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        $coursePackage = new CoursePackage($data);
        $coursePackage->save();
        $coursePackage = new CoursePackageResource($coursePackage);
        $response=['coursePackage' => $coursePackage];
        return self::success("Course-Package is created Successfully", ['data'=>$response]);
    }

    /** show a specific CoursePackage **/
    public function show($id)
    {
        $coursePackage = CoursePackage::find($id);

        if (!$coursePackage) {
            return response()->json([
                'message' => 'Could not find the course-Package',
                'code' => 404
            ], 404);
        }
        return new CoursePackageResource($coursePackage);
    }

    /** Update the specified CoursePackage **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $coursePackage = CoursePackage::find($id);

        if (!$coursePackage) {
            return response()->json([
                'message' => 'Could not find the coursePackage',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($coursePackage->update($data)) {
            $coursePackage = new CoursePackageResource($coursePackage);
            $response = ['coursePackage' => $coursePackage];
            return self::success("coursePackage is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified CoursePackage **/
    public function delete($id)
    {
        $coursePackage = CoursePackage::find($id);

        if (!$coursePackage) {
            return response()->json([
                'message' => 'Could not find the course-Package',
                'code' => 404
            ], 404);
        }

        if ($coursePackage->delete()) {
            return response()->json([
                'message' => 'coursePackage deleted successsfully',
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

        if (array_key_exists('course_id', $data)) {
            $rules['course_id'] = 'required|integer';
        }
        if (array_key_exists('package_id', $data)) {
            $rules['package_id'] = 'required|integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }

}
