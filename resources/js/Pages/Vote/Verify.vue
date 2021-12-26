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
                    <div class="flex flex-col justify-center space-x-4 items-center p-4 mb-2 mx-auto 
                    font-bold text-gray-900 ">
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
            
         <div v-if="no_vote_option" class="text-center">   
             You have selected a <span class="text-red-800 font-bold " >  VOTE FOR NO ONE </span>option .<br>
            <span class="text-gray-900 font-bold">  Please conform it </span>
        </div>
            <div class="pre">
                {{JSON.stringify(vote, null, 2)}}
            <!-- <pre><code id=acct></code></pre> -->
            <!-- <pre><code id="planets"></code></pre> -->
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