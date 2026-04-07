<template>
  <DashboardLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
      <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-2xl font-bold text-slate-900">My Organisations</h1>
            <p class="text-slate-500 mt-1">All organisations you belong to</p>
          </div>
          <Link
            :href="route('organisations.create')"
            class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition-colors"
          >
            <PlusIcon class="w-4 h-4" />
            Create Organisation
          </Link>
        </div>

        <!-- Empty state -->
        <div v-if="!organisations.length"
             class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
          <BuildingOffice2Icon class="w-12 h-12 text-slate-300 mx-auto mb-4" />
          <h2 class="text-lg font-semibold text-slate-700 mb-2">No organisations yet</h2>
          <p class="text-slate-400 text-sm mb-6">Create your first organisation to get started.</p>
          <Link
            :href="route('organisations.create')"
            class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition-colors"
          >
            <PlusIcon class="w-4 h-4" />
            Create Organisation
          </Link>
        </div>

        <!-- Organisation cards -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <Link
            v-for="org in organisations"
            :key="org.id"
            :href="route('organisations.show', org.slug)"
            class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-purple-300 hover:shadow-md transition-all group"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <BuildingOffice2Icon class="w-5 h-5 text-purple-600" />
              </div>
              <span :class="roleBadgeClass(org.role)" class="text-xs font-medium px-2.5 py-1 rounded-full">
                {{ org.role }}
              </span>
            </div>
            <h3 class="font-semibold text-slate-900 group-hover:text-purple-700 transition-colors">{{ org.name }}</h3>
            <p class="text-xs text-slate-400 mt-1">/{{ org.slug }}</p>
            <p v-if="org.joined_at" class="text-xs text-slate-400 mt-3">Joined {{ org.joined_at }}</p>
          </Link>
        </div>

      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { BuildingOffice2Icon, PlusIcon } from '@heroicons/vue/24/outline'

defineProps({
  organisations: { type: Array, required: true },
})

const roleBadgeClass = (role) => {
  const map = {
    owner:      'bg-purple-100 text-purple-700',
    admin:      'bg-blue-100 text-blue-700',
    commission: 'bg-amber-100 text-amber-700',
    member:     'bg-slate-100 text-slate-600',
    voter:      'bg-green-100 text-green-700',
  }
  return map[role] ?? 'bg-slate-100 text-slate-600'
}
</script>
