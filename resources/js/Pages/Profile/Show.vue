<template>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">User Profile</h1>
                <div class="text-gray-600">
                    <p class="mb-4">Profile page placeholder</p>
                    <a :href="route('dashboard')" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProfileShow',
};
</script>
