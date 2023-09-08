<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(){
        $credentials = request(['username', 'password']);

        $token_validate = (24*60);
        $this->guard()->factory()->setTTL($token_validate);
        
        if(!$token = auth()->attempt($credentials)) return response()->json(['error' =>  'Unauthorized'], 401); 
        return $this->responseWithToken($token);
    }
    
    public function user_data() { 
        return response()->json([
            "username" => auth()->user()->username,
            "id" => auth()->user()->id,
            "name" => auth()->user()->name,
            "email" => auth()->user()->email 
        ]); 
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message_code' => "lg_suc", "message" => "logout successfully !"]);
    }

    public function refresh(){ return $this->responseWithToken(auth()->refresh()); }

    protected function responseWithToken($token){
        return response()->json([
            "access_token" => $token, 
            "token_type" => "bearer", 
            "expires_in" => auth()->factory()->getTTL() * 60,
            "user" => auth()->user()
        ]);
    }

    protected function guard() { return Auth::guard(); }
}
