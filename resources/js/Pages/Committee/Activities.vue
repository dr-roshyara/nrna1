<template>
    <social-layout>
        <div class="flex-1 md:w-1/2">Activities</div>

        <!-- <div
            class="flex min-h-screen flex-row p-2 sm:flex-row sm:justify-between md:px-4"
        >
            <div class="hidden sm:block md:w-1/4">
                <left-bar></left-bar>
            </div>
            <div class="flex-1 md:w-1/2">Activities</div>
            <div class="hidden sm:block md:w-1/4">
                <right-bar></right-bar>
            </div>
        </div>
         -->
        <!-- <join-network></join-network>4 -->
    </social-layout>
</template>

<script>
// import NrnaLayout from "@/Layouts/NrnaLayout";
import SocialLayout from "@/Layouts/SocialLayout";
import RightBar from "@/components/Discussion/RightBar.vue";
import LeftBar from "@/components/Discussion/LeftBar.vue";
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import JoinNetwork from "@/components/General/JoinNetwork.vue";
import Timeline from "@/components/Discussion/Timeline.vue";
import Openions from "@/Components/Discussion/Openions";
export default {
    components: {
        SocialLayout,
        AppLayout,
        Welcome,
        JoinNetwork,
        Timeline,
        Openions,
        RightBar,
        LeftBar,
    },
    props: {
        authUser: Array,
    },
    computed: {},
};
</script>
