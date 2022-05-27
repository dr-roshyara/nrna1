<template>
    <a class="rouned-lg mt-2 bg-slate-100 p-2 px-4" href="/google/oauth">
        Add account
    </a>
    <div>
        {{ accounts }}
    </div>
</template>
<script>
export default {
    props: {
        accounts: Array,
    },
};
</script>
