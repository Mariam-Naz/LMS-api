<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{

    /** Display a listing of Transactions **/
    public function index()
    {
        $transaction = Transaction::all()->sortBy('id');
        return TransactionResource::collection($transaction);
    }

    /** Create a Transaction **/
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
            'cart_id' => 'required|integer',
            'payment_method' => 'required|string',
            'amount' => 'required|string',
            'transaction_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $transaction = new Transaction($data);
        $transaction->save();
        $transaction = new TransactionResource($transaction);
        $response = ['Transaction' => $transaction];
        return self::success("Transaction is created Successfully", ['data' => $response]);
    }

    /** show a specific Transaction **/
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Could not find the Transaction',
                'code' => 404
            ], 404);
        }
        return new TransactionResource($transaction);
    }

    /** Update the specified Transaction **/
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Could not find the Transaction',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($transaction->update($data)) {
            $transaction = new TransactionResource($transaction);
            $response = ['Transaction' => $transaction];
            return self::success("Transaction is updated Successfully", ['data' => $response]);
        }
    }

    /** Remove the specified Transaction **/
    public function delete($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Could not find the Transaction',
                'code' => 404
            ], 404);
        }

        if ($transaction->delete()) {
            return response()->json([
                'message' => 'Transaction deleted successsfully',
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
        if (array_key_exists('cart_id', $data)) {
            $rules['cart_id'] = 'required|integer';
        }
        if (array_key_exists('payment_method', $data)) {
            $rules['payment_method'] = 'required|string';
        }
        if (array_key_exists('amount', $data)) {
            $rules['amount'] = 'required|string';
        }
        if (array_key_exists('transaction_type', $data)) {
            $rules['transaction_type'] = 'required|string';
        }
        return Validator::make(
            $data,
            $rules
        );
    }
}
