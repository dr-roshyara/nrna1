<template>
    <social-layout>
        <div
            class="flex min-h-screen flex-row p-2 sm:flex-row sm:justify-between md:px-4"
        >
            <div class="hidden sm:block md:w-1/4">
                <left-bar></left-bar>
            </div>
            <div class="flex-1 md:w-1/2">
                <calendar></calendar>
            </div>
            <div class="hidden sm:block md:w-1/4">
                <right-bar></right-bar>
            </div>
        </div>
        <!-- <join-network></join-network>4 -->
    </social-layout>
</template>

<script>
// import NrnaLayout from "@/Layouts/NrnaLayout";
import Calendar from "@/Components/Committee/Calendar.vue";
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
        Calendar,
    },
    props: {
        authUser: Array,
    },
    computed: {},
};
</script>
<style scoped>
#google_calendar {
    @media only screen and (max-width: 640px) {
        width: "350";
        height: "650";
    }
}
.container {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}
.responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    border: none;
}
</style>
