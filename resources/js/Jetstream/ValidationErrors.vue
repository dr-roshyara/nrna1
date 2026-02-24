<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600">{{ $t('validation.errors_header') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in translatedErrors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },

            translatedErrors() {
                const errors = {};

                // Map backend error keys (from ValidationException) to frontend translation paths
                const authErrorMap = {
                    'auth.failed': 'pages.auth.login.validation.credentials_invalid',
                    'auth.email_not_registered': 'pages.auth.login.validation.email_not_registered',
                    'auth.throttle': 'pages.auth.login.validation.throttle',
                };

                for (const [fieldName, errorMessage] of Object.entries(this.errors)) {
                    let translationKey = null;

                    // Strategy 1: Check if error message is an auth.* backend key
                    if (errorMessage.startsWith('auth.')) {
                        translationKey = authErrorMap[errorMessage];
                    }

                    // Strategy 2: Try direct mapping by field name
                    if (!translationKey) {
                        translationKey = `pages.auth.login.validation.${fieldName}_required`;
                    }

                    // Apply translation if available, otherwise use original message
                    if (translationKey && this.$te(translationKey)) {
                        errors[fieldName] = this.$t(translationKey);
                    } else {
                        // Last resort: use original message from backend
                        errors[fieldName] = errorMessage;
                    }
                }

                return errors;
            },
        }
    }
</script>
