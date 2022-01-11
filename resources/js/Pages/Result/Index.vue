<template>
<nrna-layout>
<app-layout>
       <div v-for="(post, pIndx ) in posts" :key="pIndx" 
        class=" flex flex-col justify-center  my-4 mx-auto py-2 border border-lime-300 shadow-xl"> 
   
        <candidate :post="post" :final_result="final_result[0]" 
            :class ='{"pIndx%5==0": bg-blue-100 }' >
         
       </candidate>
      
       </div>
 </app-layout>
</nrna-layout>
</template>

<script>
//   import { Bar } from 'vue-chartjs';
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout' 
import Candidate from "@/Pages/Result/Candidate";

export default {

   components: {
   AppLayout,
   NrnaLayout, 
   Candidate

  },
  props: {
      final_result: Object,
      candidates:Object,
      posts: Object
  },
}
</script>