<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserIndexRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserLogoutRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * The controller class to work with requests related with User model
 *
 * Implemented resource actions: index, register, login, logout
 */
class UserController extends Controller
{
    /**
     * Get logged in user data.
     *
     * @param UserIndexRequest $request
     *
     * @return JsonResponse
    */
    public function index(UserIndexRequest $request){
        return response()->json($request->user(), 200);
    }

    /**
     * Register a new user.
     *
     * @param UserRegisterRequest $request
     *
     * @return JsonResponse
    */
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

    /**
     * User login.
     *
     * Create and return the access token in case of right login and password.
     *
     * @param UserRegisterRequest $request
     * @return JsonResponse
    */
    public function login(UserLoginRequest $request){
        $user = User::whereEmail($request->input('email'))
            ->first();

        if ($user) {
            if(! Hash::check($request->input('password'), $user->password))
                return response()->json('Wrong password', 422);

            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            $response = 'User does not exist';

            return response()->json($response, 422);
        }
    }

    /**
     * Logout user.
     * Revoke the access token.
     *
     * @param UserRegisterRequest $request
     *
     * @return JsonResponse
    */
    public function logout(UserLogoutRequest $request){
        $token = $request->user()->token();

        $token->revoke();

        $response = 'You have been successfully logged out!';
        return response()->json($response, 200);
    }
}
