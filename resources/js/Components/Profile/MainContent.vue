<template>
    <main class="profile-page">
        <profile-image
            :user="user"
            :isLoggedIn="isLoggedIn"
            :userLoggedIn="userLoggedIn"
        >
        </profile-image>
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
                <h2 class="mb-0 text-sm font-bold md:text-2xl">
                    {{ user.name }}
                </h2>
                <p v-if="user.country">
                    <span class="font-bold text-gray-800">Living in: </span>
                    <span v-if="user.city"> {{ user.city }} </span>
                    <span v-if="user.country" class=""
                        >, {{ user.country }}
                    </span>
                </p>
                <p v-if="user.designation">
                    Designation: {{ user.designation }}
                </p>
            </div>
        </section>
        <section class="mx-2 mb-2 border border-stone-100 py-2">
            <span class="font-bold text-gray-800"> status- </span> I am working
            on nrna profile
        </section>
        <!-- <section class="pinned mb-2 border border-lime-200">
            pinned text
        </section> -->
        <section class="min-h-20 mx-2 mb-2 border border-stone-100 py-2">
            About me
        </section>

        <section class="mx-2 mb-2 border border-stone-100">My reviews</section>
        <section class="mb-2 border border-stone-100">
            <openions
                :openionRoute="openionRoute"
                :user="user"
                :userLoggedIn="userLoggedIn"
            ></openions>
        </section>
    </main>
</template>
<script>
// import ImageUpload  from "@/Components/Upload/ImageUpload.vue"
import ProfileImage from "@/Components/Profile/ProfileImage.vue";
import Openions from "@/Components/Discussion/Openions.vue";

export default {
    props: {
        user: Array,
        isLoggedIn: Boolean,
        userLoggedIn: Boolean,
    },
    components: {
        // ImageUpload,
        ProfileImage,
        Openions,
    },
    computed: {
        userId() {
            return this.user.id;
        },
        openionRoute() {
            return "/openions/" + this.userId;
        },
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
