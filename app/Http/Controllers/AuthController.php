<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:6',
            'role'=>'required|in:student,lecturer'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role
        ]);

        return response()->json(['message'=>'User registered', 'user'=>$user]);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages(['email'=>['Email atau password salah']]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'Login berhasil',
            'user'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logout berhasil']);
    }
}
