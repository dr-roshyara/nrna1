<template>
  <div class="welcome-dashboard min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />

    <!-- Main Content -->
    <main class="grow max-w-4xl mx-auto w-full px-3 sm:px-4 lg:px-8 py-8 sm:py-12 lg:py-16">
      <!-- Welcome Section -->
      <div class="mb-8 sm:mb-12">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-2 sm:mb-3 leading-tight">
          {{ $t('pages.welcome-dashboard.header.title', { name: userName || 'User' }) }}
        </h1>
        <p class="text-base sm:text-lg lg:text-xl text-gray-700">
          {{ $t('pages.welcome-dashboard.header.subtitle') }}
        </p>
      </div>

      <!-- German Compliance Notice -->
      <div class="mb-8 sm:mb-12 p-4 sm:p-6 lg:p-8 border border-blue-200 bg-blue-50 rounded-lg focus-within:ring-2 focus-within:ring-blue-500" role="region" aria-label="Compliance Information">
        <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
          <span class="text-2xl sm:text-3xl shrink-0" aria-hidden="true">🇩🇪</span>
          <div class="w-full">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.compliance.title') }}
            </h3>
            <p class="text-sm sm:text-base text-gray-700 mb-3">
              {{ $t('pages.welcome-dashboard.compliance.description') }}
            </p>
            <ul class="text-xs sm:text-sm text-gray-700 space-y-1 sm:space-y-2">
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.gdpr') }}</li>
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.dataprotection') }}</li>
              <li>✓ {{ $t('pages.welcome-dashboard.compliance.encryption') }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Action Buttons Section -->
      <div class="mb-8 sm:mb-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">
          {{ $t('pages.welcome-dashboard.actions.title') }}
        </h2>
        <div class="space-y-3 sm:space-y-4">
          <!-- PRIMARY ACTION: Create organisation (with visual emphasis) -->
          <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg blur-sm opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <button
              @click="createOrganization"
              :disabled="isLoading"
              aria-label="Create a new organisation to start managing elections"
              class="relative w-full p-4 sm:p-6 lg:p-8 bg-white border-2 border-blue-500 rounded-lg hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-left group-hover:border-blue-600 disabled:opacity-50 disabled:cursor-wait"
            >
              <div class="flex items-start justify-between gap-3 sm:gap-4">
                <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                  <span class="text-2xl sm:text-3xl shrink-0" aria-hidden="true">🏢</span>
                  <div class="min-w-0">
                    <div class="font-bold text-gray-900 text-base sm:text-lg">
                      <span v-if="isLoading">⏳ {{ $t('common.loading', 'Creating...') }}</span>
                      <span v-else>{{ $t('pages.welcome-dashboard.actions.createOrg') }}</span>
                    </div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">
                      {{ $t('pages.welcome-dashboard.actions.createOrgDesc') }}
                    </div>
                  </div>
                </div>
                <span class="text-xl sm:text-2xl text-blue-600 font-bold shrink-0" v-if="!isLoading">→</span>
                <span class="text-xl sm:text-2xl text-blue-600 animate-spin shrink-0" v-else>⌛</span>
              </div>
            </button>
          </div>

          <!-- Secondary Actions -->
          <!-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
          // Join organisation
            <button
              @click="joinOrganization"
              :disabled="isLoading"
              aria-label="Join an existing organisation"
              class="p-4 sm:p-6 lg:p-8 bg-white border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all text-left disabled:opacity-50 disabled:cursor-wait"
            >
              <div class="flex items-start gap-3 sm:gap-4">
                <span class="text-2xl sm:text-3xl shrink-0" aria-hidden="true">👥</span>
                <div class="min-w-0">
                  <div class="font-bold text-gray-900 text-base sm:text-lg">
                    {{ $t('pages.welcome-dashboard.actions.joinOrg') }}
                  </div>
                  <div class="text-xs sm:text-sm text-gray-600 mt-1">
                    {{ $t('pages.welcome-dashboard.actions.joinOrgDesc') }}
                  </div>
                </div>
              </div>
            </button>

            // Skip Setup 
            <button
              @click="skipSetup"
              :disabled="isLoading"
              aria-label="Skip setup and go to the dashboard"
              class="p-4 sm:p-6 lg:p-8 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-lg focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all text-left disabled:opacity-50 disabled:cursor-wait"
            >
              <div class="flex items-start gap-3 sm:gap-4">
                <span class="text-2xl sm:text-3xl shrink-0" aria-hidden="true">📊</span>
                <div class="min-w-0">
                  <div class="font-bold text-gray-900 text-base sm:text-lg">
                    {{ $t('pages.welcome-dashboard.actions.skip') }}
                  </div>
                  <div class="text-xs sm:text-sm text-gray-600 mt-1">
                    {{ $t('pages.welcome-dashboard.actions.skipDesc') }}
                  </div>
                </div>
              </div>
            </button>
          </div>
           
          --> 
        </div>
      </div>

      <!-- Use Cases Section -->
      <div class="mb-8 sm:mb-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">
          {{ $t('pages.welcome-dashboard.usecases.title') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- German Organizations -->
          <div class="p-4 sm:p-6 border border-gray-200 rounded-lg bg-white hover:shadow-md focus-within:ring-2 focus-within:ring-blue-500 transition-shadow">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.usecases.german.title') }}
            </h3>
            <p class="text-sm sm:text-base text-gray-700 mb-4">
              {{ $t('pages.welcome-dashboard.usecases.german.description') }}
            </p>
            <ul class="text-xs sm:text-sm text-gray-700 space-y-1 sm:space-y-2">
              <li>• {{ $t('pages.welcome-dashboard.usecases.german.example1') }}</li>
              <li>• {{ $t('pages.welcome-dashboard.usecases.german.example2') }}</li>
            </ul>
          </div>

          <!-- Diaspora Communities -->
          <div class="p-4 sm:p-6 border border-gray-200 rounded-lg bg-white hover:shadow-md focus-within:ring-2 focus-within:ring-blue-500 transition-shadow">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2">
              {{ $t('pages.welcome-dashboard.usecases.diaspora.title') }}
            </h3>
            <p class="text-sm sm:text-base text-gray-700 mb-4">
              {{ $t('pages.welcome-dashboard.usecases.diaspora.description') }}
            </p>
            <ul class="text-xs sm:text-sm text-gray-700 space-y-1 sm:space-y-2">
              <li>• {{ $t('pages.welcome-dashboard.usecases.diaspora.example1') }}</li>
              <li>• {{ $t('pages.welcome-dashboard.usecases.diaspora.example2') }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Social Proof Section -->
      <div class="mb-8 sm:mb-12 pt-6 sm:pt-8 border-t border-gray-200">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 sm:mb-8 text-center">
          {{ $t('pages.welcome-dashboard.socialProof.title') }}
        </h2>
        <div class="grid grid-cols-3 gap-3 sm:gap-6 text-center">
          <div class="p-3 sm:p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-600">50+</div>
            <div class="text-xs sm:text-sm text-gray-700 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.orgs') }}
            </div>
          </div>
          <div class="p-3 sm:p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-600">3</div>
            <div class="text-xs sm:text-sm text-gray-700 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.languages') }}
            </div>
          </div>
          <div class="p-3 sm:p-6 bg-white rounded-lg border border-gray-100">
            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-600">100%</div>
            <div class="text-xs sm:text-sm text-gray-700 mt-2">
              {{ $t('pages.welcome-dashboard.socialProof.gdpr') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Key Features Section -->
      <div class="mb-8 sm:mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-6 lg:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2">
          {{ $t('pages.welcome-dashboard.keyFeatures.title') }}
        </h2>
        <p class="text-sm sm:text-base text-gray-700 mb-4 sm:mb-6">
          {{ $t('pages.welcome-dashboard.keyFeatures.subtitle') }}
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
          <!-- Feature 1: Transparency -->
          <div class="p-3 sm:p-4 lg:p-6 bg-white rounded-lg border border-blue-100 focus-within:ring-2 focus-within:ring-blue-500">
            <div class="text-2xl sm:text-3xl mb-2 sm:mb-3" aria-hidden="true">🔍</div>
            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">
              {{ $t('pages.welcome-dashboard.keyFeatures.transparency.title') }}
            </h4>
            <p class="text-xs sm:text-sm text-gray-700">
              {{ $t('pages.welcome-dashboard.keyFeatures.transparency.description') }}
            </p>
          </div>

          <!-- Feature 2: Security -->
          <div class="p-3 sm:p-4 lg:p-6 bg-white rounded-lg border border-blue-100 focus-within:ring-2 focus-within:ring-blue-500">
            <div class="text-2xl sm:text-3xl mb-2 sm:mb-3" aria-hidden="true">🔒</div>
            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">
              {{ $t('pages.welcome-dashboard.keyFeatures.security.title') }}
            </h4>
            <p class="text-xs sm:text-sm text-gray-700">
              {{ $t('pages.welcome-dashboard.keyFeatures.security.description') }}
            </p>
          </div>

          <!-- Feature 3: Multilingual -->
          <div class="p-3 sm:p-4 lg:p-6 bg-white rounded-lg border border-blue-100 focus-within:ring-2 focus-within:ring-blue-500">
            <div class="text-2xl sm:text-3xl mb-2 sm:mb-3" aria-hidden="true">🌐</div>
            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">
              {{ $t('pages.welcome-dashboard.keyFeatures.multilingual.title') }}
            </h4>
            <p class="text-xs sm:text-sm text-gray-700">
              {{ $t('pages.welcome-dashboard.keyFeatures.multilingual.description') }}
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Tips Section -->
      <div class="mb-8 sm:mb-12 bg-yellow-50 border-l-4 border-yellow-400 p-4 sm:p-6 rounded-sm">
        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 sm:mb-3">
          💡 {{ $t('pages.welcome-dashboard.tips.title') }}
        </h3>
        <ul class="space-y-1 sm:space-y-2">
          <li class="text-xs sm:text-sm text-gray-800">
            • {{ $t('pages.welcome-dashboard.tips.tip1') }}
          </li>
          <li class="text-xs sm:text-sm text-gray-800">
            • {{ $t('pages.welcome-dashboard.tips.tip2') }}
          </li>
          <li class="text-xs sm:text-sm text-gray-800">
            • {{ $t('pages.welcome-dashboard.tips.tip3') }}
          </li>
        </ul>
      </div>
    </main>

    <!-- organisation Creation Modal -->
    <OrganizationCreateModal />

    <!-- Footer -->
    <!-- Footer placeholder - replace with custom footer component -->
  </div>
</template>

<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import OrganizationCreateModal from "@/Components/Organization/OrganizationCreateModal.vue";
import { useOrganizationCreation } from "@/composables/useOrganizationCreation";
import { provide } from 'vue';

export default {
  name: 'WelcomeDashboard',

  components: {
    ElectionHeader,
    OrganizationCreateModal,
  },

  props: {
    userName: String,
    userEmail: String,
    userCreatedAt: String,
  },

  setup() {
    // Create and provide the organisation creation composable
    const organizationCreation = useOrganizationCreation();

    // Provide it to all child components (including modal)
    provide('organizationCreation', organizationCreation);

    return {
      organizationCreation,
    };
  },

  data() {
    return {
      isLoading: false,
    };
  },

  methods: {
    createOrganization() {
      console.log('🏢 Opening organisation creation modal');
      this.organizationCreation.openModal();
      this.organizationCreation.trackOrganizationCreationStarted();
    },

    joinOrganization() {
      if (this.isLoading) return;

      this.isLoading = true;
      console.log('👥 Navigating to join organisation');

      this.$inertia.visit(this.route('organisations.join'), {
        onFinish: () => {
          this.isLoading = false;
        },
        onError: () => {
          this.isLoading = false;
          console.error('❌ Failed to navigate to join organisation');
        },
      });

      // Fallback timeout in case Inertia events don't fire (3 second max)
      setTimeout(() => {
        if (this.isLoading) {
          this.isLoading = false;
          console.warn('⚠️ Navigation timeout - reset loading state');
        }
      }, 3000);
    },

    skipSetup() {
      if (this.isLoading) return;

      this.isLoading = true;
      console.log('📊 Skipping setup, going to role selection');

      this.$inertia.visit(this.route('role.selection'), {
        onFinish: () => {
          this.isLoading = false;
        },
        onError: () => {
          this.isLoading = false;
          console.error('❌ Failed to navigate to role selection');
        },
      });

      // Fallback timeout in case Inertia events don't fire (3 second max)
      setTimeout(() => {
        if (this.isLoading) {
          this.isLoading = false;
          console.warn('⚠️ Navigation timeout - reset loading state');
        }
      }, 3000);
    },
  },

  mounted() {
    console.log('Welcome Dashboard mounted');
    console.log('Current locale:', this.$i18n.locale);
    console.log('User:', this.userName);
  },
};
</script>

<style scoped>
.welcome-dashboard {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex-grow: 1;
}

/* Smooth gradient animation on hover */
@media (prefers-reduced-motion: no-preference) {
  .group:hover .blur {
    opacity: 100;
  }
}
</style>
