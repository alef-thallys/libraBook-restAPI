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
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $this->model->create($data);

        return response()->json([
            'message' => 'User registered successfully!'
        ], 201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->model->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(
                ['email' => ['The provided credentials are incorrect.']]
            );
        }

        $abilities = ['role:' . $user->role];
        $token = $user->createToken('auth-token', $abilities);

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function show(): UserResource
    {
        $user = auth()->user();
        return UserResource::make($user);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->model->find(auth()->user()->id);
        $user->update($data);
        return response()->json([
            'message' => 'Profile updated successfully!'
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful!'
        ], 200);
    }
}
