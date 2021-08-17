<?php

namespace App\Http\Controllers\API;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class WalletController extends Controller
{

    /** Display a listing of wallets **/
    public function index()
    {
        $wallet = Wallet::all()->sortBy('id');
        return WalletResource::collection($wallet);
    }

    /** Create a Wallet. **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
            'balance' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $wallet = new Wallet($data);
        $wallet->save();
        $wallet = new WalletResource($wallet);
        $response = ['wallet' => $wallet];
        return self::success("Wallet is created Successfully", ['data' => $response]);
    }

    /** show a specific wallet **/
    public function show($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json([
                'message' => 'Could not find the account',
                'code' => 404
            ], 404);
        }
        return new WalletResource($wallet);
    }

    /** Update the specified Wallet.**/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json([
                'message' => 'Could not find the Wallet',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($wallet->update($data)) {
            $wallet = new WalletResource($wallet);
            $response = ['wallet' => $wallet];
            return self::success("Wallet is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified wallet.**/
    public function destroy($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json([
                'message' => 'Could not find the account',
                'code' => 404
            ], 404);
        }

        if ($wallet->delete()) {
            return response()->json(null, 204);
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

        if (array_key_exists('balance', $data)) {
            $rules['balance'] = 'required|string';
        }
        if (array_key_exists('user_id', $data)) {
            $rules['user_id'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
