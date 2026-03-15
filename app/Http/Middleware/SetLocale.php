<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Determines the application locale with the following priority:
     * 1. Locale cookie set by frontend (when user changes language)
     * 2. Session locale (if set by previous request)
     * 3. Laravel .env APP_LOCALE setting
     * 4. Default to 'en'
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority 1: locale cookie — read from $_COOKIE superglobal directly.
        // $request->cookie() decrypts via Laravel's cookie pipeline; plain JS-set cookies
        // (not encrypted by Laravel) return null even when listed in EncryptCookies $except.
        $cookieLocale = $_COOKIE['locale'] ?? null;
        if ($cookieLocale && $this->isValidLocale($cookieLocale)) {
            app()->setLocale($cookieLocale);
            if ($request->hasSession()) {
                $request->session()->put('locale', $cookieLocale);
            }
            return $next($request);
        }

        // Priority 2: session
        if ($request->hasSession() && $request->session()->has('locale')) {
            $sessionLocale = $request->session()->get('locale');
            if ($this->isValidLocale($sessionLocale)) {
                app()->setLocale($sessionLocale);
                return $next($request);
            }
        }

        // Priority 3: config fallback
        app()->setLocale(config('app.locale', 'de'));
        return $next($request);
    }

    /**
     * Validate that the locale is supported
     *
     * @param  string  $locale
     * @return bool
     */
    private function isValidLocale(string $locale): bool
    {
        $supported = ['de', 'en', 'np'];
        return in_array($locale, $supported);
    }
}
