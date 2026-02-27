<template>
   <div class="px-2"  v-if = "candidate.length" >
                    <div class="flex flex-wrap justify-left  space-x-4  divide-y divide-light-blue-400"
                     v-for="(candi, candiIdx) in candidate" :key="candiIdx" >
                    <div class=" text-gray-900 font-bold "> {{candi.post_name}}</div>
                     <div class="" v-if="candi.candidacy_name"> {{candi.candidacy_name}}</div>
                     <div class="" v-else-if="candi.name"> {{candi.name}}</div>

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
@reference "../../css/app.css";

</style>