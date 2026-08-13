# Governance watch report 01

**Type:** Governance / architecture watch · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2)
**⛔ No production code, test, schema, migration or fixture change. Nothing decided. No implementation mechanics prescribed. Session 1's Master Matrix classifications not consumed as authority; no test classified or repaired. Session 3's work not implemented, modified, or implementation-reviewed.**

**Principle observed:** Session 2 determines and protects **what is authorized** · Session 3 implements it · Session 1 independently verifies it.

---

## 1 · Authority register — reviewed and classified

| Item | Classification | Session 2 action |
|---|---|---|
| `EM-ENT-001`…`007` · `EM-EO-001`…`004` · `EM-GOV-001`…`003` · `EM-VOC-001`…`003` · `EM-SEQ-001`/`002` · `EM-VOT-001`/`002` | **ADOPTED business rules** | traceability maintained (Manifesto §8) |
| **`EM-VOT-002` implementation** | **IMPLEMENTATION-AUTHORIZED** *(grant recorded verbatim, 2026-08-13; Election-Only; strict TDD; all paths into `voting_active`)* | **not implemented by Session 2** |
| Everything else adopted | **implementation NOT authorized** | boundary protected |
| **`BR-1.12`** admission state · **`EM-OPEN-019`** threshold · **`SD-15`** committee members · **`EM-OPEN-017`** ratification · `Q3` · `Q-E1`/`E2` · `BR-1.1`/`1.2`/`1.5`/`1.6`/`1.8`/`1.13` | **OPEN business decisions** | **not inferred** — three are decision-ready below |
| `EM-FM-001`…`006` · `FM-1`…`15` · `W-1`…`8` | **DEFERRED (Full Membership)** | untouched |
| Suspension incidental-enforcement · credential-issued-while-suspended · `invited` unwritten · audit gaps · `scopeEligible()` 0/20 · tenant-blind cache (`PBDIGIT-69`) · unparseable `VoterSlugStep` (`PBDIGIT-70`) | **IMPLEMENTATION FINDINGS ONLY — not decisions** | **none promoted** |

**Register hygiene performed:** one cross-stream ambiguity found and annotated — **the `SD-14` identifier was posed with opposite polarity in the two streams** (Session 1: *"may voting open with zero candidates?" → NO*; the ruling: *"does 'next phase' include voting?" → YES*). Same substance, dangerous shorthand. **A polarity note now sits in the ruling artifact: cite `EM-VOT-002`'s text, never the bare polarity.** *(Session 1 independently recorded the same warning — convergent, not coordinated.)*

## 2 · `EM-VOT-002` authorization boundary — checked

**Measured:** since the grant (`aaf21a90`), **zero production commits exist** — `git diff aaf21a90..HEAD -- app/ database/ routes/` is empty, and the working tree is clean. **Session 3 has not yet landed implementation work.**

> **Finding: NO BOUNDARY VIOLATION — and nothing yet to review.** The boundary checklist below is ready for the moment work lands; it is **architectural conformance, not implementation review** (Session 1's role is untouched):
>
> ☐ Election-Only scope preserved · ☐ no Full Membership behaviour · ☐ no `ElectionMembership` redesign · ☐ no suspension/credential rules introduced · ☐ no new aggregate/entity/repository without authorization · ☐ `ElectionConstitution` remains the constitutional home · ☐ lifecycle-engine change limited to enforcing the adopted invariant · ☐ strict TDD (RED evidence precedes implementation) · ☐ no unrelated refactoring · ☐ tests cite `EM-VOT-002`

## 3 · Decision-ready packages

### 3.1 🆕 `SD-15` — committee members before administration completes

> **Exact question: must an election have committee members before administration can be completed — and if so, what counts as "committee members"?**

| Evidence class | What it says |
|---|---|
| **Authoritative** | `ElectionConstitution.complete_administration.preconditions = ['has_posts', 'has_voters', 'has_chief']` — **the adopted-in-code rule is `has_chief`**, evaluated by the guard as *an active `ElectionOfficer` with role `chief` exists*. The Constitution's docblock separately states *"only committees (chief, deputy) can administer elections"* — a **role-authority** rule, not an existence precondition. **No authority anywhere states a committee-members existence rule** |
| **Implementation** | The guard has **no `has_committee_members` evaluator at all** — the name exists nowhere in production |
| **Test** | `ConstitutionalTransitionGuardTest::guard_validates_preconditions` asserts `has_committee_members` **is contained in** `complete_administration`'s preconditions — **a precondition the Constitution lacks and the guard could not evaluate.** *(Same defect shape as pre-ruling `SD-14`: a test asserting an unratified rule — Session 1's pattern observation, 2 instances, deliberately not promoted)* |
| **Runtime** | During IERVP, administration was completed by **a single chief** with no deputy — implementation behaviour, **not** a rule |
| **Contradiction** | Test expects *broader* (`committee members`); Constitution encodes *narrower* (`has_chief`); **neither reading has explicit PO authority** |

| Interpretation | Consequence |
|---|---|
| **A — YES, committee members are required** | A new business rule needs adopting **with a definition** *(chief only? chief + deputy? ≥ N officers?)* → constitutional precondition + guard evaluator → **governance change, ARB** |
| **B — NO, an active chief suffices** | `has_chief` stands as-is; **the test asserts a rule that was never adopted** → test-expectation correction *(disposal is Session 1's)* |

**Recommendation:** **none** — the evidence supports neither reading over the other; the runtime observation is behaviour, not intent. **Phrased to avoid the `SD-14` polarity trap: answer by choosing A or B's rule text, not YES/NO.**
**PO/ARB DECISION REQUIRED.**

### 3.2 `BR-1.12` — admission state (`active` vs `invited` → approval)

**Already decision-ready** — full package with consequences per option: admission gate §0.6 and the final-closure §3 *(officer workload · risk posture · effect on the existing estate · voter experience; evidence: production-only for `active` vs schema+UI+tests+docs for `invited`)*. **Unchanged; re-presented, not re-investigated. Blocks the admission slice.**

### 3.3 `EM-OPEN-019` — auto-approval threshold, 30 vs 40

**Evidence stands as recorded** *(guard `≤ 40` · Constitution docblock `≤ 40` · PO statement `≤ 30`, 2026-08-09)*, **plus one minor new observation:** `ConstitutionalTransitionGuardTest` creates an election with `expected_voter_count => 30` commented *"Free plan"* — **consistent with both readings** (30 ≤ 40), so it discriminates nothing; recorded only for completeness. **Recommendation unchanged: none — a PO statement vs the implementation is not engineering's to arbitrate.**

### 3.4 `EM-OPEN-017` — ratification

**Decision wording ready:** *ratify `ELECTION_MANIFESTO.md` as the canonical catalogue of adopted Election business rules, while retaining `ElectionConstitution` as the canonical implementation home for constitutional election workflow rules?* **No new evidence needed; awaiting the PO at leisure.**

## 4 · Manifesto / Constitution separation — verified intact

☑ Separation clause present *("Manifesto = the governed home … **when ratified by ARB**")* · ☑ header states **PROPOSED / NOT YET RATIFIED** · ☑ Constitution's six docblock rules **enumerated, not restated** (§7) · ☑ no Constitution `RULES` structure copied into the Manifesto · ☑ no entitlement/membership/suspension/credential/participation rule has entered `ElectionConstitution` *(file unchanged since `675df4e9`; the transient paste-corruption was reverted externally and is on record)* · ☑ `EM-VOT-002` traceability runs Manifesto → Constitution *(future, authorized)* → guard → tests, **one rule at two levels, no duplication.**

## 5 · Report

| Required item | Result |
|---|---|
| **Authority changes** | **None.** One **annotation** added (the `SD-14` polarity note) — a reading aid, not a change of any decision |
| **Open decisions ready for PO/ARB** | **`SD-15`** *(new package, §3.1)* · **`BR-1.12`** · **`EM-OPEN-019`** · **`EM-OPEN-017`** |
| **Architecture-boundary risks** | **One, prospective:** the `EM-VOT-002` work will touch the computed-lifecycle path, which sits beside the **mode-blind** shared code identified earlier; the §2 checklist exists precisely for that moment |
| **Session 3 boundary violations** | **None — no Session 3 production work has landed since the grant** *(measured)* |
| **New business ambiguity discovered** | **One:** the cross-stream `SD-14` polarity split — **annotated in the ruling artifact**, and `SD-15`'s package is phrased to avoid repeating it |

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php:69-75` *(complete_administration)* · `app/Application/Election/Services/ConstitutionalTransitionGuard.php:180-195` *(precondition evaluators — no `has_committee_members`)* · `tests/Unit/Application/Election/ConstitutionalTransitionGuardTest.php:177-185` *(the `SD-15` assertion)*, `:205` *("Free plan" comment)* · `docs/plans/20260808-1030-election-verification-execution-plan.md:1899-1904` *(Session 1's `SD-15` framing — read as the question's source, not as matrix authority)* · `git diff aaf21a90..HEAD -- app/ database/ routes/` *(empty)* · prior packages: admission gate §0.6 · final closure §3 · Manifesto §9.
