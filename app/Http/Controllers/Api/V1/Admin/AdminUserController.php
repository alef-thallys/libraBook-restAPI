<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminUserCollection;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminUserController extends Controller
{
    /**
     * Get a list of all users.
     *
     * @return AdminUserCollection
     *
     * @throws NotFoundHttpException If there are no users to show
     */
    public function index(): AdminUserCollection
    {
        $users = User::paginate(20);

        if ($users->isEmpty()) {
            throw new NotFoundHttpException('No users to show');
        }

        return AdminUserCollection::make($users);
    }

    /**
     * Get a single user by ID.
     *
     * @param int $id The ID of the user to fetch
     *
     * @return AdminUserResource
     *
     * @throws NotFoundHttpException If the user was not found
     */
    public function show(int $id): AdminUserResource
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return AdminUserResource::make($user);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id The ID of the user to delete
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException If the user was not found
     * @throws NotFoundHttpException If the user is currently authenticated
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);
        $currentUserId = auth()->user()->id;

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        if ($user->id === $currentUserId) {
            throw new NotFoundHttpException('Admin cannot delete himself');
        }

        if ($user->bookings->count() > 0) {
            throw new NotFoundHttpException('User has active bookings');
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
