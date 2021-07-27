<template>
   <nrna-layout> 
    <app-layout>
     <div class="flex flex-col ites-center md:mx-auto md:flex-row  md:space-x-6 ">
        <div class="flex flex-col justify-center bg-blue-100 ">
                
                <div class="m-auto">
                <jet-validation-errors class="mb-4  mx-auto text-center " /> 
                  
                </div>
                
            <form @submit.prevent="submit" class=" text-center mx-auto ">
                <div class="flex flex-col justify-center px-2 m-2"> 
                    <div class="flex flex-col justify-center space-x-4 items-center p-4 mb-2 m-auto font-bold text-gray-900 ">
                    <label for="voting_code"  class="px-4 py-2 mb-3"> 
                        <p> Vote Conformation  Code </p>
                        <p> Please check the vote what you have casted. Finally conform it by inserting the code  you got  sencod time. </p> 
                        <p>यहाँले गर्नु भएको मतदान यो पेजमा देखाइएको छ। साथी यहाँको एसएमएसमा पनि पठाइएको छ। अब यहाँले गर्नु भएको मतदान अली त्यो एसएमएस मा पठाइएको पुस्टी कोड हालेर आफ्नो मतदान लाई सेभ गर्न बत्तन थिच्नुहोस्। </p>  
                    </label>   
                    <input class=" px-4 py-6 rounded-lg bg-gray-200 w-auto
                    font-bold border border-blue-400  text-gray-900 font-bold text-xl" 
                        id="voting_id" 
                    placeholder="PLEASE ENTER HERE YOUR VOTING CODE"  
                        v-model ="form.voting_code"/> 
                    </div>  
                    <div class="mx-auto my-4 w-full"> 
                        <button type="submit" 
                        class="m-2 px-2 py-4 rounded-lg bg-blue-300 w-96 mx-auto font-bold text-gray-900">
                        SEND CODE TO save your vote </button> 
                        </div>
                            <div class="mx-auto text-center">
                    <jet-validation-errors class="mb-4  mx-auto text-center " />
                    </div>
                </div>
                </form>
                
                
        </div>
         <!-- next  -->           
         <div class="flex flex-col px-2 mt-6 w-full bg-gray-50 ml-2">
           <p class="p-2 mx-auto text-xl text-gray-900 font-bold"> Your vote  </p>  
            
        <div v-if="no_vote_option">   
             You have selected a no vote option .Please conform it 
        </div>
        <div v-else class=" mx-auto w-full">
             <voted-post v-bind:candidate ="icc_member"></voted-post> 
            <voted-post v-bind:candidate ="president"></voted-post> 
           <voted-post v-bind:candidate ="vp"></voted-post> 
            <voted-post v-bind:candidate ="general_secretary"></voted-post>     
             <voted-post v-bind:candidate ="secretary"></voted-post> 
              <voted-post v-bind:candidate ="treasure"></voted-post>
               <voted-post v-bind:candidate ="w_coordinator"></voted-post>  
                <voted-post v-bind:candidate ="y_coordinator"></voted-post>      
                 <voted-post v-bind:candidate ="cult_coordinator"></voted-post>  
                  <voted-post v-bind:candidate ="child_coordinator"></voted-post>  
                <voted-post v-bind:candidate ="studt_coordinator"></voted-post>
                <voted-post v-bind:candidate ="member_berlin"></voted-post>
                <voted-post v-bind:candidate ="member_hamburg"></voted-post>
                <voted-post v-bind:candidate ="member_nsachsen"></voted-post>
                <voted-post v-bind:candidate ="member_nrw"></voted-post>
                <voted-post v-bind:candidate ="member_hessen"></voted-post>
                <voted-post v-bind:candidate ="member_rhein_pfalz"></voted-post>
                <voted-post v-bind:candidate ="member_bayern"></voted-post>

        </div> 
         </div>
    
    </div>        
  


    </app-layout>    
    </nrna-layout> 
</template>
<script>
import VotedPost from "@/Shared/VotedPost"
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'
export default {  
    props:{
        vote : Object
    },
    setup () {
     const form = useForm({
        voting_code: '',
        })
    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
        // console.log(this.voting_code);
      form.post('/votes')
    }

    return { form, submit }
  },    
    data(){
        return {
           icc_member:               this.vote[0],
           president:                this.vote[1],
           vp:                       this.vote[2],
           wwp:                      this.vote[3],
           general_secretary:        this.vote[4],
           secretary:                this.vote[5],
           treasure:                  this.vote[6],
           w_coordinator:             this.vote[7],
           y_coordinator:              this.vote[8],  
           cult_coordinator:          this.vote[9],
           child_coordinator:          this.vote[10],
           studt_coordinator:          this.vote[11],
           member_berlin:              this.vote[12],
           member_hamburg:              this.vote[13],
           member_nsachsen:             this.vote[14],
           member_nrw:                  this.vote[15],
           member_hessen:               this.vote[16],
           member_rhein_pfalz:          this.vote[17],          
           member_bayern:               this.vote[18],
            no_vote_option:               this.vote[19]
         
         
           
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
