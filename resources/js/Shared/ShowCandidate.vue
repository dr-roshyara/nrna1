<template>
   <div class=" text-center m-auto" >  
         <img :src="full_image_name" width="" class="mx-auto py-1 h-96 w-96 rounded-lg object-cover" />
            <p class="mx-auto text-gray-900 font-bold " > {{candidacy_name}} </p> 
            <p class="mx-auto  text-gray-900 font-bold" > Candidate for {{ post_name }} </p>
            <p class="mx-auto  text-gray-900 font-bold" >  {{post_nepali_name}}  पदको उमेद्ववार </p> 
           
        
         
   </div>
</template>  
<script> 
import { computed } from '@vue/runtime-core'
export default { 
    props:{
       post_name: String,
       candidacy_name: String,
       post_nepali_name: String,
       candidacy_image_path: String 
        
    },
    computed:{
      full_image_name (){
         return this.image_path+ this.candidacy_image_path
      }
     },
    
    data(){
       return {
          image_path: "/storage/images/",
          imagename: this.image_path+ this.candidacy_image_path 
       } 
     
    } 
}
</script>