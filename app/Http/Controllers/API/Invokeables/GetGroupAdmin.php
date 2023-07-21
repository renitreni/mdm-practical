<?php

namespace App\Http\Controllers\API\Invokeables;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupAdminResource;

class GetGroupAdmin extends Controller
{
    public function __invoke()
    {
        return GroupAdminResource::collection(User::isGroupAdmin()->get());
    }
}
