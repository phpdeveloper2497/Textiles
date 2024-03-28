<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return $this->reply(UserResource::collection($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::find($id);
        return $this->reply(new UserResource($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
