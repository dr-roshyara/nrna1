<template>
    <div class="min-h-screen flex flex-col">
        <public-digit-header :canRegister="canRegister" :canLogin="canLogin">
        </public-digit-header>
        
        <!-- Main content area (grows to fill space) -->
        <main class="grow">
            <slot></slot>
        </main>
        
        <!-- Footer (stays at bottom) -->
        <public-digit-footer class="px-4"></public-digit-footer>
    </div>
</template>

<script>
import PublicDigitHeader from "@/Components/Jetstream/PublicDigitHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";

export default {
    name: 'PublicDigitLayout',
    
    props: {
        canLogin: {
            type: Boolean,
            default: true
        },
        canRegister: {
            type: Boolean,
            default: true
        },
    },
    
    components: {
        PublicDigitHeader,
        PublicDigitFooter,
    },
};
</script>

<style scoped>
/* Layout specific styles */
.min-h-screen {
    min-height: 100vh;
}
</style>