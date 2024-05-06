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

    public function store(UserStoreRequest $request): UserResource
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = $this->model->create($data);
        return UserResource::make($user);
    }

    public function login(UserLoginRequest $request): UserResource
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

        $user = $this->model->find(auth()->user()->id);
        $user->update($data);
        return UserResource::make($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
