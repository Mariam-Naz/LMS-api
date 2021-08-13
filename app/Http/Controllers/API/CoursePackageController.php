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

    /** Create a CoursePackage **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'package_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        $response = array();
        foreach($data['course_id'] as $course){
            $coursePackage = new CoursePackage();
            $coursePackage->course_id=$course;
            $coursePackage->package_id = $data['package_id'];
            $coursePackage->save();
            $coursePackage = new CoursePackageResource($coursePackage);
            array_push($response,['coursePackage' => $coursePackage]);
        }
        $responsePackage = Package::find($data['package_id']);
        return self::success("Course-Package is created Successfully", ['packageData' => $responsePackage, 'coursePackage'=>$response]);
    }

    /** show a specific CoursePackage **/
    public function show($id)
    {
        $package = CoursePackage::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Could not find the course-Package',
                'code' => 404
            ], 404);
        }
        return new CoursePackageResource($package);
    }

    /** Update the specified CoursePackage **/
    public function update(Request $request)
    {
        $data = $request->all();
        $response = array();
        $ids = CoursePackage::where(['package_id' => $data['package_id']])->pluck('id');
        // echo gettype($data['course_id']);die;
        $idCourseArray = array_combine($ids,$data['course_id']);

        foreach ($idCourseArray as $id=>$course) {
            // echo 'id:'.$id.'course:'.$course;die;
            $coursePackage = CoursePackage::where(['id'=>$id])->update(['course_id'=>$course]);
            $coursePackage = new CoursePackageResource($coursePackage);
            array_push($response, ['coursePackage' => $coursePackage]);
        }
        $responsePackage = Package::find($data['package_id']);
        return self::success("Course-Package is created Successfully", ['packageData' => $responsePackage, 'coursePackage' => $response]);
    }

    /** Remove the specified CoursePackage **/
    public function delete($id)
    {
        $package = CoursePackage::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Could not find the course-Package',
                'code' => 404
            ], 404);
        }

        if ($package->delete()) {
            return response()->json([
                'message' => 'Package deleted successsfully',
                'code' => 204
            ], 204);
        } else {
            return response()->json([
                'message' => 'Internal Error',
                'code' => 500,
            ], 500);
        }
    }

}
