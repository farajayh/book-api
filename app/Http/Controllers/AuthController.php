<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except'=>['login', 'register']]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required|alpha|between:2, 100',
            'last_name' => 'required|alpha|between:2, 100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|string|min:6',
        ]);

        if($validation->fails()){
            return response()->json($validation->errors(), 400);  
        }

        $user = User::create(array_merge(
            $validation->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'

        ]);

        if($validation->fails()){
            return response()->json($validation->errors(), 400);  
        }

        $token_validity = (24*60);

        $this->guard()->factory()->setTTL($token_validity);

        if(!$token = $this->guard()->attempt($validation->validated())){
            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json(['message' => 'You have been logged out'], 200);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL()*60
        ], 200);
    }

}