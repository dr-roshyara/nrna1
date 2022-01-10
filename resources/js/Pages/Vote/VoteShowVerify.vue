<template>
<nrna-layout>
     <app-layout>
     <div class="mt-6 text-center">  
          <div v-if="has_voted" class="m-auto text-center bg-blue-200 py-4 ">  
               <p class="m-auto text-blue-700 font-bold text-sm"> Congratulation {{user_name}}! </p> 
               <p class="m-auto"> Thank You for your vote. 
                    If you want to check your vote again, please submit the 
                    <span class="text-gray-900 text-lg font-bold"> code to check your vote.</span> 
                    Please  keep your passowrd very secret. Also please do not  ask others to show their vote. </p>
               <p> यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य राख्नु यहाँको कर्तब्य हो । 
                    यसैले कृपया आफ्नो पासवर्ड अरुलाई नदिनु होला ।  
                  
               </p>
               <jet-validation-errors class="mb-4  mx-auto text-center " />
          </div>
          <div class=" flex flex-col m-auto" > 
               <div class="p-2 mx-auto text-center my-2 text-2xl font-bold text-gray-900 "> 
                     <p> Check your email & submit the code to see and check your vote </p> 
                </div>
               
               <form @submit.prevent="submit" class=" text-center mx-auto align-top">
                    <div class="flex flex-col justify-center px-2 m-2"> 
                         <div class="flex flex-col justify-center space-x-4 
                    p-4 mb-2 mx-auto 
                         font-bold text-gray-900 ">
                         <label for="voting_code"  class="px-4 py-2 mb-3"> 
                         <p> Please insert the code to see and check your vote.<br>
                         </p> 
                         
                         </label>   
                         <input class=" px-4 py-6 rounded-lg bg-gray-200 w-auto
                         font-bold border border-blue-400  text-gray-300 font-bold text-sm" 
                         id="voting_id" 
                         placeholder="ENTER HERE YOUR  CODE TO CHECK THE VOTE"  
                         v-model ="form.voting_code"/> 
                         </div>  
                    <div class="my-4 w-full"> 
                        <button type="submit" 
                        class="w-full  px-4 py-4 rounded-lg bg-blue-300 font-extrabold text-gray-900">
                        PLEASE CLICK HERE  &  GET YOUR VOTE </button> 
                        </div>
                            <div class="mx-auto text-center">
                    <jet-validation-errors class="mb-4  mx-auto text-center " />
                    </div>
                </div>
                </form>
          </div> 
         
     </div>  
     </app-layout>
</nrna-layout> 
</template>
<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import AppLayout  from '@/Layouts/AppLayout'
import VoteFinal from '@/Pages/Vote/VoteFinal';
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
export default {
  components: { 
    NrnaLayout,
    AppLayout,
    VoteFinal ,
    JetValidationErrors
    }, 
    props:{
         vote: Object,
         has_voted:Boolean,
        
    },
      setup () {
     const form = useForm({
            voting_code: '',
            // vote: this.voteSubmitted
        })

    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
        // console.log(this.voting_code);
      form.post('/verify_final_vote', {
            preserveScroll: true
      },
      {
            resetOnSuccess: true
        });
    }

    return { form, submit }
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