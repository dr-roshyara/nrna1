<template>
    <nrna-layout>
        <div class="min-h-screen p-2">
            <timeline></timeline>
            <openions
                openionRoute="openions.index"
                :user="$page.props.user"
            ></openions>
        </div>
        <!-- <join-network></join-network>4 -->
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/NrnaLayout";
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import JoinNetwork from "@/components/General/JoinNetwork.vue";
import Timeline from "@/components/Discussion/Timeline.vue";
import Openions from "@/Components/Discussion/Openions";
export default {
    components: {
        NrnaLayout,
        AppLayout,
        Welcome,
        JoinNetwork,
        Timeline,
        Openions,
    },
};
</script>
