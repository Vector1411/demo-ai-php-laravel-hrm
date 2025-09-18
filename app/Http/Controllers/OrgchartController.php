<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

class OrgchartController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();
        $rootDeptId = $request->query('root_department_id');
        $rootUserId = $request->query('root_user_id');

        if ($actor->role === 'HR' || $actor->role === 'ADMIN') {
            // Xem toàn bộ cây
            if ($rootDeptId) {
                $root = Department::find($rootDeptId);
                if (!$root) return response()->json(['message' => 'Not found'], 404);
                return response()->json($this->buildDeptTree($root));
            }
            if ($rootUserId) {
                $user = User::find($rootUserId);
                if (!$user) return response()->json(['message' => 'Not found'], 404);
                return response()->json($this->buildUserNode($user));
            }
            // Mặc định: trả về toàn bộ cây gốc
            $roots = Department::whereNull('parent_id')->get();
            return response()->json($roots->map(fn($d) => $this->buildDeptTree($d)));
        }

        if ($actor->role === 'MANAGER') {
            $dept = $actor->department;
            if (!$dept) return response()->json(['message' => 'No department'], 403);
            return response()->json($this->buildDeptTree($dept));
        }

        // EMPLOYEE chỉ xem bản thân
        return response()->json($this->buildUserNode($actor));
    }

    protected function buildDeptTree($dept)
    {
        return [
            'id' => $dept->id,
            'name' => $dept->name,
            'type' => 'department',
            'children' => array_merge(
                $dept->users()->get()->map(fn($u) => $this->buildUserNode($u))->toArray(),
                $dept->children()->get()->map(fn($c) => $this->buildDeptTree($c))->toArray()
            )
        ];
    }

    protected function buildUserNode($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->full_name,
            'type' => 'user',
            'children' => []
        ];
    }
}
