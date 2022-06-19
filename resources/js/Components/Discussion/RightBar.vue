<template>
    <div class="ml-4 min-h-screen rounded-lg bg-slate-100 p-2">
        <h1 class="mb-4 text-xl font-bold text-blue-800">Friends</h1>
        <ul v-for="friend in friendList">
            <li class="mb-2">
                <div class="flex items-center">
                    <!-- <img
                        class="mr-2 max-h-8 rounded-full"
                        src="https://i.pravatar.cc/100"
                        alt="avatar"
                    />
                    john Doe -->
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: {
        user: Object,
    },
    data() {
        return {
            friendList: [1, 2, 4, 5, 6, 10],
        };
    },
};
</script>
