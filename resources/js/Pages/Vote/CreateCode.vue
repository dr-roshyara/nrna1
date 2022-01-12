<template>
   <nrna-layout> 
    <app-layout>
           <div class="flex flex-col justify-center mt-4 w-full mx-2 p-2">
                      <p class="p-2 my-2 text-2xl text-gray-900 font-bold  bg-blue-50 text-center mx-auto"> 
                        Check your email Now </p>
                      <div class="md:text-center"> 
                        <p> We have sent you an email  
                          <span class="text-red-500 font-bold "> {{code_duration }} </span> 
                          minutes ago, mentioning your voting code. 
                          You can use this code for the next 
                          <span class="text-red-500 font-bold ">
                            {{code_expires_in - code_duration}}  minutes 
                            </span>
                          to open the voting form.   If you don't see email in your mailbox , then please check your Spam mail also.<br/>  
                         </p>
                         <p class="py-2">
                          तपाइलाइ हामीले  भर्खरै 
                          <span class="text-red-500 font-bold ">
                            {{code_duration }}  मिनेट 
                          </span> 
                          अघाडी एउटा इमेल पठाएका छाैं। 
                          भोटिङ पर्म खोल्नको लागि त्यो कोड लाइ अर्काे 
                          <span class="text-red-500 font-bold "> 
                            {{code_expires_in- code_duration}} 
                            </span>
                            मिनेट सम्म प्रयाेग गरिसक्नु पर्ने छ। यो फर्मलाइ एकछिन यत्तिकै राखेर अव यहाँले आफ्नो इमेलमा चेक गर्नुहोस । <br>
                        आफ्नो  इमेलमा प्राप्त भएको  भोटिङ कोड थाहा पाउनु हाेस र 
                        उक्त भोटिङ कोड तलको खाली ठाउंमा भरेर सेन्ड वटन थिच्नु होस । </p>
                          <p class="text-red-700 font-semibold text-sm"> 
                           तपाइले आफ्नो मेल वक्समा एनआरएनएको इमेल भेट्टाउनु भएन भने स्पाम मेलमा गएर वसेको हुन सक्छ, त्यसैले स्पाम मेल पनि चेक गर्नुहोस। 
                        </p> 
                      </div>
             <div class="m-auto">
             <jet-validation-errors class="mb-4  mx-auto text-center " /> 
                 <!--
                  //   {{name}}
                  //  {{nrna_id}}
                  //    {{state}}
                  -->
            </div>
           <form @submit.prevent="submit" class="text-center mx-auto mt-10 w-full md:w-2/3">
            <div class=" flex flex-col justify-center px-2 mx-2 bg-lime-50 shadow border py-4 w-full"> 
                   
      
                <div class="p-2 mb-1 font-bold text-gray-900 ">
                   <label for="voting_code"  class="p-2 md:p-4"> 
                        <p class="text-xl text-gray-900 font-bold text-center"> 
                     First Voting Code</p>                 
                   </label>   
                  <input class="p-2 md:px-4 md:py-6 rounded-lg bg-gray-200 w-full md:w-1/2 
                   font-bold border border-blue-400  text-gray-900 font-bold text-xl" 
                    id="voting_id" 
                   placeholder="Write fisrt voting code"  
                    v-model ="form.voting_code"/> 
                </div>  
                 <div class="p-2 my-4 "> 
                    <button type="submit" 
                    class=" py-4 md:p-4  rounded-lg bg-blue-300 w-full md:w-1/2
                     font-bold text-gray-900">
                    PRESS HERE TO GET VOTING FORM</button> 
                    </div>
                <div class="mx-auto text-center">
                       <jet-validation-errors class="mb-4  mx-auto text-center " />
                </div>
             </div>
            </form>
             
            
            </div>
          

    </app-layout>    
    </nrna-layout> 
</template>
<script>
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'
export default {
    props:{
        name: String,
        nrna_id: String, 
        state:  String,
        code_duration: Number,
        code_expires_in: Number,
    },   
     setup () {
     const form = useForm({
        voting_code: '',
        })
    // this.$inertia.post(route('candidacy.store'), data); 
    function submit() {
        console.log(this.voting_code);
      form.post('/codes')
    }

    return { form, submit }
  },    
 components:{
   NrnaLayout,
     AppLayout,
     JetValidationErrors
 }   
}
</script>
