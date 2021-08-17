<?php

namespace App\Http\Controllers\API;

use App\Models\AccountDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountDetailResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class AccountDetailController extends Controller
{

    /** Display a listing of AccountDetails **/
    public function index()
    {
        $accountDetail = AccountDetail::all()->sortBy('id');
        return AccountDetailResource::collection($accountDetail);
    }

    /** Create a AccountDetail **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
            'account_type' => 'required|string',
            'payment_method' => 'required|string',
            'account_number' => 'required|string',
            'reference_name' => 'required|string',
            'reference_email' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $accountDetail = new AccountDetail($data);
        $accountDetail->save();
        $accountDetail = new AccountDetailResource($accountDetail);
        $response = ['accountDetail' => $accountDetail];
        return self::success("Account Detail is created Successfully", ['data' => $response]);
    }

    /** show a specific AccountDetail **/
    public function show($id)
    {
        $accountDetail = AccountDetail::find($id);

        if (!$accountDetail) {
            return response()->json([
                'message' => 'Could not find the Account Detail',
                'code' => 404
            ], 404);
        }
        return new AccountDetailResource($accountDetail);
    }

    /** Update the specified AccountDetail **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $accountDetail = AccountDetail::find($id);

        if (!$accountDetail) {
            return response()->json([
                'message' => 'Could not find the Account Detail',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($accountDetail->update($data)) {
            $accountDetail = new AccountDetailResource($accountDetail);
            $response = ['accountDetail' => $accountDetail];
            return self::success("Account Detail is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified AccountDetail **/
    public function delete($id)
    {
        $accountDetail = AccountDetail::find($id);

        if (!$accountDetail) {
            return response()->json([
                'message' => 'Could not find the Account Detail',
                'code' => 404
            ], 404);
        }

        if ($accountDetail->delete()) {
            return response()->json([
                'message' => 'Account Detail deleted successsfully',
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
        if (array_key_exists('account_type', $data)) {
            $rules['account_type'] = 'required|string';
        }
        if (array_key_exists('payment_method', $data)) {
            $rules['payment_method'] = 'required|string';
        }
        if (array_key_exists('account_number', $data)) {
            $rules['account_number'] = 'required|string';
        }
        if (array_key_exists('reference_name', $data)) {
            $rules['reference_name'] = 'required|string';
        }
        if (array_key_exists('reference_email', $data)) {
            $rules['reference_email'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
