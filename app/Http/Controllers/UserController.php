<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HasApiResponse;

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|min:3',
            'name' => 'nullable|string|min:3',
            'image' => 'nullable|string|min:3',
        ]);
        $user = User::query()->where('email', $data['email'])->first();
        if ($user) {
            return $this->successApiResponse(
                $user->toArray(),
                '200',
                'User Found Successfully'
            );
        } else {
            $newUser = User::query()->create([
                'name' => $data['name'] ?? '',
                'email' => $data['email'] ,
                'image' => $data['image'] ?? ''
            ]);
            return $this->successApiResponse(
                $newUser->toArray(),
                '200',
                'New User Created Successfully'
            );
        }
    }

//    public function index(): JsonResponse
//    {
//        $users= User::query()->latest()->get();
//        return $this->successApiResponse(
//            $users->toArray(),
//            200,
//            'All Users retrieved successfully.'
//        );
//    }
//
//    public function store(UserCreateRequest $request): JsonResponse
//    {
//        $validated_data = $request->validated();
//        $user = User::query()->create($validated_data);
//        return $this->successApiResponse(
//            ['user' => $user],
//        200,
//        'New User created successfully.'
//        );
//    }
//
//    public function show(int $id): JsonResponse
//    {
//        $user = User::query()->findOrFail($id);
//        return $this->successApiResponse(
//            ['user' => $user],
//            200,
//            ' User Found.'
//        );
//    }
//
//    public function update(UserUpdateRequest $request, int $id): JsonResponse
//    {
//        $user = User::query()->findOrFail($id);
//        $updated_data = $request->validated();
//        $is_updated = $user->update($updated_data);
//
//        if(!$is_updated) {
//            return $this->errorApiResponse([],422,"Something went wrong");
//        }
//
//        return $this->successApiResponse(
//            ['user' => User::query()->findOrFail($id)],
//            200,
//            'User updated successfully.'
//        );
//    }
//
//    public function destroy(int $id): JsonResponse
//    {
//        $user = User::query()->findOrFail($id);
//        $user->delete();
//        return $this->successApiResponse(
//            ['user' => $user],
//            200,
//            ' User deleted successfully.'
//        );
//    }
}
