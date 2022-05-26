<template>
    <nrna-layout>
        <!-- <Head title="बालवालिकाहरूका लागि निशुल्क आइटी  " /> -->
        <div class="min-h-screen">
            <p class="mx-auto mt-10 text-center">
                Thank you Very much for your registration.<br />
                We will contact you personally. <br />

                NRNA
            </p>
        </div>
    </nrna-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import NrnaLayout from "@/Layouts/LoginLayout";
import { Inertia } from "@inertiajs/inertia";
// import { Head } from '@inertiajs/inertia-vue3';
export default {
    props: {
        student: Object,
    },
    components: {
        AppLayout,
        NrnaLayout,
        // Head
    },
};
</script>
