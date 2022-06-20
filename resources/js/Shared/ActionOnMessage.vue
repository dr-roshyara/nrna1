<template>
    <section>
        <div class="-z-1 relative">
            <button
                v-if="!showActionBar"
                @click="this.showActionBar = !this.showActionBar"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                    />
                </svg>
            </button>
            <button v-else>
                <span
                    @click="this.showActionBar = !this.showActionBar"
                    v-if="showActionBar"
                    class="rounded-full bg-slate-100 px-2 text-lg font-extrabold text-gray-700 shadow-md ring-1 ring-blue-300 ring-offset-1"
                    >X</span
                >
            </button>
        </div>
        <div
            :class="showActionBar ? 'block' : 'hidden'"
            class="absolute z-10 ml-4 w-3/4 -translate-x-full rounded border-b border-slate-50 bg-zinc-100 sm:max-w-sm"
        >
            <ul class="z-20 mb-2 grid grid-cols-1 justify-end border">
                <li class="px-2" v-if="userLoggedIn">
                    <a href="/openion/edit">
                        <div
                            class="bottom-1 flex flex-row items-center justify-start space-x-1 border-b border-stone-50"
                        >
                            <!-- Edit Icon to edit a post  -->

                            <svg
                                class="h-4 w-6 text-blue-500"
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
                                <path d="M12 20h9" />
                                <path
                                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                                />
                            </svg>
                            <span> Edit post </span>
                        </div>
                    </a>
                </li>
                <!-- Delete icon  -->

                <li class="px-2" v-if="userLoggedIn">
                    <a href="/openion/edit">
                        <div
                            class="bottom-1 flex flex-row items-center justify-start space-x-1 border-b border-stone-50"
                        >
                            <!-- Edit Icon to edit a post  -->

                            <svg
                                class="h-6 w-6 text-red-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                            <span> Delete post </span>
                        </div>
                    </a>
                </li>
                <li class="p-2">
                    <a href="#">
                        <div
                            class="flex flex-row items-center justify-start space-x-1"
                        >
                            <svg
                                class="h-6 w-6 text-blue-600"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path
                                    d="M15 13l4 -4l-4 -4m4 4h-11a4 4 0 0 0 0 8h1"
                                />
                            </svg>
                            <span> Share the link</span>
                        </div>
                    </a>
                </li>
                <li class="p-2">
                    <a href="#">
                        <div
                            class="flex flex-row items-center justify-start space-x-1"
                        >
                            <svg
                                class="h-6 w-6 text-blue-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                />
                            </svg>

                            <span> Copy the link </span>
                        </div>
                    </a>
                </li>
                <!-- <li class="p-2">Action Bar3</li>
                <li class="p-2">Action Bar4</li>
                <li></li>
                <li></li> -->
            </ul>
        </div>
    </section>
</template>
<script>
export default {
    props: {
        user: Object,
        userLoggedIn: Boolean,
    },

    data() {
        return {
            showActionBar: false,
        };
    },
};
</script>
