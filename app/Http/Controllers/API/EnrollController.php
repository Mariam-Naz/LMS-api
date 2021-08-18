<?php

namespace App\Http\Controllers\API;

use App\Models\Enroll;
use App\Models\EnrollCourse;
use App\Models\CoursePackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class EnrollController extends Controller
{

    /** Display a listing of Enrolls **/
    public function index()
    {
        $enrolls = Enroll::all();
        $response = array();
        foreach($enrolls as $enroll){
            $courseArray = array();
            foreach($enroll->enrollCourses as $showCourse){
                array_push($courseArray,$showCourse->course_id);
            }
            $enroll->courses = $courseArray;
            $enroll = new EnrollResource($enroll);
            array_push($response, ['enroll' => $enroll]);
        }
        return self::success("show enrolls", ['data' => $response]);
    }

    /** Create a Enroll **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'date_range' => 'required|string',
            'active' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $enroll = new Enroll($data);
        $enroll->save();
        if($enroll->package_id != null){
                $packageId = $enroll->package_id;
                $coursesIds = CoursePackage::where(['package_id'=>$packageId])->pluck('course_id');
            foreach($coursesIds as $course){
                $enrollCourse = new EnrollCourse();
                $enrollCourse->enroll_id = $enroll->id;
                $enrollCourse->course_id = $course;
                $enrollCourse->save();
            }
        }else{
            $coursesIds = $data['courses'];
            foreach ($coursesIds as $course) {
                $enrollCourse = new EnrollCourse();
                $enrollCourse->enroll_id = $enroll->id;
                $enrollCourse->course_id = $course;
                $enrollCourse->save();
            }
        }

       $enroll->courses = array($coursesIds);
        $enroll = new EnrollResource($enroll);
        $response = ['enroll' => $enroll];
        return self::success("Enroll is created Successfully", ['data' => $response]);
    }

    /** show a specific Enroll **/
    public function show($id)
    {
        $enroll = Enroll::find($id);
        $packageId = $enroll->package_id;
        $coursesIds = CoursePackage::where(['package_id' => $packageId])->pluck('course_id');
        $enroll->courses = array($coursesIds);
        if (!$enroll) {
            return response()->json([
                'message' => 'Could not find the Enroll',
                'code' => 404
            ], 404);
        }
        return new EnrollResource($enroll);
    }

    /** Update the specified Enroll **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $enroll = Enroll::find($id);

        if (!$enroll) {
            return response()->json([
                'message' => 'Could not find the Enroll',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($enroll->update($data)) {
                $packageId = $enroll->package_id;
                $coursesIds = CoursePackage::where(['package_id' => $packageId])->pluck('course_id');
                foreach ($coursesIds as $course) {
                    $enrollCourse = new EnrollCourse();
                    $enrollCourse->enroll_id = $enroll->id;
                    $enrollCourse->course_id = $course;
                    $enrollCourse->save();
                }
            $enroll->courses = array($coursesIds);
            $enroll = new EnrollResource($enroll);
            $response = ['enroll' => $enroll];
            return self::success("Enroll is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Enroll **/
    public function delete($id)
    {
        $enroll = Enroll::find($id);

        if (!$enroll) {
            return response()->json([
                'message' => 'Could not find the Enroll',
                'code' => 404
            ], 404);
        }

        if ($enroll->delete()) {
            return response()->json([
                'message' => 'Enroll deleted successsfully',
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

        if (array_key_exists('user_id', $data)) {
            $rules['user_id'] = 'required|integer';
        }
        if (array_key_exists('start_date', $data)) {
            $rules['start_date'] = 'required|date';
        }
        if (array_key_exists('end_date', $data)) {
            $rules['end_date'] = 'required|date';
        }
        if (array_key_exists('date_range', $data)) {
            $rules['date_range'] = 'required|string';
        }
        if (array_key_exists('active', $data)) {
            $rules['active'] = 'required|integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
