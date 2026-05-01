<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
          <div class="m-auto text-center bg-primary-200 py-4 ">  
               <p class="m-auto text-primary-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
               <p class="m-auto"> Thank You for your deligatevote. Please keep your passowrd very secret. Also please do not  ask others to show their deligatevote. 
                    For verification of the result, this deligatevote will be deleted only after one month. </p>
               <p>यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो पासवर्ड अरुलाई नदिनु होला ।  
                    प्राप्त मतहरुको पुनर्पुष्ठी गर्नको लाई यहाँको यो मत  अबको एक महिना पछी मात्रा डिलेट हुने छ। 
               </p>
        
          </div>
           <!-- {{deligatevote}} -->
           <div v-if="deligatevote.no_vote_option" class="mt-10 p-2 mx-auto "> 
                
                <p class =" text-xl font-bold text-neutral-900"> You have used 
                <span class="text-danger-600" > your right to reject </span> all candidates.</p> 
 
          </div> 
         
          <div v-else class="flex flex-col justify-center  py-4 px-6  ">         
            <!--  here we put the candidate -->
                     <p class="px-2 mx-auto py-2 text-neutral-900 font-bold text-xl"> 
                          Your Vote  </p>
                    <table class=" m-4 text-left" v-if="deligatevote">
                        <tr class="border-b-2 border-neutral-400">
                           <th class="p-1">S.N.</th>
                             <th class="p-1">Post</th>
                             <th class="p-1">User ID</th>
                              <th class="p-1">NRNA ID</th>
                                <th class="p-1">State</th>
                              <th class="p-1">Deligate Name</th>

                        </tr>
                        <tr v-for="(deligate, deliIdx) in  deligatevote.deligatevote" >
                            <td class="p-1">{{ deliIdx+1 }}</td>
                            <td  class="p-1">Diligate Member</td>
                             <td class="p-1">{{ deligate.id }}</td>
                             <td class="p-1">{{ deligate.nrna_id }}</td>
                             <td class="p-1">{{ deligate.state }}</td>
                             <td  class="p-1"> {{ deligate.name }} </td>
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
import AppLayout  from '@/Layouts/AppLayout.vue'
export default {
  components: { NrnaLayout }, 
    props:{
         deligatevote: Object 
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
