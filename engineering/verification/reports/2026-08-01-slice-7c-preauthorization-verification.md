# Slice 7C — Pre-Authorization Verification

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** verify four things before authorization — not redesign.
**Result:** **all four VERIFIED.** Two precision findings and one record-integrity issue are raised before issuance.
**Repository Integrity Gate:** ✅ PASSED. **No code written · no test written · no ruling minted.**

---

## 1. The four verifications

### ✅ 1. The interface from 7B is accepted and stable

**Accepted:** Slice 7B accepted and closed under **R-59**; `EvidencePreservationWindow` is in the accepted **implementation** baseline.

**The surface 7C consumes:**

```php
ResolvesEvidencePreservationWindow::isOpenFor(Election $election, DateTimeImmutable $at): bool
```

**Stable — and the reason matters for verification 4.** `EvidencePreservationWindow` exposes only `forElection()`, `closesAt()`, `isOpenAt()`; the service exposes only `isOpenFor()`. **WP-7B-R1 extracts the INTERIM anchor into a replaceable resolver *with behaviour unchanged* — it changes `anchorOf()`'s internals and, at most, the service's constructor.** **7C consumes `isOpenFor()` and obtains the service by injection, so a constructor change is invisible to it. Different surfaces; no dependency.**

### ✅ 2. 7C introduces no new ports

**Verified against the approved architecture rather than inferred.** The plan allocates the remaining pieces to **Infrastructure**, explicitly:

> §3: *"**Infrastructure** | config keys for CW + LSM; **the guard inside `AuditCleanup`; folder→election resolution**"*
>
> §placement principle: *"**the folder→election parser, traversal, deletion and the CLI stay with the audit code and remain ungated — correctly, because they carry no policy.**"*

**And the service already accepts `App\Models\Election`**, so the command resolves the election directly — Infrastructure, where Eloquent is permitted. **No port is required, and the plan says so; this is not a design choice 7C still has to make.**

### ✅ 3. 7C consumes only the approved collaborator from 7B

**One collaborator: `ResolvesEvidencePreservationWindow`.** Everything else 7C touches already exists and carries no policy: `AuditCleanup`'s traversal and deletion, and the folder layout `{slug}_{Ymd}_{Hi}` owned by `ElectionAuditService`.

**Current state of the target, read at source** — `app/Console/Commands/AuditCleanup.php` is 60 lines: it lists directories under `storage/logs/audit`, compares `lastModified` to a `--days` cutoff, and calls `File::deleteDirectory`. **It has no election concept whatever.** **That is exactly the gap 7C fills, and it confirms no hidden collaborator is already entangled.**

### ✅ 4. WP-7B-R1 remains out of scope

**Disjoint by file and by surface.** R-1 touches anchor resolution **inside** `ResolvesEvidencePreservationWindow`; 7C touches `AuditCleanup`. **They share no file, and 7C depends on a method R-1 does not change.**

## 2. Two precision findings — raised before issuance, not after

### ⚠️ F-7C-1 — the proposed wording may forbid 7C's own approved acceptance criterion

**Proposed:** *"Slice 7C shall not alter **deletion mechanics**, retention calculations, anchor resolution, or any previously accepted architectural boundary."*

**But 7C's approved test list requires:** *"**`--days` no longer overrides the invariant**"* — and its acceptance requires *"nothing inside an open EPW is deleted."* **Both necessarily change what the command deletes.**

> **The intent is clear and correct; the word is load-bearing.** *"Deletion mechanics"* should mean **how deletion is performed** — traversal, filesystem removal, output — which 7C leaves alone. It must not be read as **what gets deleted**, which is the entire point of the slice.
>
> **Suggested precision:** *"…shall not alter **how deletion is performed** (folder traversal, filesystem removal, reporting), retention calculations, anchor resolution, or any previously accepted architectural boundary. **The guard gates the deletion decision, and `--days` ceases to override the invariant, per the approved test list.**"*

### ⚠️ F-7C-2 — a release precondition the authorization should carry

**The plan records, and the authorization does not yet mention:**

> *"**it is the first externally visible behaviour change — announcement owner required before release**"*

**7C is independently releasable and observable. Authorizing implementation is not authorizing release.** **Recommend the authorization state that explicitly**, so the announcement owner is named before the behaviour ships rather than discovered at release.

## 3. ⛔ Record-integrity issue — the identifier `R-61` is already taken

**R-61…R-64 were issued on 2026-08-01** for the governance-model operational-validation disposition. **Issuing 7C authorization as "R-61" would create two rulings with one identifier.**

**Also outstanding:** **R-65…R-71 exist as drafts** (approved in substance, unminted) — the repository-as-workspace set, the classification model, and the derivation principle.

| Option | Consequence |
|---|---|
| **Mint the drafts first**, then 7C authorization = **R-72**, acceptance = **R-73** | preserves draft numbering |
| **Give 7C the next free number now** — authorization = **R-65**, acceptance = **R-66** — and renumber the drafts on issuance | 7C is not delayed by an unrelated batch |

**Recommend the second: 7C should not wait on a governance batch it has no dependency on.** **Either is fine; what must not happen is reusing R-61.**

## 4. Recommended authorization text

**Incorporating the ARB's framing plus F-7C-1 and F-7C-2:**

> **Slice 7C is authorized to implement the decision service that determines whether evidence may be deleted**, consuming only the approved Evidence Preservation Window collaborator (`ResolvesEvidencePreservationWindow::isOpenFor`).
>
> **Slice 7C shall not alter how deletion is performed** (folder traversal, filesystem removal, reporting), **retention calculations, anchor resolution, or any previously accepted architectural boundary.** The guard gates the deletion **decision**, and `--days` ceases to override the invariant, per the approved test list.
>
> **Slice 7C shall not absorb, implement, or anticipate WP-7B-R1**, which remains an independent refinement under R-60.
>
> **Authorization covers implementation and acceptance only. Release requires a named announcement owner**, 7C being the first externally visible behaviour change.
>
> **Sequence: RED → GREEN → VERIFY → ACCEPT. WP-7 closes on acceptance. WP-7B-R1 resumes only after WP-7 closes.**

## 5. What I have not done

**No ruling minted (R-34).** **No code, no test, no plan text altered.** **7C remains unauthorized** until issuance.

**One judgement recorded rather than assumed:** I verified the four points against the **approved plan and the current source**, not against what 7C "would probably need." **Verification 2 in particular could have been answered by reasoning — the plan answers it directly, and that is a stronger answer.**

---

**Traceability:** `.claude/plans/WP-7-retention-alignment.md` §3 (layer allocation) · §5 Slice 7C (objective · acceptance · tests · dependencies) · §placement principle (folder→election parser stays with audit code, ungated) · **R-59** (7B accepted) · **R-60** (WP-7B-R1 opened) · **R-61…R-64** (already issued — the collision) · `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` · `app/Contexts/Election/Domain/EvidencePreservationWindow.php` · `app/Console/Commands/AuditCleanup.php` (60 lines, no election concept). **No code · no test · no ruling minted.**
