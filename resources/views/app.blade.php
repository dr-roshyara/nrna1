<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" type="image/png" sizes="32x32" href="/images/logo-2.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/logo-2.png">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
          SEO Meta Tags Strategy
          ═══════════════════════════════════════════════════════════

          DUAL-SOURCE APPROACH (Best of Both Worlds):

          1. SERVER-SIDE FALLBACK (This file - Blade)
             - Language-aware using Laravel trans() helper
             - Visible to all crawlers immediately (no JS wait)
             - Works for social media scrapers (FB, Twitter)
             - Acts as instant SEO for non-JS crawlers

          2. CLIENT-SIDE DYNAMIC (useMeta composable - Vue)
             - Reads from i18n translations (en.json, de.json, np.json)
             - Overwrites server tags when Vue loads
             - Enables dynamic updates on locale change
             - Required for complex/dynamic pages

          PRECEDENCE:
          When page loads:
          1. Blade renders with trans() fallback tags
          2. Vue loads
          3. useMeta() overwrites with Vue i18n values (if page uses useMeta)
          4. Final result: Always language-correct

          IMPLEMENTATION:
          - Update translations in: resources/lang/en/*, de/*, np/*
          - Blade auto-reads from trans() calls here
          - Vue reads from: resources/js/locales/en.json, de.json, np.json
          - Both use same data source (translations)
          ═══════════════════════════════════════════════════════════
        --}}

        {{-- DYNAMIC FALLBACK META TAGS (language-aware from Laravel trans) --}}
        {{-- These are overwritten by useMeta() when Vue loads, but crawlers that --}}
        {{-- don't execute JS will see language-correct content --}}

        @php
            /**
             * Safely get translation with fallback
             * Wrapped in try-catch to prevent TypeError from breaking the page
             */
            $getTranslation = function($key, $fallback = '') {
                try {
                    $translation = trans($key, [], null, false);
                    return $translation !== $key ? $translation : $fallback;
                } catch (\TypeError $e) {
                    // Log the error for debugging
                    \Log::error('Translation error for key: ' . $key, [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                    // Return fallback to prevent page crash
                    return $fallback;
                }
            };
        @endphp

        <title>{{ $getTranslation('seo.pages.home.title', $getTranslation('seo.site.title', 'Public Digit')) }}</title>
        <meta name="description" content="{{ $getTranslation('seo.pages.home.description', $getTranslation('seo.site.description', 'Secure digital voting platform')) }}">
        <meta name="keywords" content="{{ $getTranslation('seo.pages.home.keywords', $getTranslation('seo.site.keywords', 'voting, elections')) }}">
        <meta name="robots" content="index, follow">

        {{-- Open Graph Tags (Social Sharing) --}}
        <meta property="og:title" content="{{ $getTranslation('seo.pages.home.title', $getTranslation('seo.site.title', 'Public Digit')) }}">
        <meta property="og:description" content="{{ $getTranslation('seo.pages.home.description', $getTranslation('seo.site.description', 'Secure digital voting platform')) }}">
        <meta property="og:image" content="{{ config('meta.og_image', url('/images/og-default.jpg')) }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Public Digit">

        {{-- Locale Mapping: Convert Laravel locale to OG locale format --}}
        @php
            $localeMap = [
                'de' => 'de_DE',
                'en' => 'en_US',
                'np' => 'ne_NP'
            ];
            $ogLocale = $localeMap[app()->getLocale()] ?? 'en_US';
        @endphp
        <meta property="og:locale" content="{{ $ogLocale }}">

        {{-- Twitter Card Tags --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $getTranslation('seo.pages.home.title', $getTranslation('seo.site.title', 'Public Digit')) }}">
        <meta name="twitter:description" content="{{ $getTranslation('seo.pages.home.description', $getTranslation('seo.site.description', 'Secure digital voting platform')) }}">
        <meta name="twitter:image" content="{{ config('meta.og_image', url('/images/og-default.jpg')) }}">

        {{-- Canonical URL --}}
        <link rel="canonical" href="{{ url()->current() }}">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        {{-- <link rel="stylesheet" href="css/css_debugger.css"> --}}

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
         {{-- Google analytics  --}}
         <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-MH39X8L');</script>
            <!-- End Google Tag Manager -->

        <!-- Scripts -->

        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MH39X8L"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    </body>
</html>
