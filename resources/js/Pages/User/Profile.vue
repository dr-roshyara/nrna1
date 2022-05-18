<template>
<profile-layout :isLoggedIn="isLoggedIn">
<div class="min-h-screen">
  <main-content :user="user"> </main-content>
</div>
</profile-layout>

</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue"
import MainContent from "@/Components/Profile/MainContent.vue"

export default{
    props:{
        user: Array,
        isLoggedIn:Boolean
    },
    components:{
        ProfileLayout,
        MainContent,

    }

}
</script>
