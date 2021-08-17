<?php

namespace App\Http\Controllers\API;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PaymentMethodController extends Controller
{

    /** Display a listing of PaymentMethods **/
    public function index()
    {
        $paymentMethod = PaymentMethod::all()->sortBy('id');
        return PaymentMethodResource::collection($paymentMethod);
    }

    /** Create a PaymentMethod **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $paymentMethod = new PaymentMethod($data);
        $paymentMethod->save();
        $paymentMethod = new PaymentMethodResource($paymentMethod);
        $response = ['paymentMethod' => $paymentMethod];
        return self::success("PaymentMethod is created Successfully", ['data' => $response]);
    }

    /** show a specific PaymentMethod **/
    public function show($id)
    {
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return response()->json([
                'message' => 'Could not find the paymentMethod',
                'code' => 404
            ], 404);
        }
        return new PaymentMethodResource($paymentMethod);
    }

    /** Update the specified PaymentMethod **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return response()->json([
                'message' => 'Could not find the company Wallet',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($paymentMethod->update($data)) {
            $paymentMethod = new PaymentMethodResource($paymentMethod);
            $response = ['paymentMethod' => $paymentMethod];
            return self::success("PaymentMethod is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified PaymentMethod **/
    public function delete($id)
    {
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return response()->json([
                'message' => 'Could not find the PaymentMethod',
                'code' => 404
            ], 404);
        }

        if ($paymentMethod->delete()) {
            return response()->json([
                'message' => 'PaymentMethod deleted successsfully',
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
        if (array_key_exists('type', $data)) {
            $rules['type'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
