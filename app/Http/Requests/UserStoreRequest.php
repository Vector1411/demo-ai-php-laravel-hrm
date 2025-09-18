<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        // ...RBAC kiểm tra quyền...
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'full_name' => 'required|string',
            'role' => 'required|in:ADMIN,HR,MANAGER,EMPLOYEE',
            'department_id' => 'nullable|exists:departments,id',
        ];
    }
}
