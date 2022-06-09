<template>
    <div
        class="absolute bottom-0 z-20 h-full w-full translate-y-0 rounded bg-gray-50"
    >
        <img v-if="url" :src="url" class="h-full w-full" />
        <div class="mx-auto h-full w-full">
            <div class="overflow-hidden bg-gray-50 shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <label for="File">File Upload</label>
                            <input
                                type="file"
                                @change="previewImage"
                                ref="photo"
                                class="mt-2 w-full rounded-md border px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600"
                            />

                            <div v-if="errors" class="font-bold text-red-600">
                                {{ errors }}
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <button
                                class="rounded bg-gray-900 px-6 py-2 text-white"
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
import Label from "@/Jetstream/Label";
import { useForm } from "@inertiajs/inertia-vue3";
import { processSlotOutlet } from "@vue/compiler-core";
export default {
    props: {
        user: Array,
        errors: Object,
        image_tpye: String,
    },
    components: {
        Label,
    },

    data() {
        return {
            url: null,
        };
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
                this.$emit("image-uploaded");
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
