<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    // get JWT via credentials
    public function login() {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }


    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'successfully logged out']);
    }

    public function me(){
        return response()->json(auth()->user());
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }
}
