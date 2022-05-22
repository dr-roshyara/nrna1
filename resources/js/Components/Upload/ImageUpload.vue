<template>
   <div class="absolute z-20 bottom-0  translate-y-0 bg-gray-50  rounded w-full h-full">
        <img
            v-if="url"
            :src="url"
            class="w-full  h-full"
        />
    <div class="mx-auto w-full h-full ">
                <div class="overflow-hidden bg-gray-50 shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="File">File Upload</label>
                                <input
                                    type="file"
                                    @change="previewImage"
                                    ref="photo"
                                    class="
                                        w-full
                                        px-4
                                        py-2
                                        mt-2
                                        border
                                        rounded-md
                                        focus:outline-none
                                        focus:ring-1
                                        focus:ring-blue-600
                                    "
                                />

                                <div
                                    v-if="errors"
                                    class="font-bold text-red-600"
                                >
                                    {{ errors }}
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                <button
                                    class="
                                        px-6
                                        py-2
                                        text-white
                                        bg-gray-900
                                        rounded
                                    "
                                >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
    </div>



    </div>
</template>


<script>
import Label from '@/Jetstream/Label'
import { useForm } from "@inertiajs/inertia-vue3";
import { processSlotOutlet } from '@vue/compiler-core';
export default {
      props: {
       user: Array,
       errors: Object,
       image_tpye:String

   },
   components:{
       Label
   },

 data() {
    return {
      url: null,
    }
  },
    setup(props) {
        const form = useForm({
            image: null,
            image_tpye: props.image_tpye,

        });

        return { form };
    },
    methods: {
        submit() {
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
                // this.form.image_tpye =this.image_tpye;

            }
            this.form.post(route("image.store"));
        },
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
    },
};
</script>
