<template>
    <nrna-layout >
    <!-- <Head title="बालवालिकाहरूका लागि आइटी  " /> -->
    <!-- <Head>
        <title>Your page title</title>
        <meta name="description" content="Your page description">
    </Head> -->
    <div class="min-h-screen px-2  w-full ">
            <div class="mt-2 mb-2 py-2 flex flex-col items-center "> 
                <p class="mx-auto  text-xl text-gray-900 font-bold mb-2"> 
                    युरोपमा बाल वालिकाहरूका लागि निशुल्क आइटी  </p>
                <div class="p-3 md:p-4 border border-green-50 shadow-md mb-2 text-gray-900 bg-blue-50">
                <p> <b>सुरुवात मितीः</b> 5 फ्रेबुअरी २०२२<br>
                <b>समयः</b> विहान ९-१० वजे सम्म (युरोप सेन्ट्रल समय) <br>                      
                <b>स्थान/माध्यमः</b> अनलाइन जुम कक्षा<br>
                <b>उमेर समुह:</b> १३ देखि २२ वर्ष सम्मका वालवालिकाहरू<br> 
                <b>शुल्कः</b> निशुल्क (परोपकारी काम अन्तर्गत)<br>
                <b>शिक्षकः</b> डा. नवराज रोस्यारा <br>
                <b>भाषा माध्यम:</b> अंग्रेजी, नेपाली र जर्मन <br>
                <b>अभिवावक सहितको पहिलो भेटघाटः</b> २९ जनवरी २०२२ शनिवार ।  <br>
                <b>सम्पर्कको लागि </b><br> 
                  मिरा अधिकारी<br>
                  email: mpoudel@hotmail.de<br>
                  whatsapp: +4915164322589<br>               
                
                </p>
                </div>
               <p > Please fill the form below if you are interested  in  your child joins online IT Class</p>
              
              <jet-validation-errors class="mb-4  mx-auto text-center " /> 
            
            </div> 
            <form @submit.prevent="submit" class="w-full md:w-1/2 mx-auto   mt-6 mb-4 ">
             <div class="w-full  flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="first_name">First name</label>
                    <jet-input class="w-full  md:w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300  md:mx-2 py-2" 
                        id="first_name" type="text" v-model="form.first_name" required />
                </div>  
                 <!-- next  -->
                  <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full  md:w-1/3" for="last_name">Last name</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300  md:mx-2 py-2" 
                        id="last_name" type="text" v-model="form.last_name" required />
                </div>
                 <!-- next   -->
                <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="child_name">Child's full name</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_name" type="text" v-model="form.child_name" required />
                </div> 
                 <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="child_grade">Child's grade</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_grade" type="text" v-model="form.child_grade" required  />
                </div> 
                  <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="birth_year">
                    Child's birth year</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="birth_year" type="month" v-model="form.birth_year" required  />
                </div> 
                <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="child_language">Child can Speak</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="child_language"  placeholder="Nepali, English, German" 
                        type="text" v-model="form.child_language" required />
                </div> 
                 <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="city">City</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 md:mx-2 py-2" 
                        id="city"  placeholder="" 
                        type="text" v-model="form.city" required />
                    </div> 
                       <!-- next  -->
                   <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="country">Country</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100 focus:ring-indigo-300 md:mx-2 py-2" 
                        id="country"  placeholder="" 
                        type="text" v-model="form.country" required />
                    </div> 
                <!-- next  -->
                 <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="email">Email</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50  border border-blue-100  md:mx-2 py-2" 
                        id="email"  type="email" placeholder="roshyara@gmail.com" 
                        v-model="form.email" required />
                </div> 
                <!-- next  -->
                <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="telephone">Telephone</label>
                    <jet-input class="w-full md:w-2/3 bg-green-50 focus:ring-indigo-300 border border-blue-100 md:mx-2 py-2" 
                        id="telephone"  placeholder="+4915164322589" type="text" 
                        v-model="form.telephone" required  />
                </div> 
                   <!-- next  -->
                <div class="w-full w-full flex flex-col md:flex-row   mb-2 "> 
                    <label class="w-full md:w-1/3" for="about">Your expectation</label>
                     <textarea id="about" name="about" rows="3" 
                     class="w-full md:w-2/3 shadow-sm bg-green-50 focus:ring-indigo-300 focus:border-indigo-300 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" 
                    required v-model="form.about"
                    placeholder="Please write what you except from the class. तपाइले यो आइटी कक्षा वाट के अपेक्षा गर्नु भाको छ र हामीलाइ के सुझाव दिन चाहनु हुन्छ? ">
                     </textarea>
            
                </div> 
                <!-- next  -->
                <jet-button type="submit" class="mt-2 w-full ">
                    <span class="mx-auto  py-2"> Submit</span></jet-button>
            </form>
             <div class="p-2 mt-2 mb-2 w-full md:w-2/3 mx-auto">
               आदरणिय दिदी वहिनी तथा दाजुभाइहरू,<br>
गैर आवासिय नेपाली सेघ जर्मनी  भाषा तथा वालवालीका विभाग र एनआरएनएका पुर्व अध्यक्ष डा० नवराज रोस्यारा संगको सहकार्यमा  
नव निर्वचित कमिटी वने देखि नै वालवालीकाहरूको लागि कक्षा संचालन संम्वन्धमा छलफल चलिरहेको  थियो। 
छलफलमा एनआरएनए ग्लोवलको निर्वाचन पश्चात अनलाइन कक्षा संचालन गर्ने निश्कर्ष हामीले निकालेका थियौ । 
तर एनआरएनए ग्लोवलको निर्वाचन सर्दै गएको र अझै पनि निश्चत भै नसकेको अवस्थामा वालवालिकाको लागि 
संचालन गरिने कक्षाहरू अव कक्षा संचालनको कार्यलाइ अघि वढाउने निर्णय गरेका छौ।  
कक्षा संचालन गर्ने क्रममा पहिलो पल्ट हामी तेह्र देखि वीस वर्ष सम्मका वाल वालीकाहरूका लागि आइटी कोर्ष संचालन गर्न गइरहेका छौ । आइटी कोर्षको  कक्षा हरेक शनिवार विहान सात वजे देखि आठ वजे सम्म अनलाइन कक्षा गरिनेछ। आइटी कक्षामा निरन्तर एकवर्ष सहभागि भएका विध्यार्थीहरूले निम्न ज्ञान हासिल गर्न सक्नेछन। 
<ul class="px-2 m-2"> 
<li class="list-disc"> कम्प्युटरको आधारभुत आर्केटेक्चर, कम्प्युटरमा प्रोग्रामहरू इन्सटल डिइन्स्टल गर्ने तरिका</li>
<li class="list-disc">	प्रोग्रामिड० भनेको केहो ? कसरी लेख्ने? प्रोग्रामिड० गर्न चाहिने आधारभुत सिपहरू </li>
<li class="list-disc">	मानिसको दैनिक जिवनमा आइटिको प्रयोग र देनिकीमा आधारित विभिन्न उदाहरणहरूको समाधान।</li>
<li class="list-disc">	कुनै एउटा प्रेग्रामको प्रयोग गरेर प्रोजेक्ट वर्क अभ्यास </li>
</ul>
यदि यहाँहरूका छोरा छोरीहरू तेह्र देखि वीस वर्ष सम्मका छन र
 कम्युटरको ज्ञान लिन इच्छुक छन भने उनिहरूका लागि यो कक्षा एकदमै उपयुक्त् हुन सक्छ।
   यो कक्षामा यस्मा यहाँहरूको रूचि रह्यो भने आफ्ना छोरा छोरीहरू संग सर सल्लाह गरेर 
    कृपया निम्न लिन्कको फर्ममा गएर अभिभावकहरूले
     भाग लिने वच्चाहरूको जानकारि दिनुहुनेछ।  <br><br>
मिरा पौडेल अधिकारी <br>
भाषा तथा वालवालीका कोर्डिनेटर <br>
गैर आवासिय नेपाली सेघ जर्मनी  

                </div>
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
// import { Head } from '@inertiajs/inertia-vue3'
export default {
components:{
    AppLayout,
    NrnaLayout,
    JetInput,
    JetButton,
    JetValidationErrors,
    // Head

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
      birth_year:null,
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


