<?php

declare(strict_types=1);

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Requests\UserFilterRequest;
use App\Modules\Users\Requests\UserRequest;
use App\Modules\Users\Resources\UserResource;
use App\Modules\Users\Services\UserDataTableService;
use App\Modules\Users\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private UserDataTableService $userDataTableService,
    ) {}

    /**
     * Paginated list of users; pass only_trashed=1 for the trash view.
     */
    public function index(UserFilterRequest $request): AnonymousResourceCollection
    {
        $users = $this->userDataTableService->dataTable(
            $request->validated('search'),
            (int) $request->validated('per_page', 15),
            [
                'only_trashed' => $request->boolean('only_trashed'),
                'status' => $request->validated('status'),
                'roles' => $request->validated('roles'),
            ],
            $request->validated('sort_by') ?? [],
        );

        return UserResource::collection($users);
    }

    /**
     * Gets a single user by UUID.
     */
    public function show(string $uuid): Response
    {
        $user = $this->userService->findByUuidOrFail($uuid);

        return response([
            'data' => UserResource::make($user),
        ]);
    }

    /**
     * Creates a new user.
     */
    public function store(UserRequest $request): Response
    {
        $user = $this->userService->create($request->validated(), $request->user()->id);

        return response([
            'data' => UserResource::make($user),
            'message' => __('users::messages.created'),
        ], 201);
    }

    /**
     * Updates an existing user.
     */
    public function update(UserRequest $request, string $uuid): Response
    {
        $user = $this->userService->findByUuidOrFail($uuid);
        $user = $this->userService->update($user, $request->validated());

        return response([
            'data' => UserResource::make($user),
            'message' => __('users::messages.updated'),
        ]);
    }

    /**
     * Deletes a user.
     */
    public function destroy(Request $request, string $uuid): Response
    {
        $user = $this->userService->findByUuidOrFail($uuid);
        $this->userService->guardSelf($user, $request->user()->id);
        $this->userService->delete($user);

        return response([
            'message' => __('users::messages.deleted'),
        ]);
    }

    /**
     * Restores a deleted user.
     */
    public function restore(string $uuid): Response
    {
        $user = $this->userService->findTrashedByUuidOrFail($uuid);
        $this->userService->restore($user);

        return response([
            'message' => __('users::messages.restored'),
        ]);
    }
}
