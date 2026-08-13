# Session 4 Governance Reconciliation — what is evidence, what needs a decision

**Type:** Governance reconciliation (Session 2, gatekeeper) · **Date:** 2026-08-13 · **Commission:** bounded reconciliation of Session 4's architecture-authority findings against the Governance Board
**⛔ No production/test/fixture change. No ADR accepted. No PKS promoted. No decision resolved. Board not modified (recommendation only, §7).**

**Material reviewed:** archaeology report (851 lines, verdict + §21 addendum) · `ADR_20260813_1722` (PROPOSED) · PKS candidate (n=1) · Session 4 handoff — all committed in `5ff413a3`. **Committed ≠ authoritative; every claim below was classified, and four load-bearing claims were independently spot-verified by Session 2** (`ADR_20260807_1500` status = ACCEPTED; deptrac covers `Contexts/Election` singular only; provider registration at `config/app.php:212` singular only; zero `App\Contexts` references in `app/Domain` + `app/Application`). All four verify.

---

## 1. Session 4 findings classified

Classes: **A** accepted authority already exists · **B** evidence/observation · **C** architecture hypothesis · **D** open architecture decision · **E** implementation authorization · **F** outside current Election-Only scope.

| Session 4 finding | Classification A–F | Evidence | Existing authority | Decision required |
|---|---|---|---|---|
| E-1: *"`Contexts/Elections/Domain` is authoritative for Election rules"* is **REFUTED** | **B** | dependency direction (grep, both ways), no ServiceProvider, absent from deptrac — spot-verified | none needed — the refuted claim never had authority | none |
| E-6: lifecycle authority = `Domain/Election` + `Application/Election` + `Models/Election` | **A** | `ADR_20260807_1500` **ACCEPTED** (Level 1) + DI binding + call sites — three levels agree | `ADR_20260807_1500` | none — already decided |
| `state` column = compatibility artifact on the approved Option-B retirement path | **A** | same ADR; sole writer `transitionTo()` | `ADR_20260807_1500` | none — tracked as migration debt |
| `STRICT_LEVEL = 1` is deliberate governed staging (C-B correction; R-9 → LOW) | **A** | ADR step 4, escalation gated on `PBDIGIT-59` | `ADR_20260807_1500` | none — do not reopen |
| EM-VOT-002 placement correct: Constitution + guard + engine, both paths (§8, Case 1) | **A** | adopted rule + consumed grant + Session 1 verification; Session 4 concurs by independent route | Manifesto §4a · grant record · board row 1 | none — **CLOSED, do not reopen** |
| `Contexts/Election` (singular) owns adjudication→election correction; its ACL disclaims lifecycle ownership | **A** | deptrac + provider registration (spot-verified) + ADR-T refs; self-disclaiming docblock | deptrac (ARB / PB-007) + provider config | none |
| E-7: Election-Only mode vocabulary lives in `Domain/Election/Enum/VoterSourceStrategy`; mode enforced only across eligibility/assignment/import; lifecycle is mode-independent | **B** | sole definition; zero mode refs in candidacy/vote/code controllers | none — a measured fact, not an ownership ruling | `AMB-7` (Phase-4 vocabulary owner/trigger) separately |
| Eligibility-decision **runtime** authority = `ElectionOnlyPolicy::decideForContext()` behind the DI-bound port | **B** — narrow runtime fact; Session 4 itself marks it **pending falsification (F6)** | `AppServiceProvider:141` binding + live resolution — matches Session 2's own earlier verification | none — **must NOT be read as resolving Decision A**: runtime authority ≠ domain ownership | **Decision A stays open** |
| §6 duplicate authorities: `whyCannotOpenVoting()` **semantically divergent**; eligibility pair **EQUIVALENCE UNKNOWN**; results events **neither wired**; state vocabularies **disjoint** | **B** | code reads; "UNKNOWN" correctly preserved rather than rounded | none | each disposition is its own future decision; none granted |
| Matrix asymmetry: highest-risk capabilities outside every declared-architecture constraint | **B** | deptrac's own scope text (*"intentionally outside … TODAY"*) | the deptrac **scope** itself is **A** (deliberate ARB decision) | `AMB-3` only if change is wanted |
| E-10: computed fall-through reaches a terminal throw | **B** | corroborates the board's EM-OPEN-021 technical-behaviour row | board §4 | `EM-OPEN-021` (PO) |
| U-2: **entitlement has no located production authority** | **B** | consistent with the board's gated Slice-1 entitlement pins | `D-ENT-1` semantics adopted; implementation gated | already registered — no new decision |
| The ADR's decision + corollaries C-1…C-5 + five authority classes + the evidence-hierarchy ordering | **C → D** | grounded in this repository's evidence, but the *rule* is PROPOSED; Session 4 itself classifies the ordering as INFERRED | none yet | **G-1…G-3** (ARB accept / reject / modify) |
| §5 authority matrix **as a governance record** | **C** | rows are evidence-backed (B); record-status pends the ADR + a maintainer | none | G-1/G-2 + `AMB-4` |
| §6 divergence set = n=2 for *"one authority per concern"* | **C** | argued, not adjudicated | `ADR_20260806_1620` §6 is PROPOSED, n=1 | **G-10 / AMB-5** — `ES-006.1` Human-decides |
| `AMB-1` rises to a methodology-freeze exception | **C** | one incident (Level-1 ADRs missed by a competent investigation) | methodology freeze (2026-08-01) | **G-7** (ARB) |
| `ES-005` as owning home if promoted; reporting instrument over blocking gate | **C** — recommendations, labelled as such by Session 4 | — | none | **G-4…G-6** |
| Handoff §10: falsification tasking F1–F6 addressed to Session 1 | **B** — a *request*, no standing | — | ⚠️ collides with the PO's bounded Session-1 residue — see §6 risk G-a | **PO sequences Session 1's tasking** |
| §12 forbidden-actions list | **B** — restates the stop posture; **creates no grant and no obligation beyond the existing stop register** | consistent with board §5 | board §5 | none |
| `AMB-2` (`Election`/`Elections` rename) · `AMB-6` (four ADR series/conventions) · Membership context ungoverned (495 files) | **F** | real observations, repo-wide | — | parked; not needed for Election-Only correctness |

**Class E is an empty set.** No Session 4 finding is, contains, or creates an implementation authorization — and Session 4 says so itself (`R-34` header; §12).

## 2. Election-Only architecture facts *(accepted authority only — no hypotheses)*

1. **Election state SSOT** = the `ElectionLifecycle` façade over `ElectionLifecycleEngineImpl`; `status`/`is_active`/`state` are compatibility artifacts on an approved retirement path (Option B) — `ADR_20260807_1500`, ACCEPTED, recording PO decisions.
2. **`STRICT_LEVEL` staging** is part of that accepted decision, escalation gated on `PBDIGIT-59`.
3. **EM-VOT-002** is adopted, implemented on both paths, independently verified, closed; its placement (Constitution rule text · guard command path · engine computed path) is correct under the adopted rule and the consumed grant.
4. **`Contexts/Election` (singular)** is the provider-registered, deptrac-governed context owning adjudication→election correction — and not the lifecycle.
5. **deptrac's scope** (excluding `app/Domain`, `app/Application`, `app/Models`) is a deliberate, recorded ARB decision — absence from it is not absence of governance.
6. **Model B entitlement semantics** (`PBDIGIT-68`) are decided by the PO — a domain decision, expressly **not** an implementation authorization of its consequences.

## 3. Architecture hypotheses *(strongly evidenced, NOT accepted — citable only as hypotheses)*

- **Decision A interpretation (i):** the Election context owns voting-time eligibility; ambient organisation context is a forbidden dependency. *(Board guard of 2026-08-13 stands: hypothesis until Decision A is taken.)* Session 4's runtime finding (eligibility decision executes in `Contexts/Elections` policies) is **placement evidence, not a counter-claim** — code placement ≠ domain ownership, and Session 4's own C-1 says so.
- **The evidence-hierarchy ordering** (Levels 1–7 + four caveats) is correct — Session 4 classifies this as INFERRED; it becomes usable rule only via G-1…G-3.
- **§6 divergences constitute n=2** for *"one authority per concern"* — promotable only by Human decision (G-10/AMB-5).
- **AMB-1 justifies the methodology-freeze exception** — argued from one incident; ARB decides (G-7).
- **The §5 authority matrix can serve as the recorded answer to "who owns this capability today"** — only if the ADR is accepted AND a maintainer is named (AMB-4); an unmaintained map is the next misleading artifact.

## 4. Decisions still requiring PO/ARB

**Existing register (unchanged by Session 4):**

| # | Decision |
|---|---|
| 1 | **Decision A** — voting-time eligibility ownership |
| 2 | **Decision B** — 65/69 repair scope (instances vs pattern) |
| 3 | **`BR-1.12`** — admission state |
| 4 | **`EM-OPEN-021`** — urgent (PO's marking); evidence now includes Session 1 P3+P4: HTTP 500 **proven**, state **production-reachable**, `close_voting` **disproven** as recovery, re-approval obstructed by the auto-rejection data destruction; the new-candidacy exit was **deliberately fenced out** (`30d2c528`) — *unmeasurable until this decision supplies an expected outcome*. **Nothing further gates the decision.** |
| 5 | **`EM-OPEN-019`** — nomination threshold 30 vs 40 (implemented behaviour observably 40) |
| 6 | **`59`** — which clock is constitutional |
| 7 | **`67`** — what an entered time means (presented with 59, decided as two) |
| 8 | **`SD-15`** ratification — evidence favours `has_chief` |
| 9 | **`EM-OPEN-013`** confirmation — one line, via the 2026-08-06 ruling |
| 10 | **`EM-OPEN-017`** — Manifesto ratification (wording ready) · **`Q3`** — exercisability representation (standing, unhurried) |

**Raised by Session 4's committed artifacts (disposition pending — recorded, not invented by this report):** the **Session 4 disposition set** — G-1…G-10 and AMB-1…AMB-7, of which the ones bearing directly on Election-Only are **G-1/G-2** (is the ADR acceptable as a record of current authority?), **G-8** (which matrix rows need decisions before Session 3 may touch them — answer derivable from the register: every eligibility/entitlement row waits on Decisions A+B; every lifecycle-semantics row waits on EM-OPEN-021; duplicates each need their own disposition), **G-9** (temporary working instructions without adoption), **AMB-1** (Level-1 ADR discoverability), **AMB-4** (matrix maintainer), **AMB-5/G-10** (n=2 promotion), **AMB-7** (`VoterSourceStrategy` Phase-4 owner). No decision is combined with another.

## 5. Implementation authorization

```
Active implementation grants:  NONE
PBDIGIT-65 / PBDIGIT-69:       NOT AUTHORIZED  (gated on Decision A, then Decision B, then a grant)
EM-VOT-002:                    already implemented and verified — DO NOT REOPEN
Session 3:                     STOPPED
```

Nothing in Session 4's artifacts changes any line above, and Session 4's own §12 and the governing sentence — *"a business ticket may USE an existing authority; it may NOT silently REASSIGN that authority"* — are consistent with the board's stop register.

## 6. Governance risks *(evidence-backed only)*

**Domain risk** — the EM-OPEN-021 trap is production-reachable with a **proven HTTP 500** and **no measured working recovery** (close disproven; re-approval obstructed by data destruction; new-candidacy exit not established). This is the one place where deferral has a live operational cost. It remains a **PO decision**, not a defect assignable to Session 3.

**Architecture uncertainty** — (a) the eligibility pair's semantic equivalence is UNKNOWN (never differentially tested); (b) Decision A/B open; (c) entitlement production authority unlocated (U-2). These are uncertainties on the register — **none is a defect**, and none authorizes work.

**Migration debt** — named and owned under ACCEPTED `ADR_20260807_1500`: column readers vs engine readers; results events unwired end-to-end; dormant policy stubs. Debt with a decision record behind it; repair is separately authorized work.

**Governance/provenance risk** —
- **G-a · Tasking of Session 1:** the PO's bounded residue is now **COMPLETE** (P4, `30d2c528`: the 3 snapshot rows read — all pass in isolation, order-dependent execution artifacts, zero `EM-VOT-002` attribution, and they *verify* Election-Only snapshot sovereignty `O-3`; the 6 security rows likewise pass in isolation; the new-candidacy exit fenced out pending the EM-OPEN-021 decision). **The only open Session-1 tasking question is Session 4's F1–F6 falsification request** (F6 alone is a full runtime re-derivation). **Only the PO decides whether that mission runs.** Recorded, not resolved here.
- **G-b · Location-based authority creep:** a PROPOSED ADR now sits in `docs/publicdigit/adr/` beside ACCEPTED ones, alongside three relocated legacy ADRs — precisely the AMB-1/AMB-6 failure shape. Mitigation until disposition: **cite status, never location** (already board practice).
- **G-c · Matrix staleness:** if the ADR is accepted without an AMB-4 maintainer, the matrix becomes the next "authority moved, consumers stayed" artifact — the failure `ADR_20260807_1500` documents.

## 7. Board update recommendation

**Recommendation: ANNOTATION + one NEW DECISION PACKAGE. No correction — nothing on the board is wrong. Not applied; exact wording proposed below, awaiting authorization.**

**Proposed annotation A (board §4, EM-OPEN-021 evidence table)** — append one row:

> | Session 1 P3+P4 (post-board): HTTP 500 **proven** (no catch, no render, no global mapping); state **production-reachable** via `forceCloseNomination()`; `close_voting` **disproven** as recovery; re-approval obstructed by auto-rejection data destruction; new-candidacy exit **fenced out — unmeasurable until this decision supplies an expected outcome**. The 19-row surface fully decomposed: 7 superseded-rule fixtures · 3 blocked on this decision · **9 execution artifacts (order-dependent; all pass in isolation)**. **The evidence base for this decision is complete.** | ✅ measured (`eb5d3e40`, `aaa23872`, `30d2c528`) |

**Proposed annotation B (board §1, trigger-events block)** — append:

> - **`5ff413a3` (Session 4):** archaeology + PROPOSED `ADR_20260813_1722` + PKS candidate + handoff. **Reconciled** (`2026-08-13-session4-governance-reconciliation.md`): class-A confirmations consistent with the board; no finding accepted as architecture; no grant created; ADR stays PROPOSED, PKS stays CANDIDATE. Disposition set G-1…G-10 / AMB-1…AMB-7 → new decision package (row 8a). ⚠️ Session 1 tasking collision (PO residue vs handoff F1–F6) — PO sequences.

**Proposed new decision package (board §7)** — insert as row **8a**:

> | **8a** | **Session 4 disposition** — accept/reject/modify `ADR_20260813_1722`; PKS promotion or research-only (G-4–G-6); AMB-1 freeze-exception (G-7); matrix maintainer (AMB-4); n=2 promotion (G-10/AMB-5); Session-1 tasking sequence (F1–F6 vs bounded residue) | architecture/governance |

---

**Boundaries honored:** archaeology not redone (spot-checks only) · Session 1's verification not re-run (their committed conclusions consumed as evidence) · admission-time vs voting-time eligibility kept distinct throughout · no generic Eligibility context or service proposed · no fallback state selected · ADR remains PROPOSED · PKS remains CANDIDATE, NOT PROMOTED, NOT AUTHORITATIVE · board untouched.

**Evidence by pointer:** Session 4 handoff §§1–3, 11–13 · `ADR_20260813_1722` §§2–6, 9–10 · PKS candidate §§1–3 · archaeology §1 + §21 · board (2026-08-13, accepted) · Session 1 independent verification §12 (P3) · `ADR_20260807_1500` (ACCEPTED) · `ADR_20260806_1620` (PROPOSED).
