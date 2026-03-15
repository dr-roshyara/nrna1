<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon - Use absolute paths for better compatibility --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-2.png') }}">

    {{-- SEO Meta Tags — serverMeta is set by InjectPageMeta middleware (locale-aware) --}}
    <title inertia>{{ ($serverMeta['title'] ?? config('meta.title', 'Public Digit')) }}</title>
    @include('meta.meta-info')

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