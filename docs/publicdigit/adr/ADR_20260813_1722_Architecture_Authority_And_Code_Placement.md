# ADR — Current Architecture Authority and Code Placement

**Status: 🟡 PROPOSED — awaiting governance review (Session 2) and independent falsification (Session 1), then Product Owner / ARB acceptance. Nothing in this ADR authorizes a migration, a rename, or a code change.**
**Date:** 2026-08-13 17:22 · **Deciders:** Product Owner / ARB (pending) · **Recorded by:** Engineering (Session 4 — evidence and drafting only, `R-34`)
**Origin:** an architectural claim that could not be substantiated — *"`app/Contexts/Elections/Domain/` is authoritative for Election rules"* — raised during EM-VOT-002 (`PBDIGIT-64`) implementation.
**Evidence base:** [`docs/publicdigit/reviews/2026-08-13-election-context-architecture-archaeology.md`](../reviews/2026-08-13-election-context-architecture-archaeology.md). **This ADR asserts nothing that report did not establish from dependencies, DI wiring, call sites and declared architecture.**
**Companion rule (agent behaviour):** [`docs/pks/2026-08-13-architectural-authority-evidence-hierarchy-candidate.md`](../../pks/2026-08-13-architectural-authority-evidence-hierarchy-candidate.md) — a PKS **candidate**, not a standard.

---

## 0 · Placement note (ES-004.2 / ADR_20260801_1740)

Placement was **derived, not chosen**:

```
$ php scripts/doc-placement.php --scope=product-specific --maturity=adopted --domain=publicdigit
docs/publicdigit                                                        (exit 0)
```

**On the ADR identifier.** The commission asked that an existing number be used rather than invented. There is no number to take: `docs/publicdigit/adr/` uses a **timestamp** convention (`ADR_YYYYMMDD_HHMM_Title`), not a sequence — its four existing ADRs are `ADR_20260806_1340`, `ADR_20260806_1520`, `ADR_20260806_1620`, `ADR_20260807_1500`. This file follows that convention. The repository holds **four different ADR series** in three directories (`docs/adr/` with at least five mutually inconsistent schemes, `docs/publicdigit/adr/` timestamped, `engineering/architecture/adr/` `ADR-AIP-nn`); **no single next number exists**, and inventing a global sequence would be an unauthorized convention change. Recorded as ambiguity **AMB-6** (§9).

`docs/architecture/adr/` — the path named in the commission — **does not exist** in this repository.

---

## 1 · Context

### 1.1 What happened

An analysis of the Election code concluded that `app/Contexts/Elections/Domain/` was *"Active — authoritative for voting"* and that the engineering task was to *"ensure the authoritative ones (`Contexts/`) become the source of truth."* Had EM-VOT-002 been implemented on that basis, a constitutional vote-gating rule would have been placed in a module that neither the transition guard nor the lifecycle engine can reach.

The archaeology established the reverse:

- `app/Contexts/Elections/` (plural) is **downstream** of `app/Domain/Election/`, importing its enum and two of its domain events. `app/Domain/` and `app/Application/` contain **zero** references to `App\Contexts` (grep).
- It owns **one** capability — voter eligibility decision and voter assignment — in 15 files.
- It has **no ServiceProvider** of its own and is **absent from `deptrac.yaml`**.
- A **different** directory, `app/Contexts/Election/` (singular), *is* provider-registered and deptrac-governed — and its own Anti-Corruption Layer documents the legacy `elections` table as *"the CURRENT operational source of truth, until a greenfield Election-lifecycle capability replaces it (Strangler)."*

### 1.2 Why an inference this wrong was easy to make

Four properties of this repository make folder-based reasoning actively misleading:

| Property | Evidence |
|---|---|
| **Multiple architectural generations coexist**, each added beside its predecessor | `ADR_20260807_1500` §"three generations in one model" names exactly this pattern for election state |
| **Consumers are never moved when authority moves** | *ibid.*: "the platform's recurring migration failure (authority moves, consumers stay; observed in election state, votes-per-IP, voter eligibility, results publication, demo availability)" |
| **Two sibling directories differ by one character** and hold unrelated capabilities from different generations | `app/Contexts/Election/` vs `app/Contexts/Elections/` |
| **The governed-context marker (`Contexts/`) is not a reliable signal** | of 11 `app/Contexts/*` directories, only 4 are in deptrac and only 6 have a registered provider; the largest (Membership, 495 files) is in neither |

**The root cause is not carelessness. It is that the repository contains no recorded answer to "who owns this capability today?" — so each reader re-derives one, and the cheapest available heuristic is the folder name.** This ADR exists to put that answer on the record.

### 1.3 Relationship to existing accepted decisions

This ADR **does not compete with** and **does not restate**:

- **`ADR_20260807_1500` (ACCEPTED)** — `ElectionLifecycle` (the facade over `ElectionLifecycleEngineImpl`) is the single source of truth for election **state**; `status`/`is_active`/`state` are compatibility artifacts; Option B (complete migration) approved by the Product Owner. **That ADR decides state authority. This ADR records capability ownership and how ownership is determined.**
- **`ADR_20260806_1620` (PROPOSED)** — *"A rule that can deny a vote belongs to the Election's constitutional snapshot"*, with the corollary **"one authority per concern."**
- **`ES-005`** — repository organization: three-concern separation, folder rule, **document** placement litmus, never-a-copy.
- **DDD Tactical Governance Principles** — in particular **DMT** (dormant mechanisms classified by evidence, never by default labels) and **ASP** (absence is a decision).

> **A correction this ADR must record.** The archaeology report reached its conclusions from Levels 2–7 evidence and **did not cite `ADR_20260807_1500` or `ADR_20260806_1620`** — the two highest-ranked artifacts available. Its conclusions survive that omission (they agree with the accepted ADR), but the omission is itself an instance of the deficiency this ADR addresses: **Level-1 evidence was hard to find, so a competent investigation skipped it.** §9 AMB-1 carries the consequence.

---

## 2 · Decision (proposed)

> **Architectural authority over a capability is a matter of recorded evidence, ranked. It is never inferred from directory structure, namespace shape, class naming, or the apparent modernity of a folder. Where the record is silent, authority is UNRESOLVED and must be established or escalated — never assumed.**

Five corollaries:

**C-1 · Directory structure is evidence of organization, not of architectural authority.**
A folder named `Contexts/`, `Domain/`, or `Application/` describes where someone put a file. It does not establish which capability owns which invariant.

**C-2 · Newer-looking structure does not acquire authority by looking newer.**
A greenfield bounded-context layout, a hexagonal folder set, or a `Domain/Application/Infrastructure` triad confers no authority absent Level 1–4 evidence. Authority is granted by decision and wiring, not by shape.

**C-3 · Authority reassignment requires an explicit architecture decision.**
Moving a capability's ownership from one area to another is an architecture change requiring its own ADR/ARB authorization. **It may never occur as a side effect of implementing a business ticket.** (Consistent with the standing Development Discipline rule and `ES-002.1`.)

**C-4 · Every capability statement carries its authority class.**
The five classes are defined in §3 and are never conflated: `CURRENT AUTHORITY` · `CURRENT CAPABILITY OWNER` · `COMPATIBILITY / STRANGLER` · `FUTURE INTENTION` · `UNKNOWN`.

**C-5 · Conflicting evidence is a stop condition, not a tie to be broken by preference.**
When higher- and lower-ranked evidence disagree in a way §4.3 does not resolve, the agent records the conflict and escalates. It does not pick.

---

## 3 · The vocabulary this ADR fixes

These terms are distinguished because the refuted claim collapsed several of them into one.

| Term | Definition used here | Election example |
|---|---|---|
| **Business capability** | a thing the business does, nameable without reference to code | "open voting", "decide voter eligibility" |
| **Bounded context** | a boundary with its own model and language, whose collaboration with others is governed | per `deptrac.yaml`: Contestation · Adjudication · Election *(singular)* · Shared |
| **Subdomain** | a business area; may or may not map 1:1 to a context | Election, Membership, Governance, Finance |
| **Domain model** | the code holding business rules and vocabulary | `app/Domain/Election/` |
| **Application orchestration** | coordinates domain objects; holds no business rules of its own | `app/Application/Election/` |
| **Infrastructure adapter** | technology-facing implementation of a port | `EloquentVoterEligibilityQueryService` |
| **Compatibility seam / strangler** | deliberate, bounded, scheduled-for-removal bridge to an older generation | `LegacyElectionExistenceAdapter`; the legacy-field bridge in `transitionTo()` side effects |
| **Runtime authority** | the code that actually decides, today, in production | `ConstitutionalTransitionGuard` for command transitions |
| **Target / future architecture** | a stated intention not yet realised | *"a future greenfield lifecycle capability replaces it here"* |

**Explicitly NOT equivalences.** `folder ≠ bounded context` · `namespace ≠ bounded context` · `class ≠ aggregate` · `service ≠ domain service` · `a directory called Domain/ ≠ strategic domain ownership`.

### 3.1 The five authority classes

| Class | Means | Test |
|---|---|---|
| **CURRENT AUTHORITY** | decides this invariant in production today | remove it and the rule stops being enforced |
| **CURRENT CAPABILITY OWNER** | owns a capability's model and vocabulary, though other layers execute it | the concept is defined here and imported elsewhere |
| **COMPATIBILITY / STRANGLER** | live, deliberate, bounded, scheduled for removal | a decision record names its retirement |
| **FUTURE INTENTION** | stated in an ADR or docblock; not yet wired | no production call site |
| **UNKNOWN** | evidence insufficient | say so; do not round to the nearest guess |

**"Legacy" is not one of these classes.** It is used in this ADR only where the repository's own evidence uses it: `LegacyElectionExistenceAdapter` (self-named), the legacy state columns (`ADR_20260807_1500`), and `DeprecationPolicy`'s named replacements. Elsewhere, the DMT classification (implementation convenience · dead capability · missing intention — each by evidence) applies instead of the label.

---

## 4 · The evidence hierarchy

### 4.1 The ranking

| Level | Evidence | Why it ranks here |
|---|---|---|
| **1** | **Explicit ADR / ARB ruling / approved architecture** | a decision by the authority entitled to make it |
| **2** | **Declared architecture constraints** (`deptrac.yaml`, architecture constitution, fitness tests) | governance made executable; fails a build when violated |
| **3** | **DI / provider / runtime wiring** (`AppServiceProvider`, `config/app.php` providers) | selects which implementation actually runs |
| **4** | **Production dependency direction and call sites** | reveals upstream/downstream regardless of intent |
| **5** | **Executing implementation** | what the machine does |
| **6** | **Documentation / docblocks** | authorial intent, unverified |
| **7** | **Filesystem / namespace / naming conventions** | organization only |

### 4.2 Where the ranking must NOT be applied mechanically

The commission asked explicitly not to assume this ordering is universally correct. It is not. Four caveats, each derived from evidence in this repository — **these caveats are part of the decision, not commentary on it.**

**Caveat A · A Level-1 or Level-2 artifact is authoritative only within its declared scope. Silence is not a negative finding.**
`deptrac.yaml` analyses four paths and does **not** cover `app/Domain/`, `app/Application/` or `app/Models/` — the file itself names them *"approved external platform dependencies … intentionally outside the analysed paths TODAY."* Read mechanically, "absent from deptrac" would imply the lifecycle authority has no owner, which is absurd. **Scope must be read before rank is applied.** (This is `ASP` applied to declared architecture: the absence is a recorded decision, not a gap.)

**Caveat B · Level-1 status and modality gate its strength.**
- *Status:* `ADR_20260806_1620` is **PROPOSED**; it cannot yet outrank executing code. `ADR_20260807_1500` is **ACCEPTED**; it can.
- *Modality:* a **descriptive** ADR records what is; a **normative** ADR mandates what shall be. Conflating them is the deeper error.

**Caveat C · When an ACCEPTED normative ADR and executing code disagree, both are true — of different things. This is the most consequential rule in this ADR.**
`ADR_20260807_1500` states the legacy columns are *"decided on by nothing."* The archaeology found `Election::lifecycleState()` and `getCurrentStateAttribute()` still reading the `state` column. This is **not** a contradiction that voids either. The ADR anticipated it: its own finding was that *"the authority was declared but the migration was unfinished."* Therefore:

> **A normative ADR governs what NEW code must do. Executing code describes what HAPPENS today. The gap between them is migration debt with an owner — not a licence to ignore the ADR, and not evidence that the codebase already complies.**

An agent that reads only the ADR will wrongly believe the migration is done. An agent that reads only the code will wrongly conclude the ADR is dead. **Both readings have occurred in this repository.**

**Caveat D · Level 3 requires the binding *and* its resolution site; Level 6 is not uniformly weak.**
- A DI binding can be a **no-op**: `AppServiceProvider` binds `ElectionOnlyPolicy → ElectionOnlyPolicy` and `FullMembershipPolicy → FullMembershipPolicy` — self-registrations that prove nothing. A binding can also be **bypassed**: `ElectionLifecycleEngine` is bound as an interface, yet every production site resolves `ElectionLifecycleEngineImpl` concretely, so the seam is not load-bearing. **Level 3 = binding + actual resolution site.**
- Docblocks are weak as *claims about the system* but strong as evidence of *intent* — and a docblock that **disclaims** its own authority is unusually reliable, because it argues against its author's interest. `LegacyElectionExistenceAdapter`'s ACL comment is the decisive artifact in this whole investigation, and it is Level 6.

### 4.3 Resolution procedure when levels conflict

1. Check **scope** (Caveat A). Out-of-scope silence is not evidence.
2. Check Level-1/2 **status and modality** (Caveat B). PROPOSED does not outrank executing code.
3. If an ACCEPTED **normative** artifact conflicts with executing code → **both hold** (Caveat C): the artifact governs new code; the code describes today; the delta is migration debt. Record it as such; **do not "fix" it inside an unrelated ticket** (C-3).
4. If an ACCEPTED **descriptive** artifact conflicts with executing code → the artifact is **stale**. Report it. Do not silently trust either.
5. Otherwise the higher level wins, and the disagreement is recorded as a finding.
6. If none of 1–5 resolves it → **UNRESOLVED. Stop and escalate.** Do not choose by preference (C-5).

---

## 5 · Current Election authority matrix

Every row is from the archaeology's evidence. **Confidence** states what the evidence supports, not how sure the author feels. `deptrac` = whether a declared-architecture constraint covers it.

| Capability | Current owner | Layer | Evidence | Confidence | deptrac | Class |
|---|---|---|---|---|---|---|
| **Election lifecycle** (which transitions exist; roles; preconditions) | `app/Domain/Election/Constitution/ElectionConstitution::RULES` | Domain | sole definition of every action incl. `open_voting`; nothing else declares transitions | **HIGH** | ✗ (out of scope — Caveat A) | CURRENT AUTHORITY |
| **Election transition authorization** | `app/Application/Election/Services/ConstitutionalTransitionGuard` | Application | only file calling `getPreconditionsForAction()`; invoked from `Election::transitionTo()` L1650 | **HIGH** | ✗ | CURRENT AUTHORITY |
| **Voting state derivation** | `app/Application/Election/Services/ElectionLifecycleEngineImpl::getState()` | Application | bound to the Domain port (`AppServiceProvider:146`); resolved at every read site; **and `ADR_20260807_1500` (ACCEPTED) names the facade over it the SSOT** | **HIGH — Level 1 + 3 + 5 agree** | ✗ | CURRENT AUTHORITY |
| **State consumption API** | `app/Application/Election/Facades/ElectionLifecycle` | Application | `ADR_20260807_1500` Decision; used by controllers and middleware | **HIGH — Level 1** | ✗ | CURRENT AUTHORITY |
| **State persistence (`state` column)** | `Election::transitionTo()` L1670, sole writer | Model | *"only place in codebase"*; `ADR_20260807_1500` classes the columns as compatibility artifacts on an approved Option-B retirement path | **HIGH** | ✗ | COMPATIBILITY / STRANGLER |
| **Election-Only mode vocabulary** | `app/Domain/Election/Enum/VoterSourceStrategy` | Domain | sole definition of `election_only` / `full_membership`; snapshotted immutably per election; `fromElection()` throws rather than defaulting | **HIGH** | ✗ | CURRENT CAPABILITY OWNER *(case names `@deprecated` pending Phase-4 governance review — the **names** are FUTURE INTENTION, the **concept** is current)* |
| **Election-Only eligibility decision** | `Contexts/Elections/Domain/Policies/ElectionOnlyPolicy::decideForContext()`, behind the port `VoterEligibilityPolicy` → `EloquentVoterEligibilityQueryService` | Context Domain + Infra | DI-bound (`AppServiceProvider:141`); reached via constructor injection | **HIGH for `decideForContext()`** — **but PENDING independent falsification (handoff F6); this row must not be treated as final until Session 1 re-derives the runtime path** | ✗ | CURRENT AUTHORITY (**narrow** — the eligibility *decision* only; NOT Election-Only architecture ownership, and no claim that Election-Only is complete while entitlement authority is unresolved, U-2) |
| " (same port, dead members) | `…Policies::isEligible()` / `qualifyingSubset()` | Context Domain | never invoked in production; `isEligible()` is a Phase-A stub returning `true` | **HIGH that they are dormant** | ✗ | FUTURE INTENTION / dormant — classify per **DMT**, not as "legacy" |
| **Eligible-voter listing** | `App\Services\VoterEligibilityService::unassignedEligibleQuery()` | Service | own mode branch, own SQL — a **second** implementation of the same concern | **HIGH that it exists; UNKNOWN whether semantically equivalent** | ✗ | CURRENT AUTHORITY (duplicate — see §7) |
| **Voter assignment** | `Contexts/Elections/Application/Handlers/{AssignVoter,BulkAssignVoters}Handler` | Context App | injected into `ElectionVoterController` | **HIGH** | ✗ | CURRENT AUTHORITY |
| **Adjudication → Election correction** | `app/Contexts/Election/Domain/Election` (singular) aggregate | Context Domain | provider-registered (`config/app.php:212`); **deptrac-governed**; forward-only + idempotent per ADR-T8/T11/T16 | **HIGH — Level 1 + 2 + 3** | ✅ | CURRENT AUTHORITY |
| **Election existence ACL** | `Contexts/Election/Infrastructure/Acl/LegacyElectionExistenceAdapter` | Context Infra | read-only, tenant-scoped, soft-delete aware; provider-bound as the `ElectionExistencePort` strangler seam | **HIGH** | ✅ | COMPATIBILITY / STRANGLER — *and the explicit statement that lifecycle authority lies outside the greenfield context* |

**The matrix's central asymmetry, stated plainly:** the capabilities carrying the most business risk (lifecycle, transitions, voting state) are **CURRENT AUTHORITY but outside every declared-architecture constraint**; the capability carrying the least (adjudication correction) is **fully governed**. This is a factual observation about coverage. **It is not a recommendation to extend deptrac** — that would be an architecture decision (C-3), and it is registered as ambiguity AMB-3.

---

## 6 · What this ADR explicitly does NOT decide

- **Not** that `app/Domain/Election` or `app/Application/Election` is legacy, transitional, or slated for migration. No evidence supports it; `ADR_20260807_1500` treats the engine as the authority.
- **Not** that `app/Contexts/Elections` should become the Election bounded context. **Answer to that question: NO, absent an explicit ADR** — and this is not that ADR.
- **Not** that `app/Models/Election.php` (2,279 lines) should be decomposed.
- **Not** whether `deptrac.yaml` coverage should expand (AMB-3).
- **Not** whether `Election`/`Elections` should be renamed (AMB-2).
- **Not** the disposition of the duplicate authorities in §7 — each needs its own decision.
- **Not** EM-OPEN-021 (fall-through semantics), EM-VOT-002, entitlement, `STRICT_LEVEL`, `whyCannotOpenVoting()`, dead-class removal, or the duplicate results events.
- **Not** a target architecture of any kind.

---

## 7 · Consequences if accepted

**Wanted**

- The question *"who owns this capability?"* has a recorded answer, so it is looked up rather than re-derived — and re-derived wrongly.
- The refuted claim cannot recur silently: it is contradicted on the record, with evidence.
- EM-VOT-002's placement is confirmed correct by rule, not by luck. `ElectionConstitution` (rule) + guard (command path) + engine (computed path) is exactly what the hierarchy yields (§8, Case 1).
- Caveat C gives agents a defensible reading of the accepted-ADR/code gap that `ADR_20260807_1500` created deliberately and that has already misled at least one analysis.

**Unwelcome, and stated plainly**

- **This ADR makes existing duplicate authorities visible without resolving them.** Naming a problem creates an expectation of repair; the repair is separate authorized work. The known set:

  | Concern | Competing authorities | Consistent? |
  |---|---|---|
  | approved-candidate-required-for-voting | constitution precondition ≡ engine `hasCandidatesApproved()`; **but** `Election::whyCannotOpenVoting()` tests `candidates_count` / `pending_candidacies_count` | **NO — semantically different** |
  | current state | column readers vs engine readers | diverge by design (`ADR_20260807_1500`) |
  | eligible-voter query | `VoterEligibilityService` vs `EloquentVoterEligibilityQueryService` | **UNKNOWN — never differentially tested** |
  | results publication | `Contexts\Elections\…\ResultsPublishedEvent` (dispatched, **no listener**) vs `Domain\Election\Events\ResultsPublished` (**never dispatched**) | duplicate vocabulary, neither wired end-to-end |
  | state vocabulary | `ElectionLifecycleState` (live, 12 cases) vs `ElectionState` (dead, 7 cases, disjoint) | **NO — disjoint** |

- **`ADR_20260806_1620` §6 recorded "one authority per concern" as an observation at n=1** (votes-per-IP), explicitly **NOT promoted**, with a second independent occurrence as the promotion precondition (`ES-006.1`). The table above is a **candidate second occurrence** in a different concern (election lifecycle rules). **This ADR records that and promotes nothing** — promotion is `ES-006.1` Human-decides, and Session 2 owns the judgement.
- **A recorded authority map becomes stale.** It needs a maintenance owner, or it becomes the next misleading artifact — exactly the failure mode `ADR_20260807_1500` documents ("authority moves, consumers stay"). AMB-4.
- **Reading seven evidence levels costs more than reading a folder name.** The cost is the point; it should not be pretended away. §8 Case 4 is the case where it pays for itself.

---

## 8 · Validation — the commission's six cases

Each case must yield a deterministic outcome. Worked with the §4 hierarchy and the §4.3 procedure.

**Case 1 — "Where should EM-VOT-002 (approved candidate required before voting) live?"**
→ L1: no ADR names an owner for this rule. L2: silent, out of scope (Caveat A). L3/L4/L5: `ElectionConstitution::RULES` is the sole declaration of transition preconditions; `ConstitutionalTransitionGuard` is the sole evaluator; `ElectionLifecycleEngineImpl::getState()` is the sole computed-state authority and is reachable by **no** command guard.
**Outcome: rule text → `ElectionConstitution` (Domain). Command enforcement → the guard. Computed enforcement → engine rule 5. Both enforcement points are required because the two paths cannot reach each other. NOT `Contexts/Elections` — it is downstream of the rule's owner and unreachable from either path.** This matches `f2c2cc4e` as implemented.

**Case 2 — "Where should Election-Only eligibility logic live?"**
→ L3: the port is DI-bound to `EloquentVoterEligibilityQueryService`, which injects `ElectionOnlyPolicy`. L4: `App\Services\VoterEligibilityService` consumes the port. L5: `decideForContext()` executes.
**Outcome: the eligibility *decision* → `Contexts/Elections/Domain/Policies` (current authority, narrow). The *mode vocabulary* stays in `Domain/Election/Enum/VoterSourceStrategy` — it is owned there and imported by the context, and the dependency direction forbids the reverse.** Note the duplicate listing query (§7) — extending it is not covered by this outcome and needs its own decision.

**Case 3 — "Should `app/Contexts/Elections` automatically become the Election bounded context?"**
→ L1: no ADR authorizes it. L2: absent from `deptrac.yaml`, unlike the three governed contexts. L3: no ServiceProvider of its own. L4: strictly downstream of `Domain/Election`; nothing depends on it but HTTP controllers.
**Outcome: NO. Every level from 1 to 4 declines, and C-2 forbids granting authority for looking like a context. It is a single-capability strangler slice. Becoming the Election BC would require an explicit ADR — which would first have to address that lifecycle authority currently sits outside it.**

**Case 4 — "A developer wants to move `Domain/Election` into `Contexts/Election` because `Contexts` is the newer DDD architecture."**
→ The stated reason is a Level-7 argument ("newer", "more DDD"). C-2 rejects it outright. Additionally: L1 `ADR_20260807_1500` (ACCEPTED) treats the engine as the SSOT with no migration mandate; the target context's own ACL states it does **not** own the lifecycle; and the move would invert the proven dependency direction.
**Outcome: STOP. Do not move. The agent (a) states that folder modernity is not authority evidence, (b) reports that the destination disclaims lifecycle ownership in its own source, (c) notes that such a migration requires its own ADR (C-3), and (d) if the developer still wants it, escalates as an architecture proposal — never executes it inside a business ticket.** This is the case that motivated this ADR.

**Case 5 — "Two implementations exist. Folder names disagree with runtime DI. Which evidence wins?"**
→ **DI (Level 3) wins over naming (Level 7)** — but only with Caveat D applied: confirm the binding is not a self-registration and that a production site actually resolves it. If the binding is a no-op (as with `ElectionOnlyPolicy → ElectionOnlyPolicy`), it is **not** Level-3 evidence and the question falls to Level 4/5.
**Outcome: runtime authority beats folder name, once the binding is verified live. Record the loser as a duplicate authority (§7); do not delete it inside an unrelated ticket.**

**Case 6 — "The architecture documents disagree with the executing code. Should the agent silently choose one?"**
→ **Never** (C-5). Apply §4.3: check scope, then status and modality. If the document is ACCEPTED and normative, **both hold** (Caveat C) — the document governs new code, the code describes today, and the delta is migration debt to be reported. If it is descriptive, it is stale and that is the finding. If PROPOSED, it does not yet outrank code.
**Outcome: report the conflict with its classification. Silence is the one forbidden response.** This case is live in this repository right now: `ADR_20260807_1500` says the legacy columns are decided on by nothing, and two model methods still read them.

---

## 9 · Ambiguities requiring human architecture decision

| # | Ambiguity | Why engineering cannot decide it | Suggested authority |
|---|---|---|---|
| **AMB-1** | **Where does an agent *find* Level-1 evidence?** ADRs are spread across `docs/adr/`, `docs/publicdigit/adr/`, `engineering/architecture/adr/` with four naming schemes and no index of *which capability each governs*. This session's own archaeology missed two applicable ADRs. Without a resolution, the hierarchy's top level is the hardest to consult — and the rule fails in exactly the way it was written to prevent. | Creating an index is a documentation-architecture change, frozen per the methodology freeze unless deficiency is shown. **Deficiency is now shown** (a competent investigation missed Level-1 evidence) — but invoking the exception is a governance call. | ARB / Session 2 |
| **AMB-2** | `Election` vs `Elections` naming collision | a rename is an authority-affecting change (C-3); it also touches namespaces across 34 files | ARB |
| **AMB-3** | Should declared-architecture coverage (deptrac) extend to `app/Domain`, `app/Application`, `app/Models`? | `deptrac.yaml` states its scope is a deliberate ARB decision with a recorded future refinement; changing it changes the approved model | ARB |
| **AMB-4** | Who maintains the §5 authority matrix, and when is it revalidated? | an unmaintained map becomes the next misleading artifact | Product Owner / ARB |
| **AMB-5** | Is "one authority per concern" now at **n=2** and therefore promotable? | `ES-006.1` is explicitly Human-decides; engineering recommends only | ARB (via Session 2) |
| **AMB-6** | Is there one ADR identifier convention, or four legitimate series? | consolidating would be a convention change; this ADR followed the local `docs/publicdigit/adr/` timestamp convention rather than invent a global sequence | ARB |
| **AMB-7** | Does the `VoterSourceStrategy` Phase-4 vocabulary review have an owner and a trigger? | the enum's case names are `@deprecated` pending a governance-language review with no scheduled decision point | Product Owner |

---

## 10 · Status of every claim in this ADR

**ESTABLISHED** (evidence cited, reproducible)
- The refuted claim is false; `Contexts/Elections` is downstream, single-capability, unregistered, ungoverned.
- Lifecycle authority is `Domain/Election` + `Application/Election` + `Models/Election`, corroborated by ACCEPTED `ADR_20260807_1500`.
- `Contexts/Election` (singular) is provider-registered, deptrac-governed, owns adjudication correction, and disclaims lifecycle ownership in its own source.
- Election-Only mode is defined in `Domain/Election`, selected once at creation, enforced only across eligibility/assignment/import.
- The duplicate authorities in §7 exist; `whyCannotOpenVoting()` and the two state vocabularies are semantically divergent.
- The four caveats in §4.2 are each grounded in a specific artifact in this repository.

**INFERRED** (reasoned from evidence, not directly stated by it)
- That the evidence hierarchy's *ordering* is correct. It is a proposal justified by the caveats, not a proven ranking.
- That folder-name inference was the *root cause* of the refuted claim. Consistent with the artifact, but the author's reasoning was not observed.
- That §7's table constitutes a second independent occurrence for `ES-006.1`. Argued; **not** adjudicated.
- That AMB-1 rises to the level of a methodology-freeze exception. Argued; ARB decides.

**UNDECIDED** (requires authority beyond engineering)
- All of AMB-1 … AMB-7.
- Every item in §6.
- Whether this ADR is accepted at all.

---

**Traceability:** archaeology report `docs/publicdigit/reviews/2026-08-13-election-context-architecture-archaeology.md` · `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth` (ACCEPTED) · `ADR_20260806_1620_Constitutional_Rule_Ownership_Migration` (PROPOSED) · `ADR_20260806_1520_Demo_And_Production_Policy_Contexts` · `ADR_20260801_1740_Documentation Roots and Artifact Placement` · `ES-001.1` (rule parsimony) · `ES-002.1` · `ES-004.2` · `ES-005.1–.4` · `ES-006.1` (promotion ladder) · DDD Tactical Governance Principles (`ASP`, `ADP`, `DMT`) · `AIP-14` (Product Primacy) · `R-34` (engineering never accepts its own work) · `deptrac.yaml` (ARB / PB-007) · `PBDIGIT-64` (EM-VOT-002) · `PBDIGIT-68` · EM-OPEN-021 (open, untouched)
