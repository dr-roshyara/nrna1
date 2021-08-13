<template>
   <div class="px-2"  v-if = "candidate.length" >
                    <div class="flex flex-row justify-left  space-x-4  divide-y  divide-light-blue-400 m-auto align-middle" 
                     v-for="(candi, candiIdx) in candidate" :key="candiIdx" >
                    <!-- <div class=" text-gray-900 font-bold m-auto "> {{candiIdx+1}}</div> -->
                     <!-- <div class=" text-gray-900 font-bold "> {{candi.post_name}}</div> -->
                    <div class="font-bold text-gray-900"> {{candiIdx+1}}-></div>
                     <div class=""> {{candi.nrna_id}}</div>
                     <div class=""> {{candi.name}}</div>
                     
                    </div>  
    </div> 
</template>
<script>

export default {
  props:{
      candidate :Object
  }    
}
</script>
<style scoped>

</style>