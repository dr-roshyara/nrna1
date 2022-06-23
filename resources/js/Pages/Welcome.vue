<template>
    <!-- <nrna-layout> -->
    <!-- <div class="m-auto text-center">
           <a href="https://www.civiciti.com/ww/nrnagermany" class="m-auto min-h-screen" >
                      <div class=" flex flex-col h-64 py-2 m-auto text-center px-10 w-full">
                       <p class="w-full text-center text-gray-900 font-bold">
                         VOTE  HERE </p>
                       <img src="/storage/images/ballot.png" width="200" height="200" />
                    </div>

            </a>
    </div>  -->
    <!-- </nrna-layout> -->
    <!-- <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.user"
                    href="/dashboard" class="text-sm text-gray-700 underline">
                Dashboard
            </inertia-link>

            <template v-else>
                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                    Log in
                </inertia-link>

                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </inertia-link>

            </template>
        </div>  -->

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
