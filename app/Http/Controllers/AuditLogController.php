<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();
        if (!in_array($actor->role, ['ADMIN', 'HR'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $logs = AuditLog::with('actor')->orderByDesc('created_at')->limit(1000)->get();

        if ($request->query('export') === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="audit_logs.csv"',
            ];
            return new StreamedResponse(function () use ($logs) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['id', 'actor', 'resource', 'action', 'before', 'after', 'created_at', 'ip']);
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->actor ? $log->actor->username : '',
                        $log->resource,
                        $log->action,
                        json_encode($log->before),
                        json_encode($log->after),
                        $log->created_at,
                        $log->ip
                    ]);
                }
                fclose($handle);
            }, 200, $headers);
        }

        return response()->json($logs);
    }
}
