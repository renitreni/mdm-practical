<?php

namespace App\Http\Controllers\API;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-users')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(
            User::with('voucher')
                ->whereHas('roles', fn($q) => $q->where('roles.name', UserRoleEnum::USER))
                ->get()
        );
    }
}
