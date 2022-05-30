<template>
    <profile-layout :isLoggedIn="isLoggedIn" :user="user">
        <div class="min-h-screen">
            <main-content :user="user" :isLoggedIn="isLoggedIn"> </main-content>
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
    },
    components: {
        ProfileLayout,
        MainContent,
    },
};
</script>
