<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        // Validate and create a new user
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return UserResource::make($user)
            ->additional(['message' => 'User created successfully']);
    }

    /**
     * Authenticate the user and return a token
     *
     * @param  UserLoginRequest $request
     * @return UserResource
     * @throws ValidationException
     */
    public function login(UserLoginRequest $request): UserResource
    {
        // Validate the login request and authenticate the user
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(
                ['email' => ['The provided credentials are incorrect.']]
            );
        }

        // Create the access token for the user
        $abilities = ['role:' . $user->role];
        $token = $user->createToken('auth-token', $abilities);

        return UserResource::make($user)
            ->additional(['token' => $token->plainTextToken]);
    }

    /**
     * Display the specified resource.
     *
     * @return UserResource
     */
    public function show(): UserResource
    {
        // Get the authenticated user
        $user = auth()->user();

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @return UserResource
     */
    public function update(UserUpdateRequest $request): UserResource
    {
        // Validate and update the authenticated user
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = User::findOrFail(auth()->user()->id);
        $user->update($data);

        return UserResource::make($user)
            ->additional(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the authenticated user's access token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
