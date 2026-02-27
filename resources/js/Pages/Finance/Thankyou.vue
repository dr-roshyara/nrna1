<template>
    <social-layout>
        <div class="flex min-h-screen flex-col items-center">
            <div class="mx-auto py-2 text-gray-800">
                <p class="mx-auto text-xl text-red-500">
                    Thank you so much for updating your financial statement.
                </p>
                <div class="flex flex-col items-center py-2">
                    <p class="mx-auto mb-4">
                        If you would like to see more feature of the website,
                        please click on the register button.
                    </p>
                    <!-- <JetButton class="">
                        <a :href="route('register')"> Register</a>
                    </JetButton> -->
                </div>
            </div>
        </div>
    </social-layout>
</template>

<script>
import SocialLayout from "@/Layouts/SocialLayout.vue";
import JetAuthenticationCard from "@/Components/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Components/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Components/Jetstream/Button.vue";
import JetInput from "@/Components/Jetstream/Input.vue";
import JetCheckbox from "@/Components/Jetstream/Checkbox.vue";
import JetLabel from "@/Components/Jetstream/Label.vue";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";

export default {
    components: {
        SocialLayout,
        JetButton,
    },
};
</script>
