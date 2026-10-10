# PublicDigit Membership: standing development strategy (runtime binding)

**Recorded:** 2026-10-10, on the PO's instruction (`architecture/brainstorming/20261010_1046_development_strategy.md`).
**Classification:**
- purpose: how Membership work is run;
- type: runtime binding of the existing Engineering Process to one bounded context;
- steward: PO;
- lifecycle: living;
- maturity: qualified for Membership. **Not a new methodology.**

**Status:** the phase mapping (§2) and the roles (§1) record existing rules and the PO's instruction. **§4 contains one PROPOSAL that needs PR / PO approval.** Nothing here changes the current C-1 gate or state.

> **Rules live once.** This document points to the canonical rules; it does not restate them. The methodology is **frozen** (`.claude/CLAUDE.md`, Operating Loop): this binding adds no protocol. Where it seems to differ, the canonical source wins.

## 1. Roles and authority

| Role | Holder | Does | Does not |
|---|---|---|---|
| PO / Human ARB | Dr. Nab Raj Roshyara | decides business policy and reserved architecture decisions; **gives the formal approvals** (contracts, plans, gates, acceptance) | — |
| Principal Architect and Reviewer | ChatGPT | challenges assumptions; reviews DDD models, logic, invariants, completeness and trade-offs | **give formal approval.** EP-01 requires *explicit human* approval, so PR positions are advisory (precedent: C-1 gate record, authority determination) |
| Developer | Claude | inspects the repository; maintains artefacts, stories and plans; implements approved scope TDD-first; runs tests and reports evidence | turn a hypothesis into a ratified rule or a proposal into an approval; accept its own work (EP-02, R-34) |

## 2. Phases A–G mapped to the existing process

| Phase (PO strategy) | Canonical rule (pointer) | Membership practice and precedent |
|---|---|---|
| **A: Domain and requirements** (PO / ARB + PR discussion) | Operating Loop phases 1–3 (business capability, strategic DDD, canonical discovery); EP-03 readiness review; standing rule "Business → DDD → Architecture → Tests → Implementation" | the ratified architecture act (2026-10-08) is the baseline. Tag every statement as approved, derived, hypothesis, evidence or open, and keep open questions (CQ-x, BD-x, D-COR-x) open |
| **B: Architecture and artefacts** | Loop phases 3–6; ES-004 (placement, `doc-placement.php`); ES-005.4 (extend, never copy) | **update the existing contract, ADR or story first.** Contracts live in `docs/publicdigit/reviews/…-contract*.md`; models and oracles in `docs/publicdigit/membership/architecture/models/`. Decisions go in a decision record and are never ratified on the PO's behalf |
| **C: Implementation plan** | EP-01 Planning Stage; ES-004.2 (`docs/plans/YYYYMMDD-HHMM-…-plan.md`) | the plan names the story ID, contract criteria, files, the RED / GREEN sequence, the baseline, regression, exclusions, risks, and any persistence or migration impact. Precedent: `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` |
| **D: Approval gate** | EP-01 (approval applies to the **plan**); Loop phase 7 (readiness) | a written gate register, every condition CLOSED with evidence before code. Precedent: `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md`. Plan approval never authorises excluded work |
| **E: TDD implementation** | Loop phases 8–10; standing rule step 4; ER-08 | baseline first; one behaviour per RED → GREEN; the failing run is recorded before each production change; one commit per RED and per GREEN; actual commands and outcomes. See §4 for verification-only tests |
| **F: Completion and traceability** | EP-02; Loop 11–12; ES-004.3 (artefact synchronisation); End-of-Commission checklist; Session Completion Report | implementation review + developer guide (`developer_guide/membership/`) + story status. **Then the close-of-slice story traceability check (§3)** |
| **G: Controlled evolution** | Loop phase 5 stewardship (ES-002.1 / .2); Loop 13–14; ES-006.1 (no promotion from a single occurrence) | approved architecture stays stable. A gap found in implementation is **documented and stopped for review** if it exceeds the authorisation (P-1 precedent: "if an adapter change is required, stop and report") |

## 3. Membership-specific bindings (no protocol change)

1. **Close-of-slice story traceability check.** At each slice close, do what the commission's "Ongoing rule" asks:
   - check the affected stories and their dependants for status, acceptance criteria, dependencies and the index row;
   - record the evidence;
   - list uncovered obligations without creating IDs.

   First instance: `docs/publicdigit/reviews/2026-10-10-membership-story-traceability-audit.md`. Story and index changes need authorisation.
2. **Evidence conventions:**
   - exhaustive bounded differential against an independent oracle port;
   - specification-derived properties;
   - mutation measurement with the scope stated and every survivor classified.

   State "**verified within bounds**", never "proved". Oracles are evidence, never the contract.
3. **Scope boundaries in force:**
   - kernel (`Domain/Organisational`) ≠ application (C-3) ≠ persistence (C-4);
   - no Correct before C-2;
   - **cutover, migration and live writes stay blocked until C-1 to C-4 are accepted (ARB-MEM-01)**;
   - DB-touching tests only on an isolated database (ARB-SEC-01, PBDIGIT-266);
   - Registration ≠ Voter Admission ≠ Voting Entitlement (Election-owned).
4. **Shared-file hygiene.** Other sessions edit README, CONTEXT and session logs, so:
   - stage explicit paths only;
   - commit through a temporary index;
   - commit only one's own hunk of a shared file;
   - never overwrite another session's work.

## 4. PROPOSAL (needs PR / PO approval): verification-only tests within TDD

**Evidence:** the C-1 audit finding I-2. Seven C-1 criteria were already satisfied by earlier GREEN behaviour, so a RED phase was impossible without breaking production code. The wording "RED first for each criterion" (contract §15.7) cannot be met literally.

**Proposed rule:** a test for behaviour that already exists is accepted without a RED phase if **sensitivity** is shown instead:
1. a temporary production mutant that violates the behaviour;
2. the test fails against it;
3. the mutant is reverted, and `git diff` of `app/` is shown empty.

The commit message states "no RED phase" and names the mutant. Under this rule, plans and DoDs say "RED first, **or sensitivity** for verification-only criteria".

**Status:** PROPOSED (one occurrence). It is not a rule until approved. If approved, it extends the existing TDD standard (ES-005.4) rather than standing alone.

## 5. Forward strategy (recommendation, not authorisation)

Sequence derived from the approved correction contract §10 and the open decisions. **Each step needs its own authorisation.**

| # | Step | Blocked by / needs |
|---|---|---|
| 1 | PR review and **PO acceptance of C-1** (PBDIGIT-268); decide the K-E1 test (audit D-2) and I-2 (D-3) | PR, PO |
| 2 | Backlog synchronisation from the traceability audit (D-1, D-4) | PO |
| 3 | Formal acceptance acts for Slices 0 / 1a / 1b (258, 259, 260; ARB-MEM-02) | PR, PO |
| 4 | `PBDIGIT-264` (1b follow-ups: far-date defect, races, FK) and `PBDIGIT-267` (kernel test gaps) | PO authorisation. 264 is a C-4 prerequisite |
| 5 | **Phase A** for C-2 (Correct + consequences) | Human ARB: **BD-REV-ER**, D-COR-3 |
| 6 | Slice 2 contract (Admit / End application wiring), in parallel | Slice 2 decisions (names still proposed, ARB-DEC-01) |
| 7 | C-3 application (commands, authority, event emission) | **CQ-3**, G-AUTH-1; C-1 (C-2 for Correct) |
| 8 | C-4 persistence (incl. C-1 review F-1, adapter `encode()` typing) | D-COR-4; 264 |
| 9 | Cutover / live-write gate review | C-1 … C-4 accepted (ARB-MEM-01); the reverse-projection predicate (PBDIGIT-265) |

Business questions stay with the PO and Legal and are never decided by implementation: **CQ-9** (appeal body, ex tunc / ex nunc), D-COR-1, D-COR-5 (B).

## 6. Standing rule (PO wording, preserved)

> DDD and requirements engineering determine what should be built and why. Approved architecture constrains how it may be built. Human review authorises the plan. TDD and verification provide implementation evidence. Completion review updates the backlog and identifies the next justified step.

**Traceability:** the PO instruction `20261010_1046_development_strategy.md` · `.claude/CLAUDE.md` (EP rules, Operating Loop, frozen methodology) · `docs/implementation/Implementation_Process_v1.1_Draft.md` (EP-01 / 02 / 03, ER-08) · `engineering/governance/ES-002`, `ES-004`, `ES-005`, `ES-006` · the C-1 gate record, plan and review · the 2026-10-10 traceability audit.
