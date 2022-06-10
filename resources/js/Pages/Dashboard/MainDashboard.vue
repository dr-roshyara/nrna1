<template>
    <nrna-layout>
        <div class="min-h-screen p-2">
            <timeline></timeline>
            <!-- <h1>Here we will post our views and openions</h1> -->
            <div class="mx-auto mt-2 max-w-xl py-2">
                <div
                    v-for="openion in openions"
                    class="m-2 rounded-md border p-2 shadow-md"
                >
                    <p class="px-1 pb-1 text-center font-bold text-blue-800">
                        {{ openion.title }}
                    </p>
                    <p>{{ openion.body }}</p>
                    <!-- {{ openion }} -->
                </div>
            </div>
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

export default {
    components: {
        NrnaLayout,
        AppLayout,
        Welcome,
        JoinNetwork,
        Timeline,
    },
    data() {
        return {
            openions: {},
        };
    },
    mounted() {
        // const token = localStorage.getItem("test_token");
        // const headers = {
        //     headers: {
        //         Authorization: `Bearer ${token}`,
        //         Accept: "application/json",
        //     },
        // };
        window.axios.get(this.route("openions.index")).then((response) => {
            this.openions = response.data;
        });
    },
    methods: {},
};
</script>
