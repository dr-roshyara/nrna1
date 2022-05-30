<template>
    <a class="rouned-lg m-2 mt-4 bg-slate-200 p-2 px-4" href="/google/oauth">
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
