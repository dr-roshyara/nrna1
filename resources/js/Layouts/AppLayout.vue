<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <navigation></navigation>
            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
import Navigation from "@/Components/Header/ElectionNavigation.vue";
export default {
    components: {
        Navigation,
    },
};
</script>
