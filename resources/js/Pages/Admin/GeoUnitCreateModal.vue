<template>
  <!-- Backdrop -->
  <Teleport to="body">
    <transition name="backdrop">
      <div
        v-if="isOpen"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
        @click="onClose"
      />
    </transition>

    <!-- Modal -->
    <transition name="modal">
      <div
        v-if="isOpen"
        class="fixed right-0 top-0 h-screen w-full max-w-2xl bg-white z-50 shadow-2xl overflow-hidden"
      >
        <!-- Header with Grid Background -->
        <div class="relative border-b border-neutral-200 bg-gradient-to-b from-neutral-50 to-white">
          <!-- Decorative coordinate grid -->
          <div
            class="absolute inset-0 opacity-5"
            style="
              background-image:
                linear-gradient(0deg, transparent 24%, rgba(0,0,0,.05) 25%, rgba(0,0,0,.05) 26%, transparent 27%, transparent 74%, rgba(0,0,0,.05) 75%, rgba(0,0,0,.05) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(0,0,0,.05) 25%, rgba(0,0,0,.05) 26%, transparent 27%, transparent 74%, rgba(0,0,0,.05) 75%, rgba(0,0,0,.05) 76%, transparent 77%, transparent);
              background-size: 40px 40px;
            "
          />

          <div class="relative px-8 py-8">
            <div class="flex items-start justify-between mb-6">
              <div>
                <h2 class="text-3xl font-bold text-neutral-900 tracking-tight" style="font-family: 'Space Mono', monospace">
                  Add Geographic Unit
                </h2>
                <p class="mt-2 text-sm text-neutral-600" style="font-family: 'Crimson Text', serif">
                  Define a new location in your organizational hierarchy
                </p>
              </div>
              <button
                @click="onClose"
                class="text-neutral-400 hover:text-neutral-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Level Indicator Timeline -->
            <div class="flex items-center gap-2 max-w-md">
              <div
                v-for="level in [0, 1, 2, 3]"
                :key="level"
                class="flex flex-col items-center transition-all duration-300"
              >
                <div
                  class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-white text-sm transition-all duration-300"
                  :class="
                    selectedLevel === level
                      ? 'ring-2 ring-offset-2 ring-blue-500 scale-110'
                      : ''
                  "
                  :style="getLevelColor(level)"
                >
                  {{ level }}
                </div>
                <span class="text-xs text-neutral-500 mt-1" style="font-family: 'Space Mono', monospace">
                  {{ getLevelName(level) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Container -->
        <div class="overflow-y-auto h-[calc(100vh-280px)]">
          <form @submit.prevent="submitForm" class="p-8 space-y-8">
            <!-- Row 1: Code & Name -->
            <div class="grid grid-cols-2 gap-6">
              <!-- Code -->
              <div class="form-group" style="animation-delay: 0ms">
                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-widest mb-2" style="font-family: 'Space Mono', monospace">
                  Code <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.code"
                  type="text"
                  required
                  placeholder="NCC-NP"
                  maxlength="50"
                  class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors placeholder-neutral-400 font-mono text-sm"
                  style="font-family: 'Space Mono', monospace"
                />
                <p class="text-xs text-neutral-500 mt-1">Unique identifier (e.g., NCC-NP for Nepal)</p>
              </div>

              <!-- Name -->
              <div class="form-group" style="animation-delay: 50ms">
                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-widest mb-2" style="font-family: 'Space Mono', monospace">
                  Name <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="Nepal"
                  maxlength="255"
                  class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors placeholder-neutral-400"
                />
                <p class="text-xs text-neutral-500 mt-1">Display name for this location</p>
              </div>
            </div>

            <!-- Row 2: Level & Type -->
            <div class="grid grid-cols-2 gap-6">
              <!-- Level -->
              <div class="form-group" style="animation-delay: 100ms">
                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-widest mb-2" style="font-family: 'Space Mono', monospace">
                  Geographic Level <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <select
                    v-model.number="form.admin_level"
                    required
                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors bg-white appearance-none cursor-pointer"
                  >
                    <option value="">Select level</option>
                    <option value="0">0 — Global</option>
                    <option value="1">1 — Regional</option>
                    <option value="2">2 — National</option>
                    <option value="3">3 — State/Local</option>
                    <option value="4">4 — City</option>
                  </select>
                  <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-neutral-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                  </div>
                </div>
                <div v-if="selectedLevel >= 0" class="mt-2 p-3 rounded-lg" :style="{ backgroundColor: getLevelColor(selectedLevel, true) }">
                  <p class="text-xs font-medium text-neutral-700">
                    {{ getLevelDescription(selectedLevel) }}
                  </p>
                </div>
              </div>

              <!-- Type -->
              <div class="form-group" style="animation-delay: 150ms">
                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-widest mb-2" style="font-family: 'Space Mono', monospace">
                  Type <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <select
                    v-model="form.type"
                    required
                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors bg-white appearance-none cursor-pointer"
                  >
                    <option value="">Select type</option>
                    <option value="planet">🌍 Planet</option>
                    <option value="region">🗺️ Region</option>
                    <option value="country">🏳️ Country</option>
                    <option value="state">🏛️ State/Province</option>
                    <option value="city">🏙️ City</option>
                  </select>
                  <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-neutral-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Parent Selection -->
            <div class="form-group" style="animation-delay: 200ms">
              <label class="block text-xs font-bold text-neutral-700 uppercase tracking-widest mb-2" style="font-family: 'Space Mono', monospace">
                Parent Unit <span class="text-neutral-400 text-xs font-normal">(Optional — for root units, leave empty)</span>
              </label>
              <div class="relative">
                <select
                  v-model.number="form.parent_id"
                  class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors bg-white appearance-none cursor-pointer"
                >
                  <option :value="null">No parent (Top-level unit)</option>
                  <option v-for="unit in availableParents" :key="unit.id" :value="unit.id">
                    {{ unit.name }} (Level {{ unit.admin_level }})
                  </option>
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-neutral-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                  </svg>
                </div>
              </div>
              <p class="text-xs text-neutral-500 mt-1">Select the geographic unit that contains this one</p>
            </div>

            <!-- Active Status -->
            <div class="form-group flex items-center gap-3" style="animation-delay: 250ms">
              <input
                v-model="form.is_active"
                type="checkbox"
                id="is_active"
                class="w-5 h-5 border-2 border-neutral-300 rounded accent-blue-500 cursor-pointer"
              />
              <label for="is_active" class="flex flex-col cursor-pointer">
                <span class="text-sm font-medium text-neutral-900">Active</span>
                <span class="text-xs text-neutral-500">Unit is available for selection</span>
              </label>
            </div>

            <!-- Hierarchy Preview -->
            <div v-if="hierarchyPreview" class="mt-8 p-4 bg-neutral-50 rounded-lg border border-neutral-200" style="animation-delay: 300ms">
              <p class="text-xs font-bold text-neutral-600 uppercase tracking-widest mb-3" style="font-family: 'Space Mono', monospace">
                Hierarchy Preview
              </p>
              <div class="space-y-2">
                <div v-for="item in hierarchyPreview" :key="item.id" class="flex items-center gap-2 text-sm">
                  <div
                    class="w-2 h-2 rounded-full"
                    :style="{ backgroundColor: getLevelColor(item.level).backgroundColor }"
                  />
                  <span class="text-neutral-600">{{ '—'.repeat(item.depth) }} {{ item.name }}</span>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- Footer Actions -->
        <div class="border-t border-neutral-200 bg-neutral-50 px-8 py-4 flex gap-3 justify-end">
          <button
            @click="onClose"
            class="px-6 py-2.5 text-sm font-medium text-neutral-700 hover:text-neutral-900 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="submitForm"
            :disabled="isSubmitting"
            class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:bg-neutral-300 rounded-lg transition-colors"
          >
            <span v-if="!isSubmitting">Create Unit</span>
            <span v-else class="flex items-center gap-2">
              <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
              </svg>
              Creating...
            </span>
          </button>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

interface Props {
  isOpen: boolean
  allUnits: Array<{ id: number; name: string; admin_level: number; parent_id: number | null; type: string }> | undefined
}

interface Emits {
  (e: 'close'): void
  (e: 'submit', data: typeof form.value): void
}

const props = withDefaults(defineProps<Props>(), {
  allUnits: undefined,
})

const emit = defineEmits<Emits>()

const form = ref({
  code: '',
  name: '',
  admin_level: '',
  type: '',
  parent_id: null as number | null,
  is_active: true,
})

const isSubmitting = ref(false)
const selectedLevel = computed(() => form.value.admin_level === '' ? -1 : parseInt(String(form.value.admin_level)))

const availableParents = computed(() => {
  if (!props.allUnits) return []
  // Don't allow circular references
  return props.allUnits.filter(
    (u) => u.admin_level < selectedLevel.value && u.id !== form.value.parent_id
  )
})

const hierarchyPreview = computed(() => {
  if (!props.allUnits || selectedLevel.value === -1) return null

  const buildPath = (parentId: number | null): Array<{ id: number; name: string; level: number; depth: number }> => {
    if (parentId === null) {
      return [{ id: 0, name: form.value.name || 'New Unit', level: selectedLevel.value, depth: 0 }]
    }

    const parent = props.allUnits?.find((u) => u.id === parentId)
    if (!parent) return []

    const parentPath = buildPath(parent.parent_id)
    return [
      ...parentPath,
      {
        id: parentId,
        name: parent.name,
        level: parent.admin_level,
        depth: parentPath.length,
      },
      { id: 9999, name: form.value.name || 'New Unit', level: selectedLevel.value, depth: parentPath.length + 1 },
    ]
  }

  return buildPath(form.value.parent_id)
})

const getLevelColor = (level: number, light = false) => {
  const colors: Record<number, { backgroundColor: string; color?: string }> = {
    0: { backgroundColor: light ? '#f8fafc' : '#0f172a', color: 'white' },
    1: { backgroundColor: light ? '#eff6ff' : '#0284c7', color: 'white' },
    2: { backgroundColor: light ? '#e0f2fe' : '#0c4a6e', color: 'white' },
    3: { backgroundColor: light ? '#cffafe' : '#164e63', color: 'white' },
    4: { backgroundColor: light ? '#cffafe' : '#0f766e', color: 'white' },
  }
  return colors[level] || colors[0]
}

const getLevelName = (level: number) => {
  const names = ['Global', 'Regional', 'National', 'State', 'City']
  return names[level] || `Level ${level}`
}

const getLevelDescription = (level: number) => {
  const descriptions = [
    'Covers the entire world',
    'Covers a major geographic region',
    'Covers a single country',
    'Covers a state or province',
    'Covers a city or municipality',
  ]
  return descriptions[level] || `Level ${level} unit`
}

const submitForm = async () => {
  isSubmitting.value = true
  try {
    emit('submit', { ...form.value })
    // Reset after successful submission
    setTimeout(() => {
      form.value = {
        code: '',
        name: '',
        admin_level: '',
        type: '',
        parent_id: null,
        is_active: true,
      }
      isSubmitting.value = false
    }, 300)
  } catch (err) {
    isSubmitting.value = false
  }
}

const onClose = () => {
  if (!isSubmitting.value) {
    emit('close')
  }
}
</script>

<style scoped>
/* Custom fonts import */
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Crimson+Text:ital@0;1&display=swap');

/* Animations */
@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes fadeInBackdrop {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.modal-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

.backdrop-enter-active,
.backdrop-leave-active {
  transition: opacity 0.3s ease;
}

.backdrop-enter-from,
.backdrop-leave-to {
  opacity: 0;
}

/* Form field staggered entrance */
.form-group {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Input focus states with animated underline */
input:focus,
select:focus {
  box-shadow: 0 2px 0 currentColor;
}

/* Custom select styling */
select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  padding-right: 2.5rem;
}
</style>
