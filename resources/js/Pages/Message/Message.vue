<template>
  <form class="m-2 p-4" @submit.prevent="submit">
    <div> 
    <label for="from">Send message from </label>
    <input class="bg-gray-200"  id="from" v-model="form.from" /> 
    </div>
    <div> 
    <label for="to">Send message to</label>
    <input  class="bg-gray-200" id="to" v-model="form.to" />
    </div>
      <div> 
    <label for="message">Message</label>
    <input class="bg-gray-200"  id="message" v-model="form.message" />
    </div>
    <div>
    <button type="submit">Submit</button>
    </div>
  </form>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  setup () {
    const form = reactive({
      from: "012345678",
      to: null,
      message: "your code is : 1234",
    })

    function submit() {
      Inertia.post('/messages', form)
    }

    return { form, submit }
  }, 
}
</script>