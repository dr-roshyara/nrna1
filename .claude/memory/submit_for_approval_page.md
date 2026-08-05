---
name: Submit for Approval Dedicated Page Implementation
description: Replaced modal with dedicated page for election submission workflow (2026-04-28)
type: project
originSessionId: d38df17d-2b14-42d8-8d2a-df243fe02686
---
# Submit for Approval — Dedicated Page Implementation (2026-04-28)

## Decision: Modal → Dedicated Page

### Why Modal Was Wrong
Election submission is a **critical state transition**, not a lightweight action. Modal was causing:
- **UX problems**: text unreadable, rendering issues
- **Composition issues**: z-index, overflow, teleport conflicts
- **Not extensible**: approval context, workflow info hard to add
- **No deep-linking**: cannot share/resume state

### Why Dedicated Page Is Correct
- ✅ Clear decision boundary (explicit workflow step)
- ✅ Full page space for prerequisites checklist
- ✅ Deep-linkable for state recovery
- ✅ Extensible for approval context & rules
- ✅ DDD pattern: explicit application service flow

---

## Implementation (Complete)

### 1. **Created SubmitForApproval.vue Page**
**File**: `resources/js/Pages/Election/SubmitForApproval.vue`
- Clean card-based layout with sections:
  - Approval workflow context (≤40 auto vs >40 manual)
  - Prerequisites checklist (posts ✓/✗, candidates ✓/✗, voters ✓/✗)
  - Back & Submit action buttons
- Uses ElectionLayout for consistency
- All text has high contrast (slate-900, slate-800)
- Green/Red status indicators match domain rules
- Loading feedback during submission

### 2. **Backend Route (GET)**
**File**: `routes/election/electionRoutes.php` line 279
```php
Route::get('/submit-for-approval', [ElectionManagementController::class, 'showSubmitForApproval'])
    ->name('elections.submit-for-approval.show')
    ->can('manageSettings', 'election');
```

### 3. **Controller Method**
**File**: `app/Http/Controllers/Election/ElectionManagementController.php`
```php
public function showSubmitForApproval(Election $election)
{
    $this->authorize('manageSettings', $election);
    
    $postsCount = $election->posts()->count();
    $candidatesCount = Candidacy::whereIn('post_id', $election->posts()->pluck('id'))->count();
    $votersCount = $election->memberships()->active()->count();
    
    return inertia('Election/SubmitForApproval', [
        'election' => $election->only(['id', 'slug', 'name', 'expected_voter_count']),
        'organisation' => $election->organisation->only(['slug', 'name']),
        'postsCount' => $postsCount,
        'candidatesCount' => $candidatesCount,
        'votersCount' => $votersCount,
    ]);
}
```

### 4. **Updated Management.vue Button**
**File**: `resources/js/Pages/Election/Management.vue` line 438
- Changed from `@click="showSubmitApprovalModal = true"` 
- To: `@click="router.visit(route('elections.submit-for-approval.show', {...}))"`
- Removed modal state management
- Removed SubmitApprovalModal component

---

## Why:** Architectural Alignment

| Aspect | Modal ❌ | Page ✅ |
|--------|---------|--------|
| **Decision weight** | Lightweight confirmation | Critical state transition |
| **Space** | Cramped | Spacious, extensible |
| **Readability** | Poor text contrast | High contrast, clear |
| **Context** | Hard to explain rules | Room for approval workflow info |
| **Deep-linking** | No URL | Shareable state `/elections/{slug}/submit-for-approval` |
| **Extensibility** | Breaks with complexity | Easy to add audit trail, approval timeline |

---

## Key Bug Fixes

### Candidacy Query Issue
**Error**: `Undefined column: 'election_id'` in candidacies table
**Fix**: Candidacies relate through posts, not directly to elections
```php
// Wrong:
Candidacy::where('election_id', $election->id)->count()

// Correct:
Candidacy::whereIn('post_id', $election->posts()->pluck('id'))->count()
```

### Model Imports
Added `use App\Models\Candidacy;` to ElectionManagementController

---

## Prerequisite Validation

Modal enforces three prerequisites before submission:
1. **Posts created** (`postsCount > 0`)
2. **Candidates approved** (`candidatesCount > 0`)
3. **Voters registered** (`votersCount > 0`)

Submit button disabled if any prerequisite missing. Shows red warning box.

---

## Approval Workflow Context

Page shows voter-count-based approval rule:
- **≤40 voters**: "✓ FREE ELECTION — Auto-approved"
- **>40 voters**: "⭐ PAID ELECTION — Requires platform approval (1-5 business days)"

---

## Next Steps (When Needed)

1. **Add audit trail** showing approval history
2. **Add state machine timeline** showing draft → pending → approved → administration
3. **Add approval notes** editable before final submission
4. **Test end-to-end**: Create election → add posts/candidates/voters → submit → verify in pending queue
