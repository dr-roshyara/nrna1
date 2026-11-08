# PublicDigit strategic DDD: progress and resumption record (organisation and governance)

**The single entry point for resuming strategic DDD work on the organisation and governance domain.** It points to the authoritative records; it does not replace or restate them.

**Governing rule:** discover once, record the evidence, consolidate the decision, and resume from the recorded state. Reopen completed work only when new evidence justifies it.

| | |
|---|---|
| **Status date** | 2026-11-08 (Organisation-baseline update, §9a). Previous: 2026-11-02 |
| **Verified at** | branch `election-flow-updated`, HEAD `6052206be` (2026-11-08, before the commit that adds §9a; not pushed). Earlier: HEAD `532b598cd` (2026-11-02 review). `8dd493d31` and `17ef51c5e` are on `origin`. `a6b3f6ab8` (this record and the register) and three other sessions' KnowledgeOS commits are **not pushed**. The review commit follows (see the session log). |
| **Full register** | `docs/publicdigit/reviews/2026-11-02-organisation-governance-strategic-design-status-assessment.md` (T-10: every item classified and cited) |
| **Maintained by** | each strategic-design session, at session close (§8) |

**Statuses used** (the same as the register):

| Status | On resume |
|---|---|
| RATIFIED | keep unless new evidence |
| ACCEPTED | starting point within its scope |
| APPROVED | PO decision; ARB act may be pending |
| PROPOSED | continue approval; not implemented |
| OPEN | resume from the recorded question |
| IMPLEMENTATION GAP | continue the authorised remediation |
| VERIFICATION REQUIRED | verify before relying on it |

---

## 1. Objective

Complete the strategic DDD design of the **organisation and governance domain**:
- explicit context responsibilities and relationships;
- consequential open decisions resolved;
- a controlled hand-over to tactical implementation.

Preserve ratified and accepted decisions.

## 2. Scopes

| Scope | Status | Record |
|---|---|---|
| **EPIC-002**: evidence and integrity contexts (Collection & Aggregation, Record-Fixing, Custodial Integrity, Self-Verifying Integrity, Adjudication) | **ACCEPTED** for its own scope (ARB 2026-07-25) | `docs/implementation/EPIC-002_Canonical_Context_Map.md`; `docs/implementation/EPIC-003_Tactical_DDD_Entry_Assessment.md:193` |
| **Organisation and governance**: Identity, Organisation, Membership, Governance, Geography, Authorisation, Communication; Election at its boundary only | **IN PROGRESS. Not complete** (register §7). No percentage. | this record + register |

**Rules for using these:**
- EPIC-002 does not cover organisation or governance; it is not this scope's baseline.
- The 2026-07-08 percentages (`architecture_legacy/how_far_we_are/20260708_1512_how_far_we_are.md`) are **historical**; do not use them.
- **Not every named area is a ratified bounded context.** Only Membership's boundaries are ratified; the others are candidates (register P-7, P-8).

## 3. Settled: do not rediscover

| Item | Status | Record |
|---|---|---|
| Membership architecture; "no shared persistence and no Membership → entitlement shortcut" | **RATIFIED** 2026-10-08 | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`; verdict `…-final-ratification-verdict.md:46` |
| Working Organisation (B1, B10) | **APPROVED** (PO 2026-08-06) | `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` |
| INV-ORG-SCOPE-1 (owning organisation = Working Organisation; fail closed) | **APPROVED principle**; ARB ratification pending | `docs/publicdigit/reviews/2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md` |
| Business rules BR-1…8, BR-13, BR-13a, **BR-13-S (scope confirmed)**, BR-14 | **APPROVED** | `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` |
| D-1 tenancy: option C as **candidate** direction | **APPROVED as direction**; validation and ARB pending | `docs/publicdigit/reviews/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md` §8a |
| Membership–Election: option A first milestone; plan PROPOSED | **APPROVED** (`8dd493d31`) | `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`; plan `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` |
| PBDIGIT-318 S1 static guards (baseline V1, V1b, V2, V3′) | **APPROVED and implemented** (`17ef51c5e`); **acceptance pending** | `tests/Architecture/MembershipElectionBoundaryTest.php`; `developer_guide/membership_election_boundary/` |
| Identity / affiliation / provenance proposals (XD-3a, XD-3b, XD-3c, Decision C, INV-AFF-1 **referred to the ARB**, XD-PROV with AM-1 r2) | **PROPOSED** | `docs/publicdigit/reviews/2026-11-0[12]-*-decision-record.md` |
| PO-DR07-01: governance committees are organs; organisational units are a distinct concept; existing records are not classified; `CommitteeAssociation` unresolved | **APPROVED** (PO business decision, 2026-11-02) | `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §15 |
| PO-ORG-MEM-01 Decision A (the Organisation owns its Membership domain; a competent organ or authorised office holder makes Membership decisions; administrative responsibility and platform permissions are not organisational authority) and Decision B (DR-07a M-1…M-5 authorised; findings must be independently reviewed before use) | **APPROVED** (Principal / PO, 2026-11-03); ARB validation of its consequences **pending** | `docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md` (commit `c632fe45d`) |
| PO-ORG-MEM-01-J1 (Decision A applies to Membership decisions only), -J3 (Decision B, Constraints and final status line authentic), -J4 (PO and Human ARB were one person in two capacities; D-4 decided as Human ARB, as or on behalf of the Governance owner) | **DECIDED** (Principal, 2026-11-07) | `docs/publicdigit/reviews/2026-11-07-organisation-po-org-mem-01-principal-clarifications-j1-j3-j4-decision-record.md` (commit `78f87094c`, with the D-4 attribution corrections in the constitutional-authority preparation document) |

## 4. Open: the decisions that block completion

**Full list:** register §4 (O-1…O-15).

**Dependencies, evidence, next action and hold-backs per blocker:** register §4a.

**Blocking the model:**

| # | Decision | Owner |
|---|---|---|
| ~~O-1~~ | ~~**K7**~~ **resolved 2026-11-02: K′** (B1 stands; operator organisation separate; BR-12 reworded) | PO |
| O-6 | **DR-07:** committee ownership; the reading of ratified act §10 (aggregate) and **§11 (context: "organs")**; ADR-0001's location clause. Paper ready. **Updated 2026-11-08:** premise OQ-DR07-0 answered by **PO-DR07-01** (2026-11-02, paper §15). DR-07a technical discovery M-1…M-5 is written (`docs/publicdigit/architecture/reviews/2026-11-03-dr07a-membership-technical-discovery-m1-m5.md`, commit `ed8d572dc`); under PO-ORG-MEM-01 Decision B its findings **must be independently reviewed before use**, and that review has not been done. ARB-1…ARB-8 remain pending. DR-07 stays **OPEN** | PO + Membership owner + ARB |
| O-7 | **XD-4** authorisation ownership; **XD-5** geography history | ARB |
| O-8 | which positions are incompatible within an organisation (BQ-P1, CQ-7) | PO |
| O-9 | Membership ↔ tenure (BQ-P2 / P3) | PO |
| O-10 | committee authority and statutes (CQ-2 / 3 / 4 / 10) | PO |
| O-14 | vocabulary split: member / role / observer / ElectionMembership | ARB |
| — | ARB acts on INV-ORG-SCOPE-1 and INV-AFF-1 | ARB |

**Blocking validation and testing:**
- **O-3:** scale figures U-1…U-5 (PO + Ops);
- **O-4:** the isolated test database (PO / Ops).

## 5. Implementation gaps and verification

- **Gaps:** register §5.
  - Membership ↔ Election: V1, V1b, V2, V3′, guarded by S1 and closed by S2–S4, each separately gated.
  - Organisation-scope findings: PBDIGIT-266, PBDIGIT-309.
- **Verification required:** register §6 (deployed schema, production platform slug, log channel, S1 acceptance, PBDIGIT-266 tests, discovery at scale).
- **Security, independent:** **PBDIGIT-317** (secrets written to the log): triage authorised; first step is the PO / Ops read-only log-access check.

## 6. Next tasks (priority order)

| # | Task | Depends on | Owner | Done when |
|---|---|---|---|---|
| T1 | ~~Decide K7~~ **DONE 2026-11-02: K′**, recorded in PBDIGIT-30, BR-12, Decision C §2a, register O-1. PBDIGIT-32 is unaffected. Follow-ups: naming (PO); DC-R9 placement (D-1 / ARB); a read-only platform-organisation data check (needs authorisation) | — | PO | done |
| T2 | ~~Prepare the DR-07 decision paper~~ **DONE 2026-11-02:** `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md`. **DR-07 itself stays OPEN.** Next: PO answers §13 (premise OQ-DR07-0, then A or C) → Membership owner → ARB | — | Claude → PO + ARB | paper ready ✔; decision pending |
| T3 | **ARB session:** INV-ORG-SCOPE-1 ratification; INV-AFF-1; DR-07; XD-4; vocabulary (O-14). **Pack prepared 2026-11-02:** `docs/publicdigit/reviews/2026-11-02-organisation-governance-arb-decision-pack.md`. Readiness: INV-ORG-SCOPE-1 and O-14 **READY**; DR-07a (incl. ARB-8 / BDR-03), XD-4 and INV-AFF-1 (target only) **READY WITH CONDITIONS**. Hold it after the Membership owner answers DR-07 paper §16 | T2; Membership owner for DR-07a | ARB | acts recorded |
| T4 | **Accept S1** (`17ef51c5e`) | — | PO / reviewer | acceptance recorded in PBDIGIT-318 |
| T5 | Governance business rules O-8, O-9, O-10 (statutes) | statutes available | PO | rules recorded |
| T6 | Relationship specifications for the context map (start: Membership ↔ Governance cycle) | T3 (DR-07) | Claude → ARB | register exit criterion "relationships specified" met |
| T7 | Exit assessment (register §7) | T1–T6 | Claude → PO + ARB | completion declared, or exceptions accepted |
| ∥ | PBDIGIT-317 log-access check; U-1…U-5; isolated test database | — | PO / Ops | figures, database and check recorded |
| ∥ | S2–S4 (PBDIGIT-318), each when its gate is met | gates in the plan | PO approval per slice | slice accepted |

## 7. Resumption protocol (start of every session)

1. Read this record, then the latest `.claude/sessions/*.md`.
2. If repository work is needed, verify branch, HEAD, `origin` status and uncommitted changes. Compare with "Verified at" above.
3. Open only the records needed for the next task (§6).
4. State what is settled (§3), what is open (§4), and the first unfinished task.
5. **If new evidence contradicts this record, stop and reconcile before continuing.**
6. Do not restart discovery, re-derive settled items, or treat proposals as approved.

## 8. Session-close protocol

Update this record (and the session log) with:

| Field | Content |
|---|---|
| Completed | what, with evidence (file, commit) |
| Decisions | status and approver |
| Resolved / still open | question IDs |
| Priority changes | the §6 table |
| Repository state | HEAD; committed or uncommitted; pushed or not |
| Next action | exact, with prerequisites |

**Completion statement format:**
> Completed: X (evidence Y). Decision status: Z. Still open: A, B. Next action: C. Prerequisites: D. Repository: commit E, changes committed / uncommitted.

## 9. Last session (2026-11-02) and the exact next action

**Completed:**
- Decision D proposal; D-1 direction C;
- D-2 answered (BR-13 / 13a / 14), BR-13-S confirmed;
- the Membership–Election decision and plan;
- V3 corrected to V3′; V1b found;
- **S1 implemented** (`17ef51c5e`, pushed);
- the T-10 status assessment and this record.

**Repository:**
- the T-10 assessment, its governance-map pointer, this record and the session-log entries were committed in `a6b3f6ab8` (not pushed).

**Principal Reviewer quality review (2026-11-02, later):**
- Register verified:
  - one T-10 row (the reported duplicate was a diff excerpt);
  - S1 implemented, not accepted.
- R-6 class clarified.
- Added:
  - §4a dependency map;
  - §7 closure path;
  - the K7 brief.
- No status changed. Commit: see the session log.

**K7 decided (2026-11-02):** **K′**. B1 stands, and PublicDigit's real organisational life is in a separate operator organisation. Nothing is implemented.

**Next action:**
- ~~T2~~ **The DR-07 paper is ready** (not a decision).
- **Principal architectural position (2026-11-02): BOTH** (paper §14):
  - **DR-07a** governance organs: recommend A + B;
  - **DR-07b** organisational units → register O-16.
- **PO business decision PO-DR07-01 (2026-11-02):** governance committees are organs; organisational units are a separate concept; existing records are unclassified; `CommitteeAssociation` is unresolved (paper §15).
- **ARB pack prepared** (T3, above).
  - It adds **ARB-8**: the relation of DR-07a to the frozen Round 49-06 **BDR-03** "Appointment (Authority)", which owns Mandate. The DR-07 paper had not cited it (paper §19).
  - XD-4 consumes frozen **BDR-07** (Authorisation owns no truth).
- **Committee statutes:** **not yet requested**. The PO sends the request text in pack §6 to a recipient the PO names.
- **Next (exact):**
  1. **PO:** send paper §16 (M-1…M-5) to the Membership owner, and send the statutes request (pack §6).
  2. **ARB:** schedule the session on pack §0. Items 1–2 can go first.
  3. O-16 evidence per paper §18.
- DR-07 stays OPEN. T6 waits for the DR-07a ARB act and O-8…O-10.
- **Uncommitted:** the PO-DR07-01 updates and the ARB pack (2026-11-02, HEAD `54cc0af63`). *(Later committed; see the session log.)*
- **T4:** S1 acceptance (PO / reviewer). *(Superseded: S1 closed as superseded by S1b; S1b accepted 2026-11-02, PBDIGIT-318.)*

**Context map (2026-11-02, later):**
- **The consolidation point for strategic relationships** is `docs/publicdigit/architecture/context-map.md`. It is PROPOSED; its location and role were approved by the PO; it was independently reviewed and corrected. It is not ratified.
- **Q-new-1 and Q-new-2** were routed to the ARB (ARB pack items 6–7; register O-17, O-18).
- **C4:** option A (unchanged; scope stated in the map).
- **Decided later the same day (PO):**
  - the map **supersedes governance map §5** as the place where these relationships are maintained (§5 kept as history, with a pointer);
  - **PBDIGIT-320** wording corrected: ownership goes to BR-9 / OQ-C-5; persistence to OQ-ME-3 and Q-new-1.
- **Still with the PO:** the FeePaid direction question.
- **When resuming:** read the context map §10 (open questions) and §14 (change control) after this record.
- Independent of both: the PBDIGIT-317 log-access check.

## 9a. Session A, Organisation baseline (ODA-01), 2026-11-03 to 2026-11-08

**Completed** (documentation and decision records only; nothing implemented):
- ODA-01 Organisation domain architecture baseline: v1.0 and v1.1 (commit `f6471aaf0`); v1.2 (`docs/publicdigit/architecture/reviews/2026-11-06-organisation-domain-architecture-baseline-oda-01-v1.2.md`). All are **PROPOSED**.
- Reviews of ODA-01, all **Claude-family, therefore not independent**:
  - IR-ODA-01 (`f6471aaf0`);
  - the Claude subagent review of v1.1 (`ffcee7b8a`);
  - the Claude subagent review of v1.2 (CSR12);
  - a fresh-session Claude review of v1.2 (IR12, held outside the repository at `~/ir-oda-01-v1.2-review-handover/`).
- ODA-01 v1.3 correction plan (`docs/plans/20261107-0239-oda-01-v1.3-traceability-and-correction-plan.md`) and its approval package (`docs/publicdigit/architecture/reviews/2026-11-07-oda-01-v1.3-correction-plan-approval-package.md`):
  - the Principal approved documentary decisions 1–6 on 2026-11-07, then A-12 (as decision 5, option b) and I-2…I-8 on 2026-11-08;
  - these approvals cover **the wording of the plan only**. They are not approval of ODA-01 or of any architecture.

**Decisions:** PO-ORG-MEM-01 A / B (`c632fe45d`) and J1 / J3 / J4 (`78f87094c`), see §3.

**Still open:**
- ODA-01 v1.3 is **not created** (needs a separate authorisation).
- **Gate F** (independent review by a human architect or a different model family, per `docs/publicdigit/architecture/reviews/2026-11-06-ir-oda-01-v1.2-review-commission.md`) is **open**. The Principal's 2026-11-07 acceptance of residual risk covers the Claude reviews only, not Gate F.
- The definition of Organisation (ODA-M-2) is open. No constitutional source material or authoritative appointment / delegation evidence is on file.
- Change Request authority for the ratified Membership architecture (B-4 / ODA-M-19) is open.
- Whether the ARB and the Human ARB are the same authority is open.
- Who may extend, replace or supersede D-4 is open.
- B-8 (a durable record of Principle A) is open.
- The Organisation context is a **Candidate** only (ADR-004); none is established.

**Repository:**
- The ODA-01 working documents and this update are committed together. See the session log of 2026-11-08 for the commit.
- Not pushed.

**Next action:**
1. Arrange the Gate F independent review under the v1.2 review commission.
2. The Principal decides on its findings and on creating v1.3 (gate D).
3. Then accept or reject the organisational baseline.

**Strategic DDD discovery has not started** and must not start before the organisational baseline is accepted.

**Do not:**
- restart EPIC-002 discovery;
- repeat the ratified Membership design;
- use historical percentages;
- implement S2–S4 without their approvals.
