# PublicDigit strategic DDD: progress and resumption record (organisation and governance)

**The single entry point for resuming strategic DDD work on the organisation and governance domain.** It points to the authoritative records; it does not replace or restate them.

**Governing rule:** discover once, record the evidence, consolidate the decision, and resume from the recorded state. Reopen completed work only when new evidence justifies it.

| | |
|---|---|
| **Status date** | 2026-11-02 |
| **Verified at** | branch `election-flow-updated`, HEAD `532b598cd` (2026-11-02 review). `8dd493d31` and `17ef51c5e` are on `origin`. `a6b3f6ab8` (this record and the register) and three other sessions' KnowledgeOS commits are **not pushed**. The review commit follows (see the session log). |
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

## 4. Open: the decisions that block completion

**Full list:** register §4 (O-1…O-15).

**Dependencies, evidence, next action and hold-backs per blocker:** register §4a.

**Blocking the model:**

| # | Decision | Owner |
|---|---|---|
| ~~O-1~~ | ~~**K7**~~ **resolved 2026-11-02: K′** (B1 stands; operator organisation separate; BR-12 reworded) | PO |
| O-6 | **DR-07:** committee ownership; the reading of ratified act §10; ADR-0001 | PO + ARB |
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
| T2 | **Prepare the DR-07 decision paper** (two readings of ratified §10; options) | — | Claude → PO + ARB | paper ready for the ARB |
| T3 | **ARB session:** INV-ORG-SCOPE-1 ratification; INV-AFF-1; DR-07; XD-4; vocabulary (O-14) | T2 | ARB | acts recorded |
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
- **T2:** Claude prepares the DR-07 decision paper.
- **T4:** S1 acceptance (PO / reviewer).
- Independent of both: the PBDIGIT-317 log-access check.

**Do not:**
- restart EPIC-002 discovery;
- repeat the ratified Membership design;
- use historical percentages;
- implement S2–S4 without their approvals.
