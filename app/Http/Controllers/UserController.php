<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUsersRequest;
use App\Http\Resources\RoleListResource;
use App\Http\Resources\ShowUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');

    }

    public function index()
    {
        if (!Gate::authorize('viewAny', User::class)) {
            throw ValidationException::withMessages([
                'message' => "Sizda bu yerga kirish uchun ruxsat yo'q"
            ]);
        }else {
            $user = User::with('roles')->get();
            return UserResource::collection($user);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $user->load('roles');
        return $this->reply(new ShowUserResource($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsersRequest $request, string $id)
    {
        if (auth()->user()->hasPermissionTo('user:update')) {
            $user = User::find($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->nickname = $request->nickname;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->update();
            return $this->success('User updated successfully', $user);
        } else {
            return $this->error('You\'re not allowed to do this');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return "user_id = {$id} user deleted";
    }

    public function viewAnyRoles()
    {
        Gate::authorize('ViewRoles', User::class);
        $roles = Role::all();
        return RoleListResource::collection($roles);
    }

    public function assignRole(Request $request, $id)
    {
        if (!Gate::authorize('assignRole', User::class)) {
            return response()->json(["Sizda lavozim tayinlash huquqi mavjud emas"],);
        } else {
            $user = User::findOrFail($id);
            $user->assignRole($request->role);
            return $this->success('Userga role berildi', $user);
        }
    }

    public function removeRole(Request $request, $id)
    {
        Gate::authorize('removeRole', User::class);

        $user = User::findOrFail($id);
        $roleName = $request->input('role');

        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);

            if ($user->roles->isEmpty()) {
                return $this->success('Rol foydalanuvchidan olib tashlandi. Foydalanuvchida hech qanday roli qolmadi.');
            } else {
                return $this->success('Rol foydalanuvchidan olib tashlandi.');
            }
        } else {
            return $this->success('Foydalanuvchida bunday rol mavjud emas.');
        }
    }

}
