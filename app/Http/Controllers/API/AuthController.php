<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\RegisterRequest;
use App\Notifications\WelcomeNotification;

class AuthController extends Controller
{
    public function authenticate(AuthenticateRequest $authenticateRequest)
    {
        if(Auth::attempt($authenticateRequest->only(['email', 'password']))){
            $user = auth()->user();

            return response()->json([
                'permissions' => $user->getPermissionsViaRoles()->pluck('name'),
                'role' => $user->getRoleNames()->first(),
                'token' => $user->createToken('token')->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Email & Password does not match with our record.',
        ], 401);
    }

    public function register(RegisterRequest $registerRequest)
    {
        $user = User::create([
            'name' => $registerRequest->get('name'),
            'email' => $registerRequest->get('email'),
            'password' => $registerRequest->get('password'),
        ]);

        $user->notify(new WelcomeNotification());

        return response()->json([
            'message' => 'Registration is Successful!',
        ]);
    }
}
