<template>
    <main class="profile-page">
        <profile-image :user="user" :isLoggedIn="isLoggedIn"> </profile-image>
        <section class="mx-2">
            <div class="flex items-start justify-between pt-2 text-gray-900">
                <div class="flex flex-col">
                    <!-- <p class="text-sm md:text-lg tracking-tight "> Member since 2002 </p> -->
                </div>
                <div>
                    <a
                        href=""
                        class="rounded-full bg-blue-500 py-2 px-4 text-xs text-white shadow"
                    >
                        Add me</a
                    >
                </div>
            </div>
            <div class="py-2 text-gray-900">
                <h2 class="text-bold mb-0 text-sm md:text-2xl">
                    {{ user.name }}
                </h2>
                <p v-if="user.country">
                    Living in: <span v-if="user.city"> {{ user.city }} </span>
                    <span v-if="user.country" class=""
                        >, {{ user.country }}
                    </span>
                </p>
                <p v-if="user.designation">
                    Designation: {{ user.designation }}
                </p>
            </div>
        </section>
        <section class="status my-2 border border-lime-200">
            status Currently: I am working on nrna profile
        </section>
        <section class="pinned mb-2 border border-lime-200">
            pinned text
        </section>

        <section class="aboutme mb-2 border border-lime-200">about me</section>

        <section class="aboutme mb-2 border border-lime-200">my review</section>
        <section class="review mb-2 border border-lime-200">
            <openions openionRoute="user.openions" :user="user"></openions>
        </section>
    </main>
</template>
<script>
// import ImageUpload  from "@/Components/Upload/ImageUpload.vue"
import ProfileImage from "@/Components/Profile/ProfileImage.vue";
import Openions from "@/Components/Discussion/Openions.vue";

export default {
    props: {
        user: Object,
        isLoggedIn: Boolean,
    },
    components: {
        // ImageUpload,
        ProfileImage,
        Openions,
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
    mounted() {
        // console.log("mounted");
        // console.log(this.user);
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
