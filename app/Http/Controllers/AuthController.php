<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function login(Request $request)
     {
        $request->validate([
            'email'=>'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user)
        {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }
        if(!Hash::check($request->password,$user->password)){
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'=>$token
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        
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

  
}
