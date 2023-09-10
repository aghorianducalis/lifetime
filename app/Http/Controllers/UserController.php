<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexUserRequest $request
     * @param \App\Services\UserService $service
     * @return \App\Http\Resources\UserCollection
     */
    public function index(IndexUserRequest $request, UserService $service)
    {
        $users = $service->getAllUsers();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @param \App\Services\UserService $service
     * @return \App\Http\Resources\UserResource
     */
    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->createUser($request->validated());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param string $userId
     * @param \App\Services\UserService $service
     * @return \App\Http\Resources\UserResource
     */
    public function show(string $userId, UserService $service)
    {
        $user = $service->getUserById($userId);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param string $userId
     * @param \App\Services\UserService $service
     * @return \App\Http\Resources\UserResource
     */
    public function update(UpdateUserRequest $request, string $userId, UserService $service)
    {
        $user = $service->updateUser($request->validated(), $userId);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $userId
     * @param \App\Services\UserService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $userId, UserService $service)
    {
        $result = $service->deleteUser($userId);

        return response()->json(['result' => $result]);
    }
}
