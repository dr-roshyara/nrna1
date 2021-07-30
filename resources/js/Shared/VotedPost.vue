<template>
   <div class="px-2"  v-if = "candidate.length" >
                    <div class="flex flex-wrap justify-left  space-x-4  divide-y divide-light-blue-400" 
                     v-for="(candi, candiIdx) in candidate" :key="candiIdx" >
                    <div class=" text-gray-900 font-bold "> {{candi.post_name}}</div>
                     <div class=""> {{candi.candidacy_name}}</div>
                     
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