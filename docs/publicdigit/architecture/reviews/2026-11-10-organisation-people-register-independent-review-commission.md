# IR-OPR-01: commission for the independent review of the Organisation people register

| | |
|---|---|
| **Commissioning authority** | The Principal (Product Owner, sole developer, Human ARB Chief, D-94), 2026-11-10, on the Principal DDD Architect's recommendation |
| **Status** | **COMMISSION, READY TO HAND OVER** to a reviewer who is **not** Claude: a human architect or a different model family |
| **Why not Claude** | The design and this commission were written by Claude sessions. **No independent review has taken place** |
| **Precedent** | `2026-11-06-ir-oda-01-v1.2-review-commission.md` (same form) |
| **Prepared by** | Claude (a session other than the design's author). **The design itself was not edited.** **The review cannot be conducted by Claude**; this session only prepares it |
| **Reviewer disclosure** | The reviewer states any prior involvement with the design and separates independent findings from inherited assumptions |

---

## 1. Review package (fixed by fingerprint, SHA-256 first 16 hex digits, 2026-11-10)

| # | File | Fingerprint | Role |
|---|---|---|---|
| 1 | `docs/publicdigit/architecture/reviews/2026-11-10-organisation-people-register-strategic-and-tactical-design.md` | `c52069ff8e7048a1` | **Object of the review: revision 3.** *(Refreshed 2026-11-10: the earlier fingerprint `4daac9aef644ee57` was taken when the title still read "revision 2"; the author's session then renamed it "revision 3", with the same D-111 and §13 content.)* **Review the text as fingerprinted** |
| 2 | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` | `0814bf965b381c24` | Decision record: **§11.35 … §11.41 (D-101 … D-112)**; §0 for evidence labels ([PK] is practical knowledge, not documentary fact) |
| 3 | `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` | `2504e86ef0ab9b7f` | BR-1 … BR-14, including **BR-10** and its amendment note |
| 4 | `docs/publicdigit/architecture/reviews/2026-11-10-organisation-strategic-baseline-v1.0.md` | `22d763aa25638fe8` | **Accepted** Organisation strategic baseline (D-106, revision 2c), **with amendment note A-1 (D-112)** |
| 5 | `docs/publicdigit/reviews/2026-11-10-organisation-strategic-baseline-acceptance-act.md` | `4c1ab17c728d4e9e` | Acceptance act (D-106) |
| 6 | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` | `8877050439c1c007` | **Ratified** Membership (D-96), including the **CR-01 addition** (C1 `MembershipCoverageResolved`, C2 `CoverageKnownAt`) |
| 7 | `docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md` | `a67aa2ce026ff70c` | Identity act XD-3 (D-75) |
| 8 | `docs/publicdigit/architecture/reviews/2026-11-10-identity-strategic-reconciliation-addendum.md` | `d4adc2f93765312c` | Identity context (P-I1) |
| 9 | `docs/publicdigit/architecture/reviews/2026-11-10-identity-principal-decision-dossier.md` | `d21a27c4971cf179` | Identity decisions in preparation |

**If any fingerprint differs when you start, stop and report:** the text has moved.

## 2. Authority to respect (corrected 2026-11-10: statuses individually checked)

**Each decision keeps its own status. Do not treat them as one class.**

| Status | Decisions | Reviewer may |
|---|---|---|
| **Ratified architecture** | D-93 (Reference Geography); D-96 (Membership, 2026-10-08), including the **CR-01** addition (C1, C2) | Respect; report a contradiction, never reopen |
| **ARB acts** (Principal as Human ARB Chief, D-94) | D-67 (one Adjudication context); D-73 (CR-01 and the knowledge-time contract); D-75 (XD-3 Identity act); D-77 | Respect |
| **Accepted baseline** | **D-106** (Organisation strategic baseline, revision 2c, eight conclusions) with **amendment note A-1 (D-112)**, which changes the reading of BR-2, BR-8, BR-12 and the "User" capacity **without rewriting D-106** | Respect its eight conclusions; report a contradiction |
| **Principal rulings** (answers to explicit questions; binding as current rulings, **not ratified in a baseline**; the decision record's §11 header classes them as working decisions) | D-71, D-79, D-80, D-97 … D-101, D-103 … D-105, **D-107 … D-110**, D-112 | Respect; report a contradiction or an unintended consequence for the Principal |
| **Accepted working architecture direction (WD)** | **D-102** (Identity's responsibility boundary); **D-111** (single platform identity, independent organisational data ownership) | **Challenge where the evidence warrants**; a direction, not a ratified decision |
| **Working decisions (provisional)** | D-1 … D-30 (notably D-3, D-22, D-29) | Challenge freely |
| **Approved business rules / PO directions** | BR-1 … BR-8, BR-12 … BR-14 (as read under D-107, D-108); **BR-10, BR-11 are PO directions** | Respect approved rules; challenge directions |

**To watch:** the design's own label key uses one label, "WD", for "binding Principal ruling **or** working decision". Read each cited decision's status from this table, not from that label.

**A proposal is not ratified because it is coherent.**

## 3. Questions to answer

**A. Strategic coherence.** Are these ten things kept apart, with one owner each?
1. human identity;
2. Account;
3. organisation-local person record;
4. profile;
5. affiliation;
6. Membership;
7. unit belonging;
8. voter-list inclusion;
9. workspace access;
10. Governance authority.

Report duplicated ownership, contradictions and missing concepts.

**B. Aggregate boundaries.** The design separates `OrganisationPerson`, `Affiliation` (one aggregate per occurrence) and `WorkspaceAccess`.
- Challenge the rationale (independent authorities, evidence, lifecycles).
- **In particular: the stated non-overlap of periods for the same person and capacity.** Can it be enforced across separate aggregate instances? **Example:** worker relationship A runs January–June and B runs April–September.
- Identify every cross-aggregate invariant that is claimed but not demonstrated.
- **No schemas or locks.** State the consistency requirement and the architectural alternatives.

**C. Archival.** After a person is archived, **can an active affiliation or active workspace access survive?** Consider:
- an affiliation or access added while archival is pending;
- handler failure and retry;
- one relationship ending while another stays;
- restoration, if permitted.

State the guarantee the model must give. **"A handler eventually processes the event" is not a proof.**

**D. Import.** Is creating the person record kept distinct from recording the voter relationship, row processing, retry, partial failure and duplicate rows? **What is the business outcome when the record exists but the voter relationship was not recorded?** Establish the requirement before any mechanism.

**E. D-110 and D-111, duplicates.** Distinguish:
- the intended canonical Account;
- **duplicate Accounts that may exist** (no e-mail or name matching is allowed);
- Identity's explicit merge;
- an organisation's local duplicates and its own decision after a merge.

**Flag any claim of automatic uniqueness.**

**F. BR-10 and P-I1 (mandatory).** "Identity creates **or resolves** the Person" at import, without prohibited matching. Compare:
1. eager creation at import;
2. lazy, with an organisation-local placeholder;
3. eager creation with restricted discovery;
4. creation only at claim.

For each, assess:
- business consequences;
- identity correctness;
- duplicates;
- privacy and minimisation;
- organisation independence;
- voters who never register;
- the claim and merge lifecycle;
- effects on Membership and Election;
- consistency with D-107 … D-111 and XD-3.

**Recommend, with assumptions. The Principal decides the business intention.**

**G. Contracts.** Check every cross-context contract against its source:
- Identity and `PersonRef`;
- CR-01 C1/C2;
- Governance competence;
- Election's consumption of voter relationships (D-109);
- Authorization's enforcement of access (D-104);
- Geography and address references (D-93, D-101);
- D-73.

**Mark anything unsourced UNKNOWN; invent no contract.**

**H. Scenarios.** Add only scenarios that expose a missing rule or invariant. **Stay out of unit belonging, areas, coverage and Governance** (separate capabilities; see §50 of the 2026-11-08 model document for belonging).

**I. O-7, voter relationship scope.** Election-specific versus a standing register. Consequences for operations, election preparation, maintenance, historical reconstruction and Election's eligibility determination. **State the business evidence the Principal needs; assume no universal practice.**

## 4. Required output

1. Executive conclusion: sound · conditionally sound · unsound.
2. The design in business language.
3. Strategic boundaries.
4. Aggregate boundaries.
5. Lifecycles and invariants.
6. Contracts.
7. BR-10 / P-I1 comparison and recommendation.
8. O-7 alternatives and the evidence needed.
9. Omissions, contradictions, risks.
10. **Genuine Principal questions, separated from technical choices.**
11. Proposed amendments, with reasons.
12. What the review does **not** establish.

**For every finding, give:**
- its source evidence;
- whether it is a fact, an inference or a recommendation;
- its impact;
- whether it needs a Principal decision.

## 5. Restrictions

- No code, classes, schemas, locks or transaction mechanisms.
- No event sourcing assumed because events are mentioned.
- No change to ratified architecture.
- No UNKNOWN turned into fact.
- **Session conclusions are not independent evidence.**
- No ratification claimed; no implementation authorised; no plans, tickets or status updates.

## 6. After the review

Return the report to the Principal. **The Principal decides; the design's author then incorporates the accepted findings.**

## Appended note for the reviewer (2026-11-10, Claude; append-only, the commission above is unchanged)
Add to item **F (BR-10 and P-I1)**:
1. **Option 4 means:** "**reference issued at import, unbound; Person created or found at claim**". It is not lazy creation without a reference.
2. **Narrowing finding:** a `PersonRef` is needed **at import** by Membership (RAT; D-77 `CoverageKnownAt(personRefs[])`), by unit belonging (Session B model §50.3) and by an electorate. Option 2 (an organisation-local placeholder) would require re-keying ratified Membership facts. **BR-10's "issues the reference" holds in every viable option;** only the timing of the canonical Person is open.
3. **Two readings of "canonical Person" to assess:** (R1) an identity-resolution record for every human any organisation knows, registered or not (BR-10); (R2) the platform identity of a registered human, with organisations holding references meanwhile (option 4; D-111's wording). XD-3 §3 leaves K4 open; neither reading follows from the word "Identity".
4. **Privacy weight:** party membership can reveal political opinions (GDPR Art. 9 special category). Assess it as an input, not as the deciding argument; a legal assessment is separate.
5. **Further sources:**
   - `./2026-11-10-organisation-strategic-discovery-completion-decision-brief.md` §5.1, §8;
   - `./2026-11-10-identity-pre-tdd-adversarial-review.md` §3;
   - decision record D-107…D-111 (§11.39, §11.40).
