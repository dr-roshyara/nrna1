# Voter Slug Debugging Checklist
## 1. 404 Not Found on Slug Routes
Error: Visiting /v/{slug}/demo-code/create returns 404

### Check Route Binding
In routes/web.php, Route::bind('vslug') must search BOTH tables:
- VoterSlug::withoutGlobalScopes() -> first()
- If not found, try DemoVoterSlug::withoutGlobalScopes() -> first()
- If still not found, abort(404)

**Common Issues:**
- Only searches VoterSlug
- Missing withoutGlobalScopes()
- Uses firstOrFail() instead of first()

### Check Slug Exists
SELECT * FROM voter_slugs WHERE slug='abc123';
SELECT * FROM demo_voter_slugs WHERE slug='abc123';

If neither returns a row: slug was never created or was deleted

---

## 2. 403 Forbidden on Voter Slug Routes
Error: Route exists but returns 403

### Check Middleware Type Acceptance
In EnsureVoterStepOrder.php, must accept BOTH models:
if (\!$vslug instanceof VoterSlug && \!$vslug instanceof DemoVoterSlug)
    abort(403, 'Invalid voting link.');

**Common Issue:** Only checks instanceof VoterSlug

### Check Slug Activation
SELECT is_active, expires_at FROM demo_voter_slugs WHERE id=1;
- is_active = 1: Slug is active
- expires_at > NOW(): Slug not expired

---

## 3. Type Error with DemoVoterSlug
Error: "Argument 1 must be of type App\Models\VoterSlug"

### Remove Type Hints
Change: public function process(VoterSlug $slug)
To: public function process($slug)

Then detect at runtime:
$isDemo = $slug instanceof DemoVoterSlug;
$Model = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

---

## 4. SQL Unknown Column Error
Error: "Unknown column 'demo_voter_slug_id'"

### Check Model Fillable
Both models must use SAME column name:
protected $fillable = ['voter_slug_id', ...];  // Not demo_voter_slug_id\!

### Verify Database Schema
DESCRIBE voter_slug_steps;
DESCRIBE demo_voter_slug_steps;
Both should have voter_slug_id column.

---
## Quick Reference Table
| Symptom | Cause | Fix |
|--------|-------|-----|
| 404 on slug route | Route binding missing DemoVoterSlug | Add DemoVoterSlug search |
| 403 Invalid voting link | Middleware rejects DemoVoterSlug | Use compound instanceof |
| Type error in service | Service has type hints | Remove type hints |
| Unknown column error | Wrong column name in model | Use voter_slug_id |
| Slug is inactive | Expiration check | Check expires_at <= NOW() |

