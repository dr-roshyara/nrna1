<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Organisation;
use App\Services\TenantContext;

class IdentifyTenantFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        // Try multiple header variations (case-insensitive + aliases)
        // Laravel's header() helper is case-insensitive, but adding fallbacks for safety
        $organisationId = $request->header('X-Organisation-ID')
                       ?? $request->header('x-organisation-id')
                       ?? $request->header('X-Tenant-Id')
                       ?? $request->header('x-tenant-id');

        if (!$organisationId) {
            return response()->json([
                'error' => 'X-Organisation-ID header is required for API requests.',
                'code' => 'MISSING_TENANT_CONTEXT',
            ], 400);
        }

        // Fetch the organisation from the database
        $organisation = Organisation::find($organisationId);

        if (!$organisation) {
            return response()->json([
                'error' => 'Organisation context not found.',
                'code' => 'INVALID_ORGANISATION',
            ], 404);
        }

        // Verify authenticated user belongs to this organisation
        if (!$request->user()->organisations()->where('organisation_id', $organisationId)->exists()) {
            return response()->json([
                'error' => 'You do not have access to this organisation.',
                'code' => 'UNAUTHORIZED_TENANT_ACCESS',
            ], 403);
        }

        // Set the tenant context globally using static method
        TenantContext::set($organisationId);

        // Also store in session for compatibility with existing code
        session(['current_organisation_id' => $organisationId]);

        return $next($request);
    }
}
