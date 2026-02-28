<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // IMPORTANT: Always redirect to login, even for Inertia requests
        // Inertia will automatically follow server redirects
        if ($request->user()) {
            return null;
        }

        return route('login');
    }
}
