<?php

namespace App\Http\Controllers\API;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PackageController extends Controller
{

    /** Display a listing of Packages **/
    public function index()
    {
        $package = Package::all()->sortBy('id');
        return PackageResource::collection($package);
    }

    /** Create a Package **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'price' => 'required|string',
            'discount_percentage' => 'required|string'
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        $package = new Package($data);
        $package->save();
        $package = new PackageResource($package);
        $response = ['package' => $package];
        return self::success("Package is created Successfully", ['data' => $response]);
    }

    /** show a specific Package **/
    public function show($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Could not find the Package',
                'code' => 404
            ], 404);
        }
        return new PackageResource($package);
    }

    /** Update the specified Package **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Could not find the package',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if($package->update($data)){
            $package = new PackageResource($package);
            $response = ['package' => $package];
            return self::success("Package is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Package **/
    public function delete($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Could not find the Package',
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

    /** Validator update **/
    private function validator_update($data)
    {
        $rules = array();

        if (array_key_exists('name', $data)) {
            $rules['name'] = 'required|string';
        }
        if (array_key_exists('price', $data)) {
            $rules['price'] = 'required|string';
        }
        if (array_key_exists('discount_percentage', $data)) {
            $rules['discount_percentage'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
