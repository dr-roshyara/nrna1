<template>
    <head>
        <title>{{ title }}</title>
        <meta name="description" content="{{description}}" />
    </head>
</template>
<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
export default {
    props: {
        title: String,
        description: String,
    },
    components: {
        Head,
        Link,
    },
};
</script>
