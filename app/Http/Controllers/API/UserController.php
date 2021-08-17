<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $user = User::where('email', $request->email)->where('id', '!=', 1)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Client password')->accessToken;
                $user = new UserResource($user);
                $response = ['token' => $token, 'user' => $user];

                return self::success('login successful', ['data' => $response]);
            } else {
                return self::failure('incorrect password');
            }
        } else {
            return self::failure('Email dose not exist');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);
        $data['remember_token'] = Str::random(10);
        $user = new User($data);
        $user->role_id = 2;
        $user->save();
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $user = new UserResource($user);
        $response = ['token' => $token, 'user' => $user];

        return self::success("Login Successful", ['data' => $response]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $getUser = Auth::user();
        $user = User::find($getUser->id);
        if (!$user) {
            return response()->json([
                'message' => 'Could not find the user',
                'code' => 404
            ], 404);
        }

        $validator = $this->validator_update($data);
        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        if ($user->update($data)) {
            $user = new UserResource($user);
            $response = ['user' => $user];
            return self::success("User Profile", ['data' => $response]);
        }
    }

    /** Validator update **/
    private function validator_update($data)
    {
        $rules = array();

        if (array_key_exists('name', $data)) {
            $rules['name'] = 'required|string';
        }
        if (array_key_exists('email', $data)) {
            $rules['email'] = 'required|string';
        }
        if (array_key_exists('role_id', $data)) {
            $rules['role_id'] = 'integer';
        }
        return Validator::make(
            $data,
            $rules
        );
    }

}
