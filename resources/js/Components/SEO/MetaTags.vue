<!-- resources/js/Components/SEO/MetaTags.vue -->
<template>
    <Head>
        <title v-if="title">{{ title }}</title>
        <meta v-if="description" name="description" :content="description" />
        <meta v-if="robots" name="robots" :content="robots" />

        <!-- Open Graph -->
        <meta v-if="title" property="og:title" :content="title" />
        <meta v-if="description" property="og:description" :content="description" />
        <meta v-if="ogImage" property="og:image" :content="ogImage" />
        <meta property="og:url" :content="canonical || currentUrl" />

        <!-- Twitter -->
        <meta v-if="title" name="twitter:title" :content="title" />
        <meta v-if="description" name="twitter:description" :content="description" />
        <meta v-if="ogImage" name="twitter:image" :content="ogImage" />

        <!-- Canonical -->
        <link rel="canonical" :href="canonical || currentUrl" />
    </Head>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    title: { type: String, default: null },
    description: { type: String, default: null },
    robots: { type: String, default: null },
    ogImage: { type: String, default: null },
    canonical: { type: String, default: null },
})

const page = usePage()
const currentUrl = computed(() => typeof window !== 'undefined' ? window.location.href : '')
</script>
