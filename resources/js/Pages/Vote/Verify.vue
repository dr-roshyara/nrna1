<template>
<nrna-layout> 
 <app-layout>
    <div class="flex flex-col  md:mx-auto md:flex-row  md:space-x-6 ">
        <div class="flex flex-col mt-10 align-top bg-blue-100 ">
                   
                <div class="mx-auto">
                <jet-validation-errors class="mb-4  mx-auto text-center " /> 
                  
                </div>
                <!-- {{vote}} -->
                <!-- {{code_expires_in}} -->
                 <div class="p-2 mx-auto text-center my-2 text-2xl font-bold text-gray-900 "> 
                     <p> Check your email & submit the vote conformation code</p> 
                </div>
            <form @submit.prevent="submit" class=" text-center mx-auto align-top">
                <div class="flex flex-col justify-center px-2 m-2"> 
                    <div class="flex flex-col justify-center space-x-4 
                  p-4 mb-2 mx-auto 
                    font-bold text-gray-900 ">
                    <label for="voting_code"  class="px-4 py-2 mb-3"> 
                        <p> First of all, please  check below the vote what you have casted. 
                            Finally conform it by inserting the code  you got  sencod time in your email. 
                            We have just sent you an email 
                            <span class="text-red-500 font-bold "> {{vote.totalDuration}} </span> 
                          minutes ago, mentioning your vote conformation code. 
                           You can use this code for the next 
                          <span class="text-red-500 font-bold ">
                            {{vote.code_expires_in - vote.totalDuration}}  minutes 
                            </span>
                          to save your vote.<br>
                        </p> 
                         <p> यहाँले गर्नु भएको मतदान यो पेजमा देखाइएको छ। सवै भन्दा पहिला आफ्नो मतदान लाइ जाँच गर्नुहोस। 
                              अनि तपाइलाइ हामीले  भर्खरै 
                          <span class="text-red-500 font-bold ">
                            {{vote.totalDuration }}  मिनेट  
                          </span> 
                          अघाडी एउटा इमेल पठाएका छाैं। आफ्नो मतदान सेभ गर्नको लागि 
                          त्यो कोड लाइ अर्काे 
                          <span class="text-red-500 font-bold "> 
                            {{vote.code_expires_in - vote.totalDuration}} 
                            </span>
                            मिनेट सम्म प्रयाेग गरिसक्नु पर्ने छ। 
                         अब यहाँले प्राप्त गर्नु भएको मतदान पुष्टी कोड हालेर आफ्नो मतदान लाई सेभ गर्न  तलको बट्टन थिच्नुहोस्। </p>  
                    </label>   
                    <input class=" px-4 py-6 rounded-lg bg-gray-200 w-auto
                    font-bold border border-blue-400  text-gray-900 font-bold text-xl" 
                        id="voting_id" 
                    placeholder="PLEASE ENTER HERE YOUR VOTING CODE"  
                        v-model ="form.voting_code"/> 
                    </div>  
                    <div class="my-4 w-full"> 
                        <button type="submit" 
                        class="w-full  px-4 py-4 rounded-lg bg-blue-300 font-extrabold text-gray-900">
                        PLEASE CLICK HERE  &  SAVE YOUR VOTE </button> 
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
            
         <div v-if="no_vote_option" class="text-center">   
             You have selected a <span class="text-red-800 font-bold " >  VOTE FOR NO ONE </span>option .<br>
            <span class="text-gray-900 font-bold">  Please conform it </span>
        </div>
               <!-- Here we display the casted vote   -->
              <vote-display :vote="vote"> </vote-display>
        </div>   
    </div>   
      </app-layout>    
    </nrna-layout> 
    </template>
<script>
import VotedPost from "@/Shared/VotedPost"
import VoteDisplay from "@/Pages/Vote/VoteDisplay"
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'
export default {  
    props:{
        vote        :   Object,
        totalDuration:  Number,
        code_expires_in:Number     
    },
   setup () {
     const form = useForm({
            voting_code: '',
            // vote: this.voteSubmitted
        })

    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
        // console.log(this.voting_code);
      form.post('/votes')
    }

    return { form, submit }
  },  
   computed:{
        voteSubmitted(){
            return this.vote;
        }
    },
    components:{
        VotedPost,
        VoteDisplay,
         NrnaLayout,
     AppLayout,
     JetValidationErrors
    },
    methods:{
        get_json_array(){
            Object.prototype.prettyPrint = function(){
            var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
            var replacer = function(match, pIndent, pKey, pVal, pEnd) {
                var key = '<span class="json-key" style="color: brown">',
                    val = '<span class="json-value" style="color: navy">',
                    str = '<span class="json-string" style="color: olive">',
                    r = pIndent || '';
                if (pKey)
                    r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
                if (pVal)
                    r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
                return r + (pEnd || '');
            };

            return JSON.stringify(this, null, 3)
                    .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
                    .replace(/</g, '&lt;').replace(/>/g, '&gt;')
                    .replace(jsonLine, replacer);
        }
            document.getElementById('planets').innerHTML = this.vote;


        }
    } 

}
</script>
<style scoped>
body{
  background: #efefef;
}
pre {
   background-color: ghostwhite;
   border: 1px solid silver;
   padding: 10px 20px;
   margin: 20px;
   border-radius: 4px;
  width: 25%;
  margin-left: auto;
  margin-right: auto;
   }


</style>