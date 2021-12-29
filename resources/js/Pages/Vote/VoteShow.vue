<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
          <div class="m-auto text-center bg-blue-200 py-4 ">  
               <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
               <p class="m-auto"> Thank You for your vote. Please keep your passowrd very secret. Also please do not  ask others to show their vote. 
                    For verification of the result, this vote will be deleted only after one month. </p>
               <p>यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो पासवर्ड अरुलाई नदिनु होला ।  
                    प्राप्त मतहरुको पुनर्पुष्ठी गर्नको लाई यहाँको यो मत  अबको एक महिना पछी मात्रा डिलेट हुने छ। 
               </p>
               <!-- {{vote}} -->
          </div>
           <div v-if="vote.no_vote_option" class="mt-10 p-2 mx-auto "> 
                
                <p class =" text-xl font-bold text-gray-900"> You have used 
                <span class="text-red-600" > your right to reject </span> all candidates.</p> 
 
          </div> 
          <div v-else class="flex flex-col justify-center  py-4 px-6  ">         
            <!--  here we put the candidate -->
                     <p class="px-2 mx-auto py-2 text-gray-900 font-bold text-xl"> 
                          Your Vote  </p>
                          
                 <vote-final :vote="vote"> </vote-final>             
                 
          </div>
           <!-- end of list -->
     </div>  
     </app-layout>
</nrna-layout> 
</template>
<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import AppLayout  from '@/Layouts/AppLayout'
import VoteFinal from '@/Pages/Vote/VoteFinal';
export default {
  components: { 
    NrnaLayout,
    AppLayout,
    VoteFinal 
    }, 
    props:{
         vote: Object 
    },
    data(){
         return {
          //     has_voted: (this.vote)
         }
    },
    computed: { 
         user_has_voted(){
          //     console.log(this.vote);
          // return isEmpty(this.vote)
          return true
         }
    },
    
}
</script>