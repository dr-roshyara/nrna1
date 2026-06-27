<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Force request headers to expect JSON
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // 2. Catch any redirect responses (e.g., from validation or auth failures)
        // and convert them to JSON instead of letting Inertia intercept
        if ($response instanceof RedirectResponse) {
            $errors = session()->get('errors')
                ? session()->get('errors')->getBag('default')->getMessages()
                : [];

            return response()->json([
                'message' => 'Request resulted in a redirect (likely validation or authorization failure).',
                'errors' => $errors,
                'redirect_target' => $response->getTargetUrl(),
            ], 422);
        }

        return $response;
    }
}
