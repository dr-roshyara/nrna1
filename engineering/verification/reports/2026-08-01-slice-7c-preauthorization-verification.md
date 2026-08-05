# Slice 7C — Pre-Authorization Verification

**Date:** 2026-08-01 · **Prepared by:** Recording Architect · **Revision:** 2 (refinement pass; conclusions unchanged)
**Status:** **ARCHITECTURALLY COMPLETE** — ready for ARB authorization. **7C remains unauthorized.**
**Repository Integrity Gate:** ✅ PASSED. **No code · no test · no plan text · no ruling · no change of authorization state.**

---

## A. Architectural Verification

*Dependency boundaries · public interfaces · collaborator boundaries · architectural independence · ubiquitous language.*

### A1 · The consumed capability is accepted and stable ✅

> **Architectural dependency: the *Evidence Preservation Window Resolution* capability** — *given an election and an instant, is that election's preservation window still open?*
>
> **Realized today by** `ResolvesEvidencePreservationWindow::isOpenFor(Election, DateTimeImmutable): bool`. **The capability is the dependency; the class is today's realization.**

**Accepted** under **R-59**; in the accepted **implementation** baseline.

**Stable at the capability boundary.** The realizing surface is narrow — the window value object exposes only `forElection()`, `closesAt()`, `isOpenAt()`; the service exposes only `isOpenFor()`.

### A2 · No new ports ✅

**Verified against the approved plan, not inferred.**

> §3 allocates to **Infrastructure**: *"config keys for CW + LSM; **the guard inside `AuditCleanup`; folder→election resolution**"*
>
> Placement principle: *"**the folder→election parser, traversal, deletion and the CLI stay with the audit code and remain ungated — correctly, because they carry no policy.**"*

**The realizing service already accepts `App\Models\Election`**, so the command resolves the election Infrastructure-side, where Eloquent is permitted. **No port is required, and the plan says so — this is not a design choice left to 7C.**

### A3 · One capability consumed ✅

**7C consumes exactly one business capability: Evidence Preservation Window Resolution.**

Everything else it touches pre-exists and **carries no policy**: folder traversal, filesystem removal and the CLI in `AuditCleanup`; the folder layout `{slug}_{Ymd}_{Hi}` owned by `ElectionAuditService`.

### A4 · WP-7B-R1 is architecturally independent ✅

**Not asserted — demonstrated.** WP-7B-R1 extracts the INTERIM anchor into a replaceable resolver **with behaviour unchanged**: it alters `anchorOf()`'s internals and, at most, the realizing service's constructor.

> **7C depends on the capability's *query* surface (`isOpenFor`) and obtains the realization by injection. A constructor change is invisible to it.** **Disjoint by file** (`ResolvesEvidencePreservationWindow` vs `AuditCleanup`) **and by surface.**

### A5 · No new ubiquitous language ✅ *(new verification)*

| Question | Answer | Evidence |
|---|---|---|
| Any new domain term? | **No** | every term in 7C's slice definition — *deletion guard · Evidence Preservation Window · Contestation Window · Maximum Adjudication Duration · Legal Safety Margin · folder→election* — already appears in the approved WP-7 plan |
| Any renamed concept? | **No** | 7C's row introduces no name absent from §§1–4 |
| Any changed meaning? | **No** | the plan's strategic table records **Published language — untouched; no event added, changed or retired** |
| Any bounded-context ownership change? | **No** | *"WP-7 owns **one** thing: the deletion guard"*; Adjudication and Q-2 remain **providers of parameters** |

### Architectural invariant

> **Slice 7C introduces no new business concepts, no new ubiquitous language, and no new bounded-context responsibilities. It operationalizes already-approved concepts only.**
>
> **7C completes the bounded context rather than extending it** — which is why the plan can call it *"the first externally visible behaviour change"* while adding nothing to the model: **all three slices realize one capability that was fully specified before any of them began.**

---

## B. Operational Verification

*Repository state · gate state · implementation readiness.*

| Check | Result |
|---|---|
| **`composer merge-gate`** | ✅ **PASS** — Architecture fitness · Deptrac · greenfield PHPStan · widened regression |
| **Test suite** | **266 tests · 665 assertions · 0 failures** (101 pre-existing *risky* notices — handler-related, not failures) |
| **Working tree** | **clean — 0 modified paths** |
| **Branch** | `feature/pb003` · 2 commits unpushed |
| **7B artifacts present** | window VO · resolution service · durations port · 4 test files (unit · feature · architecture) |
| **7C target present** | `app/Console/Commands/AuditCleanup.php` — **60 lines, no election concept**: lists directories, compares `lastModified` to a `--days` cutoff, deletes |
| **Existing behavioural coverage of the target** | `tests/Feature/Audit/AuditCleanupTest.php` — **exists and passes today** |

**A known-green baseline exists before RED.** That is the operational precondition for a TDD slice, and it holds.

### ⚠️ F-7C-3 — the existing test suite will fail on 7C's approved acceptance *(operational, new)*

**`AuditCleanupTest` creates bare directories with `File::makeDirectory` and no `Election` records** — folder names like `election-mid_20260331_1200` and `election-old-{$i}_20260301_1200` correspond to no election row.

**Under 7C's approved acceptance — *"an unresolvable folder→election mapping ⇒ not deleted"* — every one of those folders becomes unresolvable and must be retained. The existing tests assert deletion. They will fail.**

> **This is not a defect and not a reason to delay: it is the approved acceptance criterion doing exactly what it says.** **It is raised because it changes what RED means for this slice** — 7C's RED includes **modifying existing passing tests**, not only adding new ones.
>
> **Modifying a green test is a different act from adding a red one, and it should be visibly authorized rather than absorbed.** The authorization should say so, or the slice will silently rewrite the evidence of previously accepted behaviour.
>
> **It also reinforces F-7C-1:** these tests *are* the "deletion mechanics" that must keep working — traversal, cutoff, removal — while **what gets deleted** legitimately changes.

---

## C. Precision findings — preserved unchanged

### ⚠️ F-7C-1 — the proposed wording may forbid 7C's own acceptance criterion

**Proposed:** *"Slice 7C shall not alter **deletion mechanics**, retention calculations, anchor resolution, or any previously accepted architectural boundary."*

**But 7C's approved tests require *"`--days` no longer overrides the invariant"*, and its acceptance requires *"nothing inside an open EPW is deleted."* Both necessarily change what the command deletes.**

**Suggested precision:** *"…shall not alter **how deletion is performed** (folder traversal, filesystem removal, reporting), retention calculations, anchor resolution, or any previously accepted architectural boundary. **The guard gates the deletion decision, and `--days` ceases to override the invariant, per the approved test list.**"*

### ⚠️ F-7C-2 — a release precondition the authorization should carry

The plan records 7C as *"the **first externally visible behaviour change** — announcement owner required before release."* **Authorizing implementation is not authorizing release.**

### ⛔ Record integrity — `R-61` is already taken

**R-61…R-64 were issued 2026-08-01** for the governance-validation disposition. **R-65…R-71 exist as unminted drafts.**

**Recommend: 7C takes the next free number now — authorization `R-65`, acceptance `R-66` — with the drafts renumbered on issuance**, so 7C is not delayed by an unrelated governance batch. Either option is acceptable; **reusing R-61 is not.**

---

## D. Recommended authorization text

> **Slice 7C is authorized to implement the decision service that determines whether evidence may be deleted**, consuming only the **Evidence Preservation Window Resolution** capability (realized today by `ResolvesEvidencePreservationWindow::isOpenFor`).
>
> **Slice 7C shall not alter how deletion is performed** (folder traversal, filesystem removal, reporting), **retention calculations, anchor resolution, or any previously accepted architectural boundary.** The guard gates the deletion **decision**, and `--days` ceases to override the invariant, per the approved test list.
>
> **Slice 7C shall not absorb, implement, or anticipate WP-7B-R1**, which remains an independent refinement under R-60.
>
> **7C's RED includes amending `tests/Feature/Audit/AuditCleanupTest.php`**, whose current expectations contradict the approved acceptance criterion (F-7C-3). **Amendments shall be limited to what the criterion requires.**
>
> **Authorization covers implementation and acceptance only. Release requires a named announcement owner**, 7C being the first externally visible behaviour change.
>
> **Sequence: RED → GREEN → VERIFY → ACCEPT. WP-7 closes on acceptance. WP-7B-R1 resumes only after WP-7 closes.**

---

## E. Declaration

> **The report is ARCHITECTURALLY COMPLETE and ready for ARB authorization.** No further architectural findings arose in this pass. **A5 was newly executed and returned NO across all four questions; the sole new finding, F-7C-3, is operational — it concerns test evidence, not architecture.**

**Worth recording: the restructuring produced the finding.** F-7C-3 surfaced only because architectural and operational evidence were separated and the operational side asked *"what covers this target today?"* **Under the previous single-category shape it would have been discovered during RED.**

**Not done:** no ruling minted (R-34) · no code · no test · no plan text altered · **7C remains unauthorized.**

---

**Traceability:** `.claude/plans/WP-7-retention-alignment.md` §1 (published language untouched) · §3 (layer allocation) · §5 Slice 7C · placement principle · **R-59** (7B accepted) · **R-60** (WP-7B-R1 opened) · **R-61…R-64** (issued — the collision) · `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` · `app/Contexts/Election/Domain/EvidencePreservationWindow.php` · `app/Console/Commands/AuditCleanup.php` · `tests/Feature/Audit/AuditCleanupTest.php` · `composer merge-gate` (PASS, 266/665). **Revision 2 refines presentation and adds A5 + F-7C-3; every revision-1 conclusion stands unchanged.**
