<template>
    <nrna-layout>
    <Head title="बालवालिकाहरूका लागि आइटी  " />
       <div class="min-h-screen px-2  w-full ">
            <div class="mt-2 mb-2 py-2 flex flex-col items-center "> 
                <p class="mx-auto text-center text-xl text-gray-900 font-bold"> बालवालिकाहरूका लागि आइटी  </p>
                <p > Please fill the form below if you are interested  in  your child joins online IT Class</p>
              <jet-validation-errors class="mb-4  mx-auto text-center " /> 
            
            </div> 
            <form @submit.prevent="submit" class="w-full max-w-lg  mx-auto   mt-6  ">
             <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="first_name">First name</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300  md:mx-2 py-2" 
                        id="first_name" type="text" v-model="form.first_name" required />
                </div>  
                 <!-- next  -->
                  <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="last_name">First name</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300  md:mx-2 py-2" 
                        id="last_name" type="text" v-model="form.last_name" required />
                </div>
                 <!-- next   -->
                <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="child_name">Child's full name</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_name" type="text" v-model="form.child_name" required />
                </div> 
                 <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="child_grade">Child's grade</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_grade" type="text" v-model="form.child_grade" required  />
                </div> 
                <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="child_language">Child can Speak</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_language"  placeholder="Nepali, English, German" 
                        type="text" v-model="form.child_language" required />
                </div> 
                 <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="city">City</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="city"  placeholder="" 
                        type="text" v-model="form.city" required />
                    </div> 
                       <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="country">Country</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300 md:mx-2 py-2" 
                        id="country"  placeholder="" 
                        type="text" v-model="form.country" required />
                    </div> 
                <!-- next  -->
                 <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="email">Email</label>
                    <jet-input class="w-2/3 bg-green-50  border border-blue-100  md:mx-2 py-2" 
                        id="email"  type="email" placeholder="roshyara@gmail.com" 
                        v-model="form.email" required />
                </div> 
                <!-- next  -->
                <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="telephone">Telephone</label>
                    <jet-input class="w-2/3 bg-green-50 focus:ring-indigo-300 border border-blue-100 md:mx-2 py-2" 
                        id="telephone"  placeholder="+4915164322589" type="text" 
                        v-model="form.telephone" required  />
                </div> 
                   <!-- next  -->
                <div class="w-full w-full flex flex-col md:flex-row items-center  mb-2 "> 
                    <label class="w-1/3" for="about">Your expectation</label>
                     <textarea id="about" name="about" rows="3" 
                     class="w-2/3 shadow-sm bg-green-50 focus:ring-indigo-300 focus:border-indigo-300 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" 
                    required v-model="form.about"
                    placeholder="Please write what you except from the class. तपाइले यो आइटी कक्षा वाट के अपेक्षा गर्नु भाको छ र हामीलाइ के सुझाव दिन चाहनु हुन्छ? ">
                     </textarea>
            
                </div> 
                <!-- next  -->
                <jet-button type="submit" class="mt-2 w-full">
                    <span class="mx-auto"> Submit</span></jet-button>
            </form>
            </div>
       
     
    </nrna-layout>
</template>
<script>
 import AppLayout from '@/Layouts/AppLayout'
 import NrnaLayout from '@/Layouts/NrnaLayout'     
 import { useForm } from '@inertiajs/inertia-vue3'
import JetInput from '@/Jetstream/Input'
 import JetButton from '@/Jetstream/Button'
import ShowCheckbox from "@/Shared/ShowCheckbox";
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3';
export default {
components:{
    AppLayout,
    NrnaLayout,
    JetInput,
    JetButton,
    JetValidationErrors,
    Head

},
props:{
    candidacies: Array,
  
},
setup () {
    const form = reactive({
      first_name: null,
      last_name: null,
      child_name:null,
      child_grade:null,
      child_language:null,
      city:null,
      country:null,      
      email: null,
      telephone:null,
      about: null
    })

    function submit() {
      Inertia.post('/student/store', form)
    }

    return { form, submit }
  }, 
}
</script>


