<template>
    <!-- This section is shown either
        1. background Image is given
        2. Icon image is given or
        3. user is logged in
     -->
    <section
        v-if="bgUrlValid | iconUrlValid | isLoggedIn"
        class="relative mb-1 block flex w-full flex-col bg-[url('/storage/users/background.jpg')] md:h-64"
    >
        <div class="align-center min-h-40 flex h-full w-full justify-center">
            <!-- src="images/background_images/nab_raj_roshyara.JPG" -->
            <!-- {{ user.profile_bg_photo_path }} -->
            <img
                v-if="bgUrlValid"
                :src="user.profile_bg_photo_path"
                @error="onBgImageError"
                class="md:max-h-100 max-h-64 w-full rounded object-cover"
            />
            <div
                class="h-70-px pointer-events-none absolute top-auto bottom-0 left-0 right-0 w-full overflow-hidden"
                style="transform: translateZ(0px)"
            >
                <!-- Camera icon  -->
                <svg
                    class="absolute bottom-0 overflow-hidden"
                    xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="none"
                    version="1.1"
                    viewBox="0 0 2560 100"
                    x="0"
                    y="0"
                >
                    <polygon
                        class="text-blueGray-200 fill-current"
                        points="2560 0 2560 100 0 100"
                    ></polygon>
                </svg>
            </div>
        </div>
        <div
            v-if="iconUrlValid"
            class="absolute bottom-0 mr-2"
            style="left: 50%"
        >
            <img
                v-if="iconUrlValid"
                alt="..."
                :src="user.profile_icon_photo_path"
                @error="onIconImageError"
                class="h-28 w-28 -translate-x-1/2 translate-y-1/2 rounded-full object-cover"
            />
        </div>
        <div
            @click="editBackground = !editBackground"
            v-if="isLoggedIn"
            class="absolute bottom-0 ml-2"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
        </div>
        <div
            v-show="isLoggedIn"
            class="absolute bottom-0 z-10 h-full w-full translate-y-0 rounded bg-blue-100"
            v-if="editBackground"
        >
            <div class="absolute bottom-0">
                <div
                    @click="uploadBackground = !uploadBackground"
                    class="max-w-80 mt-1 mb-2 flex flex-wrap items-center rounded-lg bg-blue-300 py-2 px-4 text-sm ring-2 ring-blue-400/50"
                >
                    <svg
                        class="h-8 w-8 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"
                        />
                    </svg>
                    <span> Upload new background picture </span>
                </div>

                <div
                    @click="uploadIcon = !uploadIcon"
                    class="max-w-80 mt-1 mb-2 flex flex-wrap items-center rounded-lg bg-blue-300 py-2 px-4 text-sm ring-2 ring-blue-400/50"
                >
                    <svg
                        class="h-8 w-8 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"
                        />
                    </svg>
                    <span> Upload new icon picture in the middle </span>
                </div>

                <!-- <div class="max-w-60 text-sm rounded-lg py-2 mt-1 mb-2 flex flex-wrap items-center ring-2 bg-blue-300 ring-blue-400/50 ">

                    <svg class="h-8 w-8 text-blue-600"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                    </svg>   <span>Select new  picture </span>
            </div> -->
            </div>
        </div>
        <!-- Upload back groud  -->
        <image-upload v-if="uploadBackground" image_type="profile">
        </image-upload>
    </section>
</template>
<script>
import ImageUpload from "@/Components/Upload/ImageUpload.vue";
export default {
    props: {
        user: Array,
        isLoggedIn: Boolean,
    },
    components: {
        ImageUpload,
    },
    data() {
        return {
            editBackground: false,
            uploadBackground: false,
            uploadIcon: false,
            bgUrlValid: true,
            iconUrlValid: true,
        };
    },
    methods: {
        onBgImageError() {
            this.bgUrlValid = false;
        },
        onIconImageError() {
            this.iconUrlValid = false;
        },
    },
};
</script>
