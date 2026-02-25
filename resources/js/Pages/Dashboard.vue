<template>
    <public-digit-layout>
        <app-layout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            </template>

            <!-- <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div
                        class="overflow-hidden bg-white shadow-xl sm:rounded-lg"
                    >
                        <welcome />
                    </div>
                </div>
            </div> -->
        </app-layout>
    </public-digit-layout> 
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Components/Jetstream/Welcome";
import PublicDigitLayout from "@/Layouts/PublicDigitLayout.vue";
import { useMeta } from "@/composables/useMeta";

export default {
    props: {
        imagename: "",
    },
    components: {
        AppLayout,
        Welcome,
        PublicDigitLayout,
    },
    created() {
        /**
         * SEO: Prevent Dashboard from being indexed
         * This is a private user dashboard and should not appear in search results
         */
        useMeta({ noindex: true, nofollow: true });
    }
};
</script>
