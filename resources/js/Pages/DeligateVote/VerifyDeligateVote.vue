<template>
   <nrna-layout> 
    <app-layout>
     <div class="flex flex-col ites-center md:mx-auto md:flex-row  md:space-x-6 ">
        <div class="flex flex-col justify-center bg-primary-100 ">
                
                <div class="m-auto">
                <jet-validation-errors class="mb-4  mx-auto text-center " /> 
                  
                </div>
                
            <form @submit.prevent="submit" class=" text-center mx-auto ">
                <div class="flex flex-col justify-center px-2 m-2"> 
                    <div class="flex flex-col justify-center space-x-4 items-center p-4 mb-2 m-auto font-bold text-neutral-900 ">
                    <label for="voting_code"  class="px-4 py-2 mb-3"> 
                        <p> Deligate Vote Conformation  Code </p>
                        <p> Please check first the deligates  you have selected. Finally conform it by inserting the code . Your code : 4321 </p> 
                        <p>यहाँले गर्नु भएको मतदान यो पेजमा देखाइएको छ। अब यहाँले गर्नु भएको मतदान लाई  पुस्टी कोड हालेर आफ्नो मतदान लाई सेभ गर्न बत्तन थिच्नुहोस्। </p>  
                    </label>   
                    <input class=" px-4 py-6 rounded-lg bg-neutral-200 w-auto
                    font-bold border border-primary-400  text-neutral-900 font-bold text-xl" 
                        id="voting_id" 
                    placeholder="PLEASE ENTER HERE YOUR VOTING CODE"  
                        v-model ="form.voting_code"/> 
                    </div>  
                    <div class="mx-auto my-4 w-full"> 
                        <button type="submit" 
                        class="m-2 px-2 py-4 rounded-lg bg-primary-300 w-96 mx-auto font-bold text-neutral-900">
                        SEND CODE TO save your deligatevote </button> 
                        </div>
                            <div class="mx-auto text-center">
                    <jet-validation-errors class="mb-4  mx-auto text-center " />
                    </div>
                </div>
                </form>
                
                
        </div>
         <!-- next  -->           
         <div class="flex flex-col px-2 mt-6 w-full bg-neutral-50 ml-2">
           <p class="p-2 mx-auto text-xl text-neutral-900 font-bold"> You have selected the following deligates </p>  
            
        <div v-if="no_vote_option" class="text-center">   
             You have selected a <span class="text-danger-800 font-bold " >  VOTE FOR NO ONE </span>option .<br>
            <span class="text-neutral-900 font-bold">  Please conform it </span>
        </div>
        <div v-else class=" mx-auto w-full">
             <voted-post v-bind:candidate ="member"></voted-post> 
           

        </div> 
         </div>
    
    </div>        
  


    </app-layout>    
    </nrna-layout> 
</template>
<script>
import VotedPost from "@/Shared/DeligateVotedPost.vue"
import { useForm } from '@inertiajs/vue3'
import JetValidationErrors from '@/Components/Jetstream/ValidationErrors.vue' 
import AppLayout from '@/Layouts/AppLayout.vue'
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
export default {  
    props:{
        deligatevote : Object
    },
    setup () {
     const form = useForm({
            voting_code: '',
            // deligatevote: this.voteSubmitted
        })

    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
        // console.log(this.voting_code);
      form.post('/deligatevotes')
    }

    return { form, submit }
  }, 
    data(){
        return {
            no_vote_option:             this.deligatevote[0],
            member:                    this.deligatevote[1]
         
         
           
        }
    },
    computed:{
        voteSubmitted(){
            return this.deligatevote;
        }
    },
    components:{
        VotedPost,
         NrnaLayout,
     AppLayout,
     JetValidationErrors
    }
}
</script>

