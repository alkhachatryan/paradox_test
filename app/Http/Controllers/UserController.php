<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserIndexRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserLogoutRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(UserIndexRequest $request){
        return response($request->user(), 200);
    }

    public function register(UserRegisterRequest $request){
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60),
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
          'user' => $user,
          'token' => $token
        ], 201);
    }

    public function login(UserLoginRequest $request){
        $user = User::whereEmail($request->input('email'))
            ->first();

        if ($user) {
            if(! Hash::check($request->input('password'), $user->password))
                return response('Wrong password', 422);

            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            return response(['token' => $token], 200);
        } else {
            $response = 'User does not exist';

            return response($response, 422);
        }
    }

    public function logout(UserLogoutRequest $request){
        $token = $request->user()->token();

        $token->revoke();

        $response = 'You have been successfully logged out!';
        return response($response, 200);
    }
}
