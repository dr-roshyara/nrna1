@php
    // Use locale-aware meta from InjectPageMeta middleware when available,
    // otherwise fall back to static config values.
    $m = $serverMeta ?? null;
    $metaTitle       = $m['title']       ?? config('meta.title');
    $metaDescription = $m['description'] ?? config('meta.description');
    $metaKeywords    = $m['keywords']    ?? config('meta.keywords');
    $metaRobots      = $m['robots']      ?? config('meta.robots');
    $metaCanonical   = $m['canonical']   ?? config('meta.canonical');
    $ogTitle         = $m['og']['title']       ?? config('meta.og_title');
    $ogDescription   = $m['og']['description'] ?? config('meta.og_description');
    $ogImage         = $m['og']['image']       ?? config('meta.og_image');
    $ogWidth         = $m['og']['width']       ?? config('meta.og_image_width');
    $ogHeight        = $m['og']['height']      ?? config('meta.og_image_height');
    $ogAlt           = $m['og']['alt']         ?? config('meta.og_image_alt');
    $ogSiteName      = $m['og']['site_name']   ?? config('meta.og_site_name');
    $ogLocale        = $m['og']['locale']      ?? config('meta.og_locale');
    $twCard          = $m['twitter']['card']    ?? config('meta.twitter_card');
    $twSite          = $m['twitter']['site']    ?? config('meta.twitter_site');
    $twCreator       = $m['twitter']['creator'] ?? config('meta.twitter_creator');
    $twImage         = $m['twitter']['image']   ?? config('meta.twitter_image');
@endphp
{{-- Primary Meta Tags --}}
<title>{{ $metaTitle }}</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="{{ config('meta.author') }}">
<meta name="robots" content="{{ $metaRobots }}">
<meta name="googlebot" content="{{ config('meta.googlebot') }}">

{{-- Language and Content Type --}}
<meta http-equiv="content-language" content="{{ app()->getLocale() }}">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $metaCanonical }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ config('meta.og_type') }}">
<meta property="og:url" content="{{ $metaCanonical }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="{{ $ogWidth }}">
<meta property="og:image:height" content="{{ $ogHeight }}">
<meta property="og:image:alt" content="{{ $ogAlt }}">
<meta property="og:site_name" content="{{ $ogSiteName }}">
<meta property="og:locale" content="{{ $ogLocale }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $twCard }}">
<meta name="twitter:site" content="{{ $twSite }}">
<meta name="twitter:creator" content="{{ $twCreator }}">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $twImage }}">

{{-- Mobile & PWA --}}
<meta name="theme-color" content="{{ config('meta.theme_color') }}">
<meta name="mobile-web-app-capable" content="{{ config('meta.mobile_app_capable') }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

{{-- Security & Referrer --}}
<meta name="referrer" content="origin-when-cross-origin">
<meta http-equiv="X-Content-Type-Options" content="nosniff">

{{-- Search Engine Verification --}}
@if(config('meta.google_verification'))
<meta name="google-site-verification" content="{{ config('meta.google_verification') }}">
@endif
@if(config('meta.bing_verification'))
<meta name="msvalidate.01" content="{{ config('meta.bing_verification') }}">
@endif
@if(config('meta.yandex_verification'))
<meta name="yandex-verification" content="{{ config('meta.yandex_verification') }}">
@endif

{{-- Hreflang Alternates --}}
@if(!empty($m['alternates']))
    @foreach($m['alternates'] as $loc => $url)
<link rel="alternate" hreflang="{{ $loc }}" href="{{ $url }}">
    @endforeach
@endif

{{-- JSON-LD Structured Data --}}
@if(!empty($m['json_ld']))
    @foreach($m['json_ld'] as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @endforeach
@else
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organisation",
  "name": "{{ config('meta.organisation.name') }}",
  "url": "{{ config('meta.organisation.url') }}"
}
</script>
@endif
