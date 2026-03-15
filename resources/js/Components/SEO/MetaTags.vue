<template>
    <Head>
        <title v-if="title">{{ title }}</title>
        <meta v-if="description" name="description" :content="description">
        <meta v-if="keywords" name="keywords" :content="keywords">
        <meta v-if="robots" name="robots" :content="robots">
        <link v-if="canonical" rel="canonical" :href="canonical">

        <!-- Open Graph -->
        <meta v-if="og.type"        property="og:type"         :content="og.type">
        <meta v-if="canonical"      property="og:url"          :content="canonical">
        <meta v-if="title"          property="og:title"        :content="title">
        <meta v-if="description"    property="og:description"  :content="description">
        <meta v-if="resolvedOgImage" property="og:image"       :content="resolvedOgImage">
        <meta v-if="og.width"       property="og:image:width"  :content="String(og.width)">
        <meta v-if="og.height"      property="og:image:height" :content="String(og.height)">
        <meta v-if="og.alt"         property="og:image:alt"    :content="og.alt">
        <meta v-if="og.site_name"   property="og:site_name"    :content="og.site_name">
        <meta v-if="og.locale"      property="og:locale"       :content="og.locale">

        <!-- Twitter Card -->
        <meta v-if="twitter.card"    name="twitter:card"        :content="twitter.card">
        <meta v-if="twitter.site"    name="twitter:site"        :content="twitter.site">
        <meta v-if="twitter.creator" name="twitter:creator"     :content="twitter.creator">
        <meta v-if="title"           name="twitter:title"       :content="title">
        <meta v-if="description"     name="twitter:description" :content="description">
        <meta v-if="resolvedOgImage" name="twitter:image"       :content="resolvedOgImage">
        <meta v-if="twitter.alt"     name="twitter:image:alt"   :content="twitter.alt">

        <!-- Hreflang Alternates -->
        <link
            v-for="[loc, url] in alternates"
            :key="loc"
            rel="alternate"
            :hreflang="loc"
            :href="url"
        >

        <!-- JSON-LD Structured Data -->
        <component
            v-for="(schema, i) in jsonLds"
            :key="i"
            :is="'script'"
            type="application/ld+json"
            v-text="JSON.stringify(schema)"
        />
    </Head>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

// Optional prop overrides — any prop passed here takes precedence over shared meta
const props = defineProps({
    title:       { type: String, default: null },
    description: { type: String, default: null },
    robots:      { type: String, default: null },
    ogImage:     { type: String, default: null },
    canonical:   { type: String, default: null },
})

const page = usePage()

// Shared meta from InjectPageMeta middleware (page.props.meta)
const m = computed(() => page.props.meta ?? {})

// Resolve values: prop overrides win, then shared meta
const title       = computed(() => props.title       ?? m.value.title       ?? null)
const description = computed(() => props.description ?? m.value.description ?? null)
const robots      = computed(() => props.robots      ?? m.value.robots      ?? null)
const canonical   = computed(() => props.canonical   ?? m.value.canonical   ?? null)
const og          = computed(() => m.value.og        ?? {})
const twitter     = computed(() => m.value.twitter   ?? {})
const keywords        = computed(() => m.value.keywords ?? null)
const resolvedOgImage = computed(() => props.ogImage ?? og.value.image ?? null)

// Hreflang: convert { en: url, de: url, ... } to entries array
const alternates = computed(() => Object.entries(m.value.alternates ?? {}))

// JSON-LD: convert { website: {...}, organization: {...} } to values array
const jsonLds = computed(() => Object.values(m.value.json_ld ?? {}))
</script>
