<?php

namespace App\Http\Controllers\API;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignMember;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MemberResource;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-groups')->only('index');
        $this->middleware('can:delete-groups')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group = Group::with('owner', 'members.user')
            ->when(
                auth()->user()->roles->first()->name == UserRoleEnum::GROUP_ADMIN->value,
                function ($q) {
                    $q->where('owner_id', auth()->id());
                }
            )->get();
        return GroupResource::collection($group);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupStoreRequest $request)
    {
        Group::create([
            'group_name' => $request->get('group_name'),
            'owner_id' => $request->get('owner_id'),
        ]);

        return response()->json(['message' => 'success']);
    }

    public function update(Group $group, GroupUpdateRequest $request)
    {
        $group->update([
            'group_name' => $request->get('group_name'),
            'owner_id' => $request->get('owner_id'),
        ]);

        return response()->json(['message' => 'success']);
    }

    public function assignMember(AssignMember $request)
    {
        $input = $request->validated();

        GroupMember::create([
            'user_id' => $input['user_id'],
            'group_id' => $input['group_id'],
        ]);

        return MemberResource::collection(GroupMember::where('group_id', $input['group_id'])->get());
    }

    public function removeMember(GroupMember $groupMember)
    {
        $groupID = $groupMember->group_id;
        $groupMember->delete();

        return MemberResource::collection(GroupMember::where('group_id', $groupID)->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->members()->delete();
        $group->delete();

        return response()->json(['message' => 'success']);
    }
}
