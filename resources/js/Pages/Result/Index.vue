<template>
<nrna-layout>
<app-layout>
      <div class="mx-auto">
         <p>  Total Voters: {{final_result[0].total_votes}}  </p>          
         <p>  Total Counted Votes: {{final_result[0].total}}  </p>    
      </div>
    <!-- {{final_result}} -->
 </app-layout>
</nrna-layout>
</template>
<script>
//   import { Bar } from 'vue-chartjs';
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'    
 
export default {
  components:{
   AppLayout,
   NrnaLayout

  },
  props:{
      final_result: Object
  }

}
</script>