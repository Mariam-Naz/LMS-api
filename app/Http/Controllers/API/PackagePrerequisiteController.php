<?php

namespace App\Http\Controllers\API;

use App\Models\PackagePrerequisite;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackagePrerequisiteResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PackagePrerequisiteController extends Controller
{

    /** Display a listing of PackagePrerequisites **/
    public function index()
    {
        $packagePrerequisite = PackagePrerequisite::all()->sortBy('id');
        return PackagePrerequisiteResource::collection($packagePrerequisite);
    }

    /** Create a PackagePrerequisite **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'package_id' => 'required|integer',
            'pre_package_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        $packagePrerequisite = new PackagePrerequisite($data);
        $packagePrerequisite->save();
        $packagePrerequisite = new PackagePrerequisiteResource($packagePrerequisite);
        $response = ['packagePrerequisite' => $packagePrerequisite];
        return self::success("Package Prerequisite is created Successfully", ['data' => $response]);
    }

    /** show a specific PackagePrerequisite **/
    public function show($id)
    {
        $packagePrerequisite = PackagePrerequisite::find($id);

        if (!$packagePrerequisite) {
            return response()->json([
                'message' => 'Could not find the Package Prerequisite',
                'code' => 404
            ], 404);
        }
        return new PackagePrerequisiteResource($packagePrerequisite);
    }

    /** Update the specified PackagePrerequisite **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $packagePrerequisite = PackagePrerequisite::find($id);

        if (!$packagePrerequisite) {
            return response()->json([
                'message' => 'Could not find the Package Prerequisite',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($packagePrerequisite->update($data)) {
            $packagePrerequisite = new PackagePrerequisiteResource($packagePrerequisite);
            $response = ['packagePrerequisite' => $packagePrerequisite];
            return self::success("Package Prerequisite is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified PackagePrerequisite **/
    public function delete($id)
    {
        $packagePrerequisite = PackagePrerequisite::find($id);

        if (!$packagePrerequisite) {
            return response()->json([
                'message' => 'Could not find the Package Prerequisite',
                'code' => 404
            ], 404);
        }

        if ($packagePrerequisite->delete()) {
            return response()->json([
                'message' => 'Package Prerequisite deleted successsfully',
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

        if (array_key_exists('package_id', $data)) {
            $rules['package_id'] = 'required|integer';
        }
        if (array_key_exists('pre_package_id', $data)) {
            $rules['pre_package_id'] = 'required|integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
