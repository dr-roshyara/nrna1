# 08 — Controllers & Routes

---

## Route Map

### Authenticated (membership prefix)

All routes below sit inside the `organisations.membership.` route group, giving full names like `organisations.membership.newsletters.index`.

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | `/organisations/{slug}/membership/newsletters` | `newsletters.index` | List all campaigns |
| GET | `/organisations/{slug}/membership/newsletters/create` | `newsletters.create` | Compose form |
| POST | `/organisations/{slug}/membership/newsletters` | `newsletters.store` | Save draft |
| GET | `/organisations/{slug}/membership/newsletters/{newsletter}` | `newsletters.show` | Campaign detail |
| GET | `/organisations/{slug}/membership/newsletters/{newsletter}/preview` | `newsletters.preview` | Recipient count (JSON) |
| PATCH | `/organisations/{slug}/membership/newsletters/{newsletter}/send` | `newsletters.send` | Dispatch |
| PATCH | `/organisations/{slug}/membership/newsletters/{newsletter}/cancel` | `newsletters.cancel` | Cancel |
| DELETE | `/organisations/{slug}/membership/newsletters/{newsletter}` | `newsletters.destroy` | Soft delete (draft only) |

### Public (no auth)

| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/unsubscribe/{token}` | `newsletter.unsubscribe` | `NewsletterUnsubscribeController@unsubscribe` |

---

## `OrganisationNewsletterController`

**File:** `app/Http/Controllers/Membership/OrganisationNewsletterController.php`

### Authorization

```php
private function authorizeAdmin(Organisation $org): void
{
    $role = UserOrganisationRole::where('user_id', auth()->id())
        ->where('organisation_id', $org->id)
        ->whereIn('role', ['owner', 'admin'])
        ->first();

    if (! $role) {
        abort(403);
    }
}
```

Every controller method calls `$this->authorizeAdmin($org)` before doing anything. Members (non-admin) receive a 403.

### `index`

Returns an Inertia page with paginated newsletters for the organisation, ordered by `created_at DESC`.

```php
$newsletters = OrganisationNewsletter::where('organisation_id', $org->id)
    ->latest()
    ->paginate(20);

return Inertia::render('Organisations/Membership/Newsletter/Index', [
    'organisation' => $org,
    'newsletters'  => $newsletters,
]);
```

### `create`

Returns the compose form page. No data is fetched; the preview count is loaded separately via the `preview` endpoint.

### `store` (rate-limited)

```php
RateLimiter::for('newsletters', function (Request $request) {
    return Limit::perHour(3)->by($request->user()->id);
});
```

Validates `subject` (required, max 255) and `html_content` (required). Delegates to `NewsletterService::createDraft()`. Redirects to `show`.

### `show`

Loads the newsletter with recipient stats and recent audit log entries.

```php
return Inertia::render('Organisations/Membership/Newsletter/Show', [
    'organisation' => $org,
    'newsletter'   => $newsletter->load('auditLogs'),
    'stats'        => [
        'pending' => $newsletter->recipients()->where('status', 'pending')->count(),
        'sent'    => $newsletter->recipients()->where('status', 'sent')->count(),
        'failed'  => $newsletter->recipients()->where('status', 'failed')->count(),
    ],
]);
```

### `previewRecipients`

JSON-only endpoint called from the Create/Show page before the admin clicks Send.

```php
return response()->json([
    'count' => $this->newsletterService->previewRecipientCount($newsletter),
]);
```

### `send`

```php
try {
    $this->newsletterService->dispatch($newsletter, $org, $request->user(), $request);
    return redirect()->route('organisations.membership.newsletters.show', [$org->slug, $newsletter])
        ->with('success', 'Campaign queued for delivery.');
} catch (InvalidNewsletterStateException $e) {
    return back()->withErrors(['state' => $e->getMessage()]);
}
```

Returns HTTP 422 (via `withErrors`) when state transition is illegal.

### `cancel`

Same pattern as `send` — delegates to `NewsletterService::cancel()`, catches `InvalidNewsletterStateException`.

### `destroy`

Soft-deletes draft newsletters only. Refuses if status ≠ `draft` (returns 422).

```php
if ($newsletter->status !== 'draft') {
    return back()->withErrors(['state' => 'Only draft newsletters can be deleted.']);
}
$newsletter->delete();
```

---

## `NewsletterUnsubscribeController`

**File:** `app/Http/Controllers/NewsletterUnsubscribeController.php`

Single public method. See [07 — Unsubscribe & Bounce](07-unsubscribe-bounce.md) for the full implementation.

---

## Route Registration

### `routes/organisations.php`

```php
use App\Http\Controllers\Membership\OrganisationNewsletterController;

// Inside the organisations.membership. group:
Route::prefix('/newsletters')->name('newsletters.')->group(function () {
    Route::get('/',                         [OrganisationNewsletterController::class, 'index'])            ->name('index');
    Route::get('/create',                   [OrganisationNewsletterController::class, 'create'])           ->name('create');
    Route::post('/',                        [OrganisationNewsletterController::class, 'store'])            ->name('store');
    Route::get('/{newsletter}',             [OrganisationNewsletterController::class, 'show'])             ->name('show');
    Route::get('/{newsletter}/preview',     [OrganisationNewsletterController::class, 'previewRecipients'])->name('preview');
    Route::patch('/{newsletter}/send',      [OrganisationNewsletterController::class, 'send'])             ->name('send');
    Route::patch('/{newsletter}/cancel',    [OrganisationNewsletterController::class, 'cancel'])           ->name('cancel');
    Route::delete('/{newsletter}',          [OrganisationNewsletterController::class, 'destroy'])          ->name('destroy');
});
```

### `routes/web.php`

```php
use App\Http\Controllers\NewsletterUnsubscribeController;

// Outside auth middleware (public route):
Route::get('/unsubscribe/{token}', [NewsletterUnsubscribeController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');
```

---

## Error Handling Summary

| Scenario | HTTP Response | Message location |
|----------|---------------|-----------------|
| Non-admin accesses any route | 403 Forbidden | — |
| Illegal state transition (send/cancel) | 422 (via `back()->withErrors`) | `errors.state` |
| Delete non-draft | 422 (via `back()->withErrors`) | `errors.state` |
| Invalid unsubscribe token | 404 Not Found | — |
| Rate limit exceeded (store) | 429 Too Many Requests | Laravel default |
