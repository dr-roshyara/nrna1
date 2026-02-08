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
        return app(DashboardResolver::class)->resolve($request->user());
    }
}

