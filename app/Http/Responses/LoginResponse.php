<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\Election;
use App\Services\DashboardResolver;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * Delegates all post-login routing logic to DashboardResolver service.
     * See DashboardResolver for routing priority and decision logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        \Log::info('🔐 LoginResponse::toResponse() CALLED', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

        try {
            $redirect = app(DashboardResolver::class)->resolve($user);

            $targetUrl = $redirect->getTargetUrl();
            \Log::info('✅ LoginResponse: Will redirect to', [
                'user_id' => $user->id,
                'target_url' => $targetUrl,
            ]);

            return $redirect;
        } catch (\Exception $e) {
            \Log::error('❌ LoginResponse: DashboardResolver failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }
}

