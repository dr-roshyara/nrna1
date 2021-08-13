<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
        
          <div  class="flex flex-col justify-center  py-4 px-6  ">          
            <!--  here we put the candidate -->
                     <p class="px-2 mx-auto py-2 text-gray-900 font-bold text-xl"> 
                          Final Result </p>
                    <table class=" m-4 text-left" v-if="deligate_result">
                        <tr class="border-b-2 border-gray-400">
                           <th class="p-1">S.N.</th>
                             <th class="p-1">Post</th>
                             <th class="p-1">User ID</th>
                              <th class="p-1">NRNA ID</th>
                                <th class="p-1">State</th>
                              <th class="p-1">Deligate Name</th>
                              <th class="p-1">Total Vote </th>

                        </tr>
                        <tr v-for="(deligate, deliIdx) in  deligate_result" >
                            <td class="p-1">{{ deliIdx+1 }}</td>
                            <td  class="p-1">Diligate Member</td>
                             <td class="p-1">{{ deligate.id }}</td>
                             <td class="p-1">{{ deligate.nrna_id }}</td>
                             <td class="p-1">{{ deligate.state }}</td>
                             <td  class="p-1"> {{ deligate.name }} </td>
                             <td  class="p-1"> {{ deligate.vote }} </td> 
                        </tr>                            
                    </table>
          </div>
           <!-- end of list -->
     </div>  
     </app-layout>
</nrna-layout> 
</template>
<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import AppLayout  from '@/Layouts/AppLayout'
export default {
  components: { NrnaLayout }, 
    props:{
         deligate_result: Object 
    },
    data(){
         return {
          //     has_voted: (this.deligatevote)
         }
    },
    computed: { 
         user_has_voted(){
          //     console.log(this.deligatevote);
          // return isEmpty(this.deligatevote)
          return true
         }
    },
    components:{
         NrnaLayout,
         AppLayout 
    }
}
</script>