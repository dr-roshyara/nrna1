<template>
  <ElectionLayout>

    <!-- Breadcrumb Schema for SEO -->
    <BreadcrumbSchema />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Organization Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ organization.name }}</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              {{ organization.email }}
            </p>
            <p class="mt-2 text-sm text-gray-500">
              {{ $t('pages.organization-show.created_on', { date: organization.created_at }) }}
            </p>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6">
          <!-- Members Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h2v-2a11 11 0 10-20 0v2h2v-2z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      {{ $t('pages.organization-show.members') }}
                    </dt>
                    <dd class="text-3xl font-bold text-gray-900">
                      {{ stats.members_count }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <!-- Elections Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      {{ $t('pages.organization-show.elections') }}
                    </dt>
                    <dd class="text-3xl font-bold text-gray-900">
                      {{ stats.elections_count }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
              {{ $t('pages.organization-show.quick_actions') }}
            </h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $t('pages.organization-show.add_members') }}
              </button>
              <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $t('pages.organization-show.create_election') }}
              </button>
            </div>
          </div>
        </div>

        <!-- Support & Contact Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
          <div class="px-4 py-6 sm:px-6">
            <h2 class="text-lg font-bold text-gray-900 mb-3">
              {{ $t('support.title') }}
            </h2>
            <p class="text-gray-700 mb-4">
              {{ $t('support.assistance') }}
            </p>

            <!-- Contact Information -->
            <div class="space-y-3">
              <!-- Email Contact -->
              <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                  <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-gray-600">{{ $t('support.email') }}:</p>
                  <a :href="`mailto:${$t('support.email_address')}`" class="text-blue-600 hover:text-blue-700 font-semibold">
                    {{ $t('support.email_address') }}
                  </a>
                </div>
              </div>

              <!-- Phone & WhatsApp Contact -->
              <div class="flex items-start">
                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773c.418 1.02 1.004 2.49 2.986 4.472 1.982 1.982 3.452 2.568 4.472 2.986l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-gray-600">{{ $t('support.phone') }}:</p>
                  <a :href="`tel:${$t('support.phone_number')}`" class="text-green-600 hover:text-green-700 font-semibold">
                    {{ $t('support.phone_number') }}
                  </a>
                </div>
              </div>
            </div>

            <!-- Additional Help Text -->
            <p class="mt-4 text-xs text-gray-500">
              💬 {{ $t('support.phone') }} {{ $t('support.assistance').toLowerCase() }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue';
import BreadcrumbSchema from '@/components/BreadcrumbSchema.vue';
import { useI18n } from 'vue-i18n';
import { useMeta } from '@/composables/useMeta';

const { t } = useI18n();

const props = defineProps({
  organization: Object,
  stats: Object,
});

/**
 * SEO Meta Tags Management
 *
 * Dynamically sets page-level meta tags based on organization data.
 * Updates title and description to include organization name, member count, and election count.
 *
 * Translates SEO keys from the 'organizations.show' page translations.
 */
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: props.organization?.name || 'Organization',
    memberCount: props.stats?.members_count || '0',
    electionCount: props.stats?.elections_count || '0'
  }
});
</script>
