<template>
    <nrna-layout>
        <app-layout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            </template>

            <!-- <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div
                        class="overflow-hidden bg-white shadow-xl sm:rounded-lg"
                    >
                        <welcome />
                    </div>
                </div>
            </div> -->
        </app-layout>
    </nrna-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import NrnaLayout from "@/Layouts/NrnaLayout.vue";

export default {
    props: {
        imagename: "",
    },
    components: {
        AppLayout,
        Welcome,
        NrnaLayout,
    },
};
</script>
