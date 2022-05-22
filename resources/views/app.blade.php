<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        {{-- <title>{{ config('app.name', 'Non Resident Nepali Association') }}</title> --}}

        {{--
            *include  meta tags

         --}}
        <title>{{ config('meta.title') }}</title>
        <meta name="description" content=" {{config('meta.description')}} " />
        <meta http-equiv="content-language" content="{{ config('meta.content_language') }}" />
        <meta http-equiv="content-script-type" content="text/javascript" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta http-equiv="window-target" content="_top" />
        <meta property="og:type" content="Organisation" />
        <meta property="og:title" content="Non resident Nepali Association " />
        <meta property="og:description" content="Nepali Diaspora around the world" />
        <meta property="og:locale" content="{{ config('meta.og_locale') }}" />
        <meta property="og:url" content="{{config('meta.og_url') }}" />
        <meta property="og:site_name" content="nrna.eu" />



        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        {{-- <link rel="stylesheet" href="css/css_debugger.css"> --}}

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv
    </body>
</html>
