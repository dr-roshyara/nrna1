<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useLocale } from '@/Composables/useLocale';
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue';

const { locale } = useLocale();

const props = defineProps({
  organisation: { type: Object, required: true },
  member:       { type: Object, required: true },
  membershipTypes: { type: Array, required: true },
});

const selectedTypeId = ref(null);
const dueDate = ref('');
const periodLabel = ref('');
const notes = ref('');
const submitting = ref(false);

const translations = {
  en: {
    page_title: 'Assign Fee',
    back: 'Back to Fees',
    select_type: 'Select Membership Type',
    fee_label: 'Fee',
    duration: 'Duration',
    duration_lifetime: 'Lifetime',
    due_date: 'Due Date',
    period_label: 'Period (e.g., "2026")',
    notes: 'Notes',
    assign_fee: 'Assign Fee',
    invalid_selection: 'Please select a membership type',
  },
  de: {
    page_title: 'Gebühr zuweisen',
    back: 'Zurück zu Gebühren',
    select_type: 'Mitgliedschaftstyp wählen',
    fee_label: 'Gebühr',
    duration: 'Dauer',
    duration_lifetime: 'Lebenszeit',
    due_date: 'Fälligkeitsdatum',
    period_label: 'Zeitraum (z.B. "2026")',
    notes: 'Notizen',
    assign_fee: 'Gebühr zuweisen',
    invalid_selection: 'Bitte wählen Sie einen Mitgliedschaftstyp',
  },
  np: {
    page_title: 'शुल्क नियुक्त गर्नुहोस्',
    back: 'शुल्कमा फर्कनुहोस्',
    select_type: 'सदस्यता प्रकार चयन गर्नुहोस्',
    fee_label: 'शुल्क',
    duration: 'अवधि',
    duration_lifetime: 'आजीवन',
    due_date: 'देय मिति',
    period_label: 'अवधि (जस्तै, "२०२६")',
    notes: 'नोटहरू',
    assign_fee: 'शुल्क नियुक्त गर्नुहोस्',
    invalid_selection: 'कृपया सदस्यता प्रकार चयन गर्नुहोस्',
  },
};

const t = computed(() => translations[locale.value] ?? translations.en);

const selectedType = computed(() => {
  return props.membershipTypes.find(type => type.id === selectedTypeId.value);
});

const page = usePage();

const handleSubmit = () => {
  if (!selectedTypeId.value) {
    alert(t.value.invalid_selection);
    return;
  }

  submitting.value = true;

  router.post(
    route('organisations.members.fees.store', [props.organisation.slug, props.member.id]),
    {
      membership_type_id: selectedTypeId.value,
      due_date: dueDate.value,
      period_label: periodLabel.value || null,
      notes: notes.value || null,
    },
    {
      preserveScroll: true,
      onFinish: () => { submitting.value = false; },
    }
  );
};
</script>

<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gray-50 py-12">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
          <a :href="route('organisations.members.index', [organisation.slug])" class="hover:text-gray-900">
            {{ organisation.name }}
          </a>
          <span>/</span>
          <a :href="route('organisations.members.fees.index', [organisation.slug, member.id])" class="hover:text-gray-900">
            {{ member.organisation_user.user.name }}
          </a>
          <span>/</span>
          <span class="text-gray-900">{{ t.page_title }}</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">{{ t.page_title }}</h1>
          <p class="mt-2 text-gray-600">
            Assign a new membership fee to {{ member.organisation_user.user.name }}
          </p>
        </div>

        <!-- Flash Messages -->
        <div v-if="page.props.flash?.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
          {{ page.props.flash.error }}
        </div>

        <!-- Type Selection Cards -->
        <div class="bg-white rounded-lg shadow mb-8 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ t.select_type }}</h2>
          <div class="grid gap-4 md:grid-cols-2">
            <div
              v-for="type in membershipTypes"
              :key="type.id"
              @click="selectedTypeId = type.id"
              class="relative p-4 border-2 rounded-lg cursor-pointer transition"
              :class="selectedTypeId === type.id
                ? 'border-blue-600 bg-blue-50'
                : 'border-gray-300 bg-white hover:border-gray-400'"
            >
              <!-- Checkmark indicator -->
              <div
                v-if="selectedTypeId === type.id"
                class="absolute top-3 right-3 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center"
              >
                ✓
              </div>

              <!-- Type info -->
              <div class="pr-8">
                <h3 class="font-semibold text-gray-900">{{ type.name }}</h3>
                <p class="text-2xl font-bold text-blue-600 mt-2">
                  {{ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EUR' }).format(type.fee_amount) }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                  {{ t.duration }}: {{ type.duration_months ? `${type.duration_months} months` : t.duration_lifetime }}
                </p>
              </div>
            </div>
          </div>
          <div v-if="page.props.errors.membership_type_id" class="mt-3 text-sm text-red-600">
            {{ page.props.errors.membership_type_id }}
          </div>
        </div>

        <!-- Form Fields -->
        <form @submit.prevent="handleSubmit" class="bg-white rounded-lg shadow p-6">
          <!-- Due Date -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-900 mb-2">
              {{ t.due_date }}
              <span class="text-red-600">*</span>
            </label>
            <input
              v-model="dueDate"
              type="date"
              :min="new Date().toISOString().split('T')[0]"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="page.props.errors.due_date && 'border-red-500'"
            />
            <div v-if="page.props.errors.due_date" class="mt-1 text-sm text-red-600">
              {{ page.props.errors.due_date }}
            </div>
          </div>

          <!-- Period Label -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-900 mb-2">
              {{ t.period_label }}
            </label>
            <input
              v-model="periodLabel"
              type="text"
              placeholder="e.g., 2026, 2025-2026"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="page.props.errors.period_label && 'border-red-500'"
            />
            <div v-if="page.props.errors.period_label" class="mt-1 text-sm text-red-600">
              {{ page.props.errors.period_label }}
            </div>
          </div>

          <!-- Notes -->
          <div class="mb-8">
            <label class="block text-sm font-medium text-gray-900 mb-2">
              {{ t.notes }}
            </label>
            <textarea
              v-model="notes"
              rows="3"
              placeholder="Optional notes about this fee..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="page.props.errors.notes && 'border-red-500'"
            />
            <div v-if="page.props.errors.notes" class="mt-1 text-sm text-red-600">
              {{ page.props.errors.notes }}
            </div>
          </div>

          <!-- Summary Panel -->
          <div v-if="selectedType" class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-600">Selected Type</p>
                <p class="text-lg font-semibold text-gray-900">{{ selectedType.name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Amount</p>
                <p class="text-2xl font-bold text-blue-600">
                  {{ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EUR' }).format(selectedType.fee_amount) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3">
            <a
              :href="route('organisations.members.fees.index', [organisation.slug, member.id])"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50"
            >
              {{ t.back }}
            </a>
            <button
              type="submit"
              :disabled="submitting || !selectedTypeId"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ submitting ? 'Assigning...' : t.assign_fee }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </PublicDigitLayout>
</template>
