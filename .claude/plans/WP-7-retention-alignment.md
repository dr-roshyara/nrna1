# WP-7 — Retention Alignment (`audit:cleanup` becomes EPW-aware)

**Status:** ✅ **SLICE 7B GREEN (2026-08-01)** — 11/11 keystones · **full merge gate PASSES** (Architecture 149 · Deptrac 0 · PHPStan max clean · regression 266/665, 0 failures) · developer guide filed · **zero suppressions**. Report: `engineering/verification/reports/2026-08-01-slice-7b-green-report.md`. **7B is INERT — nothing consumes the window.** ⚠️ One INTERIM: the **EPW anchor** remains an open Q-2 decision (`anchorOf()` marked INTERIM; it invents no duration). **NEXT: ARB acceptance (queue item 9). 7C unauthorized · F-WP6R-1 excluded.**
**Decision pack for a single ARB session — v2, AMENDED after dependency verification: FIVE items, five votes, no outcomes pre-filled** (`engineering/verification/reports/2026-08-01-arb-session-decision-pack.md`; verification: `…-arb-decision-dependency-verification.md`). **The dependency check did NOT rubber-stamp the pack — it found three defects: DD-1** item 4 conflated *two* state transitions in one vote (the very thing the batching rule forbids), now split into **4a plan approval (EP-01)** and **4b authorize slice 7A** · **DD-2** no agenda branch existed for A-1 being **REJECTED**, which spawns an unlisted decision (**option (c) is explicitly NOT pre-authorized**) · **DD-3** authorization was not slice-granular while the gates are. **Dependency graph is a DAG with a single sink: 1 ⟂ 2 · 4a ← 2 · 4b ← 1 ∧ 4a · 3 UNRELATED to the plan and to 7A (it gates 7B).**
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

**The decision that keeps AP-2 from recurring:** **MAD is NOT copied into a retention config.** It has exactly one home (`config/adjudication.php`). Only CW and LSM — which have no home yet — get new keys. *A second copy of MAD would be precisely the Decision Duplication corrected in WP-6.*

> ⚠️ **MECHANISM SUPERSEDED (alignment commission, 2026-08-01 — pending ARB ratification as A-1).** This section originally read *"WP-7 **consumes the existing `AdjudicationDurations` port**"*. **The invariant above stands unchanged; the mechanism does not.** Importing Adjudication's port from Election is a direct cross-context code dependency, which **TP-1 forbids and Deptrac would correctly fail**. **Replacement: Election declares its own `EvidencePreservationDurations` port, and its own `Infrastructure/Config/` adapter reads the one canonical MAD key.** Consuming the *value* from its single home is what AP-2 requires; importing the *interface* was never the binding part. See §ARCHITECTURE–ENFORCEMENT ALIGNMENT above.

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
| **Objective** | the **`EvidencePreservationWindow` Value Object** -- `CW + MAD + LSM` from the anchor, **with MAD obtained through Election's own `EvidencePreservationDurations` port (R-44)**; exposes *is the window open at T?* |
| **Business value** | Policy 2's arithmetic exists in code, once |
| **Acceptance** | matches §142 exactly · **MAD read from its one canonical home via Election's own port -- no second copy, and no import of Adjudication's port** · **an undecided/absent anchor yields "window open"** |
| **Dependencies** | 7A · the anchor decision (§3) |
| **Tests** | the three-term sum · MAD sourced from the port · anchor-absent ⇒ open |
| ✅ **P7B-1 — CORRECTED 2026-08-01 by ARB decision (Queue 12, R-57)** | The Objective and Acceptance rows above now name **Election's own `EvidencePreservationDurations` port (R-44)** in place of *"consuming `AdjudicationDurations`"* / *"the **existing** port"*, which instructed the **TP-1 violation R-44 rejected**. **Only the MECHANISM sentence changed — the invariant that MAD keeps exactly one canonical home is untouched, and no other slice text was altered.** Correction applied under governance authority, not by engineering. |
| ✅ **P7B-2 — SETTLED 2026-08-01 (engineering)** | The *absent anchor => window open* fallback belongs to the **APPLICATION SERVICE**, not the Value Object: a VO that **requires** an anchor can never be handed one that is missing, and *what to do when a fact is missing* is **use-case policy**, not a fact a value object can model. K9 names the application service; **K5 stays with the VO**. Record: `engineering/verification/reports/2026-08-01-p7b2-allocation-record.md`. |
| ✅ **P7B-3 — WITHDRAWN 2026-08-01 (was an inference; verification did not support it)** | I read §4's *"the guard decision (**not** the EPW itself)"* as defining **one** application service belonging to 7C. **Two pieces of evidence say otherwise: §2 lists TWO application-orchestration responsibilities and the first is *"compute an election's EPW"*; and 7B's objective (*"consuming `AdjudicationDurations` for MAD"*) is **unachievable by a pure VO**, since the construction commission forbids the VO a port — so 7B necessarily contains an application-layer collaborator.** §4's parenthetical is **descriptive, not definitional**. **Consequence: K9 STAYS IN 7B**, hosted by that collaborator — which is also the plainest reading of 7B's approved test list and acceptance criterion, taken at face value. **P7B-2 unaffected; only the service's slice changes.** |
| ⚠️ **P7B-2 (original flag, superseded above)** | *"Open window requires an anchor"* and *"absent anchor ⇒ window open"* **cannot both live in the Value Object**: if the VO requires an anchor to construct, it can never observe its absence, so the fallback is the **application service's**. **Settle before RED** — it decides where a test goes. Engineering's, within the frozen architecture. |
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

### ⚠️ IMPLEMENTATION GUARD COMMISSION (2026-08-01) — enforceability is NARROWER than the constraint set

`engineering/verification/reports/2026-08-01-wp7-implementation-guard-commission.md`. **The architecture is sound; its *protection* is partial.** Of the 11 constraints: **4 executably enforced · 7 manual**, of which **3 are cheaply automatable by existing precedent patterns** and **4 are accepted as manual with reasons**.

**Verified fact that governs everything below:** all three structural gates (Deptrac · greenfield PHPStan · `GreenfieldCoreArchitectureTest`) scan **only** `app/Contexts/{Contestation,Adjudication,Election,Shared}`. **`app/Console/Commands/AuditCleanup.php` — where this plan places the guard — and `app/Helpers/` are covered by ZERO structural gates.** Election *is* in scope, so **the EPW Value Object inherits real enforcement** (Deptrac `ElectionDomain: ~` blocks any port import; framework-free blocks Illuminate/Carbon) — *with one hole: a **PSR `ClockInterface`** would enter the VO undetected.*

> #### ⛔ G-1 — two frozen decisions collide at the enforcement layer (BLOCKS 7A)
>
> **"Consume Adjudication's existing `AdjudicationDurations` port" (AP-2)** and **Deptrac's approved model — `ElectionApplication: [ElectionDomain, Shared]`, contexts collaborate ONLY via events (TP-1)** — are **not simultaneously satisfiable** if the application service lives inside a context. Placed in `Election/Application` it **fails Deptrac**; placed where the plan puts the guard (`app/Console/`) it raises **no violation and receives no protection** — the constraint is not broken, it is **unobserved**.
>
> **Three factual options — (a)** service outside `app/Contexts/` *(consistent, unguarded)* · **(b)** inside `Election/Application` *(gated, but Deptrac fails on the MAD import)* · **(c)** extend the approved Deptrac model to admit the durations port *(preserves both — **requires ARB authority**)*. **Not chosen here:** choosing would be a ticket silently becoming architecture. **Must be settled before 7A**, which creates the retention port and faces the same question.

**🔴 C-1 is the one constraint whose manual enforcement is INSUFFICIENT** — *"never define, default, clamp or substitute a duration."* No gate inspects for it, **and this exact defect already occurred** (`max(1,$days)` in Infrastructure, plus `60` in two homes): both were caught by a human preservation review and were **invisible to all four gates**. Recommended automation **as part of 7A**, where the adapter is written anyway. Also recommended (cheap, non-blocking, each reusing an existing pattern): **C-2** config-key uniqueness · **C-4** construction exclusivity, reusing `ConstitutionalAssertionsTest::test_capability_decision_construction_exclusive`.

**Accepted as manual, deliberately:** **C-5** (a new aggregate/repository in a 3-slice change is unmissable in review, and automating absence over-fits) · **C-7** (a name is the most visible thing in a diff) · **C-10** (one named file) · **C-11** (release governance — not engineering's to enforce). *Domain events are already covered by `EventRegistryCompletenessTest` + AT-EVT-001.*

**DDD alignment verdict:** the gates **reinforce** the model rather than replace it — `deptrac.yaml` states the correct direction of authority in its own words (*"the tool verifies the architecture; the architecture never evolves because the tool guessed something"*). **The deficiency is reach and granularity, not direction.**

### ⚖️ ARCHITECTURE–ENFORCEMENT ALIGNMENT COMMISSION (2026-08-01) — G-1 ANALYSED; realization PENDING RATIFICATION

> **Governance correction (ARB, recorded not silently rewritten):** the first issue of this section declared *"G-1 RESOLVED"* while simultaneously recording two items awaiting ratification — **those cannot both be true.** The sequence is **analysis → recommended realization → ARB ratification → resolution**, never the reverse. *Same error class the programme has already named: **readiness is evidence, acceptance is authority** — applied correctly to WP-6 in the same session, then not applied to my own recommendation. **An analysis cannot ratify itself.***

**THE FOUR-LEVEL MODEL (ARB refinement — adopted; reusable beyond WP-7).** The two-level invariant/mechanism split was right but incomplete: it left unstated *where the invariant came from* and therefore *who may change what*.

| Level | **Role** | WP-7 instance | Authority | Engineering may change? |
|---|---|---|---|---|
| **Business Policy** | **decides WHAT** | retention is governed by Q-2; a duration is a business policy, not a technical setting | **Q-2 / ARB** | ❌ never |
| **Architectural Invariant** | **protects WHAT** | **MAD has exactly one canonical home** (AP-2) | **ARB** | ❌ never |
| **Mechanism** | **decides HOW** | **a consumer-side port** *(was: import Adjudication's port)* | engineering, within the invariant | ✅ **the only substitutable level** |
| **Implementation** | **realizes HOW** | `ConfiguredEvidencePreservationDurations` | engineering | ✅ yes |

**Why it earns its place:** it turns *"is this substitutable?"* from a judgement into a **lookup**. **All of G-1 was a level-3 substitution**, which is why it dissolved without touching the model. **Had the collision been at level 2, no engineering ingenuity would have helped and the honest answer would have been to return to the ARB.** The model tells you which situation you are in *before* you go looking for a clever fix.

> #### 🔍 THE LAYER VERIFICATION RULE (ARB refinement — adopted)
>
> **Can this layer change WITHOUT changing the layer above it? YES → it belongs at this layer. NO → you are modifying the wrong abstraction.**
>
> **It discriminates correctly on this programme's own cases, retrospectively included:** consumer-side port instead of importing the provider's port → the invariant is untouched → ✅ a legitimate Mechanism change (**this is G-1**) · **AP-2's actual defect** (a MAD key in a retention config) → destroys *"one canonical home"* → ❌ **presented as a mechanism choice, it was an invariant breach** · **AP-1's actual defect** (`max(1,$days)`) → overrides *"Q-2 decides durations"* → ❌ an implementation edit reaching **two levels up**.
>
> **The rule independently flags both AP-1 and AP-2 — the two defects that passed every automated gate and were caught only by human review.** That is the strongest available evidence it is a real heuristic rather than a restatement.

`engineering/verification/reports/2026-08-01-wp7-architecture-enforcement-alignment-commission.md`. **G-1 reclassified from "engineering placement" to an ARCHITECTURE–ENFORCEMENT ALIGNMENT GAP — architecture correct, enforcement correct, the MAPPING between them incomplete. The reclassification changed the answer, not just the label:** "placement" invites *"put the file where Deptrac doesn't look"* (option a — consistent, unguarded, and it would **look** like compliance); "alignment gap" forces the fix into the mapping, where the defect actually is.

**The unlock — mechanism vs invariant.** The plan recorded both in one sentence: **"MAD has exactly one home" is the INVARIANT (AP-2) and is preserved absolutely**; *"consume Adjudication's existing port"* was a **plan-level MECHANISM, never an architectural decision.** Consuming the *value* from its one home is what AP-2 requires; importing the *interface* is one way to do it — the way TP-1 forbids.

> #### ⭐ RECOMMENDED REALIZATION *(pending A-1)* — option (d): **Election declares its OWN consumer-side port**
> Hexagonal orthodoxy: **a port belongs to the consumer, in the consumer's language.** `EvidencePreservationDurations` → `app/Contexts/Election/Application/Port/` · `ConfiguredEvidencePreservationDurations` → `app/Contexts/Election/Infrastructure/Config/`, **mirroring `ConfiguredAdjudicationDurations` exactly** (injected `Config`, precedence resolution, fail-closed, no clamping). It reads **the one canonical MAD key**. **No cross-context code import anywhere.**
>
> **TP-1 ✔ · AP-2 ✔ · Deptrac UNMODIFIED ✔ · domain model UNCHANGED ✔ · fully gated ✔.** Options (a) unguarded, (b) a genuine TP-1 violation, and (c) relax the Deptrac model were all **rejected — (c) is not needed, so it is not proposed.**
>
> **Not a disguised crossing:** **MAD is not Adjudication's data — it is Q-2's policy** housed in `config/adjudication.php`. Both contexts are downstream of *governance*, not of each other. No model, type, lifecycle or deployment coupling.
>
> **Honest cost — 🔴 R-D1:** the *precedence logic* (organisation → election type → default) would exist twice and could **drift**, so MAD could resolve differently in the two contexts. **Recommended gate: a test asserting both adapters resolve the same MAD for the same `(electionType, organisationId)` — it protects AP-2's INTENT rather than its letter.** Extracting precedence into `Shared` on first repetition is **declined** (two ~10-line adapters don't justify a platform abstraction; Shared *"must never degrade into a general utility layer"*).

**Placement principle — coverage follows MEANING, not the reverse.** Code was **not** pulled into contexts to obtain coverage. Business-meaningful pieces (VO · port · service · adapter) are in Election and gated; **the folder→election parser, traversal, deletion and the CLI stay with the audit code and remain ungated — correctly, because they carry no policy.** `--days` (C-9) is behaviour, protected by a behavioural test. *Everything carrying policy or domain meaning is gated; everything ungated carries neither — a stronger claim than "everything is gated."*

**Responsibility inventory (8 rows) replaces the rule inventory as the unit of protection.** It revealed what the constraint view could not: **R2 (policy ownership) and R7 (infrastructure) are the SAME defect from both ends** — *policy ownership is violated at the moment infrastructure decides a value*, which is precisely AP-1. **So C-1 is not one fix among three; it is the single gate protecting the responsibility the constitution cares about most.** Likewise **R4/R5 are one drift with two symptoms** (construction sliding into orchestration), closed by the one construction-exclusivity precedent.

**Coverage delta:** business-meaningful code in gated paths goes from *the VO only* → *VO + port + service + adapter*; **C-6 moves from "unobserved" to actively enforced (4/11 → 5/11)**. **Alignment makes existing gates apply; it does not add gates** — C-1 🔴, C-4 and R-D1 remain the substantive additions.

**⚖️ Two items referred to the ARB, not assumed** — **A-1** mechanism substitution *(confirm the **invariant**, not the sentence, was binding)* · **A-2** boundary sharpening *(**Election answers**, **Audit/Retention acts** — consistent with the frozen "Consumption (acting on the answer)", but the guard was described as one thing and this names it as two; **no holder changes**)*. **If A-1 is declined, the fallback is option (c) — relaxing a correct gate — which must be a deliberate recorded act, not a default.**

---

**Traceability:** **Constitutional Policy 2** (primary: `EPIC-003 §THE FOUR DECISIONS` №2 — Retention Invariant, EPW definition, *"the Contestation Window itself must be explicitly defined"*) · EPIC-004K §142 · roadmap §WP-7 · WP-7 readiness (R-1/R-2/R-3) · WP-7 scope definition · domain boundary commission (three concepts; WP-7 → artifact A) · WP-6 findings **AP-1** (fail closed) and **AP-2** (one home per parameter) · `App\Models\Election` · `AuditCleanup.php` · `ElectionAuditService.php`. **No code, no config key, no test; no architecture redesigned; no governance modified.**
