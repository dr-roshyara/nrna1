# WP-7 Architecture-to-Implementation Transition Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** may WP-7 transition from architecture into implementation? **Verification of closure only** — no redesign, no rediscovery, no revisiting of prior commissions, no code.
**Repository Integrity Gate:** ✅ PASSED — working tree clean; branch `feature/pb003`, 14 commits ahead of origin; no conflict markers, no unexpected deletions.

> ## RESULT — **ARCHITECTURE FROZEN. The transition is architecturally authorized. RED is NOT yet authorized to start.**
>
> **The freeze is not "nothing more can be reviewed."** It is the stronger claim the ARB named:
>
> > **Every architectural uncertainty in WP-7 has been either RESOLVED or DELIBERATELY TRANSFERRED to a non-architectural owner. No undecided architectural responsibility remains.**
>
> **One gate still holds RED, and it is not mine to clear:** ⛔ **WP-6 slice acceptance** — programme management, the ARB's act.

---

## 1. Architecture Closure Assessment (Phase 1)

Six WP-7 commissions were completed. **Every architectural question they raised is enumerated below with exactly one outcome.** *(Verified against the report set in `engineering/verification/reports/`: readiness · scope definition · domain audit boundary · tactical plan · tactical pattern verification · VO ownership · VO construction.)*

| # | Architectural question | Outcome | Where decided |
|---|---|---|---|
| Q1 | Does WP-7's premise still hold? | ✅ **RESOLVED** — yes (R-3) | readiness commission |
| Q2 | Which audit artifact does WP-7 govern? | ✅ **RESOLVED** — **artifact A only**; B and C are different domain concepts | domain audit boundary commission |
| Q3 | Does WP-7 create a context crossing? | ✅ **RESOLVED** — **none**: no event, no consumer, no producer | tactical plan §1 |
| Q4 | Does per-election EPW require Contestation/Adjudication state? | ✅ **RESOLVED** — **no** (I-3): every field is on `App\Models\Election`; §142's proviso governs determination finality, not retention | tactical plan §3 |
| Q5 | Is EPW an application computation or a domain concept? | ✅ **RESOLVED** — **Value Object** (P-1), per Policy 2's own naming clause | pattern verification |
| Q6 | Which context **owns** EPW? | ✅ **RESOLVED** — **Election** (the window is *of an election*) | ownership commission |
| Q7 | Where is the VO **placed**? | ✅ **RESOLVED** — `app/Contexts/Election/Domain/` — **verified to exist**, with the `Domain/Policy/` precedent present | ownership commission |
| Q8 | **Who constructs** the VO? | ✅ **RESOLVED** — private ctor + **named static factory on the VO**; the service **invokes** | construction commission |
| Q9 | What may enter the constructor? | ✅ **RESOLVED** — **business values only**; no port, config, model or clock | construction commission |
| Q10 | Does MAD get a second home? | ✅ **RESOLVED** — **no** (AP-2): WP-7 consumes the **existing** `AdjudicationDurations` port | tactical plan §4 |
| Q11 | Aggregate? Domain service? Repository? Domain factory? Domain event? | ✅ **RESOLVED** — **each considered and rejected with a domain reason**, not by omission | pattern verification §3 · construction §1 |
| Q12 | May WP-7 define a duration value? | ✅ **RESOLVED** — **never**; fail closed (AP-1) | tactical plan §4 |
| Q13 | **The EPW anchor** | 🔁 **TRANSFERRED → Business Decision (Q-2/ARB)** — with a safe interim (**fail closed**) that decides nothing | tactical plan §3 |
| Q14 | CW and LSM values | 🔁 **TRANSFERRED → Business Decision (Q-2/ARB)** — INTERIM suffices for implementation | tactical plan §5 (7A) |
| Q15 | Does adding EPW **widen the Election context's language** into retention? | 🔁 **TRANSFERRED → Governance (ARB)** — **named, not hidden**; see the reopening condition below | ownership commission §4 |
| Q16 | The legacy `App\Models\Election` ↔ greenfield Election seam | 🔁 **TRANSFERRED → Governance (strangler transition)** — flagged, not a WP-7 conflict | ownership commission §4 |
| Q17 | Should Retention become a bounded context? | ⛔ **OUTSIDE WP-7** — strategic; **and unnecessary**, an owner already exists | ownership commission §2 |
| Q18 | Unifying the two audit trees · changing how evidence is written · artifacts B/C | ⛔ **OUTSIDE WP-7** | tactical plan §7 |

**Count: 12 resolved · 4 transferred with a named owner · 2 explicitly outside scope. Zero undecided.**

### The one honest reopening condition, stated with its blast radius

**Q15 is the only transferred item that could touch the model.** If the ARB rules that Election must **not** widen and Retention warrants its own context, **EPW relocates**.

**That is a namespace move, not a redesign** — ownership, construction idiom, dependency rule, factory semantics and the guard all survive unchanged, because none of them depends on *which* context holds the value. **This is why Q15 does not block RED**, and I record the reasoning rather than merely the conclusion so that a later reader can check it.

## 2. Responsibility Freeze Verification (Phase 2)

| Responsibility | Frozen holder | May implementation change it? |
|---|---|---|
| **Business capability** | *audit evidence is not deleted while constitutionally required* | ❌ |
| **Policy ownership** (CW · MAD · LSM · anchor) | **Q-2 / ARB** | ❌ — WP-7 may **never** define, default or clamp one (**AP-1**) |
| **Concept ownership** (EPW) | **Election** bounded context | ❌ |
| **Construction** (the value's validity) | **the VO's own static factory** | ❌ |
| **Orchestration** | **the Application Service** — resolve · gather through ports · invoke · ask | ❌ — and it **validates nothing** |
| **Consumption** (the guard) | **Audit / Retention** | ❌ |
| **Infrastructure** | folder→election parsing · traversal · deletion · config adapter | ❌ — **adapters translate, never decide** |

**Seven responsibilities, seven holders, no overlap, no ambiguity. Implementation realizes this allocation; it does not renegotiate it.**

## 3. Remaining Gates Classification (Phase 3)

| Gate | Class | Owner | Blocks RED? |
|---|---|---|---|
| **WP-6 slice acceptance** | 🏛️ **Programme Management** | **ARB** | ⛔ **YES** — the roadmap rule is *"no slice starts before its predecessor's acceptance"* |
| **The EPW anchor** | 💼 **Business Decision** | Q-2 / ARB | ⚠️ **No** — **fail closed** is safe in every case and decides nothing |
| CW · LSM values | 💼 Business Decision | Q-2 / ARB | ❌ No — INTERIM suffices |
| Election-language widening (Q15) | ⚖️ Governance | ARB | ❌ No — relocation risk only |
| Legacy/greenfield Election seam | ⚖️ Governance | ARB (strangler) | ❌ No |
| AT-EVT-001 · DC-1 · H-1..H-3 · A-1/A-2/A-4 · B-1..B-6 / C-1..C-5 | ⚖️ Governance | ARB | ❌ No — none is a WP-7 dependency |
| AD-007..AD-010 | 🔧 Engineering backlog | team | ❌ No |
| 7C's release announcement | 📣 Operational | ops / product | ❌ No — release, not implementation |

> ### **ARCHITECTURAL GATES REMAINING: ZERO.**
> Every remaining gate has a **non-architectural owner**. That — not the absence of further review — is what makes the freeze legitimate.

**WP-6 acceptance status, verified rather than assumed:** the WP-6 plan records *"the evidence supports ARB acceptance within the approved scope"* and, explicitly, *"⏳ Acceptance is the ARB's act and is not yet granted."* **Readiness is evidence; acceptance is authority.** The gate is open.

## 4. Tactical Stability Assessment (Phase 4)

| Dimension | Fixed? | Fixed as |
|---|---|---|
| **Tactical patterns** | ✅ | 1 Value Object · 1 new Port · 1 Application Service · 3 infrastructure pieces. **No aggregate, entity, domain service, repository, domain factory or domain event** — each rejected with a reason |
| **Dependency direction** | ✅ | Infrastructure → Application → Domain concept. **Inward only; zero inversions** |
| **Construction idiom** | ✅ | private ctor + named static factory, validating inside — **matching `ChallengeRef` · `EvidenceSet` · `ContestedOutcomeRef`, verified in the codebase** |
| **Integration boundaries** | ✅ | **none added.** No event published, consumed, changed or retired |
| **Port reuse** | ✅ | **`AdjudicationDurations` verified present** at `app/Contexts/Adjudication/Application/Port/`, already `(?electionType, ?organisationId)`-scoped — **exactly the shape 7A must mirror**, so MAD keeps one home |
| **Purity enforcement** | ✅ | `Election/Domain` **verified to exist**; `test_greenfield_domain_is_framework_free` scans that path — purity is guarded by a test, not by discipline |
| **Naming semantics** | ✅ | factory names must state a **fact** (`forElection`), never a **procedure** (`calculate`/`build`/`createFrom`) |

**Implementation realizes these decisions. It does not extend them.**

## 5. RED Authorization Recommendation (Phase 5)

**First RED slice: 7A — retention durations (config + port + adapter).** Deliberately the most inert slice: nothing consumes it, and it changes no observable behaviour.

| Precondition for 7A | Verified? |
|---|---|
| Requires **new architecture**? | ❌ **No** — a port mirroring an existing, verified one |
| Requires a **new bounded context**? | ❌ **No** |
| Requires a **new ownership decision**? | ❌ **No** — Q-2 owns the values; WP-7 owns only the resolution mechanism |
| Requires **new governance interpretation**? | ❌ **No** — Policy 2 already states *"the Contestation Window itself must be explicitly defined"* |
| Requires the **anchor** decision? | ❌ **No** — the anchor enters at **7B**, and fail-closed covers it |
| Requires final **values**? | ❌ **No** — INTERIM, as WP-6's MAD already is |

**7A is architecturally unblocked.** So are 7B and 7C, subject only to the anchor treatment already decided (fail closed).

> ### **RECOMMENDATION TO THE ARB**
>
> **Architecturally: AUTHORIZE the transition.** No architectural dependency stands between WP-7 and RED.
>
> **Programmatically: RED MUST NOT START** until WP-6 slice acceptance is granted. **I am not authorized to clear that gate, and I do not treat evidence of readiness as acceptance.**
>
> **The precise standing:** *the moment WP-6 acceptance is granted, RED may begin at slice 7A with no further architectural act.*

## 6. Architecture-to-Implementation Transition Record (Phase 6)

### Architecture Status
🧊 **FROZEN** — because **every architectural uncertainty is resolved (12) or deliberately transferred to a named non-architectural owner (4)**, with 2 explicitly out of scope. **Not** because review has been exhausted.

### Remaining Non-Architectural Gates
1. ⛔ **WP-6 slice acceptance** — *Programme Management · ARB · the only gate blocking RED*
2. ⚠️ **EPW anchor** — *Business Decision · Q-2/ARB · non-blocking via fail-closed*
3. **CW · LSM values** — *Business Decision · INTERIM suffices*
4. **Election-language widening (Q15)** — *Governance · relocation risk only*
5. **Legacy/greenfield Election seam** — *Governance · strangler transition*
6. **Register items** (AT-EVT-001 · DC-1 · H-1..H-3 · A-1/A-2/A-4 · B/C series · AD-007..010) — *Governance / backlog · none a WP-7 dependency*

### RED Preconditions — ✅ VERIFIED
Closure complete · responsibilities frozen · zero architectural gates · tactical model stable · 7A requires no architectural act.

### Implementation Constraints — **what implementation MUST NOT violate**

1. **Define, default, clamp or substitute no duration.** Missing or invalid ⇒ **throw**. *(AP-1)*
2. **MAD keeps exactly one home.** Consume `AdjudicationDurations`; **never add a retention copy**. *(AP-2)*
3. **The EPW constructor takes business values only** — no port, config, Eloquent model or clock. `is-open-at-T` takes the instant as an **argument**.
4. **The VO validates itself; the Application Service validates nothing.** Construction must not migrate into orchestration.
5. **No aggregate, entity, domain service, repository, domain factory or domain event.** Each was rejected with a reason; reintroducing one is a design change requiring authority.
6. **No context crossing.** Publish nothing; read no Contestation or Adjudication state.
7. **Factory names state a fact, not a procedure.**
8. **Fail closed on any unknown:** unresolvable folder→election, absent anchor, missing value ⇒ **do not delete**.
9. **`--days` must never override the constitutional invariant.**
10. **Do not change how audit evidence is written** (`ElectionAuditService` untouched), and **do not touch artifacts B or C**.
11. **7C is the first externally visible behaviour change** — release needs an announcement owner outside engineering.

### First Authorized RED Activity
**Slice 7A — RED:** failing tests for CW/LSM resolution **per election type**, organisation override, and **fail-closed** on a missing or invalid value — mirroring the verified `AdjudicationDurations` port shape. **Contingent on WP-6 acceptance.**

---

> ## Closure of the architectural track
>
> **This is the final architectural commission for WP-7.** The sequence it completes — **Business Meaning → Ownership → Tactical Pattern → Construction Responsibility → Implementation** — is closed at every stage.
>
> **Reopening standard (unchanged from the correction-loop closure):** architecture reopens **only if implementation exposes a genuine design issue** — not for refinement, not for expression, not for pattern preference. **Implementation consumes architecture; it does not continue it.**

---

**Traceability:** WP-7 commission set (readiness R-1/R-2/R-3 · scope definition · domain audit boundary · tactical plan · tactical pattern verification P-1 · VO ownership · VO construction) · **Constitutional Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) · EPIC-004K §81/§142 · WP-6 findings **AP-1**/**AP-2** · WP-6 plan status line (*acceptance not yet granted*) · verified in-repo: `app/Contexts/Adjudication/Application/Port/AdjudicationDurations.php` · `app/Contexts/Election/Domain/` (incl. `Policy/`) · `test_greenfield_domain_is_framework_free`. **No code written; no architecture discovered; no prior commission revisited; no gate cleared that belongs to another authority.**
