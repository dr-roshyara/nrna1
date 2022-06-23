<template>
    <div class="h-ful w-full max-w-xl text-center">
        <h1 class="text-center text-xl font-bold text-gray-900 lg:text-2xl">
            Upcoming NRNA Events in Europe
        </h1>
        <calendar></calendar>
    </div>
</template>

<script>
// import Calendar from "@/Components/Committee/Calendar.vue";
import Calendar from "@/Components/Committee/Calendar.vue";
export default {
    components: {
        Calendar,
    },
};
</script>
