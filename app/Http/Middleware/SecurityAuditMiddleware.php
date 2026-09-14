<?php

namespace App\Http\Middleware;

use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityAuditMiddleware
{
    /**
     * Handle an incoming request.
     * Logs deep security audits for sensitive operations and risk events with sanitized raw payload.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Determine if request should be logged (mutating requests, superadmin actions, or error statuses >= 400)
        $isMutating = in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true);
        $isSuperadminArea = str_starts_with($request->path(), 'superadmin');
        $isAuthAttempt = str_contains($request->path(), 'login') || str_contains($request->path(), 'logout') || str_contains($request->path(), 'password');
        $isClientOrServerError = $response->getStatusCode() >= 400;

        if ($isMutating || $isSuperadminArea || $isAuthAttempt || $isClientOrServerError) {
            $eventType = 'request_audit';
            $severity = 'low';

            if ($response->getStatusCode() === 403 || $response->getStatusCode() === 401) {
                $eventType = 'unauthorized_attempt';
                $severity = 'high';
            } elseif ($response->getStatusCode() >= 500) {
                $eventType = 'server_error_event';
                $severity = 'critical';
            } elseif (str_contains($request->path(), 'panic')) {
                $eventType = 'panic_button_event';
                $severity = 'critical';
            } elseif ($isSuperadminArea) {
                $eventType = 'superadmin_action';
                $severity = 'medium';
            } elseif ($isMutating) {
                $eventType = 'data_mutation';
                $severity = 'low';
            }

            // Extract request payload (query + body)
            $payload = array_merge($request->query(), $request->except(['_token']));

            try {
                SecurityLog::record(
                    eventType: $eventType,
                    severity: $severity,
                    endpoint: '/' . ltrim($request->path(), '/'),
                    method: $request->method(),
                    payload: $payload,
                    responseStatus: $response->getStatusCode(),
                    userId: auth()->check() ? auth()->id() : null,
                    ipAddress: $request->ip() ?? '127.0.0.1',
                    userAgent: $request->userAgent()
                );
            } catch (\Throwable $e) {
                // Failsafe: avoid breaking request cycle if logging fails
            }
        }

        return $response;
    }
}
