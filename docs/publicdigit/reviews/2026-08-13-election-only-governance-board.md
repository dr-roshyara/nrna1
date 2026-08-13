# Election-Only Governance Board — freeze, package, no implementation

**Type:** Governance board (freeze + decision packages) · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2, board secretary / architectural gatekeeper)
**⛔ No production code, test, or fixture change. No implementation grant created. No unresolved business decision closed. No decisions merged.**

> **Status (PO / Principal Architect, 2026-08-13): ACCEPTED as the current PROGRAMME CONTROL ARTIFACT — a governance register, NOT a source of domain truth.** The authority hierarchy stands above it: *business rules/accepted decisions → domain model/bounded-context architecture → ADRs → implementation → tests → this board.* The board **records** the state of those things; it never replaces them. Two posture corrections were applied on acceptance (§2 and §4, dated annotations). Standing directions issued with the acceptance: **Session 2 receives no new analysis mission and stays stopped on the trigger rule** · **Session 1's residual mission is bounded to exit-2 + reading the 3 `VoterStrategySnapshotTest` failures — no fixes, no semantic recommendations, no restart of the full-suite investigation** · **Session 4 continues its architecture-authority work separately; its PROPOSED ADR/PKS artifacts are not promoted automatically** · **Session 3 remains STOPPED** · the 8 decisions in §7 are confirmed as genuinely the PO/ARB's · **do not reopen `PBDIGIT-64`/`EM-VOT-002` because Session 1 found consequences of the rule — the rule is verified; the open question is lifecycle semantics when the rule prevents `VotingActive`.**
>
> **Standing anti-pattern, recorded verbatim in substance (PO, §6 of the acceptance):** *"Model B was decided, therefore implement 65/69" — **NO.*** A domain decision (`PBDIGIT-68`) does not automatically authorize implementation of its consequences; each consequence still needs ownership + scope + an explicit implementation grant.

---

## 1 · Current Election-Only Governance Board *(the freeze)*

| ID | Business question | Authority | Evidence | Owner (if established) | Implemented? | Authorized? | Verified? | Blocker | **Can implementation start?** | Next decision |
|---|---|---|---|---|---|---|---|---|---|---|
| **EM-VOT-002** | candidate before voting | ✅ adopted + grant | RED→GREEN→regression + independent verification | Election (Constitution + engine) | ✅ | ✅ consumed, **CLOSED** | ✅ both paths | — | **Closed** | none |
| **65/69 — OWNERSHIP** *(Decision A)* | who owns voting-time eligibility? | 🔵 **decision required** | §2 | 🟡 unresolved (`AD-2`) | n/a | n/a | n/a | gates the repair | ❌ | **ARB: Decision A** |
| **65/69 — REPAIR SCOPE** *(Decision B)* | instances or pattern? | 🔵 **decision required** | §3 | n/a | n/a | n/a | n/a | gates the repair | ❌ | **ARB/PO: Decision B** |
| **65/69 — the defects** | valid entitlement denied / cached cross-context | E under adopted rules | measured A/B, both directions | *(follows Decision A)* | 🔴 defects present | ⛔ **NOT authorized** | defect measured | 🔴 G2 | ❌ **until A + B decided** | A, then B, then a grant |
| **BR-1.12** | admission state | 🔵 decision required | package on record (gate §0.6) | Election (admission) | production=`active` only | ⛔ | n/a | G3 (admission slice) | ❌ | **PO** |
| **EM-OPEN-021** | meaning of window-open + zero candidates | 🔵 decision required — **PO: urgent, deferral no longer neutral** | §4 | Election lifecycle | exception by fall-through | ⛔ | Session 1 trace landed (exit-2 open) | G4 conditional | ❌ | **PO — now actionable** |
| **59** | which clock is constitutional | 🔵 decision required | 4/4 disagreement, measured | Election lifecycle | n/a | ⛔ | n/a | G5 conditional | ❌ | **PO** |
| **67** | what an entered time means | 🔵 decision required | 60–120 min drift, measured | scheduling input boundary | n/a | ⛔ | n/a | G5 conditional | ❌ | **PO** *(separate from 59; presented together)* |
| **SD-15** | `has_chief` vs committee members | 🔵 **ratification** required | deliberate 2026-05-22 change; stale assertion proven | Election (Constitution precondition) | `has_chief` in force | n/a | n/a | non-blocking | ❌ *(test disposition follows ratification)* | **PO ratifies** |
| **EM-OPEN-013** | unpublish vs hide/show | **B** — adopted (PO 2026-08-06), owner unclear | ruling recorded in `60` | 🟡 capability unowned | partial (`60`) | ⛔ | n/a | non-blocking | ❌ | one-line PO confirmation + `60 D-2` |
| **Q3** | exercisability representation | 🔵 architecture decision | 4 candidates on record | 🟡 unresolved | `status` overload (load-bearing) | ⛔ | n/a | suspension slice only | ❌ | **ARB, later** |
| **Full Membership** | — | ⛔ **FROZEN / OUT OF SCOPE** | `FM-1`…`15`, `EM-FM-*` | Organisation ↔ Election | dormant | ⛔ | n/a | — | ❌ | later phase |

> **There is currently NO active production implementation grant.** The only grant ever issued (`EM-VOT-002`) is consumed and closed.

**Trigger events registered while this board was being written** (evidence intake only, nothing adjudicated):
- **`1d1c1a3a` + `9dd6e265` (PO):** the [Session 3 implementation-readiness gate](2026-08-13-election-only-implementation-readiness-gate.md) — carrying the PO stamp *"ACCEPTED AS READINESS/RECONCILIATION EVIDENCE — NOT accepted architecture, NOT a domain model, NOT a business decision, NOT authorization."* Its result (one GREEN row: `EM-VOT-002`; no ticket passes the six-condition gate) **agrees with this board** — Session 3 independently reached the same "nothing may start" state; its §2 explicitly waits on disposition rulings. The same commit relocated three legacy strategic ADRs into `docs/publicdigit/adr/` — **note:** those `ADR-004`/`ADR-005` (from `architecture_legacy/strategic/`) are DIFFERENT documents from the accepted `docs/architecture/decisions/004-…`/`005-…`; the authority map must not conflate them.
- **Session 4 (untracked artifacts observed, not consumed):** an architecture-archaeology report, a **PROPOSED** ADR (`ADR_20260813_1722_Architecture_Authority_And_Code_Placement.md`), a PKS candidate (n=1), and a handoff addressed to Sessions 1 and 2 (`G-1`–`G-10`, `AMB-1`–`AMB-7`). **Not read, not classified, not committed by this stream** — intake of that handoff is a separate commission awaiting PO direction; the PROPOSED ADR joins the board only when the PO/ARB tables it.

## 2 · Decision A — OWNERSHIP of voting-time eligibility *(package, not a decision)*

> ## **Who owns the domain meaning and invariant for VOTING-TIME voter eligibility in Election-Only mode?**

**Sharply distinguished (all measured):** *admission-time* eligibility — Contexts/Elections chain, live via the interface binding, mode-aware, **NOT in question** · *voting-time* eligibility — the legacy `app/Models` gate (`isVoterInElection()` + middleware), mode-blind, **this decision's subject** · *ambient organisation context* — request-scoped carrier (middleware/session), platform-org fallback inside `BelongsToTenant` · *the legacy gates* — one live, three dormant definitions.

| Competing interpretation | Support | Against |
|---|---|---|
| **(i) Election context owns it; ambient organisation context is a forbidden dependency** | adopted language (`EM-GOV-001` *the election governs exercisability*; `EM-ENT-007`); the election **determines its own organisation** (1 election : 1 org, schema-level); **three production sites already derive context from the election**; the measured defects are exactly ambient-context intrusions | the formal owner was never ratified (`AD-2` open); `ADR-002` named an "Eligibility Context" |
| **(ii) A dedicated Eligibility context owns it** (per `ADR-002`'s axis assignment) | `ADR-002` (Accepted) assigns "Eligible" to an Eligibility Context | that context materialised only as a **0-caller** policy; no runtime, no invariants, no consumers — a name, not a context |
| **(iii) Shared/organisation-scoped ownership** (ambient context legitimately participates) | none in adopted rules | contradicts `EM-ENT-001`/`EM-EO-*` (election-specific entitlement); produced the measured defects |

**Strongest supported interpretation: (i)** — by adopted business language, by schema, and by production precedent. **What remains uncertain:** whether the ARB wants (i) formalised directly or wants the `ADR-002` "Eligibility Context" phrase disposed of in the same act (a wording, not substance, question).

> ⚠️ **Guard (PO acceptance, 2026-08-13):** interpretation (i) is an **evidence-supported architecture HYPOTHESIS, not an accepted architecture decision.** No session may shorten it to *"the Election context owns eligibility"* until the appropriate authority actually accepts Decision A.

> **EXACT DECISION REQUIRED (ARB/PO):** *"Voting-time voter eligibility is owned by the Election context; its resolution derives organisational scope from the election itself; ambient organisation context is a forbidden dependency for this resolution."* — **adopt, adopt-with-changes, or reject.** **NOT decided by Session 2.**

## 3 · Decision B — REPAIR SCOPE *(package, not a decision)*

> ## **If the 65/69 repair is authorized: does the grant cover (1) only the known instances 62/65/69, or (2) an audit-then-remediation of the broader `BelongsToTenant` pattern?**

**Completely separate from Decision A** — and *"three known defects"* must not silently become *"refactor the infrastructure pattern everywhere."*

| | **Scope 1 — known instances only** | **Scope 2 — pattern audit + remediation** |
|---|---|---|
| Covers | the voting-time gate + its cache (`65`/`69`; `62` already fixed) | every `BelongsToTenant` consumer, enumerated first |
| Blast radius | bounded, known | **unknown by construction** — consumers unaudited (organisation pages, newsletters, dashboards) |
| Risk if chosen | the pattern resurfaces at a fourth site later | unaudited row-visibility changes across the product |
| Fits current phase | ✅ Election-Only-first | ⚠️ cross-cutting; arguably its own programme |
| Prerequisite | Decision A | Decision A + an **audit deliverable** before any change |

**Consequence either way:** the eventual grant must name its scope explicitly, and Scope 1 must state that the pattern remains a recorded risk (already in the risk register). **NOT decided by Session 2.**

## 4 · `EM-OPEN-021` — decision package *(not resolved)*

> ## **What should an election MEAN when its voting window is open but there is no approved candidate?**

| Evidence | Status |
|---|---|
| `VotingActive` is (correctly) forbidden — `EM-VOT-002`, verified | adopted + verified |
| Derivation then falls through; with completion flags set it reaches **`InvalidElectionStateException`** | **CURRENT TECHNICAL BEHAVIOUR — not domain semantics** |
| Operational **dead-end**: `close_voting` and `suspend` **unreachable from inside** (rule-1 asymmetry); trap **time-bounded** to `[voting_starts_at, voting_ends_at)` | ✅ **measured — Session 1 trace (readiness gate §3)** |
| Exit 2 — repair by approving a candidacy — restores derivability? | 🟡 **NOT ESTABLISHED** (the remaining open measurement) |
| 3 regression rows are undecidable until this rule is decided (category D, incl. one HTTP consumer) | measured (Session 1 §10 / gate §2) |

**The decision is a business meaning, not a mechanism.** Candidate meanings exist (remain in nomination · a defined holding condition · window invalid until a candidate exists · closable/cancellable) — **none is recommended, none has authority, and the exception must not become the answer by repetition.** **The PO has marked this urgent — a live election in this shape is unmanageable for its whole window; with the trace landed, the decision is now actionable** (exit-2 evidence would sharpen it, not gate it).

> ⚠️ **Posture correction (PO acceptance, 2026-08-13) — "actionable" defined precisely:** the evidence is **sufficient to put the DECISION before the PO** and **NOT sufficient to implement any semantic solution.** *Actionable* must never be read as *ready for Session 3 implementation* — the board's own register stands: no active grant exists, and the candidate meanings above are **domain semantics, not implementation choices.**

## 5 · Session-3 Stop / Authorization Register

> ## ⛔ **SESSION 3 HAS NO ACTIVE IMPLEMENTATION GRANT.**
>
> **STOP IMPLEMENTATION.** Until a separate authorization is recorded on this board, Session 3 must not:
> fix `65`/`69` · modify `BelongsToTenant` · resolve `EM-OPEN-021` · add a lifecycle state · change lifecycle precedence · repair `BR-1.12` behaviour · change `59`/`67` semantics · repair the category-A regression tests · execute/activate any entitlement pin · enter Full Membership work · refactor eligibility definitions.
>
> **Grant history:** `EM-VOT-002` — granted 2026-08-13, consumed, closed. **Grants active: none.**

## 6 · Session-1 Evidence Request *(a request, not direction — LARGELY SATISFIED while this board was written)*

**Already delivered** (Session 1 §10 corrected surface, consumed via the readiness gate): 19 newly non-passing rows; ≤ 10 attributable to `EM-VOT-002`; disposition view exists (A=7 superseded-rule fixtures · B=0 genuine regressions · D=3 blocked on `EM-OPEN-021` · F=6 unrelated); close/suspend unreachability **measured**. *(Terminology rule stands: Session 1 owns the MEASUREMENT; Session 3's table is a disposition view over Session 1's numbers.)*

**Still requested of Session 1:** **(a)** exit-2 — does approving a candidacy restore derivability for an election already inside the window? (currently NOT ESTABLISHED) · **(b)** the 3 `VoterStrategySnapshotTest` rows — read, not name-guessed (currently NOT ESTABLISHED). **Session 1 classifies on its own authority; Session 2 consumes results as evidence only, after they exist.**

## 7 · Decisions now requiring PO/ARB action

| # | Decision | Kind |
|---|---|---|
| **1** | **Decision A — voting-time eligibility ownership** (§2) | architecture |
| **2** | **Decision B — repair scope, instances vs pattern** (§3) | architecture/governance |
| **3** | **`BR-1.12`** — admission state | business |
| **4** | **`EM-OPEN-021`** — **URGENT (PO's own marking); trace landed, now actionable** | business |
| **5** | **`59` + `67`** — presented together, decided as two | business |
| **6** | **`SD-15` ratification** — evidence favours `has_chief` | business (ratification) |
| **7** | **`EM-OPEN-013` confirmation** — one line, via the 2026-08-06 ruling | confirmation |
| *(standing, unhurried)* | `EM-OPEN-017` · `EM-OPEN-019` · `Q3` · `BR-1.x` set · `Q-E1`/`Q-E2` | mixed |

**The authorization loop that follows any of these:** DECISION → DOMAIN INVARIANT → BOUNDED-CONTEXT OWNER → IMPLEMENTATION HOME → EXPLICIT SCOPE → TEST OBLIGATION → IMPLEMENTATION GRANT → Session 3 → Session 1 independent verification.

---

**Boundaries:** nothing implemented · nothing granted · nothing closed · decisions kept unbundled · folder structure not used as authority · current behaviour not promoted · Session 1 not directed (a request is recorded; classification is theirs) · Session 3 explicitly stopped · Session 4's untracked artifacts and its handoff to Session 2 observed but not read, classified, or committed by this stream.

**Evidence by pointer:** [domain ownership analysis](2026-08-13-election-only-domain-ownership-analysis.md) *(two authorities · thirteen questions · §5 ownership evidence)* · [current-state report + U-addendum](2026-08-13-election-context-governance-current-state.md) · [ticket authority matrix](2026-08-13-election-only-ticket-authority-matrix.md) · admission gate §0.6 (`BR-1.12`) · `69`/`67`/`59` tickets · Manifesto §9.
