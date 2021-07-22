<template>
 <jet-validation-errors class="pt-1" />
  <form class="" @submit.prevent="submit">
    <!-- <div> 
    <input class="bg-gray-200"  id="from" v-model="form.from" /> 
    </div> -->
    <div> 
    <input  class="bg-gray-200  mx-2 py-2 hidden" id="to" v-model="form.to"  />
    </div>
      <div> 
        <!-- <select> <option :value="vt" v-model=""> </option> </select> -->
        <!-- <input class="bg-gray-200"  id="message" v-model="form.message" /> -->
    </div>
    <div class="flex flex-col justify-center  m-1 p-2 bg-blue-300 rounded-lg"> 
    <button class="px-2 text-bold text-xl " type="submit"> Send Voting Code </button>
    </div> 
  </form>   
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
export default {
    props: {
        vtelephone: String,
        message_receiver_id: Number,
        // message_receiver_name: String

    },
  setup (props) {
    const form = reactive({ 
      from: "012345678",
      to: props.vtelephone,
      message_receiver_id: props.message_receiver_id,
      // message_receiver_id:props.message_receiver_name,
      message: "your code is : 1234",
    })

    function submit() {
      Inertia.post('/messages', form)
    }

    return { form, submit }
  },
  components:{
    JetValidationErrors
  } 
}
</script>