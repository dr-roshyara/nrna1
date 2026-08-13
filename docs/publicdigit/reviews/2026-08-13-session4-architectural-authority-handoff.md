# Session 4 — Architectural Authority Handoff

**Date:** 2026-08-13 · **Session:** 4 (Architecture Archaeology & Governance Preparation) · **Branch:** `election-review` @ `d5efff2a`
**Status of this report:** FINAL for Session 4. This session's architecture work is **closed**.
**Mode:** read-only throughout. Zero tracked changes under `app/ tests/ routes/ database/ config/ engineering/ bootstrap/ deptrac.yaml` — verified by `git diff --name-only`.
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).

> **Engineering supplies evidence and never accepts its own work (`R-34`).** This session neither accepts its ADR nor promotes its PKS candidate. Both statuses are unchanged and must remain so until governance rules.

---

## Artifacts produced by Session 4

| # | Artifact | Path | Status — MUST NOT CHANGE |
|---|---|---|---|
| 1 | Election Architecture Archaeology (evidence base) | `docs/publicdigit/reviews/2026-08-13-election-context-architecture-archaeology.md` | FINAL · verdict *CURRENT ARCHITECTURE ESTABLISHED* |
| 2 | **ADR** — Current Architecture Authority and Code Placement | `docs/publicdigit/adr/ADR_20260813_1722_Architecture_Authority_And_Code_Placement.md` | 🟡 **PROPOSED** |
| 3 | **PKS** — Code Ownership and Architectural Placement / Evidence Hierarchy | `docs/pks/2026-08-13-architectural-authority-evidence-hierarchy-candidate.md` | **CANDIDATE · research · n=1 · NOT PROMOTED · NOT AUTHORITATIVE** |
| 4 | This handoff | `docs/publicdigit/reviews/2026-08-13-session4-architectural-authority-handoff.md` | FINAL |

Their jobs stay separate: **(1) evidence · (2) architectural finding + proposed decision · (3) repeatable procedure for future agents.** They are not to be merged.

---

## 1 · What was established

Evidence cited and reproducible in artifact 1.

| # | Finding | Basis |
|---|---|---|
| E-1 | **The claim *"`app/Contexts/Elections/Domain/` is authoritative for Election rules"* is FALSE.** | E-2…E-5 |
| E-2 | `app/Contexts/Elections/` (plural) is **strictly downstream** of `app/Domain/Election/`. `app/Domain/` and `app/Application/` contain **zero** references to `App\Contexts`. | grep, both directions |
| E-3 | It owns **one** capability — voter eligibility decision + voter assignment — in 15 files, has **no ServiceProvider** of its own, and is **absent from `deptrac.yaml`**. | `config/app.php`, `deptrac.yaml`, file census |
| E-4 | A **different** directory, `app/Contexts/Election/` (singular), is provider-registered (`config/app.php:212`) and deptrac-governed. It owns *adjudication → election correction*, not lifecycle. | provider, deptrac layers, aggregate source |
| E-5 | That governed context **disclaims lifecycle ownership in its own source**: the legacy `elections` table is *"the CURRENT operational source of truth, until a greenfield Election-lifecycle capability replaces it (Strangler)."* | `LegacyElectionExistenceAdapter` docblock |
| E-6 | **Lifecycle authority** = `Domain/Election` (constitution, port, vocabulary) + `Application/Election` (engine, guard, façade) + `Models/Election.php` (aggregate, sole state write). | DI binding `AppServiceProvider:146`, call sites, code read — **and `ADR_20260807_1500` (ACCEPTED) at Level 1** |
| E-7 | **Election-Only mode is defined in `Domain/Election/Enum/VoterSourceStrategy`**, selected once at creation (`ElectionManagementController::store():163`), immutable thereafter, and enforced **only** across voter eligibility, assignment and import. | sole definition; 0 mode refs in candidacy/vote/code controllers |
| E-8 | The lifecycle is **hybrid**: a command path (guard → constitution) and a computed path (engine over business facts) that **cannot reach each other**. The `state` column is a compatibility cache, not truth. | code read; `ADR_20260807_1500` |
| E-9 | Duplicate authorities exist (§6). `whyCannotOpenVoting()` and the two state vocabularies are **semantically divergent**; the eligibility pair is **equivalence-unknown**. | code read |
| E-10 | The computed fall-through (window open, zero approved candidates) reaches a terminal `throw`, so `canVote()` **raises rather than denies**. | `getState()` read; corroborates `0822f333` |
| E-11 | `STRICT_LEVEL = 1` is a compiled-in constant: the state write barrier **records** unauthorized writes and **allows** them. | `DeprecationPolicy:52` |
| E-12 | Both results-publication events are dispatched with **no listener**; a third parallel event class is **never dispatched**. | grep of dispatch + `EventServiceProvider` |

---

## 2 · What was corrected

**Corrections are retained deliberately, not hidden.** Each is evidence for why canonical Level-1 discovery matters.

| # | Correction | Detail |
|---|---|---|
| **C-A** | **Missed Level-1 ADR evidence.** | The archaeology reached its conclusions from Levels 2–7 and **did not cite `ADR_20260807_1500`** (ACCEPTED), which already names the `ElectionLifecycle` façade the SSOT for election state. The conclusions **agree** with it — but that is luck, not rigour. The lifecycle conclusion must be presented as **Level 1 + 3 + 5 agreeing**, never as a Levels 2–7 discovery. Carried as **AMB-1**. |
| **C-B** | **R-9 downgraded MEDIUM-HIGH → LOW; recommendation 3b WITHDRAWN.** | `STRICT_LEVEL = 1` is **deliberate, governed staging**, not an unfinished rollout: `ADR_20260807_1500` step 4 makes level-by-level escalation *"with zero violations"* the mechanism that **proves** the approved Option-B migration, gated on `PBDIGIT-59`. **Do not reopen** absent new contradictory evidence. |
| **C-C** | **`ADR_20260806_1620` is PROPOSED, not accepted.** | Its §6 *"one authority per concern"* is an observation at **n=1** (votes-per-IP), explicitly **not promoted**. **It must not be cited as binding.** §6 below is a *candidate* second occurrence — recorded, not adjudicated (**AMB-5**). |
| **C-D** | **`ElectionOnlyPolicy` is not caller-less.** | An earlier reading reported zero production callers; the grep had excluded the directory containing the live caller. Accurate statement: **`decideForContext()` is live** (injected into `EloquentVoterEligibilityQueryService`); **`isEligible()` is a dormant Phase-A stub returning `true`**. |
| **C-E** | **Placement was derived, not chosen.** | `docs/architecture/adr/` — the path originally specified — **does not exist**. A cross-product classification returned **exit 2 / PENDING (unruled)**; the honest product-specific classification resolved cleanly. No path was hard-coded. |
| **C-F** | **No ADR number was invented.** | `docs/publicdigit/adr/` uses a **timestamp** convention, not a sequence; the repository holds four ADR series across three directories, so **no global next number exists** (**AMB-6**). |

---

## 3 · What remains PROPOSED (Session 2 decides)

The ADR's decision and its five corollaries:

> **Architectural authority over a capability is a matter of recorded evidence, ranked. It is never inferred from directory structure, namespace shape, class naming, or the apparent modernity of a folder. Where the record is silent, authority is UNRESOLVED and must be established or escalated — never assumed.**

| Corollary | Statement |
|---|---|
| **C-1** | Directory structure is evidence of organization, not of architectural authority. |
| **C-2** | Newer-looking structure does not acquire authority by looking newer. |
| **C-3** | Authority reassignment requires an explicit architecture decision — **never a side effect of a business ticket**. |
| **C-4** | Every capability statement carries its authority class; the five are never conflated. |
| **C-5** | Conflicting evidence is a **stop condition**, not a tie broken by preference. |

Also PROPOSED: the five authority classes (`CURRENT AUTHORITY` · `CURRENT CAPABILITY OWNER` · `COMPATIBILITY/STRANGLER` · `FUTURE INTENTION` · `UNKNOWN`), the rejection of *"legacy"* as an authority class, and the §5 authority matrix.

---

## 4 · What remains CANDIDATE (not usable as authority)

The PKS procedure — 10 steps, 4 caveats, 6 worked outcomes.

```
1 Name the business capability      6 Check dependency direction / call sites
2 State the invariant at stake      7 Read the executing implementation
3 Search ALL ADR/ARB evidence       8 Classify the authority (5 classes)
4 Check declared architecture       9 Resolve conflicts explicitly (§4.1)
  (check SCOPE first)              10 If unresolved → STOP and escalate
5 Check DI + verified resolution
```

**Four caveats that must survive any revision:**

- **A · Scope before rank.** `deptrac.yaml` deliberately excludes `app/Domain`, `app/Application`, `app/Models`. **"Not in deptrac" ≠ "not governed."**
- **B · Status and modality gate Level 1.** PROPOSED cannot outrank running code; *descriptive* ≠ *normative*.
- **C · ACCEPTED normative ADR vs executing code → BOTH true, of different things.** The ADR governs **new** code; the code describes **today**; the delta is **migration debt**. Do not "resolve" it by changing code, do not declare the ADR false, do not declare the migration complete.
- **D · Level 3 = binding **+** verified production resolution.** Detect self-bindings (`ElectionOnlyPolicy → ElectionOnlyPolicy`), dead bindings, bindings bypassed by concrete construction (`ElectionLifecycleEngineImpl`), and test-only resolution. Level 6 is **not** uniformly weak: a docblock that **disclaims** its own authority is unusually reliable — and was the decisive artifact here.

**Framing:** this is an **evidence-resolution procedure**, not an absolute precedence law. Collapsing it back into bare precedence is a regression.

**DDD anti-equivalences retained:** `folder ≠ bounded context` · `namespace ≠ bounded context` · `class ≠ aggregate` · `Domain/ ≠ strategic ownership` · `service ≠ domain service` · `similar names ≠ same capability` · `newer architecture ≠ authoritative architecture`.

**Not done, deliberately:** no ES number, no Standards-Index edit, no ES-007, no CLAUDE.md change, no hooks, no CI gate, no blocking script, no architecture-authority checker. The candidate's own reasoning is preserved: automating only Levels 2–5 and 7 would enforce the **weakest** levels while skipping the strongest, manufacturing false confidence. **If promoted, a reporting instrument is recommended over a blocking gate — a recommendation, not a decision.**

---

## 5 · Authority matrix (condensed — full version in the ADR §5)

| Capability | Current owner | Class | Confidence | deptrac |
|---|---|---|---|---|
| Election lifecycle rules (transitions, roles, preconditions) | `Domain/Election/Constitution/ElectionConstitution::RULES` | CURRENT AUTHORITY | HIGH | ✗ (out of scope) |
| Transition authorization | `Application/Election/Services/ConstitutionalTransitionGuard` | CURRENT AUTHORITY | HIGH | ✗ |
| Voting state derivation | `Application/Election/Services/ElectionLifecycleEngineImpl::getState()` | CURRENT AUTHORITY | **HIGH — L1+L3+L5 agree** | ✗ |
| State consumption API | `Application/Election/Facades/ElectionLifecycle` | CURRENT AUTHORITY | HIGH — L1 | ✗ |
| State persistence (`state` column) | `Election::transitionTo()` L1670, sole writer | COMPATIBILITY / STRANGLER | HIGH | ✗ |
| Election-Only mode vocabulary | `Domain/Election/Enum/VoterSourceStrategy` | CURRENT CAPABILITY OWNER *(case names are FUTURE INTENTION pending Phase-4)* | HIGH | ✗ |
| Election-Only eligibility decision | `Contexts/Elections/Domain/Policies/ElectionOnlyPolicy::decideForContext()` behind the DI-bound port | CURRENT AUTHORITY (narrow) | HIGH — **but F6 must re-derive it** | ✗ |
| … same port, dead members (`isEligible()`, `qualifyingSubset()`) | `Contexts/Elections/Domain/Policies` | FUTURE INTENTION / dormant — classify per **DMT** | HIGH that they are dormant | ✗ |
| Eligible-voter listing | `App\Services\VoterEligibilityService::unassignedEligibleQuery()` | CURRENT AUTHORITY (duplicate) | **SEMANTIC EQUIVALENCE UNKNOWN** | ✗ |
| Voter assignment | `Contexts/Elections/Application/Handlers/*` | CURRENT AUTHORITY | HIGH | ✗ |
| Adjudication → election correction | `Contexts/Election/Domain/Election` (singular) | CURRENT AUTHORITY | HIGH — L1+L2+L3 | ✅ |
| Election existence ACL | `Contexts/Election/Infrastructure/Acl/LegacyElectionExistenceAdapter` | COMPATIBILITY / STRANGLER | HIGH | ✅ |

**The matrix's central asymmetry:** the capabilities carrying the **most** business risk are current authority but **outside every declared-architecture constraint**; the one carrying the least is fully governed. A factual coverage observation — **not** a recommendation to extend deptrac (**AMB-3**).

---

## 6 · Duplicate-authority findings — classified, NOT repaired

| Concern | Competing implementations | Classification |
|---|---|---|
| approved-candidate-required-for-voting | constitution precondition ≡ engine `hasCandidatesApproved()`; **`Election::whyCannotOpenVoting()`** tests `candidates_count` / `pending_candidacies_count` | **SEMANTICALLY DIVERGENT** — the third is a different rule, not a copy |
| current state | column readers (`lifecycleState()`, `getCurrentStateAttribute()`) vs engine readers | **COMPATIBILITY / STRANGLER** — named migration debt under ACCEPTED `ADR_20260807_1500` |
| eligible-voter query | `VoterEligibilityService` vs `EloquentVoterEligibilityQueryService` | **SEMANTIC EQUIVALENCE UNKNOWN** — never differentially tested |
| results publication | `Contexts\Elections\…\ResultsPublishedEvent` (dispatched, no listener) vs `Domain\Election\Events\ResultsPublished` (never dispatched) | duplicate vocabulary; **neither wired end-to-end** |
| state vocabulary | `ElectionLifecycleState` (live, 12 cases) vs `ElectionState` (dead, 7 cases) | **DISJOINT** — dead per DMT evidence |
| `Election` vs `Elections` | two sibling directories, unrelated capabilities | naming collision (**AMB-2**) |

**"SEMANTIC EQUIVALENCE UNKNOWN" is a valid architectural result** and is recorded as such rather than rounded to "duplicate". **None of these is repaired, consolidated, or deleted by this session.** Retirement is evidence-led: a path retires when evidence shows what retiring it changes — not because it looks redundant.

**`ES-006.1` note:** the divergences above are a **candidate second occurrence** of *"one authority per concern"* (n=1 in `ADR_20260806_1620` §6). **Recorded, not promoted** — promotion is Human-decides (**AMB-5**).

---

## 7 · Election-Only implications

The two concerns stay separate. This is the operational payload of the whole commission.

```
              ELECTION-ONLY                        ELECTION LIFECYCLE
                    │                                      │
      ┌─────────────┴─────────────┐              Domain/Election
      │                           │              ElectionConstitution
 Mode vocabulary          Eligibility decision            │
      │                           │              ConstitutionalTransitionGuard
 Domain/Election          Contexts/Elections               │
 VoterSourceStrategy      ElectionOnlyPolicy      ElectionLifecycleEngineImpl
      │                           │                        │
      └─────────────┬─────────────┘              ElectionLifecycle (facade)
                    │
            eligibility port
                    │
       infrastructure query (Eloquent)
                    │
             voter assignment
```

**Consequences for implementation:**

- Election-Only is **not** "in `Contexts/Elections`", and **not** "in `Domain/Election`". It is split by concern, and the split is evidence-backed.
- Mode vocabulary **cannot** move into `Contexts/Elections` — dependency direction forbids it (the context imports the enum).
- Election-Only's implemented footprint is **only**: creation-time snapshot → eligibility → assignment → import → UI/telemetry vocabulary. Lifecycle, nomination, candidacy, voting, credentials and results are **mode-independent**.
- **Entitlement has no located production authority** (**U-2**) — an untracked `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` exists. This is the largest open gap in an Election-Only completeness claim.

---

## 8 · EM-VOT-002 status — CLOSED, do not reopen

`ADOPTED · IMPLEMENTED (f2c2cc4e) · AUTHORIZED · both enforcement paths independently verified`

| Layer | Location |
|---|---|
| Business rule | `Domain/Election/Constitution/ElectionConstitution::RULES['open_voting']['preconditions']` → `has_approved_candidates` |
| Command enforcement | `Application/Election/Services/ConstitutionalTransitionGuard::validatePreconditions()` |
| Computed enforcement | `Application/Election/Services/ElectionLifecycleEngineImpl::getState()` rule 5 |

**Placement CONFIRMED CORRECT.** Both enforcement points are required because the command path and the computed path cannot reach each other (E-8). **Do not relocate this rule into `Contexts/Elections`** — it is downstream of the rule's owner and unreachable from either path. **The implementation location is not reopened by this session or by these artifacts.**

---

## 9 · EM-OPEN-021 status — OPEN, deliberately undecided

```
technical current behaviour = OBSERVABLE and understood  (E-10: derivation falls through to a terminal throw)
business semantics          = UNDECIDED
```

**No fallback was chosen.** Not `setup_nomination`, not a holding state, not a warning, not extension, not closure, not a new lifecycle state, not a precedence change. The in-code comment states the position accurately: *"fallback semantics are an open PO decision."*

This is **a decision, not missing archaeology**. It blocks EM-VOT-002's *completion*, not its correctness. Sequence when decided: Decision → RED → GREEN → certification.

---

## 10 · Falsification targets for Session 1

**Mission: falsify, not confirm.** No production changes. No test changes. No cleanup. Run the §4 procedure against real Election capabilities and record, per capability: *invariant · L1–L5 evidence · classification · conflict? · procedure sufficient? · falsification?*

| # | Target |
|---|---|
| **F1** | Find a capability the procedure classifies **incorrectly**. |
| **F2** | Find a capability where it yields **UNKNOWN** though authority is genuinely obvious — i.e. it is uselessly conservative. |
| **F3** | Find a **fifth caveat class** the four do not cover. |
| **F4** | Find cases where **Levels 3–7 conflict** in a way §4.1 cannot resolve. |
| **F5** | Find a legitimate case where **executing code should outrank an ACCEPTED normative ADR**. |
| **F6** | **HIGHEST PRIORITY.** Independently re-derive the runtime authority for the **Election-Only eligibility decision** — do not accept this session's answer. By actual runtime path, not grep alone: **who calls** `ElectionOnlyPolicy`, `VoterEligibilityPolicy`, `EloquentVoterEligibilityQueryService`, `VoterEligibilityService` — and **which** path serves voter verification · voter import · voter assignment · voting access · eligibility listing. Classify each: CURRENT AUTHORITY / DUPLICATE / DORMANT / COMPATIBILITY. |

Suggested capability set: election lifecycle & state · `open_voting` · Election-Only mode · Election-Only eligibility · voter assignment · voter import · voting access · candidacy · results publication · state persistence · transition authorization.

---

## 11 · Governance questions for Session 2

**Session 4 must not answer these.** Session 2 should not redo the archaeology — its job is governance.

| # | Question |
|---|---|
| G-1 | Is the ADR acceptable as a record of current authority? |
| G-2 | Is the evidence sufficient to establish the authority map? |
| G-3 | Is the evidence-resolution procedure acceptable **as a proposal**? |
| G-4 | Should the candidate extend **`ES-005`** (engineering's recommendation), extend `ES-002`, or neither? |
| G-5 | Should it remain **research-only**? |
| G-6 | Is a **second independent adopter** genuinely required before promotion (`ES-006.1`), or does the evidence here suffice? |
| G-7 | Does **AMB-1** (Level-1 ADR discovery is hard — this session's own investigation missed two applicable ADRs) trigger the methodology-freeze exception *"unless PublicDigit implementation exposes a genuine deficiency"*? |
| G-8 | Which authority-matrix rows require explicit governance decisions before Session 3 may touch them? |
| G-9 | **Which parts, if any, may be used as *temporary working instructions* without being called an adopted standard?** |
| G-10 | Is the §6 divergence set a valid **n=2** for *"one authority per concern"* (AMB-5)? |

**Ambiguities carried forward:** AMB-1 (ADR discoverability) · AMB-2 (`Election`/`Elections` naming) · AMB-3 (deptrac coverage) · AMB-4 (who maintains the authority matrix) · AMB-5 (n=2 promotion) · AMB-6 (ADR identifier conventions) · AMB-7 (`VoterSourceStrategy` Phase-4 owner and trigger). **AMB-1, AMB-4, AMB-5 and AMB-7 are the ones bearing directly on Election-Only.**

On AMB-1 specifically: **no registry, index, naming standard, new PKS or automated gate was invented.** The observation is recorded; whether the existing methodology permits an improvement is governance's call.

---

## 12 · Explicitly forbidden implementation actions

Binding on **all** sessions until governance rules otherwise.

**Must not, on the authority of these artifacts:**

- move `app/Domain/Election`, `app/Application/Election`, or `app/Contexts/Elections`;
- merge or rename `Election` / `Elections`; rename any directory or namespace;
- delete or consolidate "duplicate" implementations — including the eligibility pair, the results events, `ElectionState`, `TransitionMatrix`, `ElectionStateMachine`;
- repair `whyCannotOpenVoting()`;
- raise `DeprecationPolicy::STRICT_LEVEL` (at level 4 the write barrier begins throwing, and writes exist today that would start failing);
- change `deptrac.yaml`, DI bindings, or provider registration;
- introduce new bounded contexts, aggregates, entities, or repositories;
- refactor code to make the authority matrix look cleaner;
- reopen EM-VOT-002's implementation location;
- resolve EM-OPEN-021;
- promote the PKS candidate, accept the ADR, assign an ES number, edit the Standards Index, or modify CLAUDE.md to make the candidate binding;
- add hooks, CI gates, or blocking scripts.

**On discovering a migration candidate:** `record finding → identify current authority → identify desired future authority → record migration debt → request a separate architecture decision`. **Do not implement it.**

**The governing sentence for Session 3:**

> **A business ticket may USE an existing authority. A business ticket may NOT silently REASSIGN that authority.**

Session 3 continues **only** already-authorized Election-Only implementation, and **must not treat the PKS candidate as permission to relocate anything**.

---

## 13 · Category summary — never collapsed

**ESTABLISHED** — E-1 … E-12 (§1); all six corrections (§2); the §5 matrix rows marked HIGH; the §6 classifications; the §7 Election-Only split; EM-VOT-002's placement correctness (§8); the technical behaviour of the EM-OPEN-021 case (§9).

**INFERRED** — that the evidence ranking's *ordering* is correct; that folder-name inference *caused* the original misclassification (consistent with the artifact, but the author's reasoning was not observed); that §6 constitutes a valid n=2; that `ES-005` is the right owning standard if promoted; that a reporting instrument beats a blocking gate; that AMB-1 rises to a methodology-freeze exception.

**PROPOSED** — the ADR decision and corollaries C-1…C-5; the five authority classes; the rejection of *"legacy"* as an authority class; the §5 authority matrix as a governance record.

**UNDECIDED** — G-1 … G-10; AMB-1 … AMB-7; EM-OPEN-021 fallback semantics; every item in the ADR §6 "does not decide" list; whether the ADR is accepted; whether the PKS is promoted.

**UNKNOWN** — semantic equivalence of the two eligibility implementations (U-6); the entitlement authority for Election-Only (U-2); voter-verification path depth (U-4).

---

## 14 · Stop condition

Session 4's architecture work is **complete and closed**. No further archaeology round, no implementation, no test repair, no refactor, no file moves, no promotion, no acceptance, no new standard.

```
Session 1  →  independent falsification (F1–F6)
                        ↓
Session 2  →  governance review (G-1–G-10, AMB-1–AMB-7)
                        ↓
Human / ARB  →  accept · reject · modify
                        ↓
Session 3  →  authorized implementation only
```

**Session 4's success criterion was not a cleaner architecture.** It was to make architectural authority explicit enough that another engineer or agent can safely modify Election-Only code without accidentally changing bounded-context ownership, duplicating a business rule, or treating a future architecture as the current one. The evidence boundary is established; the repository is unoptimized and unchanged.

---

**Traceability:** archaeology `2026-08-13-election-context-architecture-archaeology.md` (incl. §21 addendum) · `ADR_20260813_1722_Architecture_Authority_And_Code_Placement` (PROPOSED) · `2026-08-13-architectural-authority-evidence-hierarchy-candidate.md` (CANDIDATE) · `2026-08-13-em-vot-002-implementation-boundary-audit.md` · `ADR_20260807_1500` (ACCEPTED) · `ADR_20260806_1620` (PROPOSED) · `ADR_20260806_1520` · `ADR_20260801_1740` · `ES-001.1` · `ES-002.1/.2` · `ES-004.2` · `ES-005.1–.4` · `ES-006.1/.4` · DDD Tactical Governance Principles (`ASP` · `ADP` · `DMT` · `RMSP` · Methodological Fitness Rule) · `AIP-14` · `R-34` · `deptrac.yaml` (ARB / PB-007) · `PBDIGIT-64` (EM-VOT-002) · `PBDIGIT-68` · `PBDIGIT-59` (gates STRICT_LEVEL escalation) · EM-OPEN-021 (open)
