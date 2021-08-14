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
        $user = Auth::user();
        if ($user) {
            $user->update($data);
            $profile = User::where('id', $user->id)->first();
            $profile = new UserResource($profile);
            return self::success("User Profile", ['data' => $profile]);
        } else {
            return self::failure("No User(s) Exist");
        }
    }

}
