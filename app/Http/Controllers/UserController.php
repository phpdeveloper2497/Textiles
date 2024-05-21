<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUsersRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        if (auth()->user()->hasPermissionTo('user:viewAny')){
            $user = User::all();
            return $this->reply(UserResource::collection($user));

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return $this->reply(new UserResource($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsersRequest $request, string $id)
    {
        if (auth()->user()->hasPermissionTo('user:update')){
            $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->nickname = $request->nickname;
        $user->phone = $request->phone;
        $user->password = $request->password;
        $user->update();
        return $this->success('User updated successfully', $user);
        }else{
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
}
