<template>
    <div class="min-h-screen flex flex-col">
        <!-- Header - sticks to top -->
        <!-- Note: Not passing :locale prop allows language preference to persist from localStorage -->
        <election-header :isLoggedIn="true"></election-header>
        <navigation></navigation>
        
        <!-- Main content - grows to fill space -->
        <main class="flex-1">
            <slot></slot>
        </main>
        
        <!-- Footer - sticks to bottom -->
        <public-digit-footer class="px-4"></public-digit-footer>
    </div>
</template>
<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import profileHeader from "@/Components/Jetstream/ProfileHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";
import Navigation from "@/Components/Header/ElectionNavigation.vue";
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
        ElectionHeader,
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
