<template>
    <nrna-layout>
        <div class="min-h-screen p-2">
            <join-network></join-network>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/NrnaLayout";
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import JoinNetwork from "@/components/General/JoinNetwork.vue";

export default {
    components: {
        NrnaLayout,
        AppLayout,
        Welcome,
        JoinNetwork,
    },
};
</script>
