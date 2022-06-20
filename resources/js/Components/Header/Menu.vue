<template>
    <div v-if="!isOpen" class="right-0 flex flex-col items-center text-gray-50">
        <svg
            class="h-12 w-12 text-gray-50 hover:text-gray-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 10h16M4 14h16M4 18h16"
            />
        </svg>
        <span class="text-sm">Menu</span>
    </div>
    <div v-else>
        <span class="text-3xl text-slate-50">X</span>
    </div>
</template>
<script>
export default {
    props: {},
    data() {
        return {
            isOpen: false,
        };
    },
};
</script>
