<template>
    <div class="relative flex min-h-screen flex-col lg:h-full">
        <profile-header
            :canRegister="canRegister"
            :canLogin="canLogin"
            :isLoggedIn="isLoggedIn"
            :user="user"
            :userLoggedIn="userLoggedIn" 
        >
        </profile-header>
        <!-- middle  -->
        <slot class="relative min-h-screen grow"> </slot>
        <!-- footer  -->
        <public-digit-footer class="px-2"> </public-digit-footer>
    </div> 
</template>
<script>
import ProfileHeader from "@/Components/Jetstream/ProfileHeader.vue";
import PublicDigitFooter   from "@/Components/Jetstream/NrnaFooter.vue";

export default {
    props: {
        image2: String,
        canLogin: Boolean,
        canRegister: Boolean,
        isLoggedIn: Boolean,
        user: Array,
        userLoggedIn: Boolean,
    }, 
    data() {
        return {
            image1: "/storage/images/logo_publicdigit.jpg",
            //  image1: this.image2
        };
    },
    components: {
        ProfileHeader,
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
