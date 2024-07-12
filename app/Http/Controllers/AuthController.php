<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $user = User::where('nickname', $request->nickname)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => "Taxallus yoki parol xato kiritildi! "
            ]);
        }
        $token = $user->createToken($request->nickname)->plainTextToken;
        return $this->success('Token created',
            ['token' => $user->createToken($request->nickname)->plainTextToken]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'password' => $request->password
        ]);
//        $user->assignRole('worker');
        auth()->login($user);
        return $this->success('You registered successfully',
            ['token' => $user->createToken($request->nickname)->plainTextToken]
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return 'User logged out';
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
