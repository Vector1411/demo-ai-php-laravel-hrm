<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OrgchartController;
use App\Http\Controllers\AuditLogController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->post('change-password', [AuthController::class, 'changePassword']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('users/me', [UserController::class, 'me']);
    Route::apiResource('users', UserController::class)->except(['index']);
    Route::apiResource('departments', DepartmentController::class)->except(['show']);
    Route::get('orgchart', [OrgchartController::class, 'index']);
    Route::get('audit-logs', [AuditLogController::class, 'index']);
});
