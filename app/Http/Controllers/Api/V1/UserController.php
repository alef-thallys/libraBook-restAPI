<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request The validation request
     *
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
       
        $user = User::create($data);

        return UserResource::make($user)->additional(['message' => 'User created successfully']);
    }

    /**
     * Authenticate a user and return the user and token if successful.
     *
     * @param UserLoginRequest $request The validation request.
     *
     * @return UserResource
     */
    public function login(UserLoginRequest $request): UserResource
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            
            Log::info('User logged in.', ['user_id' => $user->id]);
            
            $token = $user->createToken('user-token-' . $user->id, ['role:' . $user->role],
                now()->addHour())->plainTextToken;

            return UserResource::make($user)->additional(['token' => $token]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Logout a user and revoke their token.
     *
     * @param Request $request The request object.
     *
     * @return JsonResponse A JSON response with a success message.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
