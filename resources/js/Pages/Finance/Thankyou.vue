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
import SocialLayout from "@/Layouts/SocialLayout";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        SocialLayout,
        JetButton,
    },
};
</script>
