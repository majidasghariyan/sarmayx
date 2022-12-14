<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\User;

class UserController extends Controller
{
    public function register(StoreUserRequest $request)
    {

        $user = User::create([
            'username' => $request->username,
            'role' => 'member',
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('Token Name')->accessToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ], 201);
    
    }

    public function index()
    {
        if (!Gate::allows('admin')) {
            return response_error('403 Forbidden', 403);
        }

        $users = UserResource::collection(User::paginate())->response()->getData(true);

        return response_success($users);

    }
    
}
