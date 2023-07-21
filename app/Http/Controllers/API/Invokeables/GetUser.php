<?php

namespace App\Http\Controllers\API\Invokeables;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class GetUser extends Controller
{
    public function __invoke()
    {
        return UserResource::collection(User::query()->whereDoesntHave('groupMember')->isUser()->get());
    }
}
