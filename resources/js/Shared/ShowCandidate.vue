<template>
    <div class="m-auto w-full text-center">
        <!-- Candidate Name -->
        <p class="mx-auto font-bold text-xl text-indigo-700 mb-2">
            {{ candidacy_name }}
        </p>

        <!-- Candidate Photo Area -->
        <div class="mx-auto w-full flex justify-center items-center bg-linear-to-b from-gray-50 to-white rounded-lg p-2">
            <!-- Actual Candidate Photo (if provided) -->
            <img
                v-if="imageGiven && !isSvgImage"
                :src="full_image_name"
                :alt="`${candidacy_name} - Candidate Photo`"
                class="mx-auto rounded-lg object-cover py-1 sm:h-40 sm:w-40 md:h-40 md:w-40 max-h-48"
                @error="handleImageError"
            />

            <!-- SVG Icon (if provided or as fallback) -->
            <img
                v-else-if="isSvgImage"
                :src="full_image_name"
                :alt="`${candidacy_name} - Candidate Icon`"
                class="mx-auto rounded-lg object-contain p-4 sm:h-40 sm:w-40 md:h-40 md:w-40 max-h-48"
                @error="handleImageError"
            />

            <!-- Default SVG Fallback Icon (when no image provided) -->
            <div v-else class="mx-auto flex justify-center items-center">
                <img
                    :src="defaultSvgIcon"
                    :alt="`${candidacy_name} - Default Candidate Icon`"
                    class="mx-auto rounded-lg object-contain p-4 sm:h-40 sm:w-40 md:h-40 md:w-40 max-h-48 grayscale hover:grayscale-0 transition-all"
                />
            </div>
        </div>

        <!-- Candidate Position Info -->
        <p class="mx-auto font-bold text-gray-900">
            Candidate for {{ post_name }}
        </p>
        <p class="mx-auto font-bold text-gray-900">
            {{ post_nepali_name }} पदको उमेद्ववार
        </p>
    </div>
</template>

<script>
import { computed } from "@vue/runtime-core";

export default {
    props: {
        post_name: String,
        candidacy_name: String,
        post_nepali_name: String,
        candidacy_image_path: String,
    },

    computed: {
        full_image_name() {
            return this.image_path + this.candidacy_image_path;
        },

        imageGiven() {
            return (
                this.candidacy_image_path &&
                this.candidacy_image_path !== "-" &&
                this.candidacy_image_path !== ""
            );
        },

        isSvgImage() {
            return (
                this.imageGiven &&
                this.candidacy_image_path.toLowerCase().endsWith(".svg")
            );
        },
    },

    data() {
        return {
            image_path: "/storage/images/",
            defaultSvgIcon: "/storage/images/icons8-human.svg", // Default fallback icon
            imagename: this.image_path + this.candidacy_image_path,
        };
    },

    methods: {
        /**
         * Handle image loading errors
         * Falls back to default SVG icon if image fails to load
         */
        handleImageError(event) {
            console.warn(
                `Failed to load candidate image: ${event.target.src}`
            );
            // Keep the error image as is, it will show the broken image icon
            // Or we could replace it with the default SVG
        },
    },
};
</script>

<style scoped>
/* Add subtle hover effect for SVG icons */
img[src$=".svg"] {
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.05));
    transition: filter 0.3s ease;
}

img[src$=".svg"]:hover {
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}

/* Ensure grayscale effect works smoothly */
.grayscale {
    filter: grayscale(100%) drop-shadow(0 1px 2px rgba(0, 0, 0, 0.05));
}

.grayscale:hover {
    filter: grayscale(0%) drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}
</style>
