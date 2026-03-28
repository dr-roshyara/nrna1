# Officer Invitation Flow

## Overview

When an organisation admin appoints an election officer, the system:
1. Creates (or restores) an `ElectionOfficer` record with `status = 'pending'`
2. Sends a **queued email** to the appointed user with a **7-day signed URL**
3. When the user clicks the link, their record transitions to `status = 'active'`

---

## Flow Diagram

```
Admin appoints officer
        │
        ▼
ElectionOfficerController::store()
  - Create/restore ElectionOfficer (status=pending)
  - $officer->user->notify(new OfficerAppointedNotification($officer))
        │
        ▼
OfficerAppointedNotification (queued)
  - Generates temporarySignedRoute URL (7 days)
  - Sends email with "Accept Appointment" button
        │
        ▼
User clicks email link
        │
        ├─ Guest? → Store URL in session('pending_acceptance')
        │            Render Auth/Login with email pre-filled
        │                    │
        │                    ▼
        │           User logs in → LoginController checks
        │           session('pending_acceptance') → redirects to URL
        │
        ├─ Wrong user? → 403 Forbidden
        ├─ Already accepted? → Render Errors/Message
        │
        └─ Correct authenticated user
                    │
                    ▼
        ElectionOfficerInvitationController::accept()
          - $officer->markAccepted()
          - Render Organisations/ElectionOfficers/Accepted
```

---

## Signed URL

The invitation URL is generated with Laravel's `temporarySignedRoute`:

```php
// OfficerAppointedNotification.php
$acceptUrl = URL::temporarySignedRoute(
    'organisations.election-officers.invitation.accept',
    now()->addDays(7),
    [
        'organisation' => $this->officer->organisation->slug,
        'officer'      => $this->officer->id,
    ]
);
```

**Route definition** (in `routes/organisations.php`, outside the auth middleware group):

```php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
        Route::get('/invitation/{officer}/accept', [ElectionOfficerInvitationController::class, 'accept'])
            ->name('invitation.accept')
            ->middleware('signed');
    });
});
```

> ⚠️ This route must be **outside** the `auth` middleware group — unauthenticated users need to reach it so they can be redirected to login.

---

## Guest Handling

When an unauthenticated user hits the invitation link:

```php
// ElectionOfficerInvitationController::accept()
if (!Auth::check()) {
    session(['pending_acceptance' => ['url' => $request->fullUrl()]]);
    return Inertia::render('Auth/Login', [
        'email' => $officer->user->email,
    ]);
}
```

After login, `LoginController` checks:

```php
$pending = session('pending_acceptance');
if ($pending && isset($pending['url'])) {
    return redirect($pending['url']);
}
return app(DashboardResolver::class)->resolve($user);
```

> ⚠️ `LoginController` must check `session('pending_acceptance')` **before** calling `DashboardResolver`. If DashboardResolver runs first, the pending acceptance is lost.

---

## Error Cases

| Scenario | Response |
|----------|----------|
| Signature invalid or expired | 403 (Laravel `signed` middleware) |
| Authenticated as wrong user | 403 |
| Already accepted (`accepted_at` not null) | Render `Errors/Message` with "already accepted" message |
| Correct user, pending | `$officer->markAccepted()` → Render `Accepted.vue` |

---

## Vue Pages

| Page | Path | Purpose |
|------|------|---------|
| `Accepted.vue` | `resources/js/Pages/Organisations/ElectionOfficers/Accepted.vue` | Success page after accepting |
| `Message.vue` | `resources/js/Pages/Errors/Message.vue` | Generic error page (used for already-accepted) |

---

## Notification Class

**File:** `app/Notifications/OfficerAppointedNotification.php`

```php
class OfficerAppointedNotification extends Notification implements ShouldQueue
{
    public function __construct(private ElectionOfficer $officer) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $acceptUrl = URL::temporarySignedRoute(
            'organisations.election-officers.invitation.accept',
            now()->addDays(7),
            ['organisation' => $this->officer->organisation->slug, 'officer' => $this->officer->id]
        );

        return (new MailMessage)
            ->subject('You have been appointed as an Election Officer')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been appointed as a ' . $this->officer->role . ' for ' . $this->officer->organisation->name . '.')
            ->action('Accept Appointment', $acceptUrl)
            ->line('This link expires in 7 days.')
            ->line('If you did not expect this, you can ignore this email.');
    }
}
```

The notification is **queued** (`implements ShouldQueue`). Ensure a queue worker is running in production:
```bash
php artisan queue:work
```
