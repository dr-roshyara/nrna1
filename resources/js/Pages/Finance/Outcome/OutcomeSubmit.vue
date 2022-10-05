<template>
    <div
        class="flex-center flex w-full space-x-2 border border-blue-50 py-2"
        v-for="(key, kIdx) in incomeKeys"
    >
        <div class="w-1/2 px-2">{{ key }}</div>
        <div class="w-1/2 px-2">{{ incomeValues[kIdx] }}</div>
    </div>
</template>
<script>
export default {
    props: {
        income: Array,
    },
    computed: {
        incomeKeys() {
            return Object.keys(this.income);
        },
        incomeValues() {
            return Object.values(this.income);
        },
    },
};
</script>
