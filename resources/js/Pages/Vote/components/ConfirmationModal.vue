<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show"
                 class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
                 role="dialog"
                 aria-modal="true"
                 aria-labelledby="confirm-modal-title"
                 @keydown.escape="$emit('cancel')">

                <!-- Backdrop -->
                <div class="absolute inset-0 bg-neutral-900/60 backdrop-blur-sm"
                     @click="$emit('cancel')"
                     aria-hidden="true"></div>

                <!-- Panel — ref for focus trap -->
                <div ref="modalPanel"
                     tabindex="-1"
                     class="relative bg-white rounded-t-2xl sm:rounded-2xl shadow-lg
                            w-full sm:max-w-2xl max-h-[92vh] sm:max-h-[85vh]
                            overflow-y-auto flex flex-col outline-none">

                    <!-- Header -->
                    <div class="bg-gradient-to-r from-primary-700 to-indigo-800 text-white px-8 py-6 rounded-t-2xl shrink-0">
                        <h2 id="confirm-modal-title" class="text-2xl font-serif">
                            {{ $t('pages.voting.modal.title') }}
                        </h2>
                        <p class="text-primary-100 text-sm mt-1 font-sans">
                            {{ $t('pages.voting.modal.greeting', { name: userName }) }}
                        </p>
                    </div>

                    <!-- Body — scrollable -->
                    <div class="p-6 sm:p-8 grow overflow-y-auto">
                        <VoteSummary
                            v-if="voteData"
                            :national-selections="voteData.national_selected_candidates"
                            :regional-selections="voteData.regional_selected_candidates"
                        />
                        <div v-else class="text-center py-8">
                            <p class="text-neutral-400 font-sans">
                                {{ $t('pages.voting.modal.no_selections') }}
                            </p>
                        </div>
                    </div>

                    <!-- Final-action warning -->
                    <div class="mx-6 sm:mx-8 mb-4 bg-warning-50 border border-yellow-200 rounded-lg px-4 py-3 flex items-start gap-3 shrink-0">
                        <span class="text-yellow-600 shrink-0 mt-0.5" aria-hidden="true">⚠️</span>
                        <p class="text-yellow-800 text-sm font-sans">
                            <strong>{{ $t('pages.voting.modal.warning_title') }}</strong>
                            {{ $t('pages.voting.modal.warning_message') }}
                        </p>
                    </div>

                    <!-- Footer actions -->
                    <div class="px-6 sm:px-8 pb-6 sm:pb-8 flex flex-col sm:flex-row gap-3 shrink-0 border-t border-neutral-100 pt-4">
                        <button @click="$emit('cancel')"
                                class="flex-1 py-3 px-6 rounded-lg border-2 border-neutral-300
                                       text-neutral-700 font-sans font-semibold
                                       hover:bg-neutral-50 hover:border-neutral-400
                                       transition-colors duration-150
                                       focus:outline-none focus:ring-2 focus:ring-neutral-400">
                            {{ $t('pages.voting.modal.go_back') }}
                        </button>
                        <button @click="$emit('confirm')"
                                class="flex-1 py-3 px-6 rounded-lg
                                       bg-primary-600 hover:bg-primary-700 text-white
                                       font-sans font-bold shadow-md
                                       transition-colors duration-150
                                       focus:outline-none focus:ring-4 focus:ring-primary-300 focus:ring-offset-2">
                            <span class="flex items-center justify-center gap-2">
                                <span aria-hidden="true">🗳️</span>
                                {{ $t('pages.voting.modal.confirm_submit') }}
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
import VoteSummary from '@/Pages/Vote/VoteSummary.vue'

export default {
    name: 'ConfirmationModal',
    components: { VoteSummary },

    props: {
        show:     { type: Boolean, required: true },
        voteData: {
            type: Object,
            default: null,
            validator: v => !v || (
                v.national_selected_candidates !== undefined &&
                v.regional_selected_candidates !== undefined
            ),
        },
        userName: { type: String, default: '' },
    },

    emits: ['confirm', 'cancel'],

    watch: {
        show(visible) {
            // Lock body scroll + move focus into modal panel
            document.body.style.overflow = visible ? 'hidden' : ''
            if (visible) {
                this.$nextTick(() => {
                    this.$refs.modalPanel?.focus()
                })
            }
        },
    },

    beforeUnmount() {
        document.body.style.overflow = ''
    },
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: transform 0.25s ease;
}
.modal-enter-from .relative {
    transform: translateY(24px);
}
.modal-leave-to .relative {
    transform: translateY(24px);
}
</style>
