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
    public function store(UserStoreRequest $request): UserResource
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return UserResource::make($user)->additional(['message' => 'User created successfully']);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('user-token-' . $user->id, ['role:' . $user->role]);

        return UserResource::make($user)->additional(['token' => $token->plainTextToken]);
    }

    public function show(): UserResource
    {
        $user = auth()->user();
        return UserResource::make($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = User::findOrFail(auth()->user()->id);
        $user->update($data);

        return UserResource::make($user)->additional(['message' => 'User updated successfully']);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
