<template>
    <div class="min-h-screen flex flex-col">
        <nrna-header :canRegister="canRegister" :canLogin="canLogin">
        </nrna-header>
        <!-- <navigation></navigation> -->
        
        <!-- Main content area (grows to fill space) -->
        <main class="flex-grow">
            <slot></slot>
        </main>
        
        <!-- Footer (stays at bottom) -->
        <nrna-footer class="px-4"></nrna-footer>
    </div>
</template>
<script>
import NrnaHeader from "@/Jetstream/NrnaHeader";
import profileHeader from "@/Jetstream/ProfileHeader";
import NrnaFooter from "@/Jetstream/NrnaFooter";
import Navigation from "@/Components/Header/Navigation.vue";
export default {
    props: {
        image2: String,
        canLogin: Boolean,
        canRegister: Boolean,
    },
    data() {
        return {
            image1: "/storage/images/logo_nrna.jpg",
            //  image1: this.image2
        };
    },
    components: {
        NrnaHeader,
        profileHeader,
        NrnaFooter,
        Navigation,
    },
};
</script>
<style scoped>
#slogan {
    background-image: url("/images/slogan.png");
    min-height: 8rem;
    background-repeat: no-repeat;
}
#logo {
    background-image: url("/images/logo-2.png");
    min-height: 8rem;
    background-repeat: no-repeat;
}
#germanynepal {
    background-image: url("/images/germanynepal.png");
    background-repeat: no-repeat;
    background-size: 300px 200px cover;
}
</style>
