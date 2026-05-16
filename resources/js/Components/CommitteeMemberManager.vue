<template>
  <div class="space-y-6">
    <!-- Add Member Section -->
    <Card class="border-primary-200">
      <template #header>
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold text-neutral-900">Add Committee Member</h3>
        </div>
      </template>

      <form @submit.prevent="handleAddMember" class="space-y-4">
        <!-- Search Field -->
        <div class="space-y-2 relative">
          <label for="memberSearch" class="block text-sm font-medium text-neutral-700">
            Search Member
            <span class="text-neutral-400 font-normal">(by first name, last name, or email)</span>
          </label>
          <div class="relative">
            <input
              id="memberSearch"
              v-model="form.searchQuery"
              type="text"
              placeholder="e.g. John Smith or john@example.com"
              class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all"
              @input="handleSearch"
              autocomplete="off"
            />
            <svg v-if="form.searchQuery && isSearching" class="absolute right-3 top-2.5 h-5 w-5 animate-spin text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <!-- Search Results Dropdown -->
          <transition name="slideDown">
            <div v-if="form.searchQuery && searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-neutral-300 rounded-lg shadow-lg">
              <div class="max-h-48 overflow-y-auto">
                <button
                  v-for="result in searchResults"
                  :key="result.id"
                  type="button"
                  @click="selectMember(result)"
                  class="w-full text-left px-4 py-3 hover:bg-primary-50 border-b border-neutral-100 last:border-b-0 transition-colors duration-200"
                >
                  <div class="font-medium text-neutral-900">{{ result.name }}</div>
                  <div class="text-xs text-neutral-600">{{ result.email }}</div>
                </button>
              </div>
            </div>
            <div v-else-if="form.searchQuery && !isSearching && searchResults.length === 0" class="absolute z-10 w-full mt-1 bg-white border border-neutral-300 rounded-lg shadow-lg p-4">
              <p class="text-sm text-neutral-600">No members found</p>
            </div>
          </transition>

          <p v-if="errors.searchQuery" class="text-sm text-danger-600">
            {{ errors.searchQuery }}
          </p>
        </div>

        <!-- Selected Member Preview -->
        <div v-if="form.selectedMember" class="rounded-lg border border-primary-200 bg-primary-50 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-neutral-900">Selected Member</p>
              <p class="text-lg font-bold text-primary-600 mt-1">{{ form.selectedMember.name }}</p>
              <p class="text-xs text-neutral-600 mt-1">{{ form.selectedMember.email }}</p>
            </div>
            <button
              type="button"
              @click="form.selectedMember = null"
              class="text-neutral-400 hover:text-neutral-600"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Role Selection -->
        <div v-if="form.selectedMember" class="space-y-2">
          <label for="memberRole" class="block text-sm font-medium text-neutral-700">
            Role
          </label>
          <select
            id="memberRole"
            v-model="form.role"
            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all"
          >
            <option value="member">Member</option>
            <option value="chair">Chair</option>
            <option value="deputy">Deputy Chair</option>
            <option value="observer">Observer</option>
          </select>
          <p class="text-xs text-neutral-600">
            <span v-if="form.role === 'chair'" class="inline-block bg-purple-100 text-purple-700 px-2 py-1 rounded">Leadership Role</span>
            <span v-else-if="form.role === 'deputy'" class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded">Leadership Role</span>
            <span v-else class="inline-block bg-neutral-100 text-neutral-700 px-2 py-1 rounded">Regular Role</span>
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <Button
            type="submit"
            variant="primary"
            :disabled="!form.selectedMember || isLoading"
            class="flex-1"
          >
            <span v-if="!isLoading">Add Member</span>
            <span v-else class="inline-flex items-center gap-2">
              <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V0m0 24v-4m0-4h0m-8 8h0m16 0h0m-4-4h0m-4 0h0" />
              </svg>
              Adding...
            </span>
          </Button>
          <Button
            type="button"
            variant="secondary"
            @click="resetForm"
            :disabled="isLoading"
          >
            Clear
          </Button>
        </div>

        <p v-if="successMessage" class="text-sm text-success-600 bg-success-50 border border-success-200 rounded-lg px-4 py-3">
          {{ successMessage }}
        </p>
      </form>
    </Card>

    <!-- Members List Section -->
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-1 w-12 bg-gradient-to-r from-primary-600 to-primary-400 rounded-full"></div>
            <h3 class="text-lg font-bold text-neutral-900">Committee Members</h3>
          </div>
          <div class="text-sm font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full">
            {{ membersList.length }} members
          </div>
        </div>
      </template>

      <div v-if="isLoadingMembers" class="flex items-center justify-center py-8">
        <div class="text-center">
          <svg class="h-8 w-8 animate-spin text-primary-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V0m0 24v-4m0-4h0m-8 8h0m16 0h0m-4-4h0m-4 0h0" />
          </svg>
          <p class="text-neutral-600">Loading members...</p>
        </div>
      </div>

      <div v-else-if="membersList.length === 0" class="flex flex-col items-center justify-center py-16">
        <svg class="w-12 h-12 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <p class="text-neutral-500 font-medium">No members assigned yet</p>
        <p class="text-sm text-neutral-400 mt-2">Add members using the form above</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-neutral-50 border-b border-neutral-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">
                Member Name
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">
                Email
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">
                Role
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">
                Assigned Date
              </th>
              <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-neutral-600">
                Action
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-100">
            <tr
              v-for="member in membersList"
              :key="member.memberId"
              class="hover:bg-neutral-50 transition-colors duration-200"
            >
              <td class="px-6 py-4 font-medium text-neutral-900">
                {{ member.memberName || 'N/A' }}
              </td>
              <td class="px-6 py-4 text-neutral-600">
                <span class="text-xs bg-neutral-100 px-2 py-1 rounded">{{ member.memberEmail || 'N/A' }}</span>
              </td>
              <td class="px-6 py-4">
                <span
                  :class="[
                    'inline-block px-3 py-1 rounded-full text-xs font-medium',
                    member.role === 'chair' ? 'bg-purple-100 text-purple-700' :
                    member.role === 'deputy' ? 'bg-blue-100 text-blue-700' :
                    member.role === 'observer' ? 'bg-orange-100 text-orange-700' :
                    'bg-neutral-100 text-neutral-700'
                  ]"
                >
                  {{ member.role ? member.role.charAt(0).toUpperCase() + member.role.slice(1) : 'Member' }}
                </span>
              </td>
              <td class="px-6 py-4 text-neutral-600">
                {{ formatDate(member.assignedAt) }}
              </td>
              <td class="px-6 py-4 text-right">
                <ActionButton
                  variant="danger"
                  size="sm"
                  :disabled="isLoading || member.memberId === removingMemberId"
                  @click="handleRemoveMember(member.memberId)"
                  class="inline-flex items-center gap-2"
                >
                  <svg v-if="member.memberId === removingMemberId" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V0m0 24v-4" />
                  </svg>
                  <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  {{ member.memberId === removingMemberId ? 'Removing...' : 'Remove' }}
                </ActionButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Button from '@/Components/Button.vue';
import ActionButton from '@/Components/ActionButton.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({
  committeeId: {
    type: String,
    required: true,
  },
  tenantId: {
    type: String,
    required: false,
    default: null,
  },
  organisationId: {
    type: String,
    required: false,
    default: null,
  },
});

const form = ref({
  searchQuery: '',
  selectedMember: null,
  role: 'member',
});

const page = usePage();

const membersList = ref([]);
const searchResults = ref([]);
const isLoading = ref(false);
const isLoadingMembers = ref(false);
const isSearching = ref(false);
const removingMemberId = ref(null);
const errors = ref({});
const successMessage = ref('');
let searchTimeout = null;

const effectiveTenantId = computed(() => {
  if (props.tenantId) return props.tenantId;
  if (props.organisationId) return props.organisationId;
  if (page.props.auth?.user?.current_organisation_id) {
    return page.props.auth.user.current_organisation_id;
  }
  if (page.props.organisation?.id) {
    return page.props.organisation.id;
  }
  if (page.props.organisationId) {
    return page.props.organisationId;
  }
  return null;
});

const effectiveOrganisationSlug = computed(() => {
  return route().params?.organisation || props.organisationId;
});

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  } catch {
    return dateString;
  }
};

const fetchMembers = async () => {
  if (!effectiveTenantId.value) {
    console.warn('No tenant ID available');
    membersList.value = [];
    return;
  }

  isLoadingMembers.value = true;
  try {
    const fetchOptions = {
      headers: {
        'X-Tenant-Id': effectiveTenantId.value,
        'Accept': 'application/json',
      },
    };

    const response = await fetch(
      `/api/governance/committees/${props.committeeId}/members`,
      fetchOptions
    );

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(`Failed to fetch members: ${response.statusText}`);
    }

    const data = await response.json();
    membersList.value = data.members || [];
  } catch (error) {
    console.error('Error fetching members:', error);
    membersList.value = [];
  } finally {
    isLoadingMembers.value = false;
  }
};

const handleSearch = async () => {
  const query = form.value.searchQuery.trim();

  // Clear results if query is too short
  if (query.length < 2) {
    searchResults.value = [];
    return;
  }

  clearTimeout(searchTimeout);
  isSearching.value = true;

  searchTimeout = setTimeout(async () => {
    try {
      const params = new URLSearchParams({
        q: query,
        limit: '10',
      });

      const response = await fetch(
        `${route('members.search', { organisation: effectiveOrganisationSlug.value })}?${params.toString()}`,
        {
          headers: {
            'Accept': 'application/json',
          },
        }
      );

      if (!response.ok) {
        throw new Error(`Search failed: ${response.statusText}`);
      }

      searchResults.value = await response.json();
    } catch (error) {
      console.error('Error searching members:', error);
      searchResults.value = [];
    } finally {
      isSearching.value = false;
    }
  }, 300); // Debounce by 300ms
};

const selectMember = (member) => {
  form.value.selectedMember = member;
  form.value.searchQuery = '';
  form.value.role = 'member'; // Reset to default role
  searchResults.value = [];
  errors.value.searchQuery = '';
};

const handleAddMember = async () => {
  errors.value = {};
  successMessage.value = '';

  if (!form.value.selectedMember) {
    errors.value.searchQuery = 'Please select a member';
    return;
  }

  if (!effectiveTenantId.value) {
    errors.value.searchQuery = 'Tenant context is missing';
    return;
  }

  isLoading.value = true;

  try {
    const response = await fetch(
      `/api/governance/committees/${props.committeeId}/members`,
      {
        method: 'POST',
        headers: {
          'X-Tenant-Id': effectiveTenantId.value,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          memberId: form.value.selectedMember.id,
          role: form.value.role,
        }),
      }
    );

    if (!response.ok) {
      if (response.status === 422) {
        const errorData = await response.json();
        if (errorData.errors) {
          errors.value = errorData.errors;
        } else {
          errors.value.searchQuery = 'This member is already assigned';
        }
      } else {
        errors.value.searchQuery = 'Failed to add member';
      }
      return;
    }

    successMessage.value = 'Member added successfully!';
    resetForm();

    // Refresh the members list
    await fetchMembers();
  } catch (error) {
    console.error('Error adding member:', error);
    errors.value.searchQuery = 'An unexpected error occurred';
  } finally {
    isLoading.value = false;
  }
};

const handleRemoveMember = async (memberId) => {
  if (!confirm('Are you sure you want to remove this member?')) {
    return;
  }

  if (!effectiveTenantId.value) {
    alert('Tenant context is missing');
    return;
  }

  removingMemberId.value = memberId;

  try {
    const response = await fetch(
      `/api/governance/committees/${props.committeeId}/members/${memberId}`,
      {
        method: 'DELETE',
        headers: {
          'X-Tenant-Id': effectiveTenantId.value,
          'Accept': 'application/json',
        },
      }
    );

    if (!response.ok) {
      throw new Error(`Failed to remove member: ${response.statusText}`);
    }

    successMessage.value = 'Member removed successfully!';

    // Refresh the members list
    await fetchMembers();
  } catch (error) {
    console.error('Error removing member:', error);
    alert('Failed to remove member');
  } finally {
    removingMemberId.value = null;
  }
};

const resetForm = () => {
  form.value.searchQuery = '';
  form.value.selectedMember = null;
  form.value.role = 'member';
  searchResults.value = [];
  errors.value = {};
  setTimeout(() => {
    successMessage.value = '';
  }, 3000);
};

onMounted(() => {
  fetchMembers();
});
</script>
