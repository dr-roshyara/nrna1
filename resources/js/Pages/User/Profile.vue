<template>
<profile-layout>
<div>
{{user}}
test
</div>
</profile-layout>

</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue"

export default{
    props:{
        user: Array
    },
    components:{
        ProfileLayout
    }

}
</script>
