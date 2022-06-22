<template>
    <div class="flex flex-col bg-gray-50">
        <div
            class="md:flex-warp item-center md: flex w-full flex-col justify-center rounded border border-blue-100 p-2"
        >
            <div class="flex-center flex items-center justify-around space-x-4">
                <!-- <div id="logo" style="background-image: url('/logo-2.png')"> </div> -->
                <div id="logo" class="flex-none sm:h-8 sm:w-8 md:h-12 md:w-12">
                    <a href="/">
                        <img
                            class="object-cover"
                            src="/images/logo-2.png"
                            width="60"
                            height="60"
                        />
                    </a>
                </div>
                <div class="ml-4 w-3/4 px-2">
                    <div
                        class="flex flex-row items-center justify-around sm:mt-0"
                    >
                        <Link
                            class="flex flex-col items-center"
                            :href="route('dashboard')"
                        >
                            <svg
                                class="my-auto h-8 w-8 text-blue-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                            <span class="text-sm">start</span>
                        </Link>
                        <!-- The following icon is for search  -->
                        <!-- <div class="flex flex-col items-center self-end">
                            <svg
                                class="my-auto h-6 w-6 text-blue-600"
                                aria-hidden="true"
                                focusable="false"
                                data-prefix="fas"
                                data-icon="search"
                                role="img"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <path
                                    fill="currentColor"
                                    d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"
                                ></path>
                            </svg>
                            <span class="text-sm">Search</span>
                        </div> -->
                        <div class="flex flex-col items-center self-end">
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <span class="text-sm">Friends</span>
                        </div>
                        <!--edit form  -->
                        <a
                            v-if="this.isLoggedIn && this.userLoggedIn"
                            :href="user.profileEditUrl"
                        >
                            <div class="flex flex-col items-center self-end">
                                <svg
                                    class="h-8 w-8 text-blue-600"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                    />
                                    <path
                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                    />
                                </svg>
                                <span class="text-sm">Edit</span>
                            </div>
                        </a>
                        <div class="flex flex-col items-center">
                            <svg
                                class="h-10 w-10 text-blue-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                                />
                            </svg>
                            <span class="text-sm">Menu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-auto hidden text-xl font-bold text-white sm:block">
                <!-- <a href="/"> Non Resident Nepali Association </a> -->
            </div>
        </div>

        <!-- start  -->
        <!--        <inertia-link href="#" class="m-auto" >
                      <div class=" flex flex-col h-64 py-2 m-auto text-center px-10 w-full">
                       <p class="w-full text-center text-gray-900 font-bold">
                         VOTE  HERE </p>
                       <img src="/storage/images/ballot.png" width="200" height="200" />
                    </div>

            </inertia-link>-->
        <!-- <inertia-link href="/dashboard" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Dashboard</inertia-link>
                    <inertia-link href="/voters/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Voter's List </inertia-link>
                    <inertia-link href="/users/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> Member's List </inertia-link>
                    <inertia-link href="/candidacies/index" class=" p-2 mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> List of Candidates </inertia-link>
                    <inertia-link href="/candidacy/create" class=" p-2 bg-red-50 font-bold text-gray-900 rounded border border-right "> Candidacy Form </inertia-link>
                    <inertia-link href="/posts/index" class=" p-2  mx-2 bg-red-50 font-bold text-gray-900 border-right rounded-sm"> Name of Posts</inertia-link>
                     <inertia-link href="/notices/index" class=" p-2   mx-2 bg-red-50 font-bold text-gray-900 rounded-sm"> List of Notices</inertia-link> -->

        <!-- end  -->
    </div>
</template>
<script>
import SearchLoop from "@/Components/SearchLoop.vue";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        isLoggedIn: Boolean,
        user: Array,
        userLoggedIn: Boolean,
    },
    components: {
        SearchLoop,
        Link,
    },
};
</script>
