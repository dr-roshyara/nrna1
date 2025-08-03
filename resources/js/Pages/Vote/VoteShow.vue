<template>
  

    <div v-if="!loggedIn">
        <nrna-layout :canRegister="canRegister" :canLogin="canLogin">
            <div class="mx-auto">
                <join-network></join-network>
                <div id="calendar">
                    <upcoming-events
                        class="mb-4 w-full max-w-full py-2"
                    ></upcoming-events>
                </div>
                <!-- <login
                    canResetPassword="true"
                    :canRegister="canRegister"
                    :canLogin="canLogin"
                >
                </login> -->
            </div>
        </nrna-layout>
    </div>
    <div v-else>
        <dashboard> </dashboard>
    </div>

    <!-- </nrna-layout> -->
</template>

<script>
import Login from "@/Components/Auth/Login";
import Dashboard from "@/Pages/Dashboard";
import JoinNetwork from "@/components/General/JoinNetwork.vue";
import UpcomingEvents from "@/Shared/Events/UpcomingEvents.vue";
import NrnaLayout from "@/Layouts/LoginLayout";
export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        role: String,
        loggedIn: Boolean,
    },
    components: {
        Login,
        Dashboard,
        JoinNetwork,
        NrnaLayout,
        UpcomingEvents,
    },
};
</script>
<style scoped>
#calendar {
}
</style>
