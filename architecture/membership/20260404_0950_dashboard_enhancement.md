## 🚀 **Starting Dashboard Enhancements**

Let me implement the improvements in order.

---

## **1. Update Dashboard Controller - Add Member Applications**

First, add `myApplications` to the member data:

**Update `app/Http/Controllers/Membership/MembershipDashboardController.php`:**

```php
// In getMemberData() method, add after existing code
private function getMemberData(Organisation $organisation): array
{
    $member = Member::where('organisation_id', $organisation->id)
        ->whereHas('organisationUser', fn ($q) => $q->where('user_id', auth()->id()))
        ->first();

    // Get member's own applications
    $myApplications = MembershipApplication::where('organisation_id', $organisation->id)
        ->where('user_id', auth()->id())
        ->with('membershipType')
        ->latest()
        ->take(5)
        ->get()
        ->map(fn($app) => [
            'id' => $app->id,
            'status' => $app->status,
            'submitted_at' => $app->submitted_at,
            'membership_type' => $app->membershipType?->name,
        ]);

    if (! $member) {
        return [
            'has_membership' => false,
            'apply_url' => route('organisations.membership.apply', $organisation->slug),
            'my_applications' => $myApplications,
        ];
    }

    return [
        'has_membership' => true,
        'status' => $member->status,
        'expires_at' => $member->membership_expires_at?->toIso8601String(),
        'expires_in_days' => $member->membership_expires_at
            ? max(0, (int) now()->diffInDays($member->membership_expires_at, false))
            : null,
        'pending_fees' => (float) $member->fees()->where('status', 'pending')->sum('amount'),
        'can_self_renew' => method_exists($member, 'canSelfRenew') ? $member->canSelfRenew() : false,
        'my_applications' => $myApplications,
        'member_id' => $member->id, // Add member ID for fee links
    ];
}
```

---

## **2. Update Dashboard/Index.vue with Enhanced Navigation**

Now let me provide the complete enhanced Dashboard component with:

1. **Clickable stat cards**
2. **Quick navigation cards** (role-aware)
3. **Member applications list**
4. **Better fee links using real member ID**

---

**Updated Dashboard/Index.vue** (add after line 136 - after stat cards):

```vue
<!-- Quick Navigation Cards (role-aware shortcut strip) -->
<div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    
    <!-- Applications (owner/admin/commission) -->
    <a v-if="['owner', 'admin', 'commission'].includes(role)"
       :href="route('organisations.membership.applications.index', organisation.slug)"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">Applications</p>
            <p class="text-xs text-slate-400">{{ role === 'commission' ? 'View only' : 'Manage requests' }}</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Members (owner/admin only) -->
    <a v-if="['owner', 'admin'].includes(role)"
       :href="route('organisations.members.index', organisation.slug)"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">Members</p>
            <p class="text-xs text-slate-400">Directory & management</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- My Fees (member only) -->
    <a v-if="role === 'member' && memberSelf?.has_membership && memberSelf?.member_id"
       :href="route('organisations.members.fees.index', [organisation.slug, memberSelf.member_id])"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-green-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">My Fees</p>
            <p class="text-xs text-slate-400">Payment history</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Apply (non-members or members without membership) -->
    <a v-if="!memberSelf?.has_membership"
       :href="route('organisations.membership.apply', organisation.slug)"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">Apply</p>
            <p class="text-xs text-slate-400">Become a member</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Types (owner only) -->
    <a v-if="role === 'owner'"
       :href="route('organisations.membership-types.index', organisation.slug)"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">Types</p>
            <p class="text-xs text-slate-400">Manage tiers</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Renew (member with eligibility) -->
    <a v-if="role === 'member' && memberSelf?.can_self_renew"
       :href="route('organisations.members.renew', [organisation.slug, memberSelf.member_id])"
       class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 hover:border-orange-300 hover:shadow-md transition-all group">
        <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-700">Renew</p>
            <p class="text-xs text-slate-400">Extend membership</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
```

---

## **3. Make Stat Cards Clickable**

Replace the existing stat cards (lines 138-168) with clickable versions:

```vue
<!-- Stat cards (owner/admin: 4 cards; commission: 2 cards) - CLICKABLE -->
<div v-if="stats && Object.keys(stats).length" class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
    
    <!-- Total Members (clickable) -->
    <a v-if="stats.total_members !== undefined"
       :href="route('organisations.members.index', organisation.slug)"
       class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all cursor-pointer block">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Members</p>
        <p class="text-3xl font-bold text-slate-800">{{ stats.total_members ?? 0 }}</p>
        <p class="text-xs text-slate-400 mt-1">View all →</p>
    </a>

    <!-- Pending Applications (clickable) -->
    <a v-if="stats.pending_apps !== undefined"
       :href="route('organisations.membership.applications.index', organisation.slug) + '?status=pending'"
       class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all cursor-pointer block">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pending Applications</p>
        <p class="text-3xl font-bold" :class="(stats.pending_apps ?? 0) > 0 ? 'text-blue-600' : 'text-slate-800'">
            {{ stats.pending_apps ?? 0 }}
        </p>
        <p class="text-xs text-slate-400 mt-1">Review →</p>
    </a>

    <!-- Pending Fees (clickable - owner/admin only) -->
    <a v-if="stats.pending_fees_total !== undefined && role !== 'commission'"
       :href="route('organisations.members.index', organisation.slug) + '?filter=unpaid'"
       class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all cursor-pointer block">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pending Fees (EUR)</p>
        <p class="text-3xl font-bold" :class="(stats.pending_fees_total ?? 0) > 0 ? 'text-amber-600' : 'text-slate-800'">
            {{ (stats.pending_fees_total ?? 0).toFixed(2) }}
        </p>
        <p class="text-xs text-slate-400 mt-1">View unpaid →</p>
    </a>

    <!-- Expiring in 30 Days (clickable - owner/admin only) -->
    <a v-if="stats.expiring_in_30 !== undefined && role !== 'commission'"
       :href="route('organisations.members.index', organisation.slug) + '?filter=expiring'"
       class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md hover:border-purple-200 transition-all cursor-pointer block">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Expiring in 30 Days</p>
        <p class="text-3xl font-bold" :class="(stats.expiring_in_30 ?? 0) > 0 ? 'text-orange-600' : 'text-slate-800'">
            {{ stats.expiring_in_30 ?? 0 }}
        </p>
        <p class="text-xs text-slate-400 mt-1">View expiring →</p>
    </a>
</div>
```

---

## **4. Add Member Applications Section**

Add this after the quick navigation cards for member role:

```vue
<!-- Member: My Applications Section -->
<div v-if="role === 'member' && memberSelf" class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-slate-800">My Applications</h2>
        <a :href="route('organisations.membership.applications.index', organisation.slug)"
           class="text-xs font-medium text-purple-600 hover:text-purple-800">
            View all →
        </a>
    </div>
    
    <div v-if="memberSelf.my_applications && memberSelf.my_applications.length > 0" class="divide-y divide-slate-100">
        <div v-for="app in memberSelf.my_applications" :key="app.id" class="px-6 py-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <p class="text-sm font-medium text-slate-800">{{ app.membership_type || 'Membership' }}</p>
                    <p class="text-xs text-slate-400">Submitted: {{ formatDate(app.submitted_at) }}</p>
                </div>
                <span :class="statusClass(app.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                    {{ statusLabel(app.status) }}
                </span>
            </div>
        </div>
    </div>
    
    <div v-else class="px-6 py-8 text-center text-slate-400 text-sm">
        <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p>No applications yet.</p>
        <a :href="route('organisations.membership.apply', organisation.slug)" 
           class="inline-block mt-2 text-purple-600 hover:underline">
            Apply for membership →
        </a>
    </div>
</div>
```

---

## ✅ **Dashboard Enhancements Complete**

Now proceed to **Phase C: Rebuild Fees.vue** and **RenewModal.vue**.

Would you like me to continue with the Fees.vue rebuild? 🚀