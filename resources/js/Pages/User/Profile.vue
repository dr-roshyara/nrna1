<template>
    <profile-layout
        :isLoggedIn="isLoggedIn"
        :user="user"
        :userLoggedIn="userLoggedIn"
    >
        <div class="min-h-screen md:px-4">
            <main-content
                :user="user"
                :isLoggedIn="isLoggedIn"
                :userLoggedIn="userLoggedIn"
            >
            </main-content>
        </div>
    </profile-layout>
</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import MainContent from "@/Components/Profile/MainContent.vue";

export default {
    props: {
        user: Array,
        isLoggedIn: Boolean,
        loggedUser: Array,
    },
    components: {
        ProfileLayout,
        MainContent,
    },
    computed: {
        userLoggedIn() {
            let userLoggedIn = false;
            if (!this.isLoggedIn) {
                userLoggedIn = false;
                return userLoggedIn;
            }
            if (this.isLoggedIn) {
                if (this.user.id === this.loggedUser.id) {
                    userLoggedIn = true;
                }
                return userLoggedIn;
            }

            // console.log(this.$props.user);
            // console.log(this.user);

            return userLoggedIn;
        },
    },
};
</script>
