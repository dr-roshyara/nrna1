<template>
   <div class="flex flex-col text-center justify-center" >  
         <div class="mx-auto py-1 " > <img src="#"/></div> 
         <div> 
           <p class="mx-auto py-1 " > {{voted_name}} </p> 
            <p class="mx-auto" >  {{post_name}} </p>
             <p class="mx-auto" >  {{post_nepali_name}}  </p> 
           </div>       
   </div>
</template>  
<script> 
export default { 
    props:{
       voted_name: String,
        post_name: String,
       post_nepali_name: String 
        
    } 
}
</script>