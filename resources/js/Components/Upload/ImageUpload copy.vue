<template>
    <div
        class="absolute bottom-0 z-20 h-full w-full translate-y-0 rounded-sm bg-gray-50"
    >
        <img v-if="url" :src="url" class="h-full w-full" />
        <div class="mx-auto h-full w-full">
            <div class="overflow-hidden bg-gray-50 shadow-xs sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <div>
                                <label for="File">File Upload</label>
                            </div>
                            <div>
                                <!-- <button @click="upload">Upload</button> -->
                                <!-- <image-compressor
                                    :done="getFiles"
                                    :scale="scale"
                                    :quality="quality"
                                    ref="compressor"
                                    @change="previewImage"
                                ></image-compressor> -->
                                <input
                                    type="file"
                                    accept=".jpg, .jpeg, .png"
                                    ref="photo"
                                    class="mt-2 w-full rounded-md border px-4 py-2 focus:outline-hidden focus:ring-1 focus:ring-blue-600"
                                    @change="onChange"
                                    multiple
                                />
                                <div class="text-center" v-if="img">
                                    <img v-if="img" src="" alt="" :src="img" />
                                </div>
                            </div>

                            <div v-if="errors" class="font-bold text-red-600">
                                {{ errors }}
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <button
                                class="rounded-sm bg-gray-900 px-6 py-2 text-white"
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
import Label from "@/Components/Jetstream/Label.vue";
import { useForm } from "@inertiajs/vue3";
import { processSlotOutlet } from "@vue/compiler-core";
import ImageCompressor from "vue-image-compressor";
import base64toblob from "base64toblob";
export default {
    props: {
        user: Array,
        errors: Object,
        image_tpye: {
            type: String,
            default: "profile",
        },
        // // Image Scale Percentage (1 - 100)
        // scale: {
        //     type: Number,
        //     default: 100,
        // },
        // // Image Scale Percentage (1 - 100)
        // quality: {
        //     type: Number,
        //     default: 100,
        // },
        // Pass the files info when it's done
        // done: {
        //     type: Function,
        //     default: () => {},
        // },
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
            quality: 50,
            originalSize: true,
            original: {},
            //file
            file: {},
            result: {},
            reader: {},
            imgSrc: "",
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
            this.form.image = this.redraw();
            if (this.form.image) {
                // this.form.image = this.$refs.photo.files[0];
                // // this.form.image_tpye =this.image_tpye;
                this.$emit("image-uploaded");
                this.form.post(route("image.store"));
            }
        },
        /*
        When Input File has changed
      */
        onChange(e) {
            // get the file
            this.file = e.target.files[0];
            this.url = URL.createObjectURL(this.file);
            // Validation
            let type = this.file.type;
            let valid = type.indexOf("image") !== -1;
            if (!valid)
                throw "File Type Is Not Supported. Upload an image instead";
            // Make new FileReader
            this.reader = new FileReader();
            // Convert the file to base64 text
            this.reader.readAsDataURL(this.file);
            // on reader load somthing...
            this.reader.onload = this.fileOnLoad;
            // convas = this.convertImageToCanvas(this.file);
            console.log("Test");
            console.log(this.reader);
        },
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        getFiles(obj) {
            console.log(obj);
            this.form.image = obj;
        },
        getFiles1(obj) {
            this.img = obj.compressed.blob;
            this.original = obj.original;
            this.compressed = obj.compressed;
        },
        dataURItoBlob(dataURI) {
            // convert base64 to raw binary data held in a string
            var byteString = atob(dataURI.split(",")[1]);
            // separate out the mime component
            var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
            // write the bytes of the string to an ArrayBuffer
            var arrayBuffer = new ArrayBuffer(byteString.length);
            var _ia = new Uint8Array(arrayBuffer);
            for (var i = 0; i < byteString.length; i++) {
                _ia[i] = byteString.charCodeAt(i);
            }
            var dataView = new DataView(arrayBuffer);
            var blob = new Blob([dataView], { type: mimeString });
            return blob;
        },
        uploadImages(file) {
            if (!file) return false;
            // Validation
            let type = this.file.type;
            let valid = type.indexOf("image") !== -1;
            if (!valid)
                throw "File Type Is Not Supported. Upload an image instead";
            // Make new FileReader
            this.reader = new FileReader();
            // Convert the file to base64 text
            this.reader.readAsDataURL(this.file);
            // on reader load somthing...
            this.reader.onload = this.fileOnLoad;
        },
        /*
        Draw And Compress The Image
        @params {String} imgUrl
      */
        drawImage(imgUrl) {
            // Recreate Canvas Element
            let canvas = document.createElement("canvas");
            this.canvas = canvas;
            // Set Canvas Context
            let ctx = this.canvas.getContext("2d");
            // Create New Image
            let img = new Image();
            img.src = imgUrl;
            // Image Size After Scaling
            let scale = this.scale / 100;
            let width = img.width * scale;
            let height = img.height * scale;
            // Set Canvas Height And Width According to Image Size And Scale
            this.canvas.setAttribute("width", width);
            this.canvas.setAttribute("height", height);
            ctx.drawImage(img, 0, 0, width, height);
            // Quality Of Image
            let quality = this.quality ? this.quality / 100 : 1;
            // If all files have been proceed
            let base64 = this.canvas.toDataURL("image/jpeg", quality);
            let fileName = this.result.file.name;
            let lastDot = fileName.lastIndexOf(".");
            fileName = fileName.substr(0, lastDot) + ".jpeg";
            let objToPass = {
                canvas: this.canvas,
                original: this.result,
                compressed: {
                    blob: this.toBlob(base64),
                    base64: base64,
                    name: fileName,
                    file: this.buildFile(base64, fileName),
                },
            };
            objToPass.compressed.size =
                Math.round(objToPass.compressed.file.size / 1000) + " kB";
            objToPass.compressed.type = "image/jpeg";
            // this.done(objToPass);
            return objToPass;
        },
        /*
        Redraw the canvas
      */
        redraw() {
            if (this.result.base64) {
                let compressed = this.drawImage(this.result.base64);
                return compressed;
            }
        },
        /*
        When The File in loaded
      */
        fileOnLoad() {
            // The File
            let { file } = this;
            // Make a fileInfo Object
            let fileInfo = {
                name: file.name,
                type: file.type,
                size: Math.round(file.size / 1000) + " kB",
                base64: this.reader.result,
                file: file,
            };
            // Push it to the state
            this.result = fileInfo;
            // DrawImage
            this.drawImage(this.result.base64);
            console.log(this.result);
        },
        // Convert Base64 to Blob
        toBlob(imgUrl) {
            let blob = base64toblob(imgUrl.split(",")[1], "image/jpeg");
            let url = window.URL.createObjectURL(blob);
            return url;
        },
        // Convert Blob To File
        buildFile(blob, name) {
            return new File([blob], name);
        },
        // Converts image to canvas; returns new canvas element
        convertImageToCanvas(image) {
            var canvas = document.createElement("canvas");
            canvas.width = image.width;
            canvas.height = image.height;
            canvas.getContext("2d").drawImage(image, 0, 0);

            return canvas;
        },
        // Converts canvas to an image
        convertCanvasToImage(canvas) {
            var image = new Image();
            image.src = canvas.toDataURL("image/png");
            return image;
        },
        convertCanvasToImage1(canvas, callback) {
            var image = new Image();
            image.onload = function () {
                callback(image);
            };
            image.src = canvas.toDataURL("image/png");
        },
    },
};
</script>
