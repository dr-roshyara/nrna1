<script setup>
import { Head, Link } from '@inertiajs/vue3'
import NrnaLayout from '@/Layouts/NrnaLayout.vue'

defineOptions({ layout: NrnaLayout })

defineProps({
    canonical: { type: String, default: null },
})
</script>

<template>
    <Head>
        <link v-if="canonical" rel="canonical" :href="canonical">
    </Head>

    <div class="min-h-screen bg-white">
        <!-- Hero -->
        <section class="bg-gradient-to-br from-indigo-700 to-indigo-900 text-white py-20 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                    {{ $t('pages.vereinswahlen.title') }}
                </h1>
                <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                    {{ $t('pages.vereinswahlen.subtitle') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        :href="route('register')"
                        class="bg-white text-indigo-700 font-semibold px-8 py-3 rounded-lg hover:bg-indigo-50 transition"
                    >
                        {{ $t('pages.vereinswahlen.cta.trial') }}
                    </Link>
                    <Link
                        :href="route('demo')"
                        class="border border-white text-white font-semibold px-8 py-3 rounded-lg hover:bg-indigo-800 transition"
                    >
                        {{ $t('pages.vereinswahlen.cta.demo') }}
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-16 px-6 bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                    {{ $t('pages.vereinswahlen.features_title') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        v-for="(label, key) in $tm('pages.vereinswahlen.features')"
                        :key="key"
                        class="flex items-start gap-4 bg-white rounded-xl p-6 shadow-sm"
                    >
                        <span class="text-indigo-600 text-2xl" aria-hidden="true">✓</span>
                        <h3 class="font-semibold text-gray-900 text-lg">{{ label }}</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 px-6 bg-indigo-700 text-white text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold mb-4">
                    {{ $t('pages.vereinswahlen.cta_title') }}
                </h2>
                <p class="text-indigo-100 mb-8">
                    {{ $t('pages.vereinswahlen.cta_subtitle') }}
                </p>
                <Link
                    :href="route('register')"
                    class="bg-white text-indigo-700 font-semibold px-10 py-4 rounded-lg hover:bg-indigo-50 transition text-lg"
                >
                    {{ $t('pages.vereinswahlen.cta_button') }}
                </Link>
            </div>
        </section>
    </div>
</template>
