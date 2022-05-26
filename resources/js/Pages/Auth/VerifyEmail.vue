<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            <p>
                <span class="font-semibold text-gray-900">
                    Please check your email Now. कृपया अव आफ्नो इमेल चेक
                    गर्नुहाेस।
                </span>
                <br />
                Thanks for signing up! You have been registerd now. We have just
                sent you an email. Before getting started, could you please
                verify your email address?. For this you need to check your
                mailbox and click on the link we just emailed to you. If you
                didn't receive the email, we will gladly send you another.
            </p>
            <p class="text- mt-2 mb-2 text-gray-800">
                यहाँले आफुलाईलाई रजिस्टर गर्नु भएकोमा बिशेष धन्यवाद छ। भर्खरै
                हामीले तपाईं लाई एउटा ईमेल पठाएका छौ । त्यो ईमेलमा यहाँको ईमेल
                ठेगाना ठीक छ कि छैन भनेर जाँच गर्न लाई एउटा लिन्क पनि पठाइएको छ।
                आफ्नो ईमेल खोलेर यहाँले त्यो लिन्कमा क्लिक गर्नु हुने छ । अनी
                यहाँको ईमेल सही भएको प्रमाणित हुने छ। त्यस्पछी आफ्नो ईमेल र
                पासवर्ड प्रयोग गरेर लग इन गर्न सक्नु हुनेछ।
            </p>
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <jet-button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </jet-button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        Link,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form(),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("verification.send"));
        },
    },

    computed: {
        verificationLinkSent() {
            return this.status === "verification-link-sent";
        },
    },
};
</script>
