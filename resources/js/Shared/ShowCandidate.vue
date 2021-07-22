<template>
   <div class="flex flex-col text-center justify-center" >  
         <div class="mx-auto py-1 " > <img src="#"/></div> 
         <div> 
           <p class="mx-auto py-1 " > {{candidacy_name}} </p> 
            <p class="mx-auto" > Candidate for {{post_name}} </p>
             <p class="mx-auto" >  {{post_nepali_name}}  पदको उमेद्ववार </p> 
           </div>       
   </div>
</template>  
<script> 
export default { 
    props:{
       post_name: String,
       candidacy_name: String,
       post_nepali_name: String 
        
    } 
}
</script>