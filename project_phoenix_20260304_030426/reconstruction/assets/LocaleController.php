<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * LocaleController - Manage User Language Preference
 *
 * Handles setting and managing user's preferred language/locale.
 * Works in conjunction with SetLocale middleware.
 *
 * Priority:
 * 1. Locale cookie (set here via API)
 * 2. Session locale (persisted by this controller)
 * 3. Config default
 */
class LocaleController extends Controller
{
    /**
     * Set user's locale preference
     *
     * Stores locale in:
     * - Session (for current request lifecycle)
     * - Triggered via frontend cookie (handled by SetLocale middleware)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLocale(Request $request)
    {
        $locale = $request->validate([
            'locale' => 'required|in:de,en,np'
        ])['locale'];

        // Store in session
        $request->session()->put('locale', $locale);

        \Log::info('✅ Locale set via API', [
            'locale' => $locale,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => "Language set to: {$locale}",
        ]);
    }

    /**
     * Get current locale
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocale(Request $request)
    {
        $locale = $request->session()->get('locale', config('app.locale', 'en'));

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'supported_locales' => ['de', 'en', 'np'],
        ]);
    }
}
