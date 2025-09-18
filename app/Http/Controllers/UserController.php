<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function show($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Not found'], 404);

        $actor = $request->user();
        if (!$this->canView($actor, $user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($user);
    }

    public function store(UserStoreRequest $request)
    {
        $actor = $request->user();
        if (!in_array($actor->role, ['ADMIN', 'HR'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Not found'], 404);

        $actor = $request->user();
        if (!$this->canEdit($actor, $user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->only(['full_name', 'email', 'department_id', 'role', 'is_active']);
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $request->validate(['email' => 'email|unique:users,email']);
        }
        $user->update($data);
        return response()->json($user);
    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Not found'], 404);

        $actor = $request->user();
        if (!$this->canEdit($actor, $user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user->delete();
        return response()->json(null, 204);
    }

    // RBAC matrix: ADMIN/HR full, MANAGER chỉ xem/sửa nhân viên phòng mình, EMPLOYEE chỉ xem/sửa bản thân
    protected function canView($actor, $user)
    {
        if (in_array($actor->role, ['ADMIN', 'HR'])) return true;
        if ($actor->role === 'MANAGER' && $actor->department_id === $user->department_id) return true;
        if ($actor->id === $user->id) return true;
        return false;
    }

    protected function canEdit($actor, $user)
    {
        if (in_array($actor->role, ['ADMIN', 'HR'])) return true;
        if ($actor->role === 'MANAGER' && $actor->department_id === $user->department_id && $user->role === 'EMPLOYEE') return true;
        if ($actor->id === $user->id) return true;
        return false;
    }
}
