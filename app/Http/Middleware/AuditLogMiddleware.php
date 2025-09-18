<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle($request, Closure $next)
    {
        $before = null;
        $user = Auth::user();
        $resource = $request->route() ? $request->route()->getName() : $request->path();
        $action = $request->method();

        // Lưu trạng thái trước khi thay đổi cho PUT/PATCH/DELETE
        if (in_array($action, ['PUT', 'PATCH', 'DELETE'])) {
            $model = $this->getModelFromRequest($request);
            if ($model) $before = $model->toArray();
        }

        $response = $next($request);

        // Chỉ log khi là POST/PUT/PATCH/DELETE
        if (in_array($action, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $after = null;
            $model = $this->getModelFromRequest($request);
            if ($model) $after = $model->fresh()->toArray();

            AuditLog::create([
                'actor_id' => $user ? $user->id : null,
                'resource' => $resource,
                'action' => $action,
                'before' => $before,
                'after' => $after,
                'created_at' => now(),
                'ip' => $request->ip(),
            ]);
        }
        return $response;
    }

    protected function getModelFromRequest($request)
    {
        // Tùy vào route, lấy model phù hợp (ví dụ: users/{id}, departments/{id})
        $route = $request->route();
        if (!$route) return null;
        $params = $route->parameters();
        if (isset($params['id'])) {
            if (strpos($route->uri, 'users') !== false) {
                return \App\Models\User::find($params['id']);
            }
            if (strpos($route->uri, 'departments') !== false) {
                return \App\Models\Department::find($params['id']);
            }
        }
        return null;
    }
}
