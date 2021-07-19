<template>
    <app-layout> 
         <!-- here is the table 
          -->           
          <div class="flex flex-col mb-10 py-10" >
                <!-- <div class="flex flex-rwow justify-center m-auto space-x-4 mb-2 py-2">
                   <inertia-link href="/candidacies/index" class=" p-2 mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> List of Candidates </inertia-link>
                    <inertia-link href="/posts/index" class=" p-2  mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> Name of Posts</inertia-link>
                     <inertia-link href="/notices/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> List of Notices</inertia-link>
 
                </div>  -->
             <div class="p-4 m-auto bg-gray-50"> 
             <h2 class="font-bold text-xl text-gray-800 leading-tight text-center">
                Candidacy Form | उम्मेद्बारी दर्ता औन्लाईन फराम 
            </h2>
            </div> 
              <!-- here starts  -->
           <!-- <div class="mx-auto text-center">
            <jet-validation-errors class="mb-4  mx-auto text-center " />
            </div> -->
         
                <form
                @submit.prevent="submit"
                class="
                    w-full
                    md:w-2/3
                    mx-auto
                    m-10
                    w-full
                    flex flex-wrap space-x-6
                    justify-center
                    items-center
                    border border-gray-200
                    shadow-lg
                    rounded-lg
                    px-4
                    py-4
                    md:py-12
                "
            >
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">Name </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.name"
                    />
                </div>
                <!-- next -->
                 <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">Membership ID </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.nrna_id"
                    />
                </div> 
                <!-- next  -->

                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">
                        Choose a Candidacy Post
                    </label>
                    <!-- <select name="cars" id="cars" form="carform"> -->
                    <select
                        class="w-full md:w-80 mb-4"
                        type="text"
                        id="post_name"
                        v-model="form.post_id"
                    >
                        <option
                            v-for="(post, postIndx) in posts"
                            :key="postIndx"
                            :value="post.post_id"
                        >
                            {{ post.name }}
                        </option>
                    </select>
                </div>
                <!-- next -->
                <!-- next -->
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">
                        Proposer's Name
                    </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.proposer_name"
                    />
                </div>
                <!-- next -->
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">
                        Proposer's Membership ID
                    </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.proposer_id"
                    />
                </div>

                <!-- next -->
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">
                        Supporters's Name
                    </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.supporter_name"
                    />
                </div>
                <!-- next -->
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl">
                        Supperter's Membership ID
                    </label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="text"
                        v-model="form.supporter_id"
                    />
                </div>
                <!-- next -->
                <div class="w-full md:w-auto flex flex-wrap">
                    <label class="w-full md:w-64 mt-2 text-xl"> Paymentslip</label>
                    <input
                        class="w-full md:w-80 mb-4"
                        type="file"
                        @input="form.image = $event.target.files[0]"
                    />
                </div>
                <!-- next -->
                <progress
                    v-if="form.progress"
                    :value="form.progress.percentage"
                    max="100"
                >
                    {{ form.progress.percentage }}%
                </progress>
                <div class="w-full my-4 mx-auto text-center">
                    <button
                        type="submit"
                        class="
                           text-center
                            w-full
                            md:w-auto
                            my-2
                            px-16
                            py-2
                            rounded-lg
                            bg-blue-800
                            hover:bg-blue-400
                            text-white
                        "
                    >
                        Submit
                    </button>
                </div>
                <div class="mx-auto text-center">
                <jet-validation-errors class="mb-4 mx-auto text-center" />
                </div>   
            
            </form>
         
               
              <!-- Here ends  -->
             </div>

        <!-- ends of table  -->
        
    </app-layout> 
    <!-- <div> <footer> </footer></div >  -->
</template>
<script>
import { useForm } from '@inertiajs/inertia-vue3'
 import JetValidationErrors from '@/Jetstream/ValidationErrors'
import AppLayout from "@/Layouts/NrnaLayout";


export default {
      props: {
        posts: Object,
        name: "",
    },
    setup(props) {
        const form = useForm({
            name: props.name,
            nrna_id:"",
            post_id: "",
            proposer_name: "",
            proposer_id: "",
            supporter_name: "",
            supporter_id: "",
            image: null,
        });
        // this.$inertia.post(route('candidacy.store'), data);
        function submit() {
            form.post("/candidacies");
        }

        return { form, submit };
    }, 
  components:{
      AppLayout,
    JetValidationErrors,
  }
} 
</script>