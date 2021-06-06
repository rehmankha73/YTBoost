<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HasApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $users= User::query()->latest()->get();
        return $this->successApiResponse(
            $users->toArray(),
            200,
            'All Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\User\UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $validated_data = $request->validated();
        $user = User::query()->create($validated_data);
        return $this->successApiResponse(
            ['user' => $user],
        200,
        'New User created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        return $this->successApiResponse(
            ['user' => $user],
            200,
            ' User Found.'
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\User\UserUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $updated_data = $request->validated();
        $is_updated = $user->update($updated_data);

        if(!$is_updated) {
            return $this->errorApiResponse([],422,"Something went wrong");
        }

        return $this->successApiResponse(
            ['user' => User::query()->findOrFail($id)],
            200,
            'User updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
        return $this->successApiResponse(
            ['user' => $user],
            200,
            ' User deleted successfully.'
        );
    }
}
