<template>
    <PublicDigitLayout>
        <div class="flex-1 md:w-1/2">
            <timeline :user="$page.props.user"> </timeline>
            <openions
                openionRoute="/openions"
                :user="$page.props.user"
                :authUser="authUser"
                class="mb-2"
            ></openions>
        </div>
        <!--
        <div
            class="flex min-h-screen flex-row p-2 sm:flex-row sm:justify-between md:px-4"
        >
            <div class="hidden sm:block md:w-1/4">
                <left-bar></left-bar>
            </div>
            <div class="flex-1 md:w-1/2">
                <timeline :user="$page.props.user"> </timeline>
                <openions
                    openionRoute="/openions"
                    :user="$page.props.user"
                    :authUser="authUser"
                    class="mb-2"
                ></openions>
            </div>
            <div class="hidden sm:block md:w-1/4">
                <right-bar></right-bar>
            </div>
        </div> -->
        <!-- <join-network></join-network>4 -->
    </PublicDigitLayout>
</template>

<script>
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue';
import RightBar from "@/Components/Discussion/RightBar.vue";
import LeftBar from "@/Components/Discussion/LeftBar.vue";
import Timeline from "@/Components/Discussion/Timeline.vue";
import Openions from "@/Components/Discussion/Openions.vue";
export default {
    components: {
        PublicDigitLayout,
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
