# WP-7 — Retention Alignment (`audit:cleanup` becomes EPW-aware)

**Status:** 📋 **TACTICAL PLAN — 🧊 ARCHITECTURE FROZEN · TRANSITION TO IMPLEMENTATION AUTHORIZED (architecturally).** Six commissions complete; **zero architectural gates remain**. ⛔ **RED still blocked by WP-6 slice acceptance** — programme management, not architecture. Awaiting EP-01 approval. **No code, no config key, no test.**
**Gate note:** WP-6's ARB acceptance is still pending; the roadmap's rule is *"no slice starts before its predecessor's acceptance."* **Planning is the authorized activity; RED is not.**
**Slice:** WP-7 (EPIC-004 roadmap) · **Protocol:** `.claude/IMPLEMENTATION_PROTOCOL.md` (FROZEN) · **Repository Integrity Gate:** ✅ PASSED

---

## 1. Strategic alignment review — unchanged, verified not assumed

| Strategic element | Approved position | Changed? |
|---|---|---|
| **Business capability** | *audit evidence is not deleted while it is still constitutionally required* | **No** |
| **Bounded context** | Audit / Retention owns the guard; Adjudication and Q-2 are **providers of parameters** | **No** |
| **Upstream / downstream** | none — WP-7 adds **no crossing, no consumer, no producer** | **No** |
| **Published language** | untouched — no event added, changed or retired | **No** |
| **Ownership** | WP-7 owns **one** thing: the deletion guard | **No** |
| **Which artifact** | **artifact A** only (Election Event Journal, `logs/audit/`) — B and C are different concepts | **No** — settled by the domain boundary commission |

**Governing authorities, read at source:** **Constitutional Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) — *"evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved"*, with **EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin, attached to the election instance** · roadmap §WP-7 · EPIC-004K §142.

**No strategic assumption has changed. Planning continues.**

## 2. Tactical responsibility matrix

| Layer | WP-7 implements | WP-7 must NOT |
|---|---|---|
| **Business rules** | **none** — it *applies* Policy 2 and the §142 formula | define, default or clamp any duration |
| **Application orchestration** | compute an election's EPW; decide *may this folder be deleted yet* | decide *how long* evidence lives |
| **Domain behaviour** | **none** — no aggregate, no invariant, no state machine | invent a Retention aggregate |
| **Infrastructure** | config keys for CW + LSM; the guard inside `AuditCleanup`; folder→election resolution | change how audit evidence is **written** |
| **Integration** | **none** | read Contestation or Adjudication state |

**One owned responsibility: the deletion guard.** Everything else is consumed.

## 3. Dependency assessment — and **I-3 resolves favourably**

| Dependency | Owner | Consumer | Mechanism | Data required | Authority |
|---|---|---|---|---|---|
| **MAD** | **Q-2 / ARB** | WP-7 | **the existing `AdjudicationDurations` port** | days | Q-2 · §81 |
| **Contestation Window** | Q-2 / ARB | WP-7 | **new** config key, INTERIM | days, per election type | Policy 2 (*"the Contestation Window itself must be explicitly defined"*) |
| **Legal Safety Margin** | Q-2 / ARB | WP-7 | **new** config key, INTERIM | days | Policy 2 |
| **Election instance** | Election (legacy `App\Models\Election`) | WP-7 | direct model read | `slug` · `type` · `start_date` · `end_date` · `results_published_at` | — |
| Audit folder layout | `ElectionAuditService` | WP-7 | filesystem | `{slug}_{Ymd}_{Hi}` from `start_date` | — |

### I-3 — **RESOLVED: no bounded-context state is required**

The open question was *"does per-election EPW need Contestation or Adjudication state?"* **It does not.** EPW is a function of **the election instance + configuration**:

- every field needed (`slug`, `type`, `start_date`, `end_date`, `results_published_at`) is on **`App\Models\Election`** — the **legacy shared model**, not a greenfield context's internals;
- §142's *"no challenge open at closure"* proviso governs **determination finality**, **not** audit retention. **Policy 2's EPW has no such clause** — verified against the primary text.

**No crossing is created. No stop-and-report is triggered.**

### ⚠️ One dependency question that IS open — the EPW anchor

**Policy 2 defines the window's three *durations* but not its *start*.** Candidates on the model: `end_date` · `results_published_at` · `archived_at`. **This is a business decision, not a technical one** — it determines when the clock starts on constitutional evidence.

**Raised, not solved.** Recommended handling: **fail closed** — if the anchor is undecided or absent for an election, **do not delete**. That keeps WP-7 from choosing a business value (the AP-1 lesson) while remaining safe in every case.

## 4. Tactical model review — every element already authorized

| Element | New? | Strategic authorization |
|---|---|---|
| Aggregates · entities · domain services | **none** | — |
| **Domain events** | **none** | WP-7 publishes nothing |
| **Value Object — `EvidencePreservationWindow`** | ✅ **YES (P-1, corrected)** | **Policy 2 names EPW *"a single DOMAIN CONCEPT, not a configuration value"*.** Immutable, no identity, answers *is this window still open at T?* -- a Value Object by definition. *(The earlier default of "no" was set against the authority own framing.)* **Owner: the ELECTION bounded context** -- Policy 2 attaches the window to *the election instance*, and a VO belongs to the context owning the QUESTION, not those supplying inputs. **Placement: `app/Contexts/Election/Domain/`** (a `Domain/Policy/` precedent already exists there, and the framework-purity guard scans that path automatically). *Ownership commission 2026-08-01; the earlier "beside the retention code" suggestion is withdrawn -- it would have been an orphan owned by nobody* |
| **Application service** — the guard decision (**not** the EPW itself) | **yes, one** | roadmap §WP-7. It **coordinates**: resolve election, obtain durations through ports, **INVOKE the VO's own static factory**, ask the window. **It decides nothing and validates nothing** — the value owns its validity. *(Construction commission 2026-08-01: "the Application Service constructs the VO" conflated who INVOKES construction with where the construction RULES live. Construction belongs to a **private constructor + named static factory ON the VO** — the house idiom: `ChallengeRef::fromString`, `EvidenceSet::fromRefs`, `ContestedOutcomeRef::of`. A separate Domain Factory class and aggregate construction were both **considered and rejected**. The constructor takes **business values only** — anchor, CW, MAD, LSM — never a port, config, model or clock; `is-open-at-T` takes the instant as an **argument**. Factory naming must be a **fact, not a procedure**: `forElection(...)` ✅, `calculate`/`build`/`createFrom` ❌.)* |
| **Port** — retention durations (CW, LSM) | **yes, one** | Policy 2; mirrors WP-6's `AdjudicationDurations` pattern |
| Repositories | **none** — reads the existing Eloquent model | — |

**The decision that keeps AP-2 from recurring:** **MAD is NOT copied into a retention config.** It has exactly one home (`config/adjudication.php`) and WP-7 **consumes the existing `AdjudicationDurations` port**. Only CW and LSM — which have no home yet — get new keys. *A second copy of MAD would be precisely the Decision Duplication corrected in WP-6.*

**No tactical concept is introduced for implementation convenience.** *(Tactical Pattern Verification, 2026-08-01: +1 Value Object, -1 responsibility inside the application service -- the model became simpler to describe. Report: `engineering/verification/reports/2026-08-01-wp7-tactical-pattern-verification.md`.)*

## 5. Implementation slices

### Slice 7A — Retention durations (config + port + resolver)

| Field | Content |
|---|---|
| **Objective** | CW and LSM resolvable per election type, INTERIM-marked, **fail-closed** on absence |
| **Business value** | the two missing Policy 2 terms acquire an explicit, single home |
| **Owner** | Audit/Retention (implementation) · **Q-2/ARB owns the values** |
| **Acceptance** | both resolve per election type; a missing/invalid value **throws** rather than defaulting |
| **Dependencies** | none |
| **Tests** | resolution per type · organisation override if adopted · **fail-closed on missing/invalid** |
| **Independently releasable** | ✅ inert — nothing consumes it yet |

### Slice 7B — EPW calculation

| Field | Content |
|---|---|
| **Objective** | the **`EvidencePreservationWindow` Value Object** -- `CW + MAD + LSM` from the anchor, consuming `AdjudicationDurations` for MAD; exposes *is the window open at T?* |
| **Business value** | Policy 2's arithmetic exists in code, once |
| **Acceptance** | matches §142 exactly · MAD comes from the **existing** port (no second copy) · **an undecided/absent anchor yields "window open"** |
| **Dependencies** | 7A · the anchor decision (§3) |
| **Tests** | the three-term sum · MAD sourced from the port · anchor-absent ⇒ open |
| **Independently releasable** | ✅ still inert |

### Slice 7C — The deletion guard

| Field | Content |
|---|---|
| **Objective** | `audit:cleanup` refuses to delete a folder whose election's EPW is still open |
| **Business value** | **the capability** — evidence outlives its challenge window |
| **Acceptance** | **nothing inside an open EPW is deleted; deletion resumes after closure** · an unresolvable folder→election mapping ⇒ **not deleted** |
| **Dependencies** | 7A · 7B |
| **Tests** | open window ⇒ retained · closed window ⇒ deleted · unmappable folder ⇒ retained · **`--days` no longer overrides the invariant** |
| **Independently releasable** | ✅ and it is the **first externally visible behaviour change** — announcement owner required before release |

**Slice order is dependency order.** 7A and 7B are inert; **all observable behaviour lands in 7C**, which makes the release decision a single, reviewable event.

## 6. Acceptance strategy — completion and acceptance kept apart

**Implementation completion** — all three slices delivered · `composer merge-gate` · PHPStan max · Deptrac 0 · Architecture suite green · dev guide · operational record (the framework's **second**, first cross-slice).

**Architectural acceptance** — traced to the business capability, not to the code:

| Acceptance criterion | Traces to |
|---|---|
| Nothing inside an open EPW is deleted | **Policy 2 Retention Invariant** — the capability itself |
| Deletion resumes after closure | the capability is a **guard**, not a freeze |
| EPW = CW + MAD + LSM | §142 / Policy 2 |
| No duration is defined, defaulted or clamped by WP-7 | Q-2 ownership · AP-1 |
| MAD has exactly one home | AP-2 |
| No crossing introduced | frozen contract |

**Not acceptance criteria:** implementation elegance · artifacts B and C · Q-2's final numeric values (INTERIM suffices) · any governance-register item.

## 7. Out-of-scope register — explicitly excluded

| Excluded | Reason |
|---|---|
| **B-1..B-6 / C-1..C-5** — the Voter Activity Trail | separate strategic matter; **planning must not absorb it** |
| **H-1..H-3 · A-1/A-2/A-4 · DC-1 · AT-EVT-001** | governance register — ARB-owned |
| **AD-007..AD-010** | engineering backlog |
| Artifacts **B** and **C** | different domain concepts, different owners |
| Changing how audit evidence is **written** | `ElectionAuditService` untouched |
| Unifying the two audit trees · migrating folder layouts | **architectural evolution** — needs its own authority |
| Reading Contestation or Adjudication state | no crossing |
| Owning the **operational announcement** | ops/product |
| Defining any duration value | Q-2 |

## 8. Final tactical plan

> **WP-7 adds a retention guard to `audit:cleanup`: it computes each audit folder's Evidence Preservation Window from ARB-owned configuration and the election instance, and refuses deletion while that window is open. Three slices — durations, arithmetic, guard. It owns the guard and nothing else, defines no duration, and creates no crossing.**

**Ready for RED once two things are true:**
1. ⛔ **WP-6 slice acceptance** (the programme gate);
2. ⚠️ **the EPW anchor decision** (§3) — or explicit approval of the **fail-closed** default, which needs no business ruling because it decides nothing.

**No further strategic discussion is required.** Every tactical element traces to an approved strategic decision, and the one genuinely open item is a **business value**, not an architectural question.

> ### 🧊 ARCHITECTURE FROZEN FOR WP-7 — TRANSITION AUTHORIZED (2026-08-01)
>
> Strategic alignment ✔ · tactical planning ✔ · pattern verification ✔ · VO ownership ✔ · **VO construction ✔** · **transition authorization ✔** (`engineering/verification/reports/2026-08-01-wp7-architecture-to-implementation-transition.md` — **the final architectural commission**).
>
> **The freeze criterion is not "nothing more can be reviewed."** It is: **every architectural uncertainty is RESOLVED (12) or DELIBERATELY TRANSFERRED to a named non-architectural owner (4)**, with 2 explicitly out of scope. **Zero architectural gates remain.**
>
> **Seven responsibilities, seven holders, frozen:** business capability · **policy values → Q-2/ARB** · **concept → Election** · **construction → the VO's static factory** · **orchestration → the Application Service** (which validates nothing) · **the guard → Audit/Retention** · infrastructure mechanics. **Implementation realizes this allocation; it does not renegotiate it.**
>
> **⛔ RED IS NOT YET AUTHORIZED TO START** — **WP-6 slice acceptance** (programme management, the ARB's act) is the single blocking gate. The moment it is granted, **RED may begin at slice 7A with no further architectural act**. Everything else remaining is a business value or a governance item, none of them a WP-7 dependency.
>
> **Eleven implementation constraints** (what implementation must not violate) are listed in §Implementation Constraints of the transition record. **Reopening standard: only if implementation exposes a genuine design issue** — not for refinement, expression, or pattern preference. **Implementation consumes architecture; it does not continue it.**
>
> **One named reopening condition, with its blast radius:** if the ARB rules Election must not widen and Retention warrants its own context, **EPW relocates — a namespace move, not a redesign**; ownership, construction idiom, dependency rule and the guard all survive. That is why it does not block RED.

---

**Traceability:** **Constitutional Policy 2** (primary: `EPIC-003 §THE FOUR DECISIONS` №2 — Retention Invariant, EPW definition, *"the Contestation Window itself must be explicitly defined"*) · EPIC-004K §142 · roadmap §WP-7 · WP-7 readiness (R-1/R-2/R-3) · WP-7 scope definition · domain boundary commission (three concepts; WP-7 → artifact A) · WP-6 findings **AP-1** (fail closed) and **AP-2** (one home per parameter) · `App\Models\Election` · `AuditCleanup.php` · `ElectionAuditService.php`. **No code, no config key, no test; no architecture redesigned; no governance modified.**
