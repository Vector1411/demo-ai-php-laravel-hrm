<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
{
    public function authorize()
    {
        // ...RBAC kiểm tra quyền...
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:departments',
            'parent_id' => 'nullable|exists:departments,id',
            'head_id' => 'nullable|exists:users,id',
        ];
    }
}
