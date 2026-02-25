<template>
    <div class="min-h-screen flex flex-col">
        <public-digit-header :canRegister="canRegister" :canLogin="canLogin">
        </public-digit-header>
        <!-- <navigation></navigation> -->
        
        <!-- Main content area (grows to fill space) -->
        <main class="flex-grow">
            <slot></slot>
        </main>
        
        <!-- Footer (stays at bottom) -->
        <public-digit-footer class="px-4"></public-digit-footer>
    </div>
</template>
<script>
import PublicDigitHeader from "@/Components/Jetstream/PublicDigitHeader.vue";

import profileHeader from "@/Components/Jetstream/ProfileHeader";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter";
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
        PublicDigitHeader,
        profileHeader,
        PublicDigitFooter, 
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
