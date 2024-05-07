<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminUserCollection;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminUserController extends Controller
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index(): AdminUserCollection
    {
        $users = $this->model->paginate(20);
        if ($users->isEmpty()) {
            throw new NotFoundHttpException('No users to show');
        }
        return AdminUserCollection::make($users);
    }

    public function show(int $id): AdminUserResource
    {
        try {
            $user = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('User not found');
        }
        return AdminUserResource::make($user);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $user = $this->model->findOrFail($id)->load('bookings');
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('User not found');
        }

        $currentUserId = auth()->user()->id;

        if ($user->id === $currentUserId) {
            throw new NotFoundHttpException('Admin cannot delete himself');
        } elseif ($user->bookings) {
            throw new NotFoundHttpException('User has active bookings');
        } elseif ($user->fines) {
            throw new NotFoundHttpException('User has active fines');
        }

        $user->delete();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'User deleted successfully!'
        ]);
    }
}
