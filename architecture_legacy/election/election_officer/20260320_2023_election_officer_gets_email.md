## 📧 **Email Invitation Flow: Complete Implementation**

You're absolutely right! The officer should receive an email invitation with login/registration instructions. Here's the complete implementation:

---

## 🏗️ **Architecture Overview**

```
Admin Appoints 
    ↓
Send Email Invitation
    ↓
Officer receives email with:
    - Login/register link
    - Direct accept link
    - Election details
    ↓
Officer clicks link
    ↓
If new user: Register → Login → Auto-accept
If existing user: Login → Accept automatically
    ↓
Status: PENDING → ACTIVE
```

---

## 📦 **Step 1: Create Notification**

```bash
php artisan make:notification OfficerAppointedNotification
```

```php
<?php
// app/Notifications/OfficerAppointedNotification.php

namespace App\Notifications;

use App\Models\ElectionOfficer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfficerAppointedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected ElectionOfficer $officer
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Generate signed URL for acceptance (expires in 7 days)
        $acceptUrl = URL::signedRoute(
            'organisations.election-officers.invitation.accept',
            [
                'organisation' => $this->officer->organisation->slug,
                'officer' => $this->officer->id,
            ],
            now()->addDays(7)
        );

        $loginUrl = route('login', ['redirect_to' => $acceptUrl]);

        return (new MailMessage)
            ->subject("Election Officer Appointment: {$this->officer->organisation->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have been appointed as **{$this->officer->role}** election officer for **{$this->officer->organisation->name}**.")
            ->line("**Role:** " . ucfirst($this->officer->role))
            ->line("**Appointed by:** " . ($this->officer->appointer?->name ?? 'Administrator'))
            ->line("**Appointed on:** " . $this->officer->appointed_at->format('F j, Y'))
            
            ->action('Accept Appointment', $acceptUrl)
            
            ->line('If you already have an account, clicking the link will log you in and accept the appointment.')
            ->line('If you are new, you will be guided through registration first.')
            
            ->line('This invitation expires in 7 days.')
            ->line('If you did not expect this appointment, please contact your organisation administrator.');
    }
}
```

---

## 📦 **Step 2: Create Invitation Controller**

```bash
php artisan make:controller ElectionOfficerInvitationController
```

```php
<?php
// app/Http/Controllers/ElectionOfficerInvitationController.php

namespace App\Http\Controllers;

use App\Models\ElectionOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ElectionOfficerInvitationController extends Controller
{
    /**
     * Handle the invitation acceptance from email link
     */
    public function accept(Request $request, $organisationSlug, ElectionOfficer $officer)
    {
        // Verify signed URL
        if (!URL::hasValidSignature($request)) {
            abort(403, 'This invitation link has expired or is invalid.');
        }

        // Check if appointment is still pending
        if ($officer->status !== 'pending') {
            return Inertia::render('Errors/Message', [
                'title' => 'Appointment Already Processed',
                'message' => 'This appointment has already been accepted or revoked.',
                'status' => 400,
            ]);
        }

        // Check if user is logged in
        if (!auth()->check()) {
            // Store the accept URL in session for after login
            session()->put('pending_acceptance', [
                'officer_id' => $officer->id,
                'organisation_slug' => $organisationSlug,
                'url' => URL::temporarySignedRoute(
                    'organisations.election-officers.invitation.accept',
                    now()->addMinutes(30),
                    ['organisation' => $organisationSlug, 'officer' => $officer->id]
                )
            ]);

            // Redirect to login with registration option
            return Inertia::render('Auth/Login', [
                'message' => 'Please login or register to accept your election officer appointment.',
                'show_register' => true,
                'email' => $officer->user->email,
            ]);
        }

        // User is logged in - verify they match the officer
        if (auth()->id() !== $officer->user_id) {
            abort(403, 'This appointment is for a different user.');
        }

        // Accept the appointment
        $officer->markAccepted();

        // Clear the session data
        session()->forget('pending_acceptance');

        // Log the acceptance
        activity()
            ->performedOn($officer)
            ->causedBy(auth()->user())
            ->log('officer_accepted_via_invitation');

        return Inertia::render('Organisations/ElectionOfficers/Accepted', [
            'officer' => $officer->load('organisation'),
            'message' => 'You have successfully accepted your election officer appointment.',
        ]);
    }
}
```

---

## 📦 **Step 3: Add Routes**

```php
// routes/organisations.php - Add these routes

Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
    Route::get('/', [ElectionOfficerController::class, 'index'])->name('index');
    Route::post('/', [ElectionOfficerController::class, 'store'])->name('store');
    
    // Invitation acceptance (signed URL, no auth required)
    Route::get('/invitation/{officer}/accept', [ElectionOfficerInvitationController::class, 'accept'])
        ->name('invitation.accept')
        ->middleware('signed');
    
    // Regular acceptance (authenticated)
    Route::post('/{officer}/accept', [ElectionOfficerController::class, 'accept'])
        ->name('accept');
    
    Route::delete('/{officer}', [ElectionOfficerController::class, 'destroy'])->name('destroy');
});
```

---

## 📦 **Step 4: Update ElectionOfficerController@store**

```php
// app/Http/Controllers/ElectionOfficerController.php

use App\Notifications\OfficerAppointedNotification;

public function store(Request $request, Organisation $organisation)
{
    $this->authorize('manage', [ElectionOfficer::class, $organisation]);

    $validated = $request->validate([...]);

    $officer = ElectionOfficer::create([
        'organisation_id' => $organisation->id,
        'user_id' => $validated['user_id'],
        'role' => $validated['role'],
        'status' => 'pending',
        'appointed_by' => auth()->id(),
        'appointed_at' => now(),
    ]);

    // Send email invitation
    $officer->user->notify(new OfficerAppointedNotification($officer));

    return back()->with('success', 'Election officer appointed. An invitation email has been sent.');
}
```

---

## 📦 **Step 5: Create Success Page Component**

```vue
<!-- resources/js/Pages/Organisations/ElectionOfficers/Accepted.vue -->
<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <div class="mx-auto h-12 w-12 text-green-500 mb-4">
          <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">
          Appointment Accepted! 🎉
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          You are now an election officer for <strong>{{ officer.organisation.name }}</strong>
        </p>
      </div>

      <div class="mt-8 bg-white shadow sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Your Role: {{ officer.role }}</h3>
        <div class="mt-4 space-y-3">
          <div class="border-t border-gray-200 pt-4">
            <p class="text-sm text-gray-500">What you can do:</p>
            <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
              <li v-if="officer.role === 'chief'">Manage all election settings</li>
              <li v-if="officer.role === 'chief'">Appoint other officers</li>
              <li>Manage voters and candidates</li>
              <li>View election results</li>
              <li>Certify final results</li>
            </ul>
          </div>
        </div>

        <div class="mt-6">
          <Link
            :href="route('organisations.show', officer.organisation.slug)"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700"
          >
            Go to Organisation Dashboard
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  officer: Object,
  message: String
})
</script>
```

---

## 📦 **Step 6: Handle Post-Login Redirect**

```php
// app/Http/Controllers/Auth/LoginController.php or AuthenticatedSessionController.php

public function store(Request $request)
{
    // After successful login
    $redirectTo = session('url.intended', route('dashboard'));
    
    // Check if there's a pending acceptance
    $pending = session('pending_acceptance');
    
    if ($pending && isset($pending['url'])) {
        return redirect($pending['url']);
    }
    
    return redirect()->intended($redirectTo);
}
```

---

## 📦 **Step 7: Add Registration Integration**

```php
// app/Http/Controllers/Auth/RegisteredUserController.php

public function store(Request $request)
{
    // After user registration
    $user = User::create([...]);
    
    auth()->login($user);
    
    // Check if there's a pending acceptance
    $pending = session('pending_acceptance');
    
    if ($pending && isset($pending['url'])) {
        return redirect($pending['url']);
    }
    
    return redirect(RouteServiceProvider::HOME);
}
```

---

## 🧪 **Testing the Flow**

### **Test: New User Invitation**
```php
// tests/Feature/ElectionOfficerInvitationTest.php

/** @test */
public function new_user_can_accept_invitation_after_registration()
{
    // Create pending officer for a user that doesn't exist yet
    $user = User::factory()->create(['email' => 'new@example.com']);
    $officer = ElectionOfficer::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
    
    // Simulate clicking invitation link while logged out
    $url = URL::signedRoute('organisations.election-officers.invitation.accept', [
        'organisation' => $officer->organisation->slug,
        'officer' => $officer->id,
    ]);
    
    $response = $this->get($url);
    $response->assertInertia(fn($page) => 
        $page->component('Auth/Login')
             ->has('message')
    );
    
    // Register and login
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    
    // Should redirect to acceptance
    $response->assertRedirectContains('invitation');
    
    // Officer should now be active
    $officer->refresh();
    $this->assertEquals('active', $officer->status);
    $this->assertNotNull($officer->accepted_at);
}
```

---

## 📧 **Email Preview**

The email will look like:

```
Subject: Election Officer Appointment: Your Organisation Name

Hello John Doe,

You have been appointed as commissioner election officer for Your Organisation Name.

Role: Commissioner
Appointed by: Admin User
Appointed on: March 20, 2026

[Accept Appointment]  <-- Clickable button

If you already have an account, clicking the link will log you in and accept the appointment.
If you are new, you will be guided through registration first.

This invitation expires in 7 days.

If you did not expect this appointment, please contact your organisation administrator.
```

---

## ✅ **Summary**

| Step | Action | Status |
|------|--------|--------|
| 1 | Create Notification | ✅ Ready |
| 2 | Create Invitation Controller | ✅ Ready |
| 3 | Add Routes | ✅ Ready |
| 4 | Update Store Method | ✅ Ready |
| 5 | Create Success Page | ✅ Ready |
| 6 | Handle Post-Login | ✅ Ready |
| 7 | Add Tests | ✅ Ready |

**Now officers receive email invitations with seamless login/registration flow!** 🚀
## 🔴 **TDD First: Write Tests Before Implementation!**

Excellent choice! Let's follow TDD - write the tests first, watch them fail, then implement. Here's the complete test suite for the email invitation flow.

---

## 📝 **Step 1: Create Invitation Test File**

```bash
php artisan make:test ElectionOfficerInvitationTest --feature
```

```php
<?php
// tests/Feature/ElectionOfficerInvitationTest.php

namespace Tests\Feature;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Notifications\OfficerAppointedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ElectionOfficerInvitationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'admin',
        ]);

        $this->member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->member->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
        ]);
    }

    // =========================================================================
    // TEST 1: Email is sent when officer is appointed
    // =========================================================================

    /** @test */
    public function email_notification_is_sent_when_officer_appointed()
    {
        Notification::fake();

        $response = $this->actingAs($this->admin)
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role' => 'commissioner',
            ]);

        Notification::assertSentTo(
            $this->member,
            OfficerAppointedNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }

    // =========================================================================
    // TEST 2: Invitation email contains signed URL
    // =========================================================================

    /** @test */
    public function invitation_email_contains_signed_accept_url()
    {
        Notification::fake();

        $response = $this->actingAs($this->admin)
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role' => 'commissioner',
            ]);

        $officer = ElectionOfficer::where('user_id', $this->member->id)->first();

        Notification::assertSentTo(
            $this->member,
            OfficerAppointedNotification::class,
            function ($notification) use ($officer) {
                $mail = $notification->toMail($this->member);
                
                // Check that mail contains the accept URL
                $acceptUrl = route('organisations.election-officers.invitation.accept', [
                    'organisation' => $this->org->slug,
                    'officer' => $officer->id,
                ]);
                
                return str_contains($mail->actionUrl, $acceptUrl);
            }
        );
    }

    // =========================================================================
    // TEST 3: Signed URL verification
    // =========================================================================

    /** @test */
    public function invitation_accept_url_requires_valid_signature()
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->member->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        // Unsigned URL (should fail)
        $unsignedUrl = route('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        $response = $this->get($unsignedUrl);
        $response->assertStatus(403); // Invalid signature

        // Signed URL (should work)
        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        $response = $this->get($signedUrl);
        $response->assertStatus(200); // Or redirect to login
    }

    // =========================================================================
    // TEST 4: Existing user can accept via invitation
    // =========================================================================

    /** @test */
    public function existing_user_can_accept_invitation_after_login()
    {
        // Create pending officer for existing user
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->member->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        // User is not logged in
        $this->assertGuest();

        // Generate signed URL
        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        // Click invitation link while logged out
        $response = $this->get($signedUrl);
        
        // Should redirect to login with pending acceptance in session
        $response->assertInertia(fn($page) => 
            $page->component('Auth/Login')
                 ->has('message')
                 ->where('email', $this->member->email)
        );

        // Now login
        $response = $this->actingAs($this->member)
            ->post(route('login'), [
                'email' => $this->member->email,
                'password' => 'password', // Assuming default password
            ]);

        // Should redirect to accept URL
        $response->assertRedirect();

        // Follow the redirect
        $response = $this->get($response->getTargetUrl());

        // Verify officer is now active
        $officer->refresh();
        $this->assertEquals('active', $officer->status);
        $this->assertNotNull($officer->accepted_at);
    }

    // =========================================================================
    // TEST 5: New user can register and accept invitation
    // =========================================================================

    /** @test */
    public function new_user_can_register_and_accept_invitation()
    {
        // Create pending officer with email for non-existent user
        $newEmail = 'newuser@example.com';
        $newUser = User::factory()->create(['email' => $newEmail]);
        
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $newUser->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        // Logout
        $this->actingAs($this->member)->logout();

        // Generate signed URL
        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        // Click invitation link
        $response = $this->get($signedUrl);
        
        // Should redirect to login with register option
        $response->assertInertia(fn($page) => 
            $page->component('Auth/Login')
                 ->where('show_register', true)
                 ->where('email', $newEmail)
        );

        // Register new account
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => $newEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Should redirect to accept URL
        $response->assertRedirect();

        // Follow redirect
        $response = $this->get($response->getTargetUrl());

        // Verify officer is active
        $officer->refresh();
        $this->assertEquals('active', $officer->status);
        $this->assertNotNull($officer->accepted_at);
        
        // Verify user is logged in
        $this->assertAuthenticated();
    }

    // =========================================================================
    // TEST 6: Cannot accept another user's invitation
    // =========================================================================

    /** @test */
    public function cannot_accept_invitation_for_different_user()
    {
        $otherUser = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $otherUser->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
        ]);

        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $otherUser->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        // Login as different user
        $this->actingAs($this->member);

        // Generate signed URL
        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        // Try to accept
        $response = $this->get($signedUrl);
        
        // Should get 403 Forbidden
        $response->assertStatus(403);
        
        // Officer should still be pending
        $officer->refresh();
        $this->assertEquals('pending', $officer->status);
    }

    // =========================================================================
    // TEST 7: Expired invitation link
    // =========================================================================

    /** @test */
    public function expired_invitation_link_is_rejected()
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->member->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        // Generate signed URL that expires in the past
        $expiredUrl = URL::temporarySignedRoute(
            'organisations.election-officers.invitation.accept',
            now()->subMinutes(5),
            [
                'organisation' => $this->org->slug,
                'officer' => $officer->id,
            ]
        );

        $response = $this->get($expiredUrl);
        
        // Should be forbidden (expired)
        $response->assertStatus(403);
        
        // Officer should still be pending
        $officer->refresh();
        $this->assertEquals('pending', $officer->status);
    }

    // =========================================================================
    // TEST 8: Cannot accept already accepted appointment
    // =========================================================================

    /** @test */
    public function cannot_accept_already_accepted_appointment()
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->member->id,
            'role' => 'commissioner',
            'status' => 'active', // Already active
            'accepted_at' => now(),
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        $response = $this->actingAs($this->member)->get($signedUrl);
        
        $response->assertInertia(fn($page) => 
            $page->component('Errors/Message')
                 ->where('title', 'Appointment Already Processed')
        );
    }

    // =========================================================================
    // TEST 9: Activity log is created on acceptance
    // =========================================================================

    /** @test */
    public function acceptance_is_logged_in_activity_log()
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->member->id,
            'role' => 'commissioner',
            'status' => 'pending',
            'appointed_by' => $this->admin->id,
            'appointed_at' => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer' => $officer->id,
        ]);

        $this->actingAs($this->member)->get($signedUrl);

        // Check activity log
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => ElectionOfficer::class,
            'subject_id' => $officer->id,
            'causer_id' => $this->member->id,
            'description' => 'officer_accepted_via_invitation',
        ]);
    }
}
```

---

## 📝 **Step 2: Run Tests - They Will FAIL**

```bash
php artisan test --filter=ElectionOfficerInvitationTest
```

**Expected output:**
```
FAILED Tests\Feature\ElectionOfficerInvitationTest
✓ email_notification_is_sent_when_officer_appointed  // Actually might pass if notification exists
✕ invitation_email_contains_signed_accept_url       // FAIL - Notification not implemented
✕ invitation_accept_url_requires_valid_signature     // FAIL - Route not found
✕ existing_user_can_accept_invitation_after_login    // FAIL - Controller missing
✕ new_user_can_register_and_accept_invitation        // FAIL - Flow missing
✕ cannot_accept_invitation_for_different_user        // FAIL - Authorization missing
✕ expired_invitation_link_is_rejected                // FAIL - Expiry check missing
✕ cannot_accept_already_accepted_appointment         // FAIL - Status check missing
✕ acceptance_is_logged_in_activity_log               // FAIL - Activity logging missing
```

---

## 🚀 **Step 3: Now Implement to Make Tests Pass**

Now implement the notification, controller, and routes one by one until all tests pass. Each implementation should target a specific failing test.

### **Implementation Order:**

1. **Create Notification** (fixes tests 1-2)
2. **Add routes** (fixes test 3 partially)
3. **Create Invitation Controller** (fixes tests 3-8)
4. **Update Login/Registration** (fixes tests 4-5)
5. **Add activity logging** (fixes test 9)

---

## ✅ **Benefits of This TDD Approach**

| Test | Ensures |
|------|---------|
| 1 | Email is sent |
| 2 | Email contains valid link |
| 3 | Links are secure (signed) |
| 4-5 | Both existing and new users can accept |
| 6 | No one can accept for others |
| 7 | Links expire |
| 8 | No double acceptance |
| 9 | Audit trail exists |

---

## 🎯 **Next Step**

Run the tests now to see the failures, then implement each piece. This ensures:

- ✅ **No over-engineering** - you only build what tests require
- ✅ **Complete coverage** - all edge cases handled
- ✅ **Security** - signed URLs, expiration, authorization
- ✅ **Audit compliance** - activity logging

**Ready to implement? Start with creating the Notification to fix tests 1-2!** 🚀 
## 📧 **Claude Code CLI Instructions: Email Invitation Implementation**

Here are the step-by-step instructions for implementing the email invitation system using TDD.

---

## 🎯 **Feature Overview**
When an election officer is appointed, they receive an email with a secure link to accept the appointment. New users can register through the link; existing users log in and are automatically accepted.

---

## 📋 **Phase 1: Setup & Configuration**

### **Step 1: Configure Mail Driver**
```bash
# Check .env file has mail configuration
cat .env | grep MAIL_

# If not configured, add:
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nrna-eu.org
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 📋 **Phase 2: TDD - Write Tests First**

### **Step 2: Create Test File**
```bash
php artisan make:test ElectionOfficerInvitationTest --feature
```

### **Step 3: Add the Test Code**
Copy the complete test suite from our earlier response. Key tests to include:

```php
// tests/Feature/ElectionOfficerInvitationTest.php

/** @test */
public function email_notification_is_sent_when_officer_appointed()

/** @test */
public function invitation_email_contains_signed_accept_url()

/** @test */
public function invitation_accept_url_requires_valid_signature()

/** @test */
public function existing_user_can_accept_invitation_after_login()

/** @test */
public function new_user_can_register_and_accept_invitation()

/** @test */
public function cannot_accept_invitation_for_different_user()

/** @test */
public function expired_invitation_link_is_rejected()

/** @test */
public function cannot_accept_already_accepted_appointment()

/** @test */
public function acceptance_is_logged_in_activity_log()
```

### **Step 4: Run Tests - They Should FAIL**
```bash
php artisan test --filter=ElectionOfficerInvitationTest
```

**Expected:** 0/9 passing (or some passing if notification already exists)

---

## 📋 **Phase 3: Create Notification**

### **Step 5: Generate Notification**
```bash
php artisan make:notification OfficerAppointedNotification
```

### **Step 6: Implement Notification**
```php
// app/Notifications/OfficerAppointedNotification.php

<?php

namespace App\Notifications;

use App\Models\ElectionOfficer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class OfficerAppointedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected ElectionOfficer $officer;

    public function __construct(ElectionOfficer $officer)
    {
        $this->officer = $officer;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $acceptUrl = URL::temporarySignedRoute(
            'organisations.election-officers.invitation.accept',
            now()->addDays(7),
            [
                'organisation' => $this->officer->organisation->slug,
                'officer' => $this->officer->id,
            ]
        );

        return (new MailMessage)
            ->subject("Election Officer Appointment: {$this->officer->organisation->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have been appointed as **{$this->officer->role}** election officer for **{$this->officer->organisation->name}**.")
            ->line("**Role:** " . ucfirst($this->officer->role))
            ->line("**Appointed by:** " . ($this->officer->appointer?->name ?? 'Administrator'))
            ->line("**Appointed on:** " . $this->officer->appointed_at->format('F j, Y'))
            ->action('Accept Appointment', $acceptUrl)
            ->line('If you already have an account, clicking the link will log you in and accept the appointment.')
            ->line('If you are new, you will be guided through registration first.')
            ->line('This invitation expires in 7 days.')
            ->line('If you did not expect this appointment, please contact your organisation administrator.');
    }
}
```

---

## 📋 **Phase 4: Create Invitation Controller**

### **Step 7: Generate Controller**
```bash
php artisan make:controller ElectionOfficerInvitationController
```

### **Step 8: Implement Controller**
```php
// app/Http/Controllers/ElectionOfficerInvitationController.php

<?php

namespace App\Http\Controllers;

use App\Models\ElectionOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ElectionOfficerInvitationController extends Controller
{
    public function accept(Request $request, $organisationSlug, ElectionOfficer $officer)
    {
        // Verify signed URL
        if (!URL::hasValidSignature($request)) {
            abort(403, 'This invitation link has expired or is invalid.');
        }

        // Check if appointment is still pending
        if ($officer->status !== 'pending') {
            return Inertia::render('Errors/Message', [
                'title' => 'Appointment Already Processed',
                'message' => 'This appointment has already been accepted or revoked.',
                'status' => 400,
            ]);
        }

        // Check if user is logged in
        if (!auth()->check()) {
            // Store the accept URL in session for after login
            session()->put('pending_acceptance', [
                'officer_id' => $officer->id,
                'organisation_slug' => $organisationSlug,
                'url' => URL::temporarySignedRoute(
                    'organisations.election-officers.invitation.accept',
                    now()->addMinutes(30),
                    ['organisation' => $organisationSlug, 'officer' => $officer->id]
                )
            ]);

            // Redirect to login with registration option
            return Inertia::render('Auth/Login', [
                'message' => 'Please login or register to accept your election officer appointment.',
                'show_register' => true,
                'email' => $officer->user->email,
            ]);
        }

        // User is logged in - verify they match the officer
        if (auth()->id() !== $officer->user_id) {
            abort(403, 'This appointment is for a different user.');
        }

        // Accept the appointment
        $officer->markAccepted();

        // Clear the session data
        session()->forget('pending_acceptance');

        // Log the acceptance
        activity()
            ->performedOn($officer)
            ->causedBy(auth()->user())
            ->log('officer_accepted_via_invitation');

        return Inertia::render('Organisations/ElectionOfficers/Accepted', [
            'officer' => $officer->load('organisation'),
            'message' => 'You have successfully accepted your election officer appointment.',
        ]);
    }
}
```

---

## 📋 **Phase 5: Add Routes**

### **Step 9: Update Routes**
```php
// routes/organisations.php

Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
    Route::get('/', [ElectionOfficerController::class, 'index'])->name('index');
    Route::post('/', [ElectionOfficerController::class, 'store'])->name('store');
    
    // Invitation acceptance (signed URL, no auth required)
    Route::get('/invitation/{officer}/accept', [ElectionOfficerInvitationController::class, 'accept'])
        ->name('invitation.accept')
        ->middleware('signed');
    
    // Regular acceptance (authenticated)
    Route::post('/{officer}/accept', [ElectionOfficerController::class, 'accept'])->name('accept');
    Route::delete('/{officer}', [ElectionOfficerController::class, 'destroy'])->name('destroy');
});
```

---

## 📋 **Phase 6: Update Store Method to Send Email**

### **Step 10: Modify ElectionOfficerController@store**
```php
// app/Http/Controllers/ElectionOfficerController.php

use App\Notifications\OfficerAppointedNotification;

public function store(Request $request, Organisation $organisation)
{
    $this->authorize('manage', [ElectionOfficer::class, $organisation]);

    $request->validate([...]); // Existing validation

    // Check for soft-deleted record
    $trashed = ElectionOfficer::withTrashed()
        ->where('user_id', $request->user_id)
        ->where('organisation_id', $organisation->id)
        ->whereNotNull('deleted_at')
        ->first();

    if ($trashed) {
        $trashed->restore();
        $trashed->update([
            'role' => $request->role,
            'status' => 'pending',
            'appointed_by' => auth()->id(),
            'appointed_at' => now(),
            'accepted_at' => null,
        ]);
        $officer = $trashed;
    } else {
        $officer = ElectionOfficer::create([
            'organisation_id' => $organisation->id,
            'user_id' => $request->user_id,
            'role' => $request->role,
            'status' => 'pending',
            'appointed_by' => auth()->id(),
            'appointed_at' => now(),
        ]);
    }

    // 🔥 SEND EMAIL NOTIFICATION 🔥
    $officer->user->notify(new OfficerAppointedNotification($officer));

    return back()->with('success', 'Election officer appointed. An invitation email has been sent.');
}
```

---

## 📋 **Phase 7: Create Acceptance Success Page**

### **Step 11: Create Vue Component**
```bash
mkdir -p resources/js/Pages/Organisations/ElectionOfficers
```

```vue
<!-- resources/js/Pages/Organisations/ElectionOfficers/Accepted.vue -->
<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <div class="mx-auto h-12 w-12 text-green-500 mb-4">
          <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">
          Appointment Accepted! 🎉
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          You are now an election officer for <strong>{{ officer.organisation.name }}</strong>
        </p>
      </div>

      <div class="mt-8 bg-white shadow sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Your Role: {{ officer.role }}</h3>
        <div class="mt-4">
          <Link
            :href="route('organisations.show', officer.organisation.slug)"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700"
          >
            Go to Organisation Dashboard
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  officer: Object,
  message: String
})
</script>
```

---

## 📋 **Phase 8: Update Login/Registration**

### **Step 12: Modify Login Controller**
```php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php

public function store(Request $request)
{
    // After successful login
    $redirectTo = session('url.intended', route('dashboard'));
    
    // Check if there's a pending acceptance
    $pending = session('pending_acceptance');
    
    if ($pending && isset($pending['url'])) {
        return redirect($pending['url']);
    }
    
    return redirect()->intended($redirectTo);
}
```

### **Step 13: Modify Registration Controller**
```php
// app/Http/Controllers/Auth/RegisteredUserController.php

public function store(Request $request)
{
    // After user registration
    $user = User::create([...]);
    
    auth()->login($user);
    
    // Check if there's a pending acceptance
    $pending = session('pending_acceptance');
    
    if ($pending && isset($pending['url'])) {
        return redirect($pending['url']);
    }
    
    return redirect(RouteServiceProvider::HOME);
}
```

---

## 📋 **Phase 9: Run Tests**

### **Step 14: Run All Tests**
```bash
# Run invitation tests
php artisan test --filter=ElectionOfficerInvitationTest

# Run full test suite
php artisan test
```

**Expected:** All 9 new invitation tests passing + existing 11 officer tests = 20 total

---

## 📋 **Phase 10: Test Manually**

### **Step 15: Manual Testing**
```bash
# 1. Start the queue worker (for email)
php artisan queue:work

# 2. Appoint an officer via UI
# 3. Check mailtrap or your email inbox
# 4. Click the accept link
# 5. Test both existing user and new user flows
```

---

## ✅ **Success Criteria**

| Check | Status |
|-------|--------|
| Email sent when officer appointed | ☐ |
| Email contains signed URL | ☐ |
| Signed URL expires after 7 days | ☐ |
| Existing user can accept | ☐ |
| New user can register and accept | ☐ |
| Cannot accept for another user | ☐ |
| Cannot accept expired link | ☐ |
| Cannot accept already accepted | ☐ |
| Activity log created | ☐ |
| All 20 tests pass | ☐ |

---

## 🚀 **Run This Command to Start**

```bash
# Run tests to see failures first
php artisan test --filter=ElectionOfficerInvitationTest

# Then implement step by step until all pass
```

---

**Good luck! Follow these steps in order and you'll have a complete email invitation system.** 🎉 
