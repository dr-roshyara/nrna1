<template>
    <div class="flex flex-col lg:h-full">
        <public-digit-header :canRegister="canRegister" :canLogin="canLogin">
        </public-digit-header>

        <!-- middle  -->
        <slot class="min-h-screen grow"> </slot>
        <!-- footer  -->
        <public-digit-footer class="px-4"> </public-digit-footer>
    </div>
</template>
<script>
import PublicDigitHeader from "@/Components/Jetstream/PublicDigitHeader.vue";
import profileHeader from "@/Components/Jetstream/ProfileHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";
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
