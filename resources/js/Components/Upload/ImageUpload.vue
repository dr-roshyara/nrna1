<template>
    <div class="example">
        <cropper
            src="https://images.pexels.com/photos/226746/pexels-photo-226746.jpeg"
            ref="cropper"
        />
        <div class="button-wrapper">
            <span class="button" @click="cropImage">Crop image</span>
        </div>
    </div>
</template>

<script>
import Label from "@/Jetstream/Label";
import { useForm } from "@inertiajs/inertia-vue3";
import { processSlotOutlet } from "@vue/compiler-core";
import ImageCompressor from "vue-image-compressor";
import base64toblob from "base64toblob";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
export default {
    props: {
        user: Array,
        errors: Object,
        image_tpye: {
            type: String,
            default: "profile",
        },
    },
    components: {
        Label,
        ImageCompressor,
        Cropper,
    },
    data() {
        return {
            url: null,
            scale: 99,
            quality: 60,
            changeQuality: true,
            originalSize: true,
            original: {},
            //file
            file: {},
            result: {},
            reader: {},
            imgSrc: "",
            canvas: null,
            coordinates: {
                width: 0,
                height: 0,
                left: 0,
                top: 0,
            },
            image: null,
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
        onChangeCrops({ coordinates, canvas }) {
            this.coordinates = coordinates;
            // You able to do different manipulations at a canvas
            // but there we just get a cropped image, that can be used
            // as src for <img/> to preview result
            this.image = canvas.toDataURL();
        },
        submit() {
            this.form.image = this.redraw();
            if (this.form.image) {
                // this.form.image = this.$refs.photo.files[0];
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
            let imgSize = this.file.size / 1024;
            // console.log("imgSize: " + imgSize);
            /** change the quality of the imsage depending on the
             * kb size of the image . If the image is very larger than 280,
             *  reduce the quality. by its ratio with the image size
             */
            if (imgSize > 280) {
                this.quality = (280 / imgSize) * 100;
                this.changeQuality = true;
            } else {
                this.quality = 1;
                this.changeQuality = false;
            }
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
            // console.log("Test");
            // console.log(this.reader);
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
            // console.log("image size: " + img.width * img.height * 1024);
            let scale = this.scale / 100;
            let width = img.width * scale;
            let height = img.height * scale;
            // Set Canvas Height And Width According to Image Size And Scale
            this.canvas.setAttribute("width", width);
            this.canvas.setAttribute("height", height);
            ctx.drawImage(img, 0, 0, width, height);

            // this.canvas = this.convertImageToScaledCanvas(img, this.scale);

            // Quality Of Image
            let quality = this.quality ? this.quality / 100 : 1;
            // If all files have been proceed
            let base64 = null;
            if (this.changeQuality) {
                base64 = this.canvas.toDataURL("image/jpeg", quality);
            } else {
                base64 = this.canvas.toDataURL("image/jpeg");
            }

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
            // console.log(objToPass);
            return objToPass.compressed;
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
            // console.log(this.result);
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
        convertImageToScaledCanvas(image, scale) {
            // Recreate Canvas Element
            let canvas = document.createElement("canvas");
            // Set Canvas Context
            let ctx = canvas.getContext("2d");
            // Create New Image
            // Image Size After Scaling
            let width = image.width * scale;
            let height = image.height * scale;
            // Set Canvas Height And Width According to Image Size And Scale
            canvas.setAttribute("width", width);
            canvas.setAttribute("height", height);
            ctx.drawImage(image, 0, 0, width, height);
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
<style scoped>
.example-cropper {
    border: solid 1px #eee;
    min-height: 300px;
    width: 100%;
    height: 85vh;
}

.button-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 17px;
}

.button {
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    background: #35b392;
    cursor: pointer;
    transition: background 0.5s;
    font-family: Open Sans, Arial;
    margin: 0 10px;
}

.button:hover {
    background: #38d890;
}

.button input {
    display: none;
}
.cropper {
    height: 600px;
    width: 600px;
    background: #ddd;
}
</style>
