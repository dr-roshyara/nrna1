<template>
    <nrna-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </template>

        <div>
            <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <update-profile-information-form :user="$page.props.user" />

                    <jet-section-border />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <update-password-form class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <div
                    v-if="
                        $page.props.jetstream.canManageTwoFactorAuthentication
                    "
                >
                    <two-factor-authentication-form class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <logout-other-browser-sessions-form
                    :sessions="sessions"
                    class="mt-10 sm:mt-0"
                />

                <template
                    v-if="$page.props.jetstream.hasAccountDeletionFeatures"
                >
                    <jet-section-border />

                    <!-- <delete-user-form class="mt-10 sm:mt-0" /> -->
                </template>
            </div>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/NrnaLayout";
// import DeleteUserForm from './DeleteUserForm'
import JetSectionBorder from "@/Jetstream/SectionBorder";
import LogoutOtherBrowserSessionsForm from "./LogoutOtherBrowserSessionsForm";
import TwoFactorAuthenticationForm from "./TwoFactorAuthenticationForm";
import UpdatePasswordForm from "./UpdatePasswordForm";
import UpdateProfileInformationForm from "./UpdateProfileInformationForm";

export default {
    props: ["sessions"],

    components: {
        NrnaLayout,
        // DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
};
</script>
