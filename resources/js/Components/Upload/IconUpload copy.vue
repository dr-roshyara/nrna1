<template>
    <div>
        <a class="btn" @click="toggleShow">Set Avatar</a>
        <img
            class="avatar"
            v-if="avatarUrl2"
            :src="avatarUrl2"
            v-show="true"
            style="display: none"
        />
        <div class="item">
            <a class="btn" @click="toggleShow3">аватара</a>
            <img
                class="avatar"
                v-if="avatarUrl3"
                :src="avatarUrl3"
                v-show="true"
                style="display: none"
            />
            <my-upload
                url="/avatar/upload"
                @crop-success="cropSuccess"
                @crop-upload-success="cropUploadSuccess"
                @crop-upload-fail="cropUploadFail"
                :no-circle="true"
                field="avatar3"
                ki="0"
                lang_type="en"
                v-model="show3"
            ></my-upload>
        </div>
        <img :src="imgDataUrl" />
    </div>
</template>

<script>
/****
 * Here we have changed the package file upload-3.vue
 * Used axios instead of xmlthttprequest directly.
 * Problem with xmlHttprequest is that  there is a problem to use crsf_token()
 *
 *
 *
 */
/** Here is changed part of the code :
   * This change in vue-upload file upload-3.vue
   *  Changed is written as comment within this comment
			that.reset();
			that.loading = 1;
			that.setStep(3);

    **remove new Promise(function(resolve, reject) {
	**remove let client = new XMLHttpRequest();
	**remove client.open(method, url, true);
	**remove client.withCredentials = withCredentials;
	**remove client.onreadystatechange = function() {
	**remove if (this.readyState !== 4) {
	**remove			return;
	**remove }
	**remove if (this.status === 200 || this.status === 201 || this.staus ===202 ) {
	**remove	resolve(JSON.parse(this.responseText));
	**remove } else {
	**remove		reject(this.status);
	**remove	}
	**remove };
	**remove client.upload.addEventListener("progress", uploadProgress, false); //监听进度
	**remove		// 设置header
	**remove if (typeof headers == 'object' && headers) {
	**remove	Object.keys(headers).forEach((k) => {
	**remove    client.setRequestHeader(k, headers[k]);
	**remove	})
    **remove	 }
    **remove	client.send(fmData);
    **remove   })

    **add this line
        axios.post(url, fmData)
    **keep as it is from here down.
        .then(
        function(resData) {
					if (that.modelValue) {
						that.loading = 2;
						that.$emit('crop-upload-success', resData, field, ki);
					}
				},
				// 上传失败
				function(sts) {
					if (that.modelValue) {
						that.loading = 3;
						that.hasError = true;
						that.errorMsg = lang.fail;
						that.$emit('crop-upload-fail', sts, field, ki);
					}
				}
			);
		}
    **/
import myUpload from "vue-image-crop-upload";
export default {
    components: {
        myUpload,
    },

    data() {
        return {
            show: false,
            params: {
                token: "12321",
                name: "avatar",
            },
            headers: {
                smail: "*_~",
            },
            imgDataUrl: "",
        };
    },
    methods: {
        toggleShow() {
            this.show = !this.show;
        },
        /**
         * crop success
         *
         * [param] imgDataUrl
         * [param] field
         */
        cropSuccess(imgDataUrl, field) {
            console.log("-------- crop success --------");
            this.imgDataUrl = imgDataUrl;
        },

        /**
         * upload success
         *
         * [param] jsonData  server api return data, already json encode
         * [param] field
         */
        cropUploadSuccess(jsonData, field) {
            console.log("-------- upload success --------");
            console.log(jsonData);
            console.log("field: " + field);
        },
        /**
         * upload fail
         *
         * [param] status    server api return error status, like 500
         * [param] field
         */
        cropUploadFail(status, field) {
            console.log("-------- upload fail --------");
            console.log(status);
            console.log("field: " + field);
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
