<template>
    <div>
        <a
            class="btn mb-4 rounded-lg bg-blue-300 py-4 px-4"
            @click="toggleShow3"
            >Upload your avatar photo</a
        >
        <img
            class="avatar"
            v-if="avatarUrl3"
            :src="avatarUrl3"
            v-show="true"
            style="display: none"
        />
        <my-upload
            url="/avatar/upload"
            img-format="JPG"
            img-bgc="#fff"
            v-model="show3"
            field="avatar1"
            lang-type="en"
            ki="0"
            @crop-success="cropSuccess"
            @crop-upload-success="cropUploadSuccess"
            @crop-upload-fail="cropUploadFail"
            :no-rotate="false"
            :params="params"
            headers="headers"
        >
        </my-upload>
        <img :src="imgDataUrl" />
    </div>
</template>

<script>
import myUpload from "vue-image-crop-upload";
export default {
    components: {
        myUpload,
    },

    data() {
        return {
            show1: false,
            show2: false,
            show3: false,
            avatarUrl1: null,
            avatarUrl2: null,
            avatarUrl3: null,
            params: {
                _token: "{{ csrf_token() }} ",
                name: "img",
            },
            show: false,

            csrfToken: " {{csrf_token() }}",
            headers: {
                "X-CSRF-TOKEN": this.csrfToken,
                // 'Content-type':"application/x-www-form-urlencoded",
                // 'X-Requested-With': 'XMLHttpRequest'
                // 'X-Requested-With': 'XMLHttpRequest',
                // 'Content-type': 'multipart/form-data',
                // 'X-CSRF-Token': FD.get("_token")
            },
            imgDataUrl: "",
        };
    },
    methods: {
        toggleShow1: function toggleShow1() {
            var show1 = this.show1;

            this.show1 = !show1;
        },
        toggleShow2: function toggleShow2() {
            var show2 = this.show2;

            this.show2 = !show2;
        },
        toggleShow3: function toggleShow3() {
            var show3 = this.show3;
            this.show3 = !show3;
            // console.log("closing");
        },
        cropSuccess: function cropSuccess(data, field, key) {
            if (field == "avatar1") {
                this.avatarUrl1 = data;
            } else if (field == "avatar2") {
                this.avatarUrl2 = data;
            } else {
                this.avatarUrl3 = data;
            }
            console.log("-------- 剪裁成功 --------");
        },
        cropUploadSuccess: function cropUploadSuccess(data, field, key) {
            console.log("-------- success message --------");
            console.log(data);
            console.log("field: " + field);
            console.log("key: " + key);
            // this.route("user.show", this.user.user_id).click();
            this.$emit("icon-uploaded");
        },
        cropUploadFail: function cropUploadFail(status, field, key) {
            console.log("-------- 上传失败 --------");
            console.log(status);
            console.log("field: " + field);
            console.log("key: " + key);
        },
        //end of methods
    },
    props: {
        // field name
        field: {
            type: String,
            default: "avatar",
        },
        // unique key
        ki: {
            type: String,
            default: "0",
        },
        // shows the component
        modelValue: {
            type: Boolean,
            default: true,
        },
        // upload url
        url: {
            type: String,
            default: "",
        },
        // more object parameters
        params: {
            type: Object,
            default: () => null,
        },
        // add custom headers
        headers: {
            type: Object,
            default: () => null,
        },
        // width
        width: {
            type: Number,
            default: 200,
        },
        // height
        height: {
            type: Number,
            default: 200,
        },
        // disable rotate
        noRotate: {
            type: Boolean,
            default: true,
        },
        // disable circle image
        noCircle: {
            type: Boolean,
            default: false,
        },
        // disable square image
        noSquare: {
            type: Boolean,
            default: false,
        },
        // max size
        maxSize: {
            type: Number,
            default: 10240,
        },
        // language
        langType: {
            type: String,
            default: "en",
        },

        // language package
        langExt: {
            type: Object,
            default: () => null,
        },

        // image format
        imgFormat: {
            type: String,
            default: "png",
        },
        // image background
        imgBgc: {
            type: String,
            default: "#fff",
        },
        // allows cross domain
        withCredentials: {
            type: Boolean,
            default: false,
        },
        // upload method
        method: {
            type: String,
            default: "POST",
        },
        // initial image url
        initialImgUrl: {
            type: String,
            default: "",
        },
        // allowed image format
        allowImgFormat: {
            type: Array,
            default: () => ["gif", "jpg", "png"],
        },
    },
};
</script>
