# WP-7 Value Object Ownership Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** which existing bounded context owns the **`EvidencePreservationWindow`** Value Object? Ownership verification only — no new context, no redesign, no code.
**Repository Integrity Gate:** ✅ PASSED.

> ## RESULT
>
> **The Election bounded context owns EPW** — because **Constitutional Policy 2 attaches the window to the election instance**, and a Value Object belongs to the context that owns *the question it answers*, not to the contexts that supply its *inputs*.
>
> **My earlier suggestion — "a plain-PHP VO beside the retention code" — was placement reasoning, not ownership reasoning, and it is withdrawn.** It would have created an **orphan domain object**: owned by nobody, protected by no guard.

---

## 1. EPW's business meaning

| Question | Answer |
|---|---|
| **What business question does it answer?** | *Until when must this election's evidence be preserved?* |
| **Which policy does it represent?** | **Constitutional Policy 2** — the Retention Invariant: *"evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved"* |
| **Which ubiquitous-language term does it embody?** | **Evidence Preservation Window** — the ARB's own naming, *"a single domain concept, not a configuration value"* |
| **Whose window is it?** | **An election's.** Policy 2: *"attached to the **election instance*** … *different election types may carry different windows"* |

**The subject of an EPW is an election.** That single sentence decides ownership.

## 2. Candidate ownership matrix

| Context | Relationship | Reasoning |
|---|---|---|
| **Election** | ⭐ **OWNS the concept** | The window is *of an election*, attached to the election instance, and varies **by election type**. The context that owns elections owns the question |
| **Adjudication** | **Supplies an input** (MAD) | Owning EPW would make Adjudication answerable for *elections'* evidence retention — a responsibility it has never held |
| **Contestation** | **Supplies an input** (Contestation Window as a policy term) | Same: an input, not the subject |
| **Audit / Retention** | **CONSUMES** the concept | It *applies* the window when deciding deletion. **A consumer is not an owner** — and it is not a bounded context at all |
| **Shared** | ⛔ **Must not own it** | The baseline ratified **zero Shared Kernel anywhere, on accountability grounds**. Placing a *business* concept in Shared would create precisely the co-owned model that ruling forbids |
| **Elections** (plural) · Trust · Governance · Membership · Committee · Geography · Finance | **Unrelated** | different subjects |

## 3. Dependency analysis — three inputs, one owner

EPW consumes **Contestation Window** (Contestation's policy term) · **MAD** (Adjudication/Q-2) · **Legal Safety Margin** (legal/ARB) · **the election instance** (Election).

> **Consuming values from multiple contexts does NOT imply shared ownership.**

This project has already ruled the pattern twice, and both precedents point the same way:

- **WP-6:** *"the APM **enforces** a duration it does not **own**"* (§81). Consuming MAD never made Adjudication its owner.
- **The frozen relationship baseline:** *"zero Partnership/Shared Kernel anywhere … jointly-owned models blur precisely that attribution."*

**A Value Object belongs to the context that owns the *question*, not to those supplying *inputs*.** Three inputs, one subject, one owner: **Election**.

## 4. Value Object placement — follows ownership, and inherits a guard

| Option | Verdict |
|---|---|
| **`app/Contexts/Election/Domain/`** | ✅ **RECOMMENDED** — ownership placement, and there is already a **`Domain/Policy/`** precedent (`ElectionCorrectionPolicy`) for domain policy objects in this exact context |
| Shared kernel | ⛔ forbidden by the ratified baseline (§2) |
| *"Beside the retention code"* (my earlier suggestion) | ❌ **withdrawn** — an orphan: **owned by nobody, and covered by no purity guard** |
| A new Retention context | ⛔ out of scope; **and unnecessary** — an owner already exists |

**The practical argument that clinches it:** `test_greenfield_domain_is_framework_free` scans **`app/Contexts/*/Domain`**. A VO placed in Election's Domain is **automatically protected** by the existing purity guard, forever. A VO placed loose in `app/` is protected by **nothing** — its purity would depend on discipline rather than on a test.

**One honest counter-consideration, recorded rather than hidden:** Election's greenfield Domain is currently scoped to the **correction loop** (`applyDetermination`, `CorrectionType`, `ElectionCorrectionPolicy`). Adding EPW **widens that context's language** to include retention. I judge this correct — the window *is* a property of an election, and Policy 2 says so in its own words — **but it is a widening, and the ARB may prefer to see it named.** *(If the ARB judges Election should not widen, the alternative is a Retention context, which is strategic and outside this commission.)*

**A second observation, flagged not solved:** WP-7 reads the **legacy `App\Models\Election`**, while the VO would live in the **greenfield** Election context. That is not a conflict — the VO is a pure value and needs no aggregate — but it is a seam worth stating, since the two Election representations coexist during the strangler transition.

## 5. Purity review

| Requirement | Satisfied by the concept? |
|---|---|
| **No identity** | ✅ two windows with the same anchor and durations **are the same window** |
| **Immutable** | ✅ a computed fact; nothing mutates it |
| **No infrastructure dependency** | ✅ dates and durations only — no config, no Eloquent, no filesystem |
| **Only domain behaviour** | ✅ its operations are *when does this close?* and *is it open at T?* |
| **Only business operations exposed** | ✅ no getters onto configuration, no persistence concerns |
| **Framework-independent** | ✅ **and enforced automatically** once placed in `Election/Domain` |

## 6. Tactical integrity — no responsibility migrates

| Requirement | Confirmed |
|---|---|
| The **Application Service constructs** the VO | ✅ resolves election → obtains durations through ports → constructs → asks |
| The **Value Object answers** the business question | ✅ *is this window open at T?* — the domain question lives in the domain |
| **Infrastructure supplies only technical inputs** | ✅ folder parsing, config reading, filesystem traversal |
| **Policy remains owned by governance** | ✅ every duration arrives through a port; **nothing defaulted or clamped** (AP-1) · MAD keeps one home (AP-2) |
| No responsibility migrates during implementation | ✅ Election owns the **concept**; Audit/Retention owns the **guard**; Q-2 owns the **values** — three owners, three responsibilities, no overlap |

## 7. RED readiness assessment

| Criterion | Status |
|---|---|
| EPW has **one identified business owner** | ✅ **Election** |
| Placement follows ownership | ✅ `app/Contexts/Election/Domain/` |
| No new bounded context introduced | ✅ |
| Tactical responsibilities unchanged | ✅ only the VO's *home* was decided |
| No architectural ambiguity remains | ✅ — the placement question that blocked RED is **closed** |

> ### **RED-ready once the two remaining gates clear — and neither is architectural:**
> 1. ⛔ **WP-6 slice acceptance** — the programme gate;
> 2. ⚠️ **the EPW anchor** — a **business value** (or approval of fail-closed, which decides nothing).
>
> **Optional, and the ARB's call:** name the Election-context widening (§4) explicitly, since a context's language is being extended.

**Architectural refinement is complete.** Strategic alignment ✔ · tactical planning ✔ · pattern verification ✔ · **ownership ✔**. The next activity is **RED**.

---

**Traceability:** **Constitutional Policy 2** (primary — `EPIC-003 §THE FOUR DECISIONS` №2: *"a single domain concept, not a configuration value"* · *"attached to the election instance"*) · EPIC-004K §81 (*enforces a duration it does not own*) · `EPIC-002_Relationship_Pattern_Selection.md` (**zero Shared Kernel**, accountability grounds) · WP-7 tactical plan + pattern verification (P-1) · `app/Contexts/Election/Domain/` (incl. the existing `Policy/` precedent) · `tests/Architecture/GreenfieldCoreArchitectureTest.php` (`test_greenfield_domain_is_framework_free`) · `deptrac.yaml`. **No context created; no responsibility moved; no code written.**
