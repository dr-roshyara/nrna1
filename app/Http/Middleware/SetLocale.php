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
        $locale = null;

        // Debug 1: Log request details
        \Log::info('🔍 SetLocale: Request started', [
            'path' => $request->path(),
            'method' => $request->method(),
            'hasSession' => $request->hasSession(),
        ]);

        // Debug 2: Log ALL cookies in detail
        $allCookies = $request->cookies->all();
        \Log::info('🍪 SetLocale: ALL cookies received (raw)', $allCookies);

        // Debug 3: Specifically check for locale cookie
        $localeCookieValue = $request->cookie('locale');
        \Log::info('🎯 SetLocale: Checking locale cookie', [
            'hasCookie(locale)' => $request->hasCookie('locale'),
            'cookie(locale)' => $localeCookieValue,
            'type' => gettype($localeCookieValue),
            'is_string' => is_string($localeCookieValue),
            'length' => is_string($localeCookieValue) ? strlen($localeCookieValue) : 'N/A',
        ]);

        // Priority 1: Check for locale cookie
        if ($localeCookieValue && is_string($localeCookieValue)) {
            $locale = $localeCookieValue;
            \Log::info('✅ SetLocale: Found valid locale cookie string', ['value' => $locale]);

            if ($this->isValidLocale($locale)) {
                app()->setLocale($locale);
                \Log::info('🎉 SetLocale: SUCCESS - Set locale from cookie', ['locale' => $locale]);

                // Store in session
                if ($request->hasSession()) {
                    $request->session()->put('locale', $locale);
                    \Log::info('💾 SetLocale: Stored in session', ['locale' => $locale]);
                }

                return $next($request);
            } else {
                \Log::warning('⚠️ SetLocale: Invalid locale from cookie', [
                    'locale' => $locale,
                    'valid_locales' => $this->locales
                ]);
            }
        }

        // If we get here, cookie wasn't valid or wasn't found
        \Log::info('❌ SetLocale: No valid locale cookie found');

        // Priority 2: Check session
        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            \Log::info('📋 SetLocale: Found locale in session', ['locale' => $locale]);

            if ($this->isValidLocale($locale)) {
                app()->setLocale($locale);
                \Log::info('✅ SetLocale: Set locale from session', ['locale' => $locale]);
                return $next($request);
            }
        }

        // Priority 3: Config fallback
        $locale = config('app.locale', 'en');
        \Log::info('⚙️ SetLocale: Falling back to config', ['locale' => $locale]);

        if ($this->isValidLocale($locale)) {
            app()->setLocale($locale);
            \Log::info('✅ SetLocale: Set locale from config', ['locale' => $locale]);
        }

        \Log::info('🏁 SetLocale: Final locale', ['locale' => app()->getLocale()]);
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
