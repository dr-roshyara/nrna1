<template>
    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">Platform Admin Dashboard</h1>
                <p class="mt-2 text-neutral-600">Manage elections and platform configurations.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Link :href="route('platform.elections.pending')" class="rounded-lg bg-white p-6 shadow hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-neutral-600">Pending Approvals</p>
                            <p class="text-3xl font-bold text-neutral-900 mt-2">{{ stats.pending_elections }}</p>
                        </div>
                        <div class="text-4xl text-yellow-500">⏳</div>
                    </div>
                    <p class="mt-4 text-xs text-primary-600">View all pending →</p>
                </Link>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-neutral-600">Platform Admins</p>
                            <p class="text-3xl font-bold text-neutral-900 mt-2">{{ stats.platform_admins }}</p>
                        </div>
                        <div class="text-4xl text-purple-500">👥</div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-neutral-600">Active Organizations</p>
                            <p class="text-3xl font-bold text-neutral-900 mt-2">{{ stats.organisations }}</p>
                        </div>
                        <div class="text-4xl text-primary-500">🏢</div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-neutral-600">Total Elections</p>
                            <p class="text-3xl font-bold text-neutral-900 mt-2">{{ stats.total_elections }}</p>
                        </div>
                        <div class="text-4xl text-green-500">🗳️</div>
                    </div>
                </div>
            </div>

            <!-- Elections Management Navigation -->
            <div class="rounded-lg bg-white p-6 shadow">
                <h2 class="text-lg font-bold text-neutral-900 mb-6">Elections Management</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Pending Approvals Navigation -->
                    <Link
                        :href="route('platform.elections.pending')"
                        class="rounded-lg border-2 border-yellow-200 bg-yellow-50 p-6 hover:bg-yellow-100 hover:shadow-lg transition group"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">Pending Approvals</p>
                                <p class="text-4xl font-bold text-yellow-900 mt-2">{{ stats.pending_elections }}</p>
                            </div>
                            <div class="text-5xl">⏳</div>
                        </div>
                        <p class="text-sm text-yellow-800">
                            Elections waiting for platform admin approval (> 40 voters)
                        </p>
                        <p class="mt-4 text-sm font-medium text-yellow-600 group-hover:text-yellow-700">
                            View Details →
                        </p>
                    </Link>

                    <!-- All Elections Navigation -->
                    <Link
                        :href="route('platform.elections.all')"
                        class="rounded-lg border-2 border-green-200 bg-green-50 p-6 hover:bg-green-100 hover:shadow-lg transition group"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">All Elections</p>
                                <p class="text-4xl font-bold text-green-900 mt-2">{{ stats.total_elections }}</p>
                            </div>
                            <div class="text-5xl">📊</div>
                        </div>
                        <p class="text-sm text-green-800">
                            View all elections with subscription status (Free vs Paid)
                        </p>
                        <p class="mt-4 text-sm font-medium text-green-600 group-hover:text-green-700">
                            View Details →
                        </p>
                    </Link>
                </div>

                <!-- Subscription Status Legend -->
                <div class="mt-6 pt-6 border-t border-neutral-200">
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-3">Subscription Status</p>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100">
                                <span class="text-sm font-bold text-green-700">✓</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Free</p>
                                <p class="text-xs text-neutral-500">≤ 40 voters (auto-approved)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100">
                                <span class="text-sm font-bold text-amber-700">⭐</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Paid</p>
                                <p class="text-xs text-neutral-500">> 40 voters (requires approval)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <h2 class="text-lg font-bold text-neutral-900 mb-4">Coming Soon</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-lg bg-purple-50 p-4 border border-purple-200">
                        <p class="font-medium text-purple-900">Manage Platform Admins</p>
                        <p class="text-sm text-purple-700 mt-1">Add/remove platform staff members</p>
                    </div>

                    <div class="rounded-lg bg-primary-50 p-4 border border-primary-200">
                        <p class="font-medium text-primary-900">System Settings</p>
                        <p class="text-sm text-primary-700 mt-1">Configure platform-wide settings</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-primary-50 border border-primary-200 p-6">
                <h3 class="font-bold text-primary-900">Platform Admin Guide</h3>
                <ul class="mt-3 space-y-2 text-sm text-primary-800">
                    <li>• <strong>Pending Approvals:</strong> Elections requesting approval when voter count exceeds 40</li>
                    <li>• <strong>Approve:</strong> Move election to administration phase to proceed with voting setup</li>
                    <li>• <strong>Reject:</strong> Return election to draft with rejection reason</li>
                    <li>• <strong>Role:</strong> Only super_admin and platform_admin can approve elections</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

export default {
    components: { AdminLayout, Link },
    props: {
        stats: {
            type: Object,
            default: () => ({
                pending_elections: 0,
                platform_admins: 0,
                organisations: 0,
                total_elections: 0,
            })
        }
    }
}
</script>
