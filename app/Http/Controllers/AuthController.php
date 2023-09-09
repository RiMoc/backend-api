<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    public function login(){
        $credentials = request(['username', 'password']);

        $token_validate = (60);
        $this->guard()->factory()->setTTL($token_validate);
        
        if(!$token = auth()->attempt($credentials)) return response()->json(['error' =>  'Unauthorized'], 401); 
        return $this->responseWithToken($token);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Password::default()]
        ]);

        if($validator->fails()) return response()->json(["status" => "error", "error" => $validator->errors()]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole('user');

        event(new Registered($user));

        return response()->json([
            "message_code" => "rg_succ",
            "message" => "register successfully !"
        ]);
    }
    
    public function user_data() { 
        return response()->json([
            "username" => auth()->user()->username,
            "id" => auth()->user()->id,
            "name" => auth()->user()->name,
            "email" => auth()->user()->email,
            "roles" => auth()->user()->roles()->pluck('name'),
            "permissions"=> auth()->user()->getPermissionsViaRoles()->pluck('name')
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
            "user" => [
                "username" => auth()->user()->username,
                "id" => auth()->user()->id,
                "name" => auth()->user()->name,
                "email" => auth()->user()->email,
            ],
            "roles" => auth()->user()->roles()->pluck('name'),
            "permissions"=> auth()->user()->getPermissionsViaRoles()->pluck('name')
        ]);
    }

    protected function guard() { return Auth::guard(); }
}
