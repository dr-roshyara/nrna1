# Identity: strategic reconciliation addendum (D-100, D-101)

| | |
|---|---|
| **Status** | **PROPOSED addendum.** Decides nothing; ratifies nothing; does not modify D-100 or D-101. Not independently reviewed (Claude family; the author also wrote the documents it corrects). |
| **Commission** | Principal DDD Architect commission "Identity Strategic Reconciliation and Principal Decision Dossier", accepted 2026-11-10. Deliverable A of three (B: `./2026-11-10-identity-principal-decision-dossier.md`; C: `./2026-11-10-identity-i-tdd-02-scope.md`). |
| **Corrects** | `./2026-11-10-identity-context-architecture-maturity-review.md`; `./2026-11-09-identity-participant-access-strategic-discovery.md`; `./2026-11-09-i-tdd-01-identity-participant-access-tactical-design.md`. All three are PROPOSED and uncommitted. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`; the Organisation strategic baseline v1.0 and its findings are **untracked** (another session). No code changed. |

**Labels:** **RAT** ratified (ARB act; D-94: the Principal is the ARB) · **WD** working decision. The decision record's §11 header classes direct Principal rulings as working decisions: binding current rulings, not ratified. That is how the Organisation baseline v1.0 reads them (its §0 key, `:21`). · **P** proposal · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. The sources and their authority

| Source | Status | Authoritative for |
|---|---|---|
| Membership act §11 (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:309-313`) | **RAT** (D-96 confirms) | "Identity owns Person identity and identity equivalence" |
| XD-3 act (`docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md`) = D-75 | **RAT** (D-94: stands as recorded) | canonical Person; per-organisation `PersonRef`; Person ≠ Account; no e-mail linking; C-1…C-5 binding |
| XD-3a (C-1…C-5), XD-3b (§2a), XD-3c (R-XD3c-1…5) | **accepted through D-75** (append-only notes `:106`, `:130`, `:91`); headers still say PROPOSED (stale) | as above |
| XD-PROV (`2026-11-02-identity-xd-prov-…`) | **P** (Principal chose organisation-local provenance 2026-11-02; AM-1 r2 accepted) | deletion authority: proposal |
| D-100 (DR §11.34, `:821-825`) | **WD** (direct ruling 2026-11-10) | the meaning of Organisation; "a platform record is not an Organisation"; federations of natural persons |
| D-101 (DR §11.35, `:827-831`) | **WD** (direct ruling 2026-11-10) | affiliation and person-profile ownership; discovery left to Identity's tactical design |
| D-84, D-88, D-90 | **WD** (direct rulings) | steward independence: "a distinct canonical person" (D-88); Identity "establishes" sameness (D-90) |
| Affiliation discovery evaluation (2026-11-01) | research | L1/L2/L3 analysis; feasibility not established |
| Context map (`docs/publicdigit/architecture/context-map.md`, last committed `feccc988d` 2026-11-02) | P (consolidation) | R-01…R-03 (stale, §3) |
| Organisation strategic baseline v1.0 (`./2026-11-10-organisation-strategic-baseline-v1.0.md`, untracked) | **P**, revision 1, not accepted | Organisation's reading of D-100, D-101 (§5, §7) |
| Discovery, I-TDD-01, maturity review (2026-11-09/10) | P (self-authored) | — |

**Consistency check:** the Organisation baseline v1.0 §5 (`:139-212`) matches D-101 and XD-3. It assigns the person profile and affiliation to Organisation, Identity only the canonical person, leaves discovery undecided, and records that the stricter INV-AFF-1 wording was not adopted. **No conflict found.**

## 2. What D-100 and D-101 settle for Identity

| | Concern | Owner | Status | Source |
|---|---|---|---|---|
| A | **Affiliation ownership**: a person's relationship with an organisation, in a capacity (User, Member, Worker, Guest, voter) | each **Organisation**, for its own people; never disclosed to another | **WD** (settled) | D-101 |
| B | **Affiliation discovery at sign-in** ("which organisations can I enter?") | **Identity**'s mechanism within C-1…C-5; L1 and L2 both open; INV-AFF-1's "no persisted central register" **not adopted** | **open** (mechanism) | D-101; XD-3a C-4 |
| C | **Person identity and equivalence** | **Identity** | **RAT** | act §11; D-75 |
| D | **Organisation-local person profile**: name, contact, address as supplied, its components and resolution | **Organisation** (not Membership; staff and guests have addresses) | **WD** (settled) | D-101; V-54 |
| E | **Membership status** | **Membership** | **RAT** | RA |
| F | **Access permission for an operation** | the owning business context; "platform access permissions do not constitute organisational authority" | **WD** (Decision A); per-context rules | D-100 principles; Organisation baseline §2 |

**What changed in substance:**
1. **Ownership is no longer an Identity question.** P-I2 shrinks to the mechanism only.
2. **C-4 permits a minimised central index, subject to data-protection review** (XD-3a `:47`: "The central index of which organisations a person may access is minimised and undergoes data-protection review"). The stricter "none" was a proposal (INV-AFF-1) that D-101 explicitly did not adopt. A stored minimal mapping (L1) is therefore admissible in principle; L2 remains the option that stores less.
3. **The "platform organisation" is not an Organisation (D-100).** Registration's automatic `member` role in it (`RegisterController.php:41-63`, FACT) is an affiliation to something that is not an Organisation (see P-I12).
4. **Federations (D-100):** a federation is a separate Organisation. A delegate is affiliated with the federation in the federation's own records; the federation gets its own `PersonRef` and sees nothing of the member organisation's records.

## 3. Stale or overstated statements, corrected

| # | Document, place | Stale statement | Corrected reading | Cause |
|---|---|---|---|---|
| S-1 | maturity review §1, §4.1, §5 | affiliation owner "**P**: INV-AFF-1, referred to the ARB" | **settled, WD, D-101: Organisation**; only the discovery mechanism is open | D-101 made after the review |
| S-2 | maturity review §4.1; discovery §2 | person profile owner "pending (INV-AFF-1, proposed Organisation)" | **Organisation** (D-101) | as S-1 |
| S-3 | maturity review §11 P-I2 | "who owns affiliation, and how does discovery work" | **mechanism only** | as S-1 |
| S-4 | maturity review §11 P-I1 | "eager is privacy-safe only with L2 or K4 iii" | Under C-4 as worded and with INV-AFF-1's strict wording not adopted, eager with a minimised, data-protection-reviewed index (L1) is admissible. L2 or K4 iii stores less; it is not the only lawful-by-architecture route. | over-reading of INV-AFF-1 |
| S-5 | I-TDD-01 §2.1 T3; INV-ID-1 rationale | "no organisation is recorded on the Person (C-4)" | C-4 requires **minimisation and review**, not absence. Whether Identity stores an organisation mapping is P-I2. | as S-4 |
| S-6 | I-TDD-01 §2.1 `anchored`; P-I7 | the flag is needed so that no organisation is recorded | Under L1 the flag is **redundant** (the mapping exists); under L2 it is a minimal option. **P-I7 depends on P-I2.** | as S-4 |
| S-7 | maturity review §4.2, §11 P-I12 | the platform-organisation role is "affiliation without consent" | Stronger: under D-100 the platform record **is not an Organisation**, so it can hold no affiliation at all | D-100 after the review |
| S-8 | maturity review | did not read the Organisation baseline v1.0 | baseline read; consistent (§1) | not yet in scope then |
| S-9 | context map R-01 | "`PersonRef` form: XD-3a (PROPOSED)" | settled by D-75 §2.2 | XD-3 act after the map's last update |
| S-10 | context map R-02 | "PROPOSED … XD-3a / b / c (PO + ARB)" | `PersonRef` per organisation and claim-binding: **RAT** (D-75); affiliation owner WD (D-101); discovery open | as S-9 |
| S-11 | `strategic-design-status.md:60` | XD-3a/b/c and INV-AFF-1 "PROPOSED … referred to the ARB" | XD-3a/b/c accepted (D-75); INV-AFF-1 ownership part answered (D-101), wording not adopted | the status page was last updated 2026-11-08 |
| S-12 | discovery §0.2; maturity review §6.4 | the D-90 equivalence query is unmapped | still unmapped; now analysed (§5 below) | — |

**Minimum updates to restore consistency (REC; none made here):**
1. Context map rows R-01 and R-02: the per-organisation `PersonRef` and claim-binding as RAT (D-75); affiliation and profile to Organisation (D-101); discovery open. Add a new row: Identity → Reference Geography (§5).
2. `strategic-design-status.md:60`: XD-3a/b/c accepted via D-75; INV-AFF-1 ownership answered by D-101.
3. One append-only correction note on each of the three self-authored Identity documents, pointing here (S-1…S-8).
4. XD-3a/b/c headers: none needed (the notes at the end already record acceptance).

## 4. Relationships: what Identity provides to each context

| Relationship | Owner of the fact | What crosses | Direction | Authoritative or reference | Sync / async | Must never cross | Unknown |
|---|---|---|---|---|---|---|---|
| Identity ↔ **Organisation** | Organisation: affiliation, profile (D-101). Identity: Person, `PersonRef` (D-75) | `PersonRef` issued to the organisation; claim issuance and redemption outcome (`ClaimRedeemed` to the issuer only) | Identity → Organisation (references); Organisation → Identity (claim requests) | `PersonRef`: authoritative **identifier**, not proof of identity or authorisation (Organisation baseline `:212`) | not established | another organisation's references, affiliations, profiles; any profile data into Identity | the discovery mechanism (L1/L2); the claim delivery channel |
| Identity ↔ **Membership** | Membership: membership facts (RAT) | `PersonRef` only | Identity → Membership | reference | not established | names, contact, accounts | P-I1 (whether imported members have a `PersonRef` at once) |
| Identity ↔ **Governance** | Governance: competence and authority | authority evidence for officers issuing claims (P); requests for account review (R-XD3c-3, RAT) | Governance → Identity (authority); Organisation → Identity (requests) | authority evidence is Governance's | not established | — | which competence issues claims (an organisation rule) |
| Identity ↔ **Election** | Election: eligibility, entitlement, rights issuance | `PersonRef` (per organisation) | Identity → Election | reference | not established | anything to Voting; equivalence beyond the double-vote check | R-03 (P-I8) |
| Identity ↔ **Voting** | Voting: ballots (RAT VO-1; D-44, D-45) | **nothing** | none | — | — | any identity, `PersonRef` or Account | — |
| Identity ↔ **Reference Geography** | Geography: approvals and their validity (D-84, D-88, D-90) | its stewards' references; "same / different / unknown" answers; merge notices for its own references | both (§5) | Identity's answer is authoritative for "distinct canonical person" (D-88) | not established | anything about organisations | the whole contract (P-I9) |

## 5. Reference Geography and D-90 (analysis for P-I9)
- **The business meaning (FACT, D-84/D-88/D-90):**
  - Stewards are appointed by the **platform owner**, not by an organisation.
  - From the second steward on, a structural change needs approval by "a distinct canonical person" other than its preparer (D-88).
  - If Identity later establishes that the two were the same person, the approval is flagged "approval-defective" and history is kept (D-90).
- **Consequences:**
  1. The question concerns **platform-level actors**, not organisation participants. Organisations are not parties to it.
  2. With 0..1 Account per Person, two different Accounts are two Persons **as far as Identity knows**. Sameness surfaces only through a merge (C-5). So the D-90 trigger is an **Identity merge**, and the ex-ante check reduces to "not the same Account and not already merged".
  3. **Minimum safe capability (option for the Principal):**
     - Geography holds **its own reference namespace**, as an organisation holds its own `PersonRef`s.
     - Identity answers only "**same / different / unknown**" for **two references in the same namespace**.
     - After a merge, Identity tells each namespace holder **only about its own references** that became equal.
     - No one can ask about references in another namespace, so no organisation can use the capability to discover anyone.
- **Open points (for the dossier):**
  - which callers may ask (Geography's governance function only?);
  - the recorded purpose;
  - logging and audit;
  - treating "unknown" (for example a pending merge review) as "not established as the same": D-90 already keeps the approval valid until shown otherwise.

## 6. Updated maturity (after D-100, D-101)

| Area | Verdict | Condition for "complete" |
|---|---|---|
| **Strategic DDD** | **substantially complete; ownership now settled; limited open decisions remain**: K4 (P-I1), the discovery mechanism (P-I2), recovery and security policy (P-I3), equivalence queries (P-I9), Election reference (P-I8), XD-PROV, legal (OQ-3a-1, OQ-3b-2) | P-I1, P-I8, P-I9 decided; XD-PROV ratified or amended; context map consistent; legal basis recorded. P-I2 and P-I3 may stay open if their seams are explicit. |
| **Tactical DDD** | **one proposed iteration** (I-TDD-01) for one slice, partly built on the over-read C-4 (S-5, S-6) | I-TDD-02 resolves the gaps (deliverable C), passes an independent review, and the Principal accepts it |
| **Technical architecture** | **not started** | P-I11 decided; authentication port; persistence; the migration of `users` (profile data moves to Organisation, D-101) |
| **Existing implementation** | legacy, conflicting (e-mail matching; platform-organisation role; invitation-created membership) | the conflicting flows are replaced or retired under authorised slices |
| **Security and privacy** | 339 and 340 closed; 341 live | 341 resolved; claim flow live |
| **Context integration** | R-01/R-02 stale; R-03 open; Geography relationship new | map updated; R-03 and the Geography contract decided |
| **Implementation readiness** | not ready for the claim slice | P-I1 and P-I11 decided; I-TDD-02 accepted; EP-01 plan approved |

## Traceability
DR §11 D-75, D-84, D-88, D-90, D-94, D-96, D-100 (`:821-825`), D-101 (`:827-831`); XD-3 act §2, §3, §5; XD-3a `:27`, `:44-48`; Organisation baseline v1.0 `:6-21`, `:38`, `:80-83`, `:139-212`; context map R-01…R-03; `strategic-design-status.md:60`; `app/Http/Controllers/Auth/RegisterController.php:41-63`; `app/Models/Organisation.php:179, :193-195` (platform type).

## 7. Incorporated later on 2026-11-10: D-102 (Identity's responsibility boundary, WD)

D-102 (DR §11.36) records the accepted working direction; it is **not ratified**.

| Responsibility | Owner | Note |
|---|---|---|
| Registration and Account creation | **Identity** | creates no affiliation or Membership (D-100, D-101) |
| Verification of the **login address** | **Identity** | contact addresses: the organisation's profile (D-101); an applicant's e-mail confirmation (PD-M2) is the organisation's |
| Authentication, login, sessions | **Identity** | — |
| Password reset and recovery | **Identity** | recovery without the login address: P-I3 open; organisations may never re-bind (INV-ID-6) |
| Account suspension and lifecycle | **Identity** | R-XD3c-1…5 |
| Claim (Person ↔ Account) | **Identity** | D-75 |
| Sign-in discovery mechanism | **Identity** | undecided (D-101; P-I2) |
| Selecting an organisation | session working context | grants nothing |
| Access to an organisation's workspace | **Organisation**'s rules | supported by Identity's authenticated session |
| Screens and journey orchestration | application / presentation layer | **must not contain Identity's rules** |
| Affiliation; Membership; eligibility; ballots; authority | Organisation; Membership; Election; Voting; Governance | unchanged |

**Effect on the other Identity documents (no rewrite; REC):**
- **Maturity review §7 (Keycloak):** consistent; the authentication port now follows from D-102.
- **I-TDD-02 scope:** `RegisterWithClaim` includes login-address verification inside Identity. The general registration journey without a claim is a **separate later design item**, not part of the first capability.
- **Adversarial review (`./2026-11-10-identity-pre-tdd-adversarial-review.md`):** consistent with D-102. Its P-I1 analysis and option (d) are unaffected. Only P-I1 still blocks I-TDD-02.
- **Strategic maturity:** the responsibility boundary moves from "proposed" to **WD**. The remaining strategic items are unchanged: K4/P-I1, the discovery mechanism, recovery and session policy, P-I8, P-I9, XD-PROV, legal.
