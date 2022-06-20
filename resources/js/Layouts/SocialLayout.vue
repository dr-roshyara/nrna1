<template>
    <div class="relative flex flex-col lg:h-full">
        <div class="z-100 fixed grid w-full grid-cols-1 overflow-hidden">
            <nrna-header
                :canRegister="canRegister"
                :canLogin="canLogin"
                class="relative"
            >
            </nrna-header>
            <navigation class="md:hidden"></navigation>
        </div>

        <!-- middle  -->
        <section class="mx-auto mt-48 sm:mt-20">
            <slot class="min-h-screen flex-grow overflow-hidden"> </slot>
        </section>
        <!-- footer  -->
        <nrna-footer class="px-2"> </nrna-footer>
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
