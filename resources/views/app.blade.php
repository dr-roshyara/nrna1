<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon - Use absolute paths for better compatibility --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-2.png') }}">
    
    @php
        $getTranslation = function($key, $fallback = '') {
            try {
                // Using lang() helper is more modern in Laravel 11
                return lang()->has($key) ? __($key) : $fallback;
            } catch (\Throwable $e) {
                return $fallback;
            }
        };

        $localeMap = ['de' => 'de_DE', 'en' => 'en_US', 'np' => 'ne_NP'];
        $ogLocale = $localeMap[app()->getLocale()] ?? 'en_US';
        $currentTitle = $getTranslation('seo.pages.home.title', $getTranslation('seo.site.title', 'Public Digit'));
    @endphp

    <title inertia>{{ $currentTitle }}</title>

    {{-- SEO & Open Graph --}}
    <meta name="description" content="{{ $getTranslation('seo.pages.home.description', 'Secure digital voting platform') }}">
    <meta property="og:title" content="{{ $currentTitle }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Fonts - Nunito is now default in Tailwind v4 config we wrote --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Ziggy Routes --}}
    @routes 

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    {{-- Google Tag Manager (noscript) --}}
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MH39X8L"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    {{-- Inertia Root --}}
    @inertia
</body>
</html>