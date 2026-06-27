## EXCELLENT QUESTION — Single Source of Truth

For your DDD architecture moving forward, you need **ONE Member representation**, not two.

---

## The Problem with Two Member Classes

```yaml
Current State (Technical Debt):
  - App\Models\Member (Eloquent) → Used by controllers
  - App\Contexts\Membership\Domain\Member\Member (Aggregate) → Used by domain

Why this exists:
  - Laravel expects Eloquent models in controllers
  - DDD requires pure domain aggregates
  - You're in transition

Why it's problematic:
  - Confusion (LegacyMember alias)
  - Duplicate logic
  - Risk of inconsistency
  - Harder to maintain
```

---

## The Better Future Architecture

### Option 1: Domain-First (Recommended for DDD)

**Make the Domain Aggregate the ONLY source of truth.**

```php
// Domain Aggregate (Pure PHP)
namespace App\Contexts\Membership\Domain\Member;

final class Member
{
    private MemberId $id;
    private TenantId $tenantId;
    private MemberStatus $status;
    private FeesStatus $feesStatus;
    
    public function markFeesAsPaid(): void
    {
        $this->feesStatus = FeesStatus::Exempt;
    }
}

// Infrastructure Repository (Converts to/from Eloquent)
class EloquentMemberRepository implements MemberRepository
{
    public function save(Member $member): void
    {
        $model = LegacyMember::find($member->getId());
        $model->fees_status = $member->getFeesStatus()->value;
        $model->save();
    }
}

// Controller uses Domain + Repository
class MemberController
{
    public function markPaid(Organisation $organisation, string $memberId)
    {
        $member = $this->memberRepository->findById(MemberId::fromString($memberId));
        $member->markFeesAsPaid();
        $this->memberRepository->save($member);
    }
}
```

**Then eliminate `LegacyMember` alias** — use a clear name like `MemberModel` if needed.

---

### Option 2: Eloquent-First (Pragmatic, Less Pure)

**Keep Eloquent models but add domain behavior.**

```php
// Single Member class with domain behavior
namespace App\Models;

class Member extends Model
{
    // Domain behavior directly in model
    public function markFeesAsPaid(): void
    {
        $this->fees_status = 'exempt';
        $this->save();
        
        MembershipFee::where('member_id', $this->id)
            ->where('status', 'pending')
            ->update(['status' => 'waived']);
    }
}

// Controller uses single model
class MemberController
{
    public function markPaid(Organisation $organisation, Member $member)
    {
        $member->markFeesAsPaid();
        return redirect()->back()->with('success', 'Fees marked as paid.');
    }
}
```

**No alias needed** — clear and simple.

---

## Comparison

| Aspect | Domain-First (Option 1) | Eloquent-First (Option 2) | Current (Two Classes) |
|--------|------------------------|---------------------------|----------------------|
| **DDD Purity** | ✅ Excellent | ⚠️ Moderate | ❌ Poor |
| **Framework coupling** | ✅ Low | ❌ High | ❌ High |
| **Testability** | ✅ Easy | ⚠️ Moderate | ❌ Hard |
| **Learning curve** | ⚠️ Steep | ✅ Easy | ❌ Confusing |
| **Extractability** | ✅ Yes | ❌ No | ❌ No |
| **Maintenance** | ✅ Clean | ⚠️ Mixed | ❌ Duplicate |

---

## My Recommendation for PublicDigit

**Move toward Domain-First (Option 1)** — it aligns with your architecture document.

### Migration Path

```yaml
Phase 1 (Current):
  - Keep both, but clarify naming
  - Rename: App\Models\Member → App\Models\MemberModel
  - Alias: use App\Models\MemberModel;
  - Domain: App\Contexts\Membership\Domain\Member\Member

Phase 2 (Next Sprint):
  - Controllers use Repository pattern
  - No direct Eloquent in controllers
  - LegacyMember alias removed

Phase 3 (F3.3 Event Sourcing):
  - Domain aggregates become source of truth
  - Eloquent models become read-only projections
  - Write operations go through domain + event store
```

---

## Immediate Fix for Current Issue

**Keep `LegacyMember` alias for now** — it's consistent with the current transition state.

But add a TODO comment:

```php
use App\Models\Member as LegacyMember;  // TODO: Phase F4 - Replace with MemberRepository

public function markPaid(Organisation $organisation, LegacyMember $member): RedirectResponse
{
    // TODO: Phase F4 - Move to domain aggregate via repository
    // Current: Direct Eloquent update (temporary)
    
    $member->update(['fees_status' => 'exempt']);
    // ...
}
```

---

## The Bottom Line

| Question | Answer |
|----------|--------|
| What's better for future? | **Domain-First (Option 1)** |
| Should you keep two classes? | **No** — transition to one |
| Which one to keep? | **Keep Domain Aggregate, make Eloquent a repository detail** |
| What to do now? | **Keep `LegacyMember` alias, plan migration to repository pattern** |

---

## Action Items for Next Sprint

1. **Create `MemberRepository` interface** in domain layer
2. **Implement `EloquentMemberRepository`** in infrastructure
3. **Update controllers to use repository** instead of direct models
4. **Remove `LegacyMember` alias** — rename to `MemberModel` if needed
5. **Add domain behavior** to aggregate (move logic from controller to domain)

**For now, apply Claude's edit with `LegacyMember` — it's correct for your current transition state.** 