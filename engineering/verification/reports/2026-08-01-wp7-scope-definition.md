# WP-7 Scope Definition Commission

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** define WP-7's **architectural boundary** before implementation. Not a design, not a plan, no code.
**Repository Integrity Gate:** ✅ PASSED — working tree == index == HEAD, no operation in progress. Evidence sourced from the working tree at HEAD.

---

## 1. Business capability statement

> **WP-7 delivers exactly one capability: audit evidence is not deleted while it is still constitutionally required.**

Everything below must trace to that sentence. Anything that does not is out of scope by construction. Note what the sentence does *not* say: it does not say *"compute retention policy"*, *"decide how long evidence lives"*, or *"manage audit storage"*. It says **do not delete too early** — an **absence**, which is why WP-7's acceptance criterion is also an absence.

## 2. Bounded context matrix

| Context | Classification | Why |
|---|---|---|
| **Audit / Retention** (`AuditCleanup`, `ElectionAudit`, `storage/logs/**`) | **OWNER** | WP-7's change lives here; it owns *when deletion is permitted* |
| **Adjudication** | **PROVIDER** | supplies **MAD** via `config/adjudication.php` (WP-6). WP-7 reads configuration, **never** Adjudication's process state |
| **Contestation** | **PROVIDER** *(of a parameter, not of state)* | the **Contestation Window** is its policy term. ⚠️ WP-7 must consume it as **configuration**, never by reading challenge state — see §5 |
| **Election** | **PROVIDER** | election identity and closure timing. ⚠️ the integration question — see §5 |
| **Constitutional Policy 2** | **AUTHORITY** (not a context) | defines the Retention Invariant and the EPW formula |
| Governance · Membership · Voting · Messaging | **UNAFFECTED** | WP-7 adds no event, no crossing, no consumer |

**No new bounded context is introduced.** The context map is unchanged.

## 3. Responsibility matrix

| Responsibility | Owner | **WP-7's role** |
|---|---|---|
| The Retention Invariant (*nothing covered is deleted inside its window*) | **Constitutional Policy 2** | **APPLIES** — never restates, never reinterprets |
| **EPW arithmetic** `CW + MAD + LSM` | **Architecture** (EPIC-004K §142) | **APPLIES** the given formula. *The arithmetic is architecture; the numbers are the ARB's* |
| **Maximum Adjudication Duration** | **Q-2 / ARB** | **CONSUMES** from config (WP-6 supplied it) |
| **Contestation Window** | **Q-2 / ARB** | **CONSUMES** — ⚠️ must be **added** to config as INTERIM (readiness finding R-2); WP-7 wires it, does not choose it |
| **Legal Safety Margin** | **Q-2 / ARB** | **CONSUMES** — same as above |
| Deciding *whether* a folder may be deleted | **WP-7** | **OWNS** — this is its single genuine ownership |
| Performing deletion | Audit subsystem (`AuditCleanup`) | **OWNS** the guard around it |
| **Election identity and closure timing** | **Election** | **CONSUMES** — ⚠️ see §5 |
| Writing audit evidence | `ElectionAudit` helper | **MUST NEVER MODIFY** |
| Adjudication process state (`adjudication_processes`) | Adjudication | **MUST NEVER READ OR MODIFY** |
| Challenge state | Contestation | **MUST NEVER READ OR MODIFY** |
| Operational announcement of the behaviour change | **ops / product** | **MUST NEVER OWN** — WP-7 flags it; someone else announces it |

**WP-7 owns exactly one responsibility: the deletion guard.** Everything else it consumes or must not touch.

## 4. Policy ownership matrix

| Policy | Originates in | Owner | How WP-7 receives it |
|---|---|---|---|
| Retention Invariant | Constitutional Policy 2 | the constitution | as a **rule to apply** |
| EPW formula | EPIC-004K §142 | architecture | as a **given formula** |
| MAD | Q-2 §187 | ARB | `config/adjudication.php` |
| Contestation Window | Q-2 §187 | ARB | configuration, **INTERIM-marked, to be added** |
| Legal Safety Margin | Q-2 §187 | ARB | configuration, **INTERIM-marked, to be added** |

> **WP-7 applies policy. It never defines policy.**

**Two structural safeguards carried from WP-6's findings, and they are non-negotiable here:**
- **AP-1 (unauthorized decision):** WP-7's config reader must **fail closed** on a missing or invalid parameter. It may **never substitute a default**, because substituting a retention value would be WP-7 deciding how long constitutional evidence lives — the most consequential possible instance of the AP-1 defect.
- **AP-2 (duplicated authority):** each parameter has **exactly one home**. No fallback copy anywhere in code.

## 5. Integration boundary — the question that must be answered in planning

> **Does WP-7 require another bounded context's internal state?**

**Evidence gathered, and it is uncomfortable:**

**(a) `audit:cleanup` currently has no election identity at all.**
```php
$auditPath = storage_path('logs/audit');
$cutoff = now()->subDays($days)->timestamp;
foreach (File::directories($auditPath) as $folder) {
    if (File::lastModified($folder) < $cutoff) { File::deleteDirectory($folder); }
}
```
It deletes by **folder mtime**. It knows nothing of elections, organisations, windows or challenges.

**(b) Election identity exists in the folder name only by convention.** Observed folders: `est-accusamus-est-totam_20260531_1439` — a `{slug}_{YYYYMMDD}_{HHMM}` pattern. So an election is *derivable* from a directory name, but by **string convention**, not by structure. **A slug is not an identity**, and mapping slug → election requires the Election context's data.

**(c) ⚠️ THERE ARE TWO AUDIT TREES, AND THE COMMAND REACHES ONLY ONE.**

| Tree | Written by | Reached by `audit:cleanup`? |
|---|---|---|
| `storage/logs/audit/{slug}_{timestamp}/` | admin/voter export paths | ✅ **yes** |
| `storage/logs/organisation_{id}/{election_name}/{user}.log` | `ElectionAudit` helper — the **per-voter constitutional audit trail** | ❌ **NO** — a different tree entirely |

**This is the commission's most consequential finding.** The per-voter audit trail — the artifact the project describes as *"invaluable for dispute resolution"* — is **not under `logs/audit`**, so the command WP-7 modifies **never touches it**. Two readings follow, and they are not equivalent:

1. If the Retention Invariant covers the **per-voter trail**, then making `audit:cleanup` EPW-aware **protects the wrong tree** and the invariant is unaddressed.
2. If it covers only `logs/audit`, then WP-7 is correctly scoped — and the second tree's retention is an **open question nobody has asked**.

**Classified as an INTEGRATION CONCERN requiring explicit decision before implementation. Not solved here.** Three integration questions for WP-7's plan:

| # | Question | If the answer requires another context's state… |
|---|---|---|
| **I-1** | **Which tree does the Retention Invariant cover?** | a **scope decision**, ARB-owned — and it may redirect the whole slice |
| **I-2** | How does a folder map to an election? | if it needs Election's data, that is a **crossing** — stop and report |
| **I-3** | Does *"inside its window"* depend on whether a challenge is open? | §142's finality proviso does. If yes, **this is the same Contestation-state dependency that kept the finality evaluator out of WP-6** |

**I-3 is the one to watch.** §142 conditions preservation on *"at least the latest such closure plus any running adjudication's completion"* — which is Adjudication and Contestation state. **If EPW cannot be computed from configuration and election data alone, WP-7 has the finality evaluator's problem in a new costume.**

## 6. Explicit out-of-scope

WP-7 must **not**:

| Excluded | Why |
|---|---|
| Define, choose or default **any** retention duration | Q-2/ARB own the numbers (§4) |
| Read or modify **Adjudication process state** | Provider of a *parameter*, not of state |
| Read or modify **Challenge state** | Same — and I-3 must be answered by decision, not by a query |
| Change how audit evidence is **written** | `ElectionAudit` is untouched |
| Add any **event, hydrator, consumer or crossing** | WP-7 introduces no published language |
| Model **Evidence Demand** or the **finality evaluator** | Deferred: unmodelled concept · undesigned crossing |
| Consume `AdjudicationExpired` | That is WP-6B |
| Refactor the audit subsystem, unify the two trees, or migrate folder layouts | **Architectural refactoring** — needs its own authority |
| Own or perform the **operational announcement** | ops/product |
| Refine the review framework or governance | Refinement is closed |
| Resolve **DC-1** or **AT-EVT-001** | ARB items, unrelated to this capability |

## 7. Acceptance boundary

Acceptance answers **one** question: *has WP-7 delivered its business capability?*

| Criterion | Form |
|---|---|
| **Nothing inside an open EPW is deleted** | an **absence**, asserted by test |
| **Deletion resumes after the window closes** | the capability is a guard, not a freeze |
| Every parameter is consumed, never defaulted | fail-closed evidence |
| The EPW formula matches §142 exactly | `CW + MAD + LSM` |

**Explicitly NOT acceptance criteria:** implementation quality or elegance · the second audit tree's fate (that is I-1's decision) · Q-2's final numeric values (INTERIM suffices) · WP-8's end-to-end proof · DC-1 · AT-EVT-001 · anything in §6.

## 8. Final scope statement

> **WP-7 adds a retention guard to the audit-cleanup command: before deleting audit evidence, it computes that evidence's Evidence Preservation Window from ARB-owned configuration parameters and refuses deletion while the window is open. It owns the guard and nothing else. It consumes every duration as configuration and defines none. It reads no other bounded context's state. It changes nothing about how audit evidence is written.**

**Implementation may begin without revisiting ownership — once three prerequisites are met:**
1. ⛔ **WP-6 slice acceptance** (the readiness blocker);
2. ⛔ **I-1 decided** — which audit tree the Retention Invariant covers, since the answer may redirect the slice;
3. ⚠️ **I-3 assessed in planning** — if EPW needs Contestation or Adjudication *state*, WP-7 stops and reports rather than reaching across a boundary.

**CW and LSM enter as INTERIM configuration** with their pending authority named (readiness finding R-2).

---

**Traceability:** Constitutional Policy 2 (Retention Invariant) · EPIC-004K §142 (EPW arithmetic; *the arithmetic is architecture, the numbers are the ARB's*) · Q-2 §187 (five parameters) · roadmap §WP-7 · WP-7 readiness commission `2026-08-01-wp7-readiness-commission.md` (findings R-1/R-2/R-3) · WP-6 findings AP-1/AP-2 (fail closed; one home per parameter) · frozen Cross-Context Integration Contract. **Evidence read from `AuditCleanup.php`, `ElectionAudit.php`, `config/adjudication.php`, `storage/logs/**`. No code written; no plan produced; no design decided.**
