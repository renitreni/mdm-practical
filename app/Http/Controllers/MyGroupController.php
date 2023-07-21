<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use Illuminate\Http\Request;

class MyGroupController extends Controller
{
    public function index()
    {
        return GroupMember::where('user_id', auth()->id())->with('group')->first();
    }
}
