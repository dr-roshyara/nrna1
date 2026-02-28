{{-- Primary Meta Tags --}}
<title>{{ config('meta.title') }}</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="description" content="{{ config('meta.description') }}">
<meta name="keywords" content="{{ config('meta.keywords') }}">
<meta name="author" content="{{ config('meta.author') }}">
<meta name="robots" content="{{ config('meta.robots') }}">
<meta name="googlebot" content="{{ config('meta.googlebot') }}">

{{-- Language and Content Type --}}
<meta http-equiv="content-language" content="{{ config('meta.content_language') }}">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ config('meta.canonical') }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ config('meta.og_type') }}">
<meta property="og:url" content="{{ config('meta.og_url') }}">
<meta property="og:title" content="{{ config('meta.og_title') }}">
<meta property="og:description" content="{{ config('meta.og_description') }}">
<meta property="og:image" content="{{ config('meta.og_image') }}">
<meta property="og:image:width" content="{{ config('meta.og_image_width') }}">
<meta property="og:image:height" content="{{ config('meta.og_image_height') }}">
<meta property="og:image:alt" content="{{ config('meta.og_image_alt') }}">
<meta property="og:site_name" content="{{ config('meta.og_site_name') }}">
<meta property="og:locale" content="{{ config('meta.og_locale') }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ config('meta.twitter_card') }}">
<meta name="twitter:site" content="{{ config('meta.twitter_site') }}">
<meta name="twitter:creator" content="{{ config('meta.twitter_creator') }}">
<meta name="twitter:title" content="{{ config('meta.twitter_title') }}">
<meta name="twitter:description" content="{{ config('meta.twitter_description') }}">
<meta name="twitter:image" content="{{ config('meta.twitter_image') }}">

{{-- Mobile & PWA --}}
<meta name="theme-color" content="{{ config('meta.theme_color') }}">
<meta name="apple-mobile-web-app-capable" content="{{ config('meta.mobile_app_capable') }}">
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

{{-- Structured Data (JSON-LD) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "organisation",
  "name": "{{ config('meta.organisation.name') }}",
  "legalName": "{{ config('meta.organisation.legal_name') }}",
  "url": "{{ config('meta.organisation.url') }}",
  "logo": "{{ config('meta.organisation.logo') }}",
  "foundingDate": "{{ config('meta.organisation.founding_date') }}",
  "email": "{{ config('meta.organisation.email') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ config('meta.organisation.address.street_address') }}",
    "addressLocality": "{{ config('meta.organisation.address.address_locality') }}",
    "addressRegion": "{{ config('meta.organisation.address.address_region') }}",
    "postalCode": "{{ config('meta.organisation.address.postal_code') }}",
    "addressCountry": "{{ config('meta.organisation.address.address_country') }}"
  },
  "sameAs": @json(config('meta.organisation.same_as'))
}
</script>

{{-- WebSite Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ config('meta.site_name') }}",
  "url": "{{ config('meta.og_url') }}",
  "description": "{{ config('meta.description') }}",
  "publisher": {
    "@type": "organisation",
    "name": "{{ config('meta.publisher') }}"
  }
}
</script>
