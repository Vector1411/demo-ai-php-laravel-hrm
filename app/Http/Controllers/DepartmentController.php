<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentStoreRequest;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        if ($request->has('name')) {
            $query->where('name', 'ilike', '%' . $request->name . '%');
        }
        $perPage = $request->input('per_page', 10);
        $departments = $query->paginate($perPage);
        return response()->json([
            'data' => $departments->items(),
            'meta' => [
                'total' => $departments->total(),
                'per_page' => $departments->perPage(),
                'current_page' => $departments->currentPage(),
            ]
        ]);
    }

    public function store(DepartmentStoreRequest $request)
    {
        $actor = $request->user();
        if (!in_array($actor->role, ['ADMIN', 'HR'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $department = Department::create($request->validated());
        return response()->json($department, 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        if (!$department) return response()->json(['message' => 'Not found'], 404);

        $actor = $request->user();
        if (!in_array($actor->role, ['ADMIN', 'HR'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->only(['name', 'parent_id', 'head_id']);
        $department->update($data);
        return response()->json($department);
    }

    public function destroy($id, Request $request)
    {
        $department = Department::find($id);
        if (!$department) return response()->json(['message' => 'Not found'], 404);

        $actor = $request->user();
        if (!in_array($actor->role, ['ADMIN', 'HR'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if ($department->users()->count() > 0) {
            return response()->json(['message' => 'Cannot delete department with employees'], 409);
        }
        $department->delete();
        return response()->json(null, 204);
    }
}
