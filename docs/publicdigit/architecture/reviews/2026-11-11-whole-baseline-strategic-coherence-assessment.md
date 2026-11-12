# PublicDigit whole-baseline strategic coherence assessment

| | |
|---|---|
| **Status** | **Assessment.** Records no ruling; changes no ratified text or code. Written by Claude, **not independent** |
| **Commission** | "Whole PublicDigit Baseline Strategic Coherence Assessment", accepted by the Principal |
| **Builds on, does not repeat** | `2026-11-10-strategic-baseline-consolidation-review.md` (whole platform); `2026-11-10-integrated-baseline-architecture-review.md` (+ Addendum A); `2026-11-10-membership-c1-c2-contract-conformance-review.md`; `2026-11-11-authoritycheck-organisation-governance-contract-review.md`; context map §16, §16.6; decision record D-1 … D-162 |

**Labels:** [RAT] · [AD] · [BF] · [PK] · [AC] · [TD] · [TU] · [WD] · [P] · [H] · [OQ] · [LQ].

## 0. Disagreements (first)

**None of substance.**
- One correction to my own earlier work comes from the brief's point 3.3 (§6 B-3).
- **The headline finding (B-1) was not anticipated by any earlier review, including mine.**

## 1. Executive conclusion

**The baseline is coherent, with specific open questions.**
- Every important business fact has one owner.
- No context needs adding.
- The contexts collaborate through checked contracts (C1/C2 + CR-02, AuthorityCheck).

**One finding cuts across accepted decisions and needs the Principal:**
- **B-1: release 1 cannot keep members in good standing.** The ledger has no renewal or suspension yet, and D-161 makes posting and receiving newsletters depend on good standing.
- **The rest are tactical corrections, deferred processes or implementation and proof obligations.**

## 2. Business-domain completeness

**Established [AD]:**
- PublicDigit serves **self-governing organisations with written rules** (D-97 … D-100).
- **Authority comes only from those rules and their competent bodies' decisions**; PublicDigit records, never creates, authority (D-1a, D-106, D-148, PO-ORG-MEM-01).
- **Six distinct notions are kept apart:**
  - **identity** (Identity);
  - **organisational record and belonging** (Organisation);
  - **membership** and its **coverage** (Membership);
  - **good standing** (Membership, through CR-02);
  - **office and authority** (Governance);
  - **electoral eligibility** (Election).
- **Constitutional authority ≠ technical permission** (D-104, BDR-07).

**Unclear:**
- **how a membership's term is set and renewed in release 1** (B-1);
- **how a whole organisation is dissolved** (B-5, deferred);
- each organisation's statute content (UNKNOWN, O-8 … O-10).

## 3. Bounded-context ownership (consolidated; detail in the integrated review §3)

| Context | Owns | Never | Maturity |
|---|---|---|---|
| Identity | Person, Account, login, claim, `PersonRef` | affiliation, membership, authority | **understood**; claim code (I-TDD-02) pending; legacy e-mail matching (PBDIGIT-341) open |
| Organisation | registry, profiles, contacts, capacities, units, belonging, workspace access | membership truth, authority | **understood**; tactical ACC (D-156) |
| Membership | periods, facts, coverage, standing | belonging, entitlement, authority | **understood** (RAT); **kernel incomplete (B-1)** |
| Governance | rules on file, committees, offices, terms, decisions, AuthorityCheck | structure facts, membership, permissions | **understood**; thin slice ACC (D-157), round 7 P |
| Communication / Deliberation | newsletters / forums | audiences' facts, authority | **understood** (D-158, D-161) |
| Election / Voting | electorate, entitlement, issuance / ballots, secrecy | membership, belonging | **understood** at boundary; tactical WD; outside release 1 |
| Adjudication | challenges, determinations | remedies' application | **understood** (ARB D-67); release 1 handles challenges manually |
| Reference Geography | official references | belonging, organisation areas | **understood** (RAT D-93); **dormant in release 1** |
| Finance | payments, settlement | standing, eligibility | **partial**: FeePaid direction OPEN (ARB act) |
| Authorization | enforcement | business truth | scope OPEN (XD-4) |
| Platform / tenancy | working organisation, scoping | authority | owner OPEN (HD-8) |

**No ownership conflict found.** The pairs the commission names are separated by recorded decisions:
- Identity / Organisation: D-111;
- Organisation / Membership: C0, D-142;
- Organisation / Governance: D-29, D-144, the AuthorityCheck review;
- Governance / Authorization: D-72, BDR-07;
- Membership / Election: ratified boundary;
- Finance / Membership: RAT outline, direction OPEN;
- Geography / Organisation: D-106 §6, A-8;
- Tenancy / business contexts: E-11.

## 4. Context-map coherence

- **Contracts checked field by field:**
  - Membership → Organisation (C1/C2 + CR-02);
  - Governance ⇄ Organisation (AuthorityCheck: push for committee decisions, pull for office holders' acts);
  - Organisation → Communication and Deliberation (belonging + standing, D-158 / D-161).
- **Cycles:** Organisation ⇄ Governance carry different facts each way. **The runtime cycle is avoided** because the caller supplies the ancestor chain (AuthorityCheck review §5).
- **Coupling watched:** Governance ⇄ Membership (authority in, C3 facts out; CR-01 §6).
- **Missing contracts (known, not release 1):**
  - FeePaid (Finance ↔ Membership);
  - Organisation → Election (R-5 / T-8 mechanism UNKNOWN);
  - Organisation → Authorization (D-104 contract UNKNOWN).

## 5. End-to-end processes

| # | Process | Explainable without inventing rules? | Basis |
|---|---|---|---|
| 1 | Registration and account | **Yes** | D-110, D-111, D-113 |
| 2 | Organisation onboarding | **Yes** (ledger from joining, registrar by act) | D-148, D-160 |
| 3 | Recording existing structure | **Yes** (with B-4) | D-159 |
| 4 | Application and admission | **Yes**; **the term at admission is open (B-1)** | D-149, AuthorityCheck; interim adapter excluded |
| 5 | **Standing and suspension** | **No: B-1** | D-161 vs kernel |
| 6 | Placement | **Yes** | D-154, §50 |
| 7 | Unit creation and change | **Yes** | §49, D-29, D-154 |
| 8 | Committees, appointments, terms | **Yes** (elections of office holders via Election, outside the package) | D-144, D-145, D-23, D-28 |
| 9 | Authority checks and decisions | **Yes** | D-157 + review |
| 10 | Forum and newsletter | **Yes, once B-1 is resolved** | D-158, D-161 |
| 11 | Challenges | **Partly:** the default reviewer is "the tribunal" (D-53); **an organisation may not have one recorded.** Release 1 records challenges manually | D-53, D-67; UNKNOWN per organisation |
| 12 | Election electorate | **Yes** at strategic level (outside the package) | ratified boundary; D-123 … D-139 |
| 13 | Voting and anonymity | **Yes** | VO-1 (FRZ) |
| 14 | Finance and membership | **Partly:** FeePaid direction OPEN; renewal linked to payment by organisation rule (PD-M3) | RAT outline |
| 15 | Structure change / organisation closure | Unit changes **yes**; **dissolving the whole organisation: no design** (D-120 says only "a separate act") | B-5 |

## 6. Gaps (classified)

| # | Gap | Class | Contexts | Significance |
|---|---|---|---|---|
| **B-1** | **The ledger kernel implements Admit, End, Annul, Void and Correct only: no Renew, Suspend, Restore** [BF]. **An admission without an initial term is "lapsed" at once** (`Admit.php`: "no default term"). Release 1 (ledger only, D-160) excludes renewal (mobile baseline "not now"). With **D-161** (good standing to post and receive): **members admitted without an open-ended term never post or receive; members with a finite term lose it permanently at expiry; suspension cannot be recorded** | **Missing business rule + implementation gap** (ratified facts not built) | Membership, Communication, Deliberation | **Release 1 would not work as intended.** Principal question §8 |
| B-2 | `competenceVerified` mapping. The code's meaning (`AuthorityEvidence.php`): "**`false` marks a technical application-role check only**". So it means "**evaluated by Governance, not an application-role proxy**", **not** "worked out from structured data" | Contract (tactical) | Governance, Membership | **My mapping in the AuthorityCheck review was wrong.** Correct: `competenceVerified = competenceKind ∈ {workedOut, asserted, contradiction}`; **false only for `interimRole`**. One meaning per field (§7) |
| B-3 | **The registrar's recording authority versus the original act's authority** (D-148, D-159): recording a province does not prove the congress had authority to create it | Contract (tactical) | Governance, Organisation | **Two layers of basis:** (1) the AuthorityCheck answer covers **only the registrar's authority to record**; (2) the recorded unit's own basis is **"recorded from evidence of act X"** (minutes etc.), **not checked, challengeable** (§7) |
| B-4 | The ancestor chain must be the structure **as at (d, τ) of the act**, not "now" | Tactical | Organisation | Already in the review; restated as a binding note (§7) |
| B-5 | **Dissolution of a whole organisation** (D-120 "separate act"): what happens to members, records, forums, data | Missing process; **[LQ]** retention and export | all | **Not in release 1**; deferred discovery |
| B-6 | Challenges where no tribunal organ is recorded (D-53 default) | Missing rule per organisation (UNKNOWN) | Governance, Adjudication | Release 1 handles challenges manually; the design must say who receives them. **Deferred** with Adjudication's integration |
| B-7 | FeePaid direction; Organisation → Election and → Authorization mechanisms; tenancy owner; Authorization scope | Contract / ownership (OPEN, ARB acts) | Finance, Election, Authorization, tenancy | **Outside release 1**; already registered |
| B-8 | Ledger-clock proofs (D-162); storage constraints (§41.8); legacy coexistence (`is_committee_member`, frozen committee code, legacy geo fields) | Proof / implementation obligations | — | Not architectural gaps |

**Geography:**
- **No gap.** The contract is ratified and dormant (§16.6).
- **The area rules are designed and gated** (D-156); the one open Geography question (defective publications) only matters once area rules are built.
- **Legacy geo fields never decide belonging.**

## 7. Corrections applied with this assessment (tactical; no change of accepted meaning)

1. **B-2:** `competenceVerified` mapping corrected in the thin-slice note.
2. **B-3:** two-layer basis for registrar recordings.
3. **B-4:** ancestor chain as at (d, τ).

## 8. Principal decision (one) — **DECIDED: D-163** (release 1 builds Renew and Suspend / Restore; term per the organisation's rules at admission; authorised office records them via AuthorityCheck; payment outside PublicDigit)

**B-1: how release 1 sets and renews membership terms, and whether suspension is available.** Asked separately.

## 9. Recommendation

**After B-1:**
- the minimum package is coherent end to end, apart from proof and implementation obligations;
- **the next tactical design** is the **Membership kernel slice for the ratified facts B-1 requires** (Renew; Suspend / Restore if chosen), **because both D-161 and the product depend on it.**

**After that:** implementation planning can begin under EP-01, the first increment being structure + Governance core.

## 10. Verdict

**BASELINE COHERENT WITH SPECIFIC OPEN QUESTIONS.**
- **Coherent:** one owner per fact; checked contracts; one time model (D-73, CR-01, Geography); authority never inferred.
- **Open:**
  - **B-1** (Principal);
  - B-5 and B-6 (deferred processes);
  - B-7 (registered ARB items outside release 1);
  - B-8 (proofs).

**Outcome (2026-11-11):** B-1 decided by **D-163**. **The baseline is coherent for release 1, apart from deferred processes (B-5, B-6), registered ARB items outside release 1 (B-7) and proof obligations (B-8).** Next tactical design: the Membership kernel slice for Renew and Suspend / Restore under the ratified algebra.
