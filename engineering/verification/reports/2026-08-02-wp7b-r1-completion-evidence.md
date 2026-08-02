# WP-7B-R1 — Completion Evidence

**Date:** 2026-08-02 · **Prepared by:** Engineering, under **R-70**
**Status:** ✅ **ACCEPTED — R-71 issued by the ARB, 2026-08-02. WP-7B-R1 is CLOSED.** RED → GREEN → VERIFY complete; **ACCEPT was the ARB's act (R-34) and engineering did not accept its own work.**

> **Slice record kept here rather than in a new plan file.** WP-7 s plan is closed and R-60 defines this package separately; **delivery mode discourages creating governance artifacts that implementation does not need.** Triple qualification is in §5, where WP-1/WP-2 precedent puts it in the plan.

---

## 1. Changes made

| File | Change |
|---|---|
| `app/Contexts/Election/Application/Port/EvidenceAnchorResolver.php` | **new** — the port. `anchorFor(Election): ?DateTimeImmutable` |
| `app/Contexts/Election/Infrastructure/Config/TemporaryDefaultAnchorResolver.php` | **new** — the interim rule, **moved unchanged** |
| `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` | port injected; **`anchorOf()` deleted** |
| `app/Contexts/Election/Infrastructure/Providers/ElectionServiceProvider.php` | **one binding** |
| `tests/Feature/Contexts/Election/EvidenceAnchorResolutionTest.php` | **new** — 4 tests |
| `developer_guide/election/09_evidence_anchor_resolver.md` · `00_index.md` | developer guide (DoD) |

## 2. Changes deliberately NOT made

**No decision about the anchor** — that is Q-2's act · **`EvidencePreservationWindow` (Domain) untouched** · **`EvidencePreservationDurations` untouched** · **the *absent anchor ⇒ report OPEN* rule not moved** — it is use-case policy and stays in the service (P7B-2) · **no pre-existing test modified** · **no config key added** · **nothing in Adjudication or Audit/Retention**.

**The candidate order is byte-for-byte the same rule:** `results_published_at` → `end_date` → `archived_at`, first match wins, no candidate ⇒ `null`.

## 3. Verification

```
RED   php artisan test …/EvidenceAnchorResolutionTest.php
      →  4 failed (0 assertions) — "Class TemporaryDefaultAnchorResolver not found"

GREEN the new seam + the entire behaviour-preservation harness
      →  33 passed (51 assertions)   first run, no iteration

GATE  composer merge-gate  →  PASS
      Architecture fitness · Deptrac · greenfield PHPStan · widened regression
      270 tests · 671 assertions · 105 risky notices
```

**RED was genuine:** all four failed for the same reason — **the port and its implementation did not exist**. None passed by accident.

### The behaviour-preservation harness — R-70's acceptance boundary

| Suite | Result | Modified? |
|---|---|---|
| `EvidencePreservationWindowResolutionTest` (2) | ✅ | **no** |
| `EvidencePreservationDurationsTest` (8) | ✅ | **no** |
| `EvidencePreservationWindowTest` (unit, 9) | ✅ | **no** |
| **`AuditCleanupTest` (10)** | ✅ | **no** |

> ⚠️ **Run directly, as R-70 requires.** `composer merge-gate`'s `GreenfieldCore` suite does not execute `tests/Feature/Audit/`, so **the gate's PASS does not cover `AuditCleanupTest`** — it is reported above from `php artisan test`.

### ⚠️ One unexplained delta, reported rather than smoothed over

**Risky notices rose 101 → 105** (tests 266 → 270, assertions 665 → 671 — both accounted for by the four new tests).

**The four new tests are not among the risky entries** — verified twice: standalone, `vendor/bin/phpunit …EvidenceAnchorResolutionTest.php` → `OK (4 tests, 6 assertions)` with no risky flag; and in the full `GreenfieldCore` run, `EvidenceAnchorResolutionTest` appears nowhere in the risky list.

**So the four additional notices fall on pre-existing tests, and their cause is not established.** **Carried as `ENG-012` by R-71 — tracked, not resolved here.** The house pattern for these notices is *"Test code or tested code removed error handlers other than its own"*, and adding a test to this namespace changes execution order — **a plausible explanation, not a demonstrated one. Recorded as an open observation, not a claim.**

## 4. Architectural invariants preserved

| Invariant | Evidence |
|---|---|
| **Behaviour unchanged** (R-70) | four pre-existing suites green **unmodified** |
| **Policy 2's three durations** | still resolved through `EvidencePreservationDurations`; **no duration defined, defaulted or clamped here** |
| **AP-2** — MAD has one home | untouched; `DurationPolicyOwnershipTest` green in the gate |
| **AP-1** — fail closed | the *no anchor ⇒ OPEN* rule is unmoved and still asserted by `test_k9`, unmodified |
| **TP-1** — no cross-context call | nothing in Adjudication is called; Deptrac **0 violations** |
| **Q-2 not pre-empted** | the resolver **reports** a date; it **decides** nothing. No test asserts *which* candidate is correct |
| Dependency direction | Infrastructure → Application → Domain; the port is declared by the consumer |

## 5. Evidence supporting Triple Qualification

> **Wording corrected at ARB direction (R-71): engineering PRODUCES the evidence; the QUALIFICATION belongs to the acceptance package. What follows is evidence offered, not a self-certification.**

### Architecture
Deptrac **0 violations** · greenfield PHPStan **no errors** · architecture fitness suite green. **No fitness rule and no Deptrac rule was changed to make the slice pass.** The new adapter sits in `Election/Infrastructure/Config/` beside `ConfiguredEvidencePreservationDurations`, which `ElectionInfrastructure: [ElectionDomain, ElectionApplication, Shared]` already permits.

### DDD
**Ownership unchanged** — Election owns Evidence Preservation Window Resolution; Audit/Retention owns the deletion decision; Adjudication supplies MAD as configuration and is not called. **No aggregate, entity, value object, repository or domain service added** — one port and one adapter, both Application/Infrastructure. **Ubiquitous Language unchanged:** *anchor* was already the word the code and the docblocks used; **the port names an existing concept rather than introducing one.** **No published language, no event, no crossing.**

### Trustworthiness
**Fail-closed preserved** (AP-1) and still asserted by an unmodified test · **no substituted date** — absence returns `null`, so a missing fact can never shorten a legally-mandated retention period · **replay/idempotence untouched** — the resolver is pure and deterministic over one model · **tenant isolation untouched** — the slice adds no query; 7C's `withoutGlobalScopes()` read is unchanged · **anonymity not implicated** — no voter or vote data is read or carried · **loud failure** — an unbound port fails at container resolution, never silently.

## 6. Operational evidence — evaluated per the four outcomes

| Outcome | Assessment |
|---|---|
| **No reusable knowledge** | ✅ **this is the assessment** |
| PKS observation | no — the risky-count delta (§3) is **one unexplained observation in one run**, and an implementation finding is the sufficient record |
| Repeated operational evidence | no |
| KnowledgeOS candidate | no |

**Nothing is promoted.**

## 7. Next

**ACCEPT was the ARB's act.** ✅ **R-71 issued 2026-08-02 — WP-7B-R1 is CLOSED.** **It does not advance §WP-4 and does not move WP-8 closer** — R-70 records that it is parallel work.

---

**Traceability:** **R-70** (authorization) · **R-60** (opening) · **R-59 · R-65 · R-66** · **Constitutional Policy 2** · **AP-1 · AP-2 · TP-1 · P7B-2** · **Q-2** (open — the anchor's value) · `engineering/verification/reports/2026-08-02-wp7b-r1-authorization-readiness.md` · `developer_guide/election/09_evidence_anchor_resolver.md` · `composer merge-gate` (PASS). **Engineering supplies evidence; it does not accept its own work.**
