<template>
  <Modal :open="open" @close="handleClose">
    <div class="max-w-md mx-auto">
      <h2 class="text-xl font-bold mb-4">
        {{ $t('pages.committee.assign_member.title') }}
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Member Search -->
        <div>
          <label for="member" class="block text-sm font-medium mb-1">
            {{ $t('pages.committee.assign_member.form.member') }}
          </label>
          <input
            id="member"
            v-model="search"
            type="text"
            :placeholder="$t('pages.committee.assign_member.form.member_placeholder')"
            @input="handleMemberSearch"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
            required
          />
          
          <!-- Search Results Dropdown -->
          <ul v-if="searchResults.length > 0" class="mt-2 border border-gray-300 rounded-md max-h-40 overflow-y-auto">
            <li
              v-for="member in searchResults"
              :key="member.id"
              class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
              @click="selectMember(member)"
            >
              {{ member.name }} ({{ member.email }})
            </li>
          </ul>
          
          <span v-if="errors.member" class="text-red-600 text-sm mt-1 block">
            {{ errors.member }}
          </span>
        </div>

        <!-- Role Path Selection -->
        <div>
          <label for="role" class="block text-sm font-medium mb-1">
            {{ $t('pages.committee.assign_member.form.role') }}
          </label>
          <select
            id="role"
            v-model="form.role_path"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
            required
          >
            <option value="">{{ $t('pages.committee.assign_member.form.role_placeholder') }}</option>
            <option v-for="role in roles" :key="role.value" :value="role.value">
              {{ role.label }}
            </option>
          </select>
          <span v-if="errors.role_path" class="text-red-600 text-sm mt-1 block">
            {{ errors.role_path }}
          </span>
        </div>

        <!-- Nomination Type -->
        <div>
          <label for="nomination_type" class="block text-sm font-medium mb-1">
            {{ $t('pages.committee.assign_member.form.nomination_type') }}
          </label>
          <select
            id="nomination_type"
            v-model="form.nomination_type"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
            required
          >
            <option value="elected">{{ $t('pages.committee.assign_member.form.elected') }}</option>
            <option value="appointed">{{ $t('pages.committee.assign_member.form.appointed') }}</option>
            <option value="volunteered">{{ $t('pages.committee.assign_member.form.volunteered') }}</option>
          </select>
          <span v-if="errors.nomination_type" class="text-red-600 text-sm mt-1 block">
            {{ errors.nomination_type }}
          </span>
        </div>

        <!-- Election Date (if elected) -->
        <div v-if="form.nomination_type === 'elected'">
          <label for="election_date" class="block text-sm font-medium mb-1">
            {{ $t('pages.committee.assign_member.form.election_date') }}
          </label>
          <input
            id="election_date"
            v-model="form.election_date"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
            required
          />
          <span v-if="errors.election_date" class="text-red-600 text-sm mt-1 block">
            {{ errors.election_date }}
          </span>
        </div>

        <!-- Term End Date (Optional) -->
        <div>
          <label for="term_end_date" class="block text-sm font-medium mb-1">
            {{ $t('pages.committee.assign_member.form.term_end_date') }}
          </label>
          <input
            id="term_end_date"
            v-model="form.term_end_date"
            type="date"
            :placeholder="$t('pages.committee.assign_member.form.term_end_placeholder')"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
          />
        </div>

        <!-- Form Actions -->
        <div class="flex gap-2 pt-4">
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50"
          >
            {{ loading ? 'Assigning...' : $t('pages.committee.assign_member.button_assign') }}
          </button>
          <button
            type="button"
            @click="handleClose"
            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
          >
            {{ $t('pages.committee.assign_member.button_cancel') }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  open: Boolean,
  organisation: Object,
  committeeId: String,
  roles: {
    type: Array,
    default: () => [
      { value: '1.1.1', label: 'President' },
      { value: '1.1.2', label: 'Vice President' },
      { value: '1.1.3', label: 'Secretary' },
      { value: '1.2.1', label: 'Member' },
    ],
  },
});

const emit = defineEmits(['close']);

const form = ref({
  member_id: '',
  role_path: '',
  nomination_type: 'elected',
  election_date: '',
  term_end_date: '',
});

const search = ref('');
const searchResults = ref([]);
const errors = ref({});
const loading = ref(false);

const handleMemberSearch = debounce(async (e) => {
  const query = e.target.value;
  if (!query || query.length < 2) {
    searchResults.value = [];
    return;
  }

  try {
    const response = await fetch(
      route('members.search') + `?q=${encodeURIComponent(query)}&limit=10`
    );
    const data = await response.json();
    searchResults.value = data;
  } catch (error) {
    console.error('Error searching members:', error);
    searchResults.value = [];
  }
}, 300);

const selectMember = (member) => {
  form.value.member_id = member.id;
  search.value = member.name;
  searchResults.value = [];
};

const handleSubmit = () => {
  loading.value = true;
  errors.value = {};

  if (!form.value.member_id) {
    errors.value.member = 'Please select a member';
  }
  if (!form.value.role_path) {
    errors.value.role_path = 'Please select a role';
  }
  if (!form.value.nomination_type) {
    errors.value.nomination_type = 'Please select nomination type';
  }
  if (form.value.nomination_type === 'elected' && !form.value.election_date) {
    errors.value.election_date = 'Election date is required for elected members';
  }

  if (Object.keys(errors.value).length > 0) {
    loading.value = false;
    return;
  }

  router.post(
    route('committees.members.assign', {
      organisation: props.organisation.slug,
      committeeId: props.committeeId,
    }),
    form.value,
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        loading.value = false;
        handleClose();
      },
      onError: (errors) => {
        loading.value = false;
        Object.assign(errors, errors);
      },
    }
  );
};

const handleClose = () => {
  form.value = {
    member_id: '',
    role_path: '',
    nomination_type: 'elected',
    election_date: '',
    term_end_date: '',
  };
  search.value = '';
  searchResults.value = [];
  errors.value = {};
  emit('close');
};
</script>
