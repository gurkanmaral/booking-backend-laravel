<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function login(LoginRequest $request)
     {
       

        // $user = User::where('email',$request->email)->first();

        // if(!$user)
        // {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect']
        //     ]);
        // }
        // if(!Hash::check($request->password,$user->password)){
            
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect']
        //     ]);
        // }
        $request->authenticate();

        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'=>$token
        ]);
    }


    public function register(CreateUserRequest $request)
    {
    
        
        $user = User::create([
            'name'=>$request->name,
            'email'=> $request->email,
            'password'=>Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message'=>'User successfully registered',
            'token'=> $token,
        ],201);
    }

    public function logout(Request $request)
    { 
      
    
     $request->user()->tokens()->delete();
    
        return response()->json(['message' => 'Successfully logged out']);
    }

  
}
