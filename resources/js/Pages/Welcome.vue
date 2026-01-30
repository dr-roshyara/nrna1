<template>
    <div v-if="!loggedIn" class="min-h-screen bg-gray-50">
        <!-- New Professional Header -->
        <ElectionHeader :isLoggedIn="false" />

        <!-- Hero Section -->
        <HeroSection />

        <!-- Audience Section -->
        <AudienceSection />

        <!-- Main Content -->
        <div class="bg-white py-12 md:py-16">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <join-network></join-network>
            </div>
        </div>

        <!-- Upcoming Events Timeline -->
        <div class="bg-gray-50 py-12 md:py-16">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <div id="calendar">
                    <upcoming-events
                        class="mb-4 w-full max-w-full py-2"
                    ></upcoming-events>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <PublicDigitFooter />
    </div>
    <div v-else>
        <dashboard> </dashboard>
    </div>
</template>

<script>
import Dashboard from "@/Pages/Dashboard";
import JoinNetwork from "@/Components/General/JoinNetwork.vue";
import UpcomingEvents from "@/Shared/Events/UpcomingEvents.vue";
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import AudienceSection from "@/Components/Welcome/AudienceSection.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";

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
        Dashboard,
        JoinNetwork,
        UpcomingEvents,
        ElectionHeader,
        HeroSection,
        AudienceSection,
        PublicDigitFooter,
    },
};
</script>
