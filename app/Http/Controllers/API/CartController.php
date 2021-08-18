<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use App\Models\CoursePackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class CartController extends Controller
{

    /** Display a listing of Carts **/
    public function index()
    {
        $cart = Cart::all()->sortBy('id');
        return CartResource::collection($cart);
    }

    /** Create a Cart **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
            'is_paid' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($data['package_id'] != null) {
            $packageId = $data['package_id'];
            $data['courses'] = CoursePackage::where(['package_id' => $packageId])->pluck('course_id');
            // echo $coursesIds;die;
            // $coursesIds = implode(",", $coursesIds);
        } else {
            $coursesIds = $data['courses'];
            $data['courses'] = implode(",", $coursesIds);
        }
        $cart = new Cart($data);
        $cart->save();
        $cart = new CartResource($cart);
        $response = ['cart' => $cart];
        return self::success("Cart is created Successfully", ['data' => $response]);
    }

    /** show a specific Cart **/
    public function show($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'message' => 'Could not find the Cart',
                'code' => 404
            ], 404);
        }
        return new CartResource($cart);
    }

    /** Update the specified Cart **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'message' => 'Could not find the Cart',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        if(!empty($data['courses'])){
            $coursesIds = $data['courses'];
            $data['courses'] = implode(",", $coursesIds);
        }
        if ($cart->update($data)) {
            $cart = new CartResource($cart);
            $response = ['cart' => $cart];
            return self::success("Cart is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Cart **/
    public function delete($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'message' => 'Could not find the Cart',
                'code' => 404
            ], 404);
        }

        if ($cart->delete()) {
            return response()->json([
                'message' => 'Cart deleted successsfully',
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
            $rules['user_id'] = 'required|string';
        }
        if (array_key_exists('is_paid', $data)) {
            $rules['is_paid'] = 'required|string';
        }
        if (array_key_exists('payment_method', $data)) {
            $rules['payment_method'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
