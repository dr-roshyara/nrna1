<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Primary Meta --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo-2.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo-2.png">

    {{--
      SEO Meta Tags Strategy
      ═══════════════════════════════════════════════════════════
      DUAL-SOURCE APPROACH (Best of Both Worlds):
      1. SERVER-SIDE FALLBACK (Blade) - Language-aware using trans()
      2. CLIENT-SIDE DYNAMIC (useMeta composable) - Vue i18n
    --}}

    @php
        /**
         * Safely get translation with fallback
         * Prevents TypeError from breaking the page
         */
        $getTranslation = function($key, $fallback = '') {
            try {
                $translation = trans($key, [], null, false);
                return $translation !== $key ? $translation : $fallback;
            } catch (\TypeError $e) {
                \Log::error('Translation error for key: ' . $key, [
                    'error' => $e->getMessage(),
                ]);
                return $fallback;
            }
        };
    @endphp

    {{-- Dynamic Meta Tags (language-aware from Laravel trans) --}}
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

    @php
        $localeMap = ['de' => 'de_DE', 'en' => 'en_US', 'np' => 'ne_NP'];
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

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Google Tag Manager --}}
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MH39X8L');
    </script>

    {{-- Vite Assets (Laravel 11) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ziggy Routes (Manual initialization - Fortify removal workaround) --}}
    <script>
        window.Ziggy = {
            url: "{{ url('/') }}",
            port: null,
            defaults: {},
            routes: {}
        };
    </script>
</head>
<body class="font-sans antialiased">
    {{-- Google Tag Manager (noscript) --}}
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MH39X8L"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    {{-- Inertia Root --}}
    @inertia

    {{-- BrowserSync (Development only) --}}
    @env('local')
        <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
    @endenv
</body>
</html>