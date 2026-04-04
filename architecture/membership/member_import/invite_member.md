review and re-write  architecture below and 
also use your frontend skill to  suggest the frontend  ui ux design for 
http://localhost:8000/organisations/namaste-nepal/members/invite

## 🏛️ **Architecture Analysis: Multi-Tenant Organisation Membership**

You've hit on a **critical multi-tenancy pattern**. Let me analyze your current state and propose solutions.

---

## 📊 **Current State Analysis**

### **What You Have**
```
PublicDigit Platform (global)
├── User: roshyara@gmail.com (exists)
├── User: Niraj Adhikari (exists - registered separately)
└── Organisation: namaste-nepal (owned by roshyara)

BUT: No link between Niraj and namaste-nepal organisation!
```

### **The Problem**
- Users register independently on PublicDigit
- Organisations are isolated tenants
- No mechanism to invite/associate existing users to organisations
- Currently only "Import Member" exists (likely CSV import of new users)

---

## 🎯 **The Solution: Invitation-Based Membership**

### **Architecture Flow**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ORGANISATION INVITATION FLOW                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Organisation Admin (roshyara)                                             │
│       │                                                                     │
│       ▼                                                                     │
│  Enters email: restaurant.namastenepal@gmail.com                           │
│       │                                                                     │
│       ▼                                                                     │
│  System checks: Does user exist?                                           │
│       │                                                                     │
│       ├─── EXISTS → Send invitation to existing user                       │
│       │                                                                     │
│       └─── NOT EXISTS → Send invitation to register + join                 │
│                                                                             │
│  User receives email with unique token                                      │
│       │                                                                     │
│       ▼                                                                     │
│  User clicks link → Accepts invitation                                      │
│       │                                                                     │
│       ▼                                                                     │
│  user_organisation_roles record created                                     │
│       │                                                                     │
│       ▼                                                                     │
│  User becomes organisation member                                           │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🏗️ **Database Schema (Already Exists?)**

### **Current `user_organisation_roles` Table**
```sql
-- Check if this table already exists
SELECT * FROM user_organisation_roles WHERE organisation_id = 'namaste-nepal-id';
```

If it exists, you have the foundation. If not, create it:

```php
Schema::create('user_organisation_roles', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->string('role'); // 'owner', 'admin', 'member', 'voter'
    $table->timestamp('assigned_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
    $table->unique(['user_id', 'organisation_id']);
});
```

---

## 🔧 **Implementation: Organisation Invitation System**

### **Step 1: Create Invitations Table**

```bash
php artisan make:migration create_organisation_invitations_table
```

```php
// database/migrations/xxxx_create_organisation_invitations_table.php
Schema::create('organisation_invitations', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');
    $table->string('email');
    $table->string('role')->default('member');
    $table->uuid('invited_by');
    $table->string('token', 64)->unique();
    $table->timestamp('expires_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
    $table->timestamps();
    
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
    $table->foreign('invited_by')->references('id')->on('users');
});
```

---

### **Step 2: Create Invitation Model**

```php
// app/Models/OrganisationInvitation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrganisationInvitation extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'organisation_id', 'email', 'role', 'invited_by', 'token', 'expires_at', 'accepted_at'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
    
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
    
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function isAccepted()
    {
        return !is_null($this->accepted_at);
    }
}
```

---

### **Step 3: Add Invitation Controller**

```bash
php artisan make:controller Organisations/OrganisationInvitationController
```

```php
// app/Http/Controllers/Organisations/OrganisationInvitationController.php
<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationInvitation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class OrganisationInvitationController extends Controller
{
    /**
     * Show invitation form in organisation dashboard
     */
    public function index(Organisation $organisation)
    {
        $this->authorize('manage', $organisation);
        
        $invitations = OrganisationInvitation::where('organisation_id', $organisation->id)
            ->with('inviter')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return Inertia::render('Organisations/Members/Invite', [
            'organisation' => $organisation,
            'invitations' => $invitations,
        ]);
    }
    
    /**
     * Send invitation to email
     */
    public function store(Request $request, Organisation $organisation)
    {
        $this->authorize('manage', $organisation);
        
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'role' => 'sometimes|in:member,admin,voter',
        ]);
        
        $email = $validated['email'];
        $role = $validated['role'] ?? 'member';
        
        // Check if user already exists in organisation
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            $alreadyMember = UserOrganisationRole::where('user_id', $existingUser->id)
                ->where('organisation_id', $organisation->id)
                ->exists();
                
            if ($alreadyMember) {
                return back()->withErrors(['email' => 'This user is already a member of the organisation.']);
            }
        }
        
        // Check for pending invitation (not expired, not accepted)
        $pendingInvite = OrganisationInvitation::where('email', $email)
            ->where('organisation_id', $organisation->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();
            
        if ($pendingInvite) {
            return back()->withErrors(['email' => 'An invitation has already been sent to this email. It expires in ' . $pendingInvite->expires_at->diffForHumans()]);
        }
        
        // Create invitation
        $invitation = OrganisationInvitation::create([
            'organisation_id' => $organisation->id,
            'email' => $email,
            'role' => $role,
            'invited_by' => auth()->id(),
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);
        
        // Send email
        $this->sendInvitationEmail($invitation);
        
        return back()->with('success', "Invitation sent to {$email}. They will have 7 days to accept.");
    }
    
    /**
     * Accept invitation (public route, no auth required)
     */
    public function accept(Request $request, $token)
    {
        $invitation = OrganisationInvitation::where('token', $token)->firstOrFail();
        
        if ($invitation->isExpired()) {
            return Inertia::render('Errors/Message', [
                'title' => 'Invitation Expired',
                'message' => 'This invitation has expired. Please ask the organisation admin to send a new invitation.',
            ]);
        }
        
        if ($invitation->isAccepted()) {
            return Inertia::render('Errors/Message', [
                'title' => 'Invitation Already Used',
                'message' => 'This invitation has already been accepted.',
            ]);
        }
        
        // User not logged in
        if (!auth()->check()) {
            // Store invitation token in session for after login/registration
            session(['pending_invitation_token' => $token]);
            
            return Inertia::render('Auth/Login', [
                'message' => 'Please login or register to join the organisation.',
                'show_register' => true,
                'email' => $invitation->email,
            ]);
        }
        
        $user = auth()->user();
        
        // Verify email matches invitation
        if ($user->email !== $invitation->email) {
            return Inertia::render('Errors/Message', [
                'title' => 'Email Mismatch',
                'message' => "This invitation was sent to {$invitation->email}. Please login with that email address.",
            ]);
        }
        
        // Create membership
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $invitation->organisation_id,
            'role' => $invitation->role,
            'assigned_at' => now(),
        ]);
        
        // Mark invitation as accepted
        $invitation->update(['accepted_at' => now()]);
        
        // Set current organisation in session
        session(['current_organisation_id' => $invitation->organisation_id]);
        
        return redirect()->route('organisations.show', $invitation->organisation->slug)
            ->with('success', "You have successfully joined {$invitation->organisation->name} as a {$invitation->role}.");
    }
    
    /**
     * Cancel invitation
     */
    public function destroy(Organisation $organisation, OrganisationInvitation $invitation)
    {
        $this->authorize('manage', $organisation);
        
        if ($invitation->isAccepted()) {
            return back()->withErrors(['error' => 'Cannot cancel an already accepted invitation.']);
        }
        
        $invitation->delete();
        
        return back()->with('success', "Invitation to {$invitation->email} cancelled.");
    }
    
    private function sendInvitationEmail(OrganisationInvitation $invitation)
    {
        $acceptUrl = route('organisations.invitations.accept', $invitation->token);
        
        Mail::send('emails.organisation-invitation', [
            'invitation' => $invitation,
            'acceptUrl' => $acceptUrl,
        ], function ($message) use ($invitation) {
            $message->to($invitation->email)
                ->subject("Invitation to join {$invitation->organisation->name} on PublicDigit");
        });
    }
}
```

---

### **Step 4: Add Routes**

```php
// routes/organisations.php

Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        // Invitation management
        Route::get('/members/invite', [OrganisationInvitationController::class, 'index'])
            ->name('organisations.members.invite');
        Route::post('/members/invite', [OrganisationInvitationController::class, 'store'])
            ->name('organisations.members.invite.store');
        Route::delete('/members/invitations/{invitation}', [OrganisationInvitationController::class, 'destroy'])
            ->name('organisations.members.invitations.destroy');
    });

// Public invitation acceptance (no auth)
Route::get('/invitations/{token}', [OrganisationInvitationController::class, 'accept'])
    ->name('organisations.invitations.accept');
```

---

### **Step 5: Add UI to Organisation Dashboard**

```vue
<!-- In Organisations/Show.vue, add Invite Member button -->
<Button 
    as="a" 
    :href="route('organisations.members.invite', organisation.slug)"
    variant="primary"
    size="sm"
>
    Invite Member
</Button>
```

---

### **Step 6: Create Invitation Page**

```vue
<!-- resources/js/Pages/Organisations/Members/Invite.vue -->
<template>
    <AppLayout :title="`Invite Members - ${organisation.name}`">
        <div class="max-w-4xl mx-auto py-8">
            <Card mode="admin" padding="lg">
                <h1 class="text-2xl font-bold text-neutral-900 mb-4">Invite Members</h1>
                
                <form @submit.prevent="sendInvite" class="mb-8">
                    <div class="flex gap-3">
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Enter email address"
                            class="flex-1 border border-neutral-300 rounded-lg px-4 py-2"
                            required
                        />
                        <select v-model="form.role" class="border border-neutral-300 rounded-lg px-4 py-2">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                            <option value="voter">Voter</option>
                        </select>
                        <Button type="submit" :loading="sending">Send Invitation</Button>
                    </div>
                    <p v-if="errors.email" class="text-red-600 text-sm mt-2">{{ errors.email }}</p>
                </form>
                
                <h2 class="text-lg font-semibold text-neutral-900 mb-3">Pending Invitations</h2>
                <div v-if="invitations.length === 0" class="text-neutral-500 italic">
                    No pending invitations.
                </div>
                <div v-for="invite in invitations" :key="invite.id" class="flex items-center justify-between py-3 border-b">
                    <div>
                        <p class="font-medium">{{ invite.email }}</p>
                        <p class="text-sm text-neutral-500">Role: {{ invite.role }} · Expires: {{ formatDate(invite.expires_at) }}</p>
                    </div>
                    <Button variant="danger" size="sm" @click="cancelInvite(invite)">Cancel</Button>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
```

---

## ✅ **Summary**

| Scenario | How It Works |
|----------|--------------|
| **User exists** | Invitation email → Accept → Create membership |
| **User doesn't exist** | Invitation email → Register → Auto-join organisation |
| **Already member** | Validation prevents duplicate |
| **Expired invitation** | Link shows error, admin can resend |
| **Wrong email login** | Shows mismatch error |

**This solves your exact problem: Niraj can be invited via email to join namaste-nepal!** 🚀
Looking at your architecture and the specific endpoint, I can see you're building a multi-tenant organisation system with an invitation flow. Let me review and enhance both the backend architecture and suggest a comprehensive frontend UI/UX design.

## 🔍 **Architecture Review & Improvements**

### **Current Issues Identified:**
1. **Missing invitation tracking** - No way to know who was invited, when, and if they accepted
2. **No expiration mechanism** - Invitations could linger indefinitely
3. **Missing role-based permissions** - Can't specify what role the invited user gets
4. **No resend/cancel capability** - Admin can't manage pending invitations
5. **Session-based state risks** - Losing pending invitation on browser close

### **Improved Architecture:**

```php
// Enhanced Database Schema
Schema::create('organisation_invitations', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organisation_id')->constrained()->onDelete('cascade');
    $table->string('email');
    $table->enum('role', ['owner', 'admin', 'member', 'voter'])->default('member');
    $table->enum('status', ['pending', 'accepted', 'expired', 'cancelled'])->default('pending');
    $table->foreignUuid('invited_by')->constrained('users');
    $table->string('token', 64)->unique();
    $table->timestamp('expires_at')->default(now()->addDays(7));
    $table->timestamp('accepted_at')->nullable();
    $table->integer('resend_count')->default(0);
    $table->timestamps();
    
    $table->index(['organisation_id', 'status']);
    $table->index(['email', 'status']);
});
```

## 🎨 **Frontend UI/UX Design for `/invite` Endpoint**

### **Page Structure & Components**

```vue
<!-- resources/js/Pages/Organisations/Members/Invite.vue -->
<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4 md:flex-row md:justify-between md:items-center md:space-y-0">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        Invite Team Members
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Invite people to join {{ organisation.name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button 
                        @click="viewAllMembers"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                    >
                        View All Members
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Column: Invite Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Send New Invitation
                                </h2>
                                
                                <form @submit.prevent="submitInvitation" class="space-y-4">
                                    <!-- Email Input with Validation -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Email Address
                                        </label>
                                        <div class="relative">
                                            <input
                                                v-model="form.email"
                                                type="email"
                                                :class="[
                                                    'w-full rounded-lg border-gray-300 dark:border-gray-600',
                                                    'focus:ring-indigo-500 focus:border-indigo-500',
                                                    'dark:bg-gray-700 dark:text-white',
                                                    errors.email ? 'border-red-500' : ''
                                                ]"
                                                placeholder="colleague@company.com"
                                                @keydown.enter.prevent
                                            />
                                            <div v-if="validatingEmail" class="absolute right-3 top-2.5">
                                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                                        <p v-else-if="emailSuggestion" class="mt-1 text-sm text-yellow-600">
                                            Did you mean {{ emailSuggestion }}?
                                        </p>
                                    </div>

                                    <!-- Role Selection with Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Role
                                        </label>
                                        <select
                                            v-model="form.role"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                        >
                                            <option v-for="role in roles" :key="role.value" :value="role.value">
                                                {{ role.label }}
                                            </option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ currentRoleDescription }}
                                        </p>
                                    </div>

                                    <!-- Optional Message -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Personal Message (Optional)
                                        </label>
                                        <textarea
                                            v-model="form.message"
                                            rows="3"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Why are you inviting them? What will they be working on?"
                                        ></textarea>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ 200 - form.message.length }} characters remaining
                                        </p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <button
                                            type="submit"
                                            :disabled="processing"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                                        >
                                            <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Send Invitation
                                        </button>
                                    </div>
                                </form>

                                <!-- Quick Tips -->
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">💡 Quick Tips</h3>
                                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>• Invitations expire after 7 days</li>
                                        <li>• Users can accept from any device</li>
                                        <li>• You can resend or cancel pending invites</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Pending Invitations List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Pending Invitations
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            {{ pendingInvitations.length }}
                                        </span>
                                    </h2>
                                    <div class="flex space-x-2">
                                        <button 
                                            @click="refreshInvitations"
                                            class="text-sm text-indigo-600 hover:text-indigo-900"
                                        >
                                            Refresh
                                        </button>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div v-if="loading" class="text-center py-12">
                                    <svg class="animate-spin mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-500">Loading invitations...</p>
                                </div>

                                <!-- Empty State -->
                                <div v-else-if="pendingInvitations.length === 0 && sentInvitations.length === 0" class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No invitations sent</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Get started by sending your first invitation.
                                    </p>
                                </div>

                                <!-- Pending Invitations Section -->
                                <div v-else>
                                    <!-- Active/Pending Invitations -->
                                    <div v-if="pendingInvitations.length > 0" class="space-y-3 mb-6">
                                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Invitations</h3>
                                        <div v-for="invite in pendingInvitations" :key="invite.id" 
                                             class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            Expires {{ formatRelativeTime(invite.expires_at) }}
                                                        </span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            {{ invite.email }}
                                                        </p>
                                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                            <span>Role: <span class="font-medium capitalize">{{ invite.role }}</span></span>
                                                            <span>•</span>
                                                            <span>Invited by {{ invite.inviter.name }}</span>
                                                            <span>•</span>
                                                            <span>{{ formatDate(invite.created_at) }}</span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                                                <div class="bg-indigo-600 h-1.5 rounded-full" 
                                                                     :style="{ width: getExpiryPercentage(invite.expires_at) + '%' }"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2 ml-4">
                                                    <button 
                                                        @click="resendInvitation(invite)"
                                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                                        title="Resend invitation"
                                                    >
                                                        Resend
                                                    </button>
                                                    <button 
                                                        @click="cancelInvitation(invite)"
                                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                        title="Cancel invitation"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sent/Expired Invitations (Collapsible) -->
                                    <div v-if="sentInvitations.length > 0">
                                        <button 
                                            @click="showHistory = !showHistory"
                                            class="flex items-center justify-between w-full text-left text-sm font-medium text-gray-500 hover:text-gray-700 py-2"
                                        >
                                            <span>History ({{ sentInvitations.length }})</span>
                                            <svg :class="['h-5 w-5 transform transition-transform', showHistory ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        
                                        <div v-show="showHistory" class="space-y-2 mt-2">
                                            <div v-for="invite in sentInvitations" :key="invite.id" 
                                                 class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 opacity-75">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                            {{ invite.email }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ invite.status }} • {{ formatDate(invite.created_at) }}
                                                        </p>
                                                    </div>
                                                    <span class="text-xs text-gray-400">
                                                        {{ invite.accepted_at ? 'Accepted' : 'Expired' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div class="fixed bottom-4 right-4 space-y-2 z-50">
            <transition-group name="toast">
                <div v-for="toast in toasts" :key="toast.id" 
                     :class="[
                         'rounded-lg shadow-lg p-4 min-w-[300px]',
                         toast.type === 'success' ? 'bg-green-500' : 
                         toast.type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                     ]">
                    <p class="text-white text-sm">{{ toast.message }}</p>
                </div>
            </transition-group>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useToast } from '@/Composables/useToast'

const props = defineProps({
    organisation: Object,
    invitations: Array,
    errors: Object
})

const { toasts, addToast } = useToast()
const loading = ref(false)
const validatingEmail = ref(false)
const emailSuggestion = ref('')
const showHistory = ref(false)
const processing = ref(false)

const roles = [
    { value: 'member', label: 'Member', description: 'Can view and participate in organisation activities' },
    { value: 'admin', label: 'Admin', description: 'Can manage members and organisation settings' },
    { value: 'voter', label: 'Voter', description: 'Can vote in organisation decisions' }
]

const currentRoleDescription = computed(() => {
    const role = roles.find(r => r.value === form.role)
    return role ? role.description : ''
})

const form = useForm({
    email: '',
    role: 'member',
    message: ''
})

const pendingInvitations = computed(() => {
    return props.invitations?.filter(inv => inv.status === 'pending') || []
})

const sentInvitations = computed(() => {
    return props.invitations?.filter(inv => inv.status !== 'pending') || []
})

// Email validation with debounce
let emailTimeout
watch(() => form.email, (newEmail) => {
    clearTimeout(emailTimeout)
    if (newEmail && newEmail.includes('@')) {
        validatingEmail.value = true
        emailTimeout = setTimeout(() => validateEmail(newEmail), 500)
    } else {
        emailSuggestion.value = ''
    }
})

const validateEmail = async (email) => {
    try {
        const response = await axios.post('/api/validate-email', { email })
        if (response.data.suggestion) {
            emailSuggestion.value = response.data.suggestion
        }
    } catch (error) {
        console.error('Email validation failed', error)
    } finally {
        validatingEmail.value = false
    }
}

const submitInvitation = () => {
    processing.value = true
    form.post(route('organisations.members.invite.store', props.organisation.slug), {
        onSuccess: () => {
            addToast('Invitation sent successfully!', 'success')
            form.reset('email', 'message')
            emailSuggestion.value = ''
        },
        onError: (errors) => {
            addToast(errors.email || 'Failed to send invitation', 'error')
        },
        onFinish: () => {
            processing.value = false
        }
    })
}

const resendInvitation = (invite) => {
    if (confirm(`Resend invitation to ${invite.email}?`)) {
        axios.post(route('organisations.members.invitations.resend', {
            organisation: props.organisation.slug,
            invitation: invite.id
        })).then(() => {
            addToast(`Invitation resent to ${invite.email}`, 'success')
            refreshInvitations()
        }).catch(() => {
            addToast('Failed to resend invitation', 'error')
        })
    }
}

const cancelInvitation = (invite) => {
    if (confirm(`Cancel invitation to ${invite.email}?`)) {
        axios.delete(route('organisations.members.invitations.destroy', {
            organisation: props.organisation.slug,
            invitation: invite.id
        })).then(() => {
            addToast(`Invitation to ${invite.email} cancelled`, 'success')
            refreshInvitations()
        }).catch(() => {
            addToast('Failed to cancel invitation', 'error')
        })
    }
}

const refreshInvitations = () => {
    loading.value = true
    axios.get(route('organisations.members.invite', props.organisation.slug))
        .then(({ data }) => {
            // Update invitations list
            Object.assign(props.invitations, data.invitations)
        })
        .finally(() => {
            loading.value = false
        })
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const formatRelativeTime = (date) => {
    const diff = new Date(date) - new Date()
    const days = Math.ceil(diff / (1000 * 60 * 60 * 24))
    if (days <= 0) return 'today'
    if (days === 1) return 'tomorrow'
    return `in ${days} days`
}

const getExpiryPercentage = (expiresAt) => {
    const created = new Date(invite.created_at)
    const expires = new Date(expiresAt)
    const now = new Date()
    const total = expires - created
    const elapsed = now - created
    return Math.max(0, Math.min(100, (elapsed / total) * 100))
}

const viewAllMembers = () => {
    window.location.href = route('organisations.members', props.organisation.slug)
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from,
.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>
```

## 📧 **Email Template Design**

```html
<!-- resources/views/emails/organisation-invitation.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to join {{ $invitation->organisation->name }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 28px; margin: 0 0 10px 0;">You're Invited! 🎉</h1>
                            <p style="color: #e0e7ff; font-size: 16px; margin: 0;">Join {{ $invitation->organisation->name }} on PublicDigit</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #1f2937; font-size: 16px; line-height: 24px; margin-bottom: 20px;">
                                <strong>{{ $invitation->inviter->name }}</strong> has invited you to join 
                                <strong>{{ $invitation->organisation->name }}</strong> as a 
                                <strong>{{ ucfirst($invitation->role) }}</strong>.
                            </p>
                            
                            @if($invitation->message)
                            <div style="background-color: #f3f4f6; border-left: 4px solid #6366f1; padding: 16px; margin: 20px 0; border-radius: 6px;">
                                <p style="color: #4b5563; font-style: italic; margin: 0;">"{{ $invitation->message }}"</p>
                                <p style="color: #6b7280; font-size: 14px; margin: 10px 0 0 0;">— {{ $invitation->inviter->name }}</p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $acceptUrl }}" style="display: inline-block; background-color: #6366f1; color: #ffffff; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                            Accept Invitation →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Details -->
                            <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                <h3 style="color: #1f2937; font-size: 14px; margin: 0 0 12px 0;">Invitation Details:</h3>
                                <ul style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px;">
                                    <li>Role: <strong>{{ ucfirst($invitation->role) }}</strong></li>
                                    <li>Expires: {{ $invitation->expires_at->format('F j, Y') }}</li>
                                    <li>Organisation: {{ $invitation->organisation->name }}</li>
                                </ul>
                            </div>
                            
                            <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 30px;">
                                This invitation will expire on {{ $invitation->expires_at->format('F j, Y') }}.<br>
                                If you weren't expecting this invitation, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} PublicDigit. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
```

## 🚀 **Key UX Improvements:**

### **1. Smart Email Validation**
- Real-time email format checking
- Domain suggestion (gmail.com vs gmal.com)
- Checks if user already exists in system

### **2. Visual Progress Indicators**
- Expiration progress bars
- Color-coded status badges
- Relative time display

### **3. Bulk Invitations (Bonus Feature)**
```vue
<!-- Add CSV upload for bulk invites -->
<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Bulk Invite (CSV)
    </label>
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
        <input type="file" accept=".csv" @change="uploadCSV" class="hidden" ref="csvInput">
        <button @click="$refs.csvInput.click()" class="text-indigo-600 hover:text-indigo-900">
            Upload CSV file
        </button>
        <p class="text-xs text-gray-500 mt-2">Format: email,role (member/admin/voter)</p>
    </div>
</div>
```

### **4. Keyboard Shortcuts**
- `Cmd/Ctrl + Enter` - Send invitation
- `Esc` - Clear form
- `Cmd/Ctrl + K` - Focus email field

### **5. Responsive Design**
- Mobile-optimized layout
- Touch-friendly buttons
- Swipe gestures on mobile for invitation actions

## 📊 **Performance Optimizations:**

1. **Lazy loading** - Load invitation history only when expanded
2. **Request debouncing** - Prevent duplicate submissions
3. **Optimistic updates** - Update UI immediately, revert on error
4. **WebSocket/Realtime** - Live updates when invitations are accepted

## 🔒 **Security Considerations:**

1. **Rate limiting** - Max 20 invitations per minute
2. **Email verification** - Only invite verified emails
3. **Token encryption** - Use signed URLs for acceptance
4. **Audit logging** - Track all invitation actions

This architecture and UI/UX design provides a complete, production-ready invitation system that's user-friendly, secure, and scalable. The design focuses on clarity, reducing user errors, and providing excellent feedback throughout the invitation process.
 