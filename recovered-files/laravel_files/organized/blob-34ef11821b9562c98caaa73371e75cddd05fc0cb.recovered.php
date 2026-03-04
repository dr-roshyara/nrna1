<template>
    <div class="flex flex-col min-h-screen bg-gray-50">
        <!-- Header (like login page) -->
        <public-digit-header :canRegister="false" :canLogin="false">
        </public-digit-header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot></slot>
        </main>

        <!-- Footer (like login page) -->
        <public-digit-footer class="px-4 mt-12"></public-digit-footer>
    </div>
</template>

<script>
import PublicDigitHeader from "@/Jetstream/PublicDigitHeader.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";

export default {
    name: 'DashboardLayout',
    components: {
        PublicDigitHeader,
        PublicDigitFooter,
    },
};
</script>

<style scoped>
</style>
