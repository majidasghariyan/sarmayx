<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {

        $info = $request->only(['username','password']);

        if(auth()->attempt($info))
        {
            $token = auth()->user()->createToken('Token Name')->accessToken;

            return response()->json([
                'user' => new UserResource(auth()->user()),
                'token' => $token
            ], 201);
        }  
        
        return response()->json([
          'Invalid login crednetials'  
        ], 401); 
    }
}
