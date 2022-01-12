<template>
   <div class=" text-center m-auto w-full" >  
         <!-- {{full_image_name}} -->
         <img v-if="imageGiven" :src="full_image_name"  
            class="mx-auto py-1 sm:h-40 sm:w-40  md:h-96 md:w-96 rounded-lg object-cover" />
            <p class="mx-auto text-gray-900 font-bold " > {{ candidacy_name}} </p> 
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
      },
      imageGiven (){
         let btemp =false;
          if(this.candidacy_image_path!="-") {
             btemp =true;
          }
          return btemp;
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