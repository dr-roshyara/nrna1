<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-transparent bg-blue-400 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-500 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: "submit",
        },
    },
};
</script>
