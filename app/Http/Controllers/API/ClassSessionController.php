<?php

namespace App\Http\Controllers\API;

use App\Models\ClassSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassSessionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ClassSessionController extends Controller
{

    /** Display a listing of ClassSessions **/
    public function index()
    {
        $classSession = ClassSession::all()->sortBy('id');
        return ClassSessionResource::collection($classSession);
    }

    /** Create a ClassSession **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'initiator_id' => 'required|integer',
            'schedule_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $classSession = new ClassSession($data);
        $classSession->save();
        $classSession = new ClassSessionResource($classSession);
        $response = ['ClassSession' => $classSession];
        return self::success("ClassSession is created Successfully", ['data' => $response]);
    }

    /** show a specific ClassSession **/
    public function show($id)
    {
        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'message' => 'Could not find the ClassSession',
                'code' => 404
            ], 404);
        }
        return new ClassSessionResource($classSession);
    }

    /** Update the specified ClassSession **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'message' => 'Could not find the ClassSession',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($classSession->update($data)) {
            $classSession = new ClassSessionResource($classSession);
            $response = ['ClassSession' => $classSession];
            return self::success("ClassSession is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified ClassSession **/
    public function delete($id)
    {
        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'message' => 'Could not find the ClassSession',
                'code' => 404
            ], 404);
        }

        if ($classSession->delete()) {
            return response()->json([
                'message' => 'ClassSession deleted successsfully',
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

        if (array_key_exists('initiator_id', $data)) {
            $rules['initiator_id'] = 'required|integer';
        }
        if (array_key_exists('schedule_id', $data)) {
            $rules['schedule_id'] = 'required|integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
