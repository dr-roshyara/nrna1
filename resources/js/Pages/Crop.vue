<template>
    testing the image
    <cropper :src="img" />
</template>
<script>
import { Cropper } from "vue-advanced-cropper";
// Add the following line to import the cropper styles
import "vue-advanced-cropper/dist/style.css";

export default {
    components: {
        Cropper,
    },
    data() {
        return {
            img: "https://images.pexels.com/photos/4323307/pexels-photo-4323307.jpeg",
        };
    },
    methods: {
        reset() {
            this.image = null;
        },
        uploadImage() {
            const { canvas } = this.$refs.cropper.getResult();
            if (canvas) {
                const form = new FormData();
                canvas.toBlob((blob) => {
                    form.append("file", blob);
                    // You can use axios, superagent and other libraries instead here
                    fetch("http://example.com/upload/", {
                        method: "POST",
                        body: form,
                    });
                    // Perhaps you should add the setting appropriate file format here
                }, "image/jpeg");
            }
        },
    },
};
</script>
