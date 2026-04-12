<template>
    <div>
        <jet-banner v-if="$page.props.auth && $page.props.auth.user" />

        <div class="min-h-screen bg-gray-100">
            <navigation v-if="$page.props.auth && $page.props.auth.user"></navigation>
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
