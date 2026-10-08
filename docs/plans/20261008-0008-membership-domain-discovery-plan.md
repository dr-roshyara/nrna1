# Plan — Membership Domain Discovery & Constitution v1 (PublicDigit)

**Status:** 🟢 **APPROVED (PO, 2026-10-08)**, with placement `docs/publicdigit/reviews/` and a **generic** reference (no customer organisation). §3 C-1 is accepted as part of the approval. **No code, no story and no ADR is changed by it.**
**Created:** 2026-10-08 · **Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Origin:** review `docs/publicdigit/reviews/2026-10-08-member-to-election-membership-integration-review.md`, plus the PO's direction of 2026-10-08 ("Membership Domain Discovery & Constitution v1", 23-section outline, Actor → Goal → Business Rule → Authority → State → Decision → Evidence → Outcome)
**Deliverable placement (derived):** `doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0). Proposed file: `docs/publicdigit/discovery/2026-10-…-membership-domain-discovery.md`. The `discovery/` sub-folder is **not** a ruled category, so it is **PENDING** and must be confirmed by governance; the fallback is `docs/publicdigit/reviews/`.

---

## 1. Objective

Establish **what PublicDigit means by Membership** as a product capability, separate from Election-Only, before any further Full Membership implementation. The output is **one discovery document**. It is not stories and not code. The document ends with:
- a list of **PO/ARB decisions required**;
- a **traceability table to the existing backlog**, where any new story is created only under the frozen-baseline rule.

## 2. Background

The 2026-10-08 review found that the Member → ElectionMembership path is unreachable and internally inconsistent (I-1…I-6). Elections reads Membership's tables directly, and there is no published contract (O-1). Underneath that sits a requirements gap: Membership was implemented in parts before its business meaning was fixed.

## 3. Corrections to the direction, from canonical discovery (Phase 3, done for this plan)

The PO's direction is adopted. Canonical discovery for this plan found four points that change *how* it is executed:

| # | Direction said | Evidence | Consequence for the plan |
|---|---|---|---|
| C-1 | "Standing vs entitlement … needs an explicit ARB/constitutional decision" (§8) | **Already decided.** `PBDIGIT-68`: *"`D-ENT-1` RESOLVED · `Q-A0` = `F1`"* (PO, 2026-08-12): *"In Full Membership mode, organisation membership is an **admission prerequisite** — **not** a continuously evaluated prerequisite for retaining the election entitlement"* (`2026-08-12-d-ent-1-two-mode-domain-decision-report.md:12-43`) | The discovery **records F1 as decided input. It does not reopen it.** It does carry forward what F1 left open: the ADR-002 amendment (`D-APPLY`) still awaits the PO with `V-1`/`V-2`/`V-3`; `V-1` says *"no election-level termination rule is ratified"*; and `Q3` is open. `Member::endMembership()` (I-3) is evidence **against** F1. That makes it a code finding, not an open domain question. |
| C-2 | Election-Only has *"no organisational membership relationship"* (§1A) | Election-Only voters **must be active `OrganisationUser`s** of the organisation (`VoterEligibilityService.php:60-70`; import error *"is not an active member of this organisation"*, `VoterImportService.php:183`) | The ubiquitous language must name the **OrganisationUser** relationship explicitly: neither a Member nor "nothing". Note that the import's own wording says "member" here, so the current code already conflates the two terms. |
| C-3 | New terms *"Election Participant"*, *"Voter Entitlement"* | The code's term is `ElectionMembership` (role voter/candidate/…). `PBDIGIT-68` and ADR_20261004_1549 already define **Registration ≠ Admission ≠ VotingEntitlement** | New terms are **candidates**, mapped against the existing ones. **No second vocabulary for the same concept** (ES-005.4). |
| C-4 | Start a Membership discovery | **158 tracked Membership documents exist**: `architecture_legacy/membership/` (incl. `…user_member_voter_speration.md`, `…redefine_membership_participants_guests.md`, `…membership_ddd_context.md`, `election_only_or_full_membership.md`, ADR-F3.2/F3.3); `developer_guide/membership*`, `organisations/membership_mode`; `docs/membership/` (incl. a business case); `docs/knowledge/domains/membership/README.md`; `PBDIGIT-EPIC-02`, `PBDIGIT-66`, `PBDIGIT-68` | Phase A below **harvests these first.** The discovery classifies each prior statement as decided / proposed / legacy implementation / contradicted. **It invents nothing that already has a home.** |

### 3a. Correction to §3 C-1 (2026-10-08, Phase A, additive)

C-1 cited F1 alone. Phase A found **`D-ENT-2`** (PO, 2026-08-12, ADOPTED), which **refines** F1 along an existence/exercisability split. Under it, in Full Membership mode, removal of organisation membership **automatically suspends** the ElectionMembership (EM-FM-003/004/006, `ELECTION_MANIFESTO.md:358-363`). All `EM-FM-*` rules are **ADOPTED · DEFERRED** (EM-SEQ-001/002: Full Membership is a later phase). The open questions are already registered as **FM-1…FM-15** and EM-OPEN-014/015/016.

**Consequence:** the discovery takes **F1 + D-ENT-2 + EM-EO-* + EM-FM-*** as decided input. It **consumes the FM-1…FM-15 register** as its question list rather than re-deriving one. It **does not implement or activate** any deferred rule (EM-SEQ-002). The review `2026-10-08-member-to-election-membership-integration-review.md` §8 carries the matching correction.

## 4. Scope

**In:**
- the business capability (Election-Only vs Membership digitalisation as two products);
- ubiquitous language;
- the Membership Constitution: which parts are **organisation-configurable** and which are **platform invariants**;
- the lifecycle, authorities, rights and obligations, types, fees and standing;
- the Membership ↔ Election contract (conceptual, for example a Membership-owned standing query);
- the context map;
- open questions and the decisions required;
- traceability to the backlog.

**Out:**
- any code, migration or test;
- any change to existing stories or ADRs (proposed corrections are *listed*, not applied);
- reopening F1, the frozen baseline or other completed decisions;
- the defect stories `PBDIGIT-257`, `202` and `203`, which remain independently authorisable and are **not** blocked by this discovery.

## 5. Method (per requirement)

`Actor → Goal → Business Rule → Authority → State → Decision → Evidence → Outcome`.

Every statement carries a **source tag**: `[decided: ref]` · `[proposed: ref]` · `[legacy-impl: file:line]` · `[candidate]` · `[PO question]`.

**Legacy implementation is never presented as domain truth.** The strongest statement made must not exceed its evidence.

## 6. Task checklist

- [x] **A. Canonical harvest** (read-only): index the 158 documents, plus EPIC-02, `PBDIGIT-66`/`68`/`49`/`96`/`202`/`203`/`209`/`211`, ADR_20261004_1549, ADR-F3.2/F3.3, and the Membership context code (statuses, transitions, fee states, types). Output: a source register with a status for each statement.
- [x] **B. Business capability**: the two products (§1–5 of the outline), with the actors and personas taken from existing material.
- [x] **C. Ubiquitous language**: Person · User · OrganisationUser · Member · Applicant · ElectionMembership (voter/candidate) · Registration · Admission · VotingEntitlement. Each term gets a definition, an owning context and the current code mapping. **The conflations found are listed** (for example "member" in the Election-Only import text).
- [x] **D. Membership Constitution**: for each rule, decide configurable or invariant, and mark it `[PO question]` where no decision exists. This includes the voting-right question (is it a property of the membership type, of the member, of the constitution, or of the election?). `grants_voting_rights` is treated as **legacy-impl** until decided.
- [ ] **E. Lifecycle and authorities**: map the states and transitions as they exist in code against the candidates, giving each transition its actor, authority, decision and evidence.
- [ ] **F. Fees and standing**: define *Membership Standing* as the single published fact. Inputs are status, fees, type and expiry, with dependencies on `209`/`211` noted.
- [ ] **G. Membership ↔ Election contract**: Standing → Eligibility (election constitution, snapshot strategy) → Admission (ADR_20261004_1549) → Entitlement (F1). Give the **candidate** port shape and resolve I-1 and I-6 conceptually.
- [ ] **H. Context map**: Membership, Organisation, Elections, Governance/Committee. Mark each relationship as upstream/downstream and published-language or not.
- [ ] **I. Decisions required**: a numbered list with the authority for each (PO or ARB). This includes F-2 (participation-lock guard), ADR G8, the owner of the predicate (O-1), the voting-right home, `V-1`, auto-enrolment (O-2) and `endMembership`.
- [ ] **J. Traceability**: map every requirement to an existing PBDIGIT ID, or mark it "uncovered — candidate for a new ID with PO approval".
- [ ] **K. Close**: session log, CONTEXT-publicdigit, and this plan's progress. One commit is made only when the PO asks for it.

## 7. Progress

- 2026-10-08: plan drafted. Canonical discovery for the plan itself is done (§3).
- 2026-10-08: **approved** by the PO: "approved, use docs/publicdigit/reviews, keep it generic". Open questions 1–3 are answered. Phase A started.
- 2026-10-08: **Phases A–D done**. Part 1 is `docs/publicdigit/reviews/2026-10-08-membership-domain-discovery.md`. **Stopped at the PO checkpoint** with MQ-0…MQ-10 (§3.2, §7.3). E–J wait for the answers. Correction §3a was added (D-ENT-2 / FM register).
- 2026-10-08: **PO E0a-2 commission** re-scoped Phases E–J as reconcile → discover → decide → trace. Delivered `reviews/2026-10-08-membership-architecture-reconciliation.md`, `…-e0a2-question-register.md` (EQ-1…16, gates G0–G6) and `…-e0a2-membership-discovery.md` (cards, legacy→target mapping, proportionality). No decision taken; G1/G3/G4/G5 OPEN. Next decision: EQ-1 (+EQ-2).
- 2026-10-08: **PO commission "Membership Development Theme"**: wrote `reviews/2026-10-08-e0a2-membership-development-report.md` (existence analysis, authority trace, admission, multiplicity/INV-MULT-1, lifecycle, suspension vs restriction, termination, temporal, type/INV-TT-1, dues publisher, MembershipFacts v0, audit, candidate aggregate, REQ-MEM-001…012, decision delta, legacy→target, context map, 10-point summary). **STOPPED for Principal Reviewer review.** Flagged: the commission's "Membership ≠ Member" vs DECIDED EM-VOC-001; Finance is downstream of Membership (contrary to the commission's assumption).
- 2026-10-08: **Principal Review phase (falsification)**: wrote `reviews/2026-10-08-e0a2-membership-identity-and-lifecycle-falsification.md`. Rejoin=new-id: INSUFFICIENT EVIDENCE; Model B falsified as sole identity (30,015/30,015); identity (D-ID, business form = membership-number persistence) precedes EQ-7/8; lapse derived; I2 termination closes the interval; approval-number test runs FAILED (regression). STOPPED for the Principal Reviewer.
- 2026-10-08: **Decision Engineering phase**: wrote `reviews/2026-10-08-e0a2-membership-decision-pack.md`. Self-corrected: Model B not falsified (dated artefacts); I2 downgraded (I2′ derived); N-U is a hypothesis. D-ID factorised into D-ID.1 × D-ID.2 (A/B/B′/C). Minimum set = D-ID.1, D-ID.2, D-TERM (w/a), D-SUSP, EQ-2′ + confirmations I5, N-U. Defects F-1…F-12 classified (F-4/6/8/11/12 model-neutral). STOPPED for the Principal Reviewer.
- 2026-10-08: **Final Decision Gate**: `reviews/2026-10-08-e0a2-final-decision-gate.md`. MUST NOW = D-ID.1, D-ID.2, D-TERM-1, D-SUSP, REC-0, I5. Awaiting the PO / Principal Reviewer.
- 2026-10-08: **E0a-3 synthesis**: `reviews/2026-10-08-e0a3-membership-theory-and-architecture-synthesis.md`. Act-ledger theory; Architecture D candidate; decision-robust core identified. Awaiting the Principal Reviewer + PO.
- 2026-10-08: **E0a-4 stress test**: `reviews/2026-10-08-e0a4-principal-falsification-and-architecture-stress-test.md`. Outcome B: D survives with amendments (O3 facts, 3 clocks, term segments, g1+g2, authority snapshot, RestrictionSet, number uniqueness outside). Awaiting the Principal Reviewer + PO.
- 2026-10-08: **E0b finalisation**: `reviews/2026-10-08-e0b-membership-architecture-finalization.md`. CONDITIONAL-FINAL; canonical D + B′; INV-9 interval-disjoint lifetimes (interval g1/g2). Awaiting PO confirmations (§29) + Principal Reviewer ratification (§24).

## 8. Risks

- **Re-litigating settled decisions** (F1, the frozen baseline). Mitigated by the source tags and §3 C-1.
- **Inventing a vocabulary parallel to existing documents.** Mitigated by Phase A coming first and by C-3.
- **Analysis without business input.** Many answers in D and E are organisation knowledge that the code cannot supply. These become `[PO question]`s and are **not guessed**.
- **Size.** 158 prior documents. Phase A may use read-only Explore agents for indexing, while all judgment stays in the main session.
- **Discovery being used to delay real defects.** `257`/`202`/`203` stay independently authorisable.

## 9. Open questions for the PO (before or at approval)

1. Approve this plan as written, including §3 C-1 (treat F1 as decided input)?
2. Placement: confirm `docs/publicdigit/discovery/` as a new sub-folder (governance PENDING), or use `docs/publicdigit/reviews/`.
3. Is there a real customer organisation whose membership rules the discovery should use as its reference case (for example the NRNA or Namaste structure), or should it stay generic?

## 10. Next actions

On approval, Phase A begins. Phases B–J then follow in order. A checkpoint with the PO comes after D, because the constitution questions must be answered before E–G can be completed.

**Traceability:** review `2026-10-08-member-to-election-membership-integration-review.md` · `PBDIGIT-68` (D-ENT-1, Q-A0 = F1, V-1..V-3, Q3) · `2026-08-12-d-ent-1-two-mode-domain-decision-report.md` · ADR_20261004_1549 (G8) · `PBDIGIT-EPIC-02`, `-49`, `-66`, `-96`, `-202`, `-203`, `-209`, `-211`, `-257` · `VoterEligibilityService.php:60-70` · `VoterImportService.php:183`.
