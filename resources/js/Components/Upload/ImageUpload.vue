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
                            <div>
                                <label for="File">File Upload</label>
                            </div>
                            <div>
                                <!-- <button @click="upload">Upload</button> -->
                                <image-compressor
                                    :done="getFiles"
                                    :scale="scale"
                                    :quality="quality"
                                    ref="img"
                                    @change="previewImage"
                                >
                                </image-compressor>
                                <div class="text-center" v-if="img">
                                    <img v-if="img" src="" alt="" :src="img" />
                                </div>
                            </div>
                            <!-- <input
                                type="file"
                                @change="previewImage"
                                ref="photo"
                                class="mt-2 w-full rounded-md border px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600"
                            /> -->

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
import ImageCompressor from "vue-image-compressor";
export default {
    props: {
        user: Array,
        errors: Object,
        image_tpye: String,
    },
    components: {
        Label,
        ImageCompressor,
    },

    data() {
        return {
            url: null,
            img: "",
            scale: 100,
            quality: 60,
            originalSize: true,
            original: {},
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
            // let compressor = this.$refs.compressor.$el;
            console.log(this.$refs);
            // this.form.image = this.$refs.compressor.$el;
            this.form.image = this.compressed;
            // console.log(this.compressed);
            // this.form.image_tpye =this.image_tpye;
            this.$emit("image-uploaded");
            // compressor.click();
            this.form.post(route("image.store"));
            // if (this.$refs.photo) {
            //     this.form.image = this.$refs.photo.files[0];
            //     // this.form.image_tpye =this.image_tpye;
            //     this.$emit("image-uploaded");
            // }
            // this.form.post(route("image.store"));
        },
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        upload() {
            let compressor = this.$refs.compressor.$el;
            this.form.image = this.$refs.compressor.$el;
            // this.form.image_tpye =this.image_tpye;
            this.$emit("image-uploaded");

            compressor.click();
            this.form.post(route("image.store"));
        },
        getFiles(obj) {
            this.img = obj.compressed.blob;
            this.original = obj.original;
            this.compressed = obj.compressed;
        },
    },
};
</script>
