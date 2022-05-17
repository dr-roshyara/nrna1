<template>
<profile-layout>
<div >
  <main-content :user="user"> </main-content>
</div>
</profile-layout>

</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue"
import MainContent from "@/Components/Profile/MainContent.vue"

export default{
    props:{
        user: Array
    },
    components:{
        ProfileLayout,
        MainContent
    }

}
</script>
