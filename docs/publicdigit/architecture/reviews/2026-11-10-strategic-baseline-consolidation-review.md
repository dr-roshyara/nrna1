# Strategic baseline consolidation and consistency review

| | |
|---|---|
| **Status** | **Consolidation review (baseline candidate).** Records no ruling; edits no decision record, baseline, tactical design or code. Written by Claude, **not independent**. |
| **Commission** | "Strategic Architecture Baseline Consolidation and Consistency Review", Principal, 2026-11-10. |
| **Sources (working tree, 2026-11-10)** | decision record `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (D-1…D-139); context map `docs/publicdigit/architecture/context-map.md` (§3 rows `:69-97`, consolidated table `:348-361`); status record `docs/publicdigit/architecture/strategic-design-status.md` §4; Membership ratification act (§10–§12, `:323-372`) and ratified artefact §8; CR-01 (C0–C4); Organisation baseline v1.0 rev. 2c; tactical model §22, §25; `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md`; this session's Election and people-register reviews |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Update (2026-11-11)** | **§11 extends this review to D-164** at the Principal's request ("update the consolidation review to cover D-164"). §0–§10 stay as written for D-1 … D-139; **where §11 differs, §11 governs.** Still records no ruling; still not independent. |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **ACC** (accepted act) · **FRZ** (frozen programme decision) · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. Disagreements with the commission (stated first)
1. **The context list is incomplete.** The commission names eight contexts. The record and context map also contain:
   - **Reference Geography** (RAT, D-93);
   - **Deliberation** (WD, D-50…D-56);
   - **Communication** (WD rules D-57…D-64; context P);
   - the **evidence and integrity contexts** (ACC, EPIC-002);
   - **Authorization**, which is a supporting capability and **not** a context (FRZ, BDR-07);
   - **Platform / tenancy**, whose owner is OPEN (HD-8).

   A baseline that omits them would be silently narrower than the record. They are included below.
2. **Voting does not own "voting-right execution and credentials".** **Election issues rights** (D-46: "the per-network vote limit applies when voting rights are issued, inside Election"; §22: Election records "right issued"). **Voting** owns ballots, the acceptance and consumption of credentials, and anonymity.
3. **VO-1 is broader than "voter identity must not appear in Vote events".** Its ratified statement is "a vote cannot be traced back to the voter who cast it" (Round 29). The event rule is one derivative (CI-5).
4. **Adjudication does not "execute procedural correction".** It issues determinations. **Remedy parts are applied by their owning contexts**, citing the determination (§25; D-67, D-71).
5. **Agreed:** the offline membership area is a **proposed product capability**, not part of the strategic baseline. It already sits in the proposed mobile channel baseline (commit `7a8f35f17`) and needs its own freshness, privacy and device design.

## 1. Executive conclusion
- **Discovery is complete.** No new domain or context appeared after D-94.
- **The decision record is intact** (§6.1).
- **The baseline is consistent** for Identity, Organisation, Membership, Election, Voting and Finance.
- **Four findings need attention before acceptance** (§7). One touches a ratified text (F-1). One is an **older open list that later rulings probably closed but never closed explicitly** (F-4: DR-07b act, XD-4, O-8…O-10, O-14).
- **Verdict:** ready for Principal acceptance **after F-1 and F-4 are settled**. Identity and Organisation tactical work may continue now.
- **Superseded by §11.9 (2026-11-11):** F-1 and F-4 are settled (D-141 … D-144, D-164). Through D-164 the baseline is consistent, with **one clarification for the Principal (H-1)**.

## 2. Business purpose and scope
**PublicDigit is a platform for organisations with written rules (D-97) to run their own affairs digitally.** That covers:
- people and membership;
- geographic structure;
- governance decisions;
- elections and voting;
- deliberation and communication;
- challenges.

Each organisation is independent and isolated (D-111). **It is not just an online voting app.** Voting is one context among many, and it depends on facts the others own.

**Out of scope or not decided:**
- organisations without written rules (D-97);
- employment and HR (D-130);
- postal or paper voting (UNKNOWN);
- erasure policy (XD-PROV, open).

## 3. Contexts and ownership
| Context | Owns facts / decisions | Does not own | Status |
|---|---|---|---|
| **Identity** | canonical Person, Account (0..1), login, claims, `PersonRef` issue, binding and resolution, Person merges | affiliations, profiles, membership, belonging, entitlement, permissions | ARB D-75; WD D-102, D-111, D-113 |
| **Organisation** | register entries and profiles, affiliations (Worker, Guest, standing voter), units and belonging over time, workspace access, organisation areas, **electoral divisions** | membership truth, authority, entitlement, persons, official geography | ACC D-106 (+ D-112); WD D-97…D-139 as listed in §6 |
| **Membership** | membership periods, facts, lifecycle, types | belonging, entitlement, affiliation | RAT (D-96); CR-01 (D-73, D-77) |
| **Governance** (incl. Appointment) | written rules (versions), organs, positions, tenures, decisions, competence, candidacy and voter criteria, authorised voting channels | membership, entitlement | WD (D-38, D-65…); Appointment FRZ (BDR-03) |
| **Election** | electorate and snapshot, eligibility, entitlement, post districts, candidacy evaluation, **right issuance**, holds | membership, belonging, geography, divisions | boundary RAT; WD D-32, D-41…D-49, D-123…D-139 |
| **Voting** | ballots, credential acceptance and consumption, secrecy (VO-1) | entitlement, issuance | FRZ (BDR); RAT VO-1 |
| **Finance** | payments, settlement, refunds, credits | membership standing, eligibility | RAT (outline) |
| **Adjudication** (incl. Contestation) | challenges, determinations, the adjudication process | authority (Governance's); applying remedies (owners do) | ARB D-67; WD D-71 |
| **Reference Geography** | official geographical references | organisation areas, belonging | RAT D-93 |
| **Deliberation** | drafting, discussion, supports, co-signatures | motions after submission (Governance) | WD D-50…D-56 |
| **Communication** | newsletters, messages, notices (as the rules allow) | audiences' underlying facts | WD D-57…D-64; context P |
| **Evidence and integrity** | capture, record-fixing, integrity | the facts themselves | ACC (EPIC-002) |
| *Authorization* (capability) | technical enforcement | any business truth | FRZ BDR-07 |
| *Platform / tenancy* (capability) | working organisation, scoping, demo | organisational authority | ACC E-11; **owner OPEN (HD-8)** |

## 4. Relationships (published facts, all "in force at asOf, as known at τ")
| From → to | Fact | Status |
|---|---|---|
| Identity → Organisation | claims, bindings, merge notices for its own references | WD D-113 |
| Identity → Election | binding at issuance (as known **at issuance**) | WD D-123, D-138 |
| Organisation → Election | unit belonging (R-5); voter relationships (T-8); division assignments; contact-verification basis for alternative channels | WD R-5; P T-8; WD D-125, D-137 |
| Membership → Organisation / Governance / Election | C1, C2 / C3 / C4 | RAT CR-01 |
| Governance → Election | election rules, criteria, channels (R-6a), mandates (R-6b) | WD D-32 |
| Finance → Membership | payment evidence | outline RAT; **direction: separate ARB act** |
| Finance → Election | settlement fact for a fee period (arrangement B) | WD D-132 |
| Election → Voting | ballot-style keys, the authority to accept; **never identity** | FRZ, RAT VO-1 |
| any owner ← Adjudication | determinations; owners apply remedies | ARB D-67; WD D-71 |

**Governing principle:** no context decides for another (context map).

## 5. Essential distinctions (business meaning)
| Distinction | Why it matters | Source |
|---|---|---|
| Person ≠ Account | one human can sign in only one way, and a Person exists only after a claim | D-75, D-113 |
| Membership ≠ affiliation | the "Member" label only reflects Membership's facts | D-103 |
| Membership ≠ unit belonging | where a member belongs is not whether they are a member | CR-01; D-79 |
| Unit belonging ≠ electoral division | voting districts need not create branches or rights | D-125 |
| Electoral division ≠ organ; organ ≠ unit | a committee decides; a unit is where people belong | D-125; PO-DR07-01 |
| Workspace access ≠ membership ≠ authority | using the tools grants no say and no membership | D-104 |
| Authority ≠ entitlement | holding office does not make anyone a voter | RAT boundary |
| Entitlement ≠ issuance ≠ ballot | entitled members may still need an authorised channel; ballots never carry identity | D-123, D-137, VO-1 |
| Determination ≠ authority | a ruling binds through the owners, under Governance's authority | D-67, D-71 |

**Key invariants:**
- **VO-1**;
- **D-107** (registration creates only an Account);
- **D-113** (binding only by claim);
- **D-111** (isolation);
- **D-42** (fixed snapshot);
- **D-128** (secrecy described per layer);
- **D-138**, a limited guarantee: at most one authorisation per entry and one right per claimed Person; **no promise of one right per human while duplicates are undetected; no revocation after issuance**;
- **D-139** (an import never creates an Account, a Membership or a right);
- **D-133** (one fixed decision for display, preview, issuance and validation).

## 6. Decision traceability

### 6.1 Record integrity (FACT, checked 2026-11-10)
- **Every ID from D-1 to D-139 appears exactly once as a decision entry, with no gaps.** The only repeated match, "D-1" at `:254`, is a row in a consistency table, not a decision.
- **D-136** is the other session's ruling (nested areas, §11.49).
- **D-137, D-138 and D-139** amend **D-123, D-124 and D-131** respectively (§11.50), with a renumbering note and their provenance.
- The uncommitted changes to the record are **additions only** (no removed lines). Section headings §11.25…§11.50 are unique.
- **No correction is needed.**

### 6.2 Where each area's rules live
| Area | Decisions |
|---|---|
| organisation scope and meaning | D-97…D-101, D-106, D-112 |
| identity boundary | D-75, D-102, D-107, D-110, D-111, D-113 |
| structure and belonging | D-1…D-19, D-79…D-83, D-114…D-122, D-136 |
| people register | D-101, D-103, D-104, D-107…D-109, D-129…D-131, D-139 |
| Election and Voting | D-32, D-41…D-49, D-123…D-128, D-133, D-134, D-137, D-138 |
| fees | D-132 |
| Adjudication | D-67, D-71 |
| legacy flows | D-135 |

## 7. Contradiction and ambiguity findings
| # | Finding | Classification |
|---|---|---|
| **F-1** | **The ratified Membership act §12 says "Election-Only → ImportedVoterRegistry" in its Election section** (act `:353-372`; context map `:73`). **D-109 and D-131 place the standing voter relationship in Organisation.** Reconciling reading (P): the "ImportedVoterRegistry" is the election-only **input** to Election's electorate. Its source is now Organisation's standing relationship, and the act's point, "does not require Membership", is unchanged. A WD cannot amend a RAT text, so **the reading needs the Principal's confirmation**, as an interpretation note in the style of X-10, not an amendment. | **clarification touching a RAT text: PRINCIPAL** |
| F-2 | `ADR_20261004_1549` (DECIDED, PBDIGIT-213) describes per-election voter import into `ElectionMembership` with evidence-based admission. Under D-131/D-139, an import feeds Organisation's register and the voter relationship; Election's admission by evidence stays. This is compatible, but the ADR's import path must be **aligned in tactical design**. | tactical alignment |
| F-3 | The context map lists Organisation as "PROPOSED (candidate context)" at `:90`, but as **ACC (D-106)** at `:349`. The first row is stale. | baseline clarification (documentation) |
| **F-4** | **The status record's open list (§4) predates D-94…D-139:** DR-07b (formal act for D-3, "Organisation owns C8"), XD-4 (authorisation ownership), XD-5, O-8 (incompatible positions), O-9 (membership ↔ tenure), O-10 (committee authority), O-14 (vocabulary), HD-8 (tenancy owner). Probable closure: XD-4 by D-102/D-104; XD-5 by D-93; O-9 in principle by CR-01 C3; O-10 by D-38/D-97; O-14 partly by D-103/D-108. **DR-07b is a pending ARB act, and HD-8 is open.** None is closed explicitly. | **needs Principal confirmation of which are closed, and by what** |
| 1 | Identity vs Organisation person records | resolved (D-111, D-113) |
| 2 | Membership vs voter relationship | resolved (D-131, D-139); see F-1 |
| 3 | belonging vs division | resolved (D-125, D-126) |
| 4 | authority vs organ and unit | resolved (D-38, PO-DR07-01, D-114); DR-07b act pending (F-4) |
| 5 | entitlement vs channel | resolved (D-123, D-137) |
| 6 | snapshot vs later merges | resolved (D-138) |
| 7 | duplicate-case issuance vs anonymity | strategic rule resolved (D-138). **Tactical:** which contact receives a code for an unclaimed duplicate case, and how the case's single authorisation stays unlinked to the ballot |
| 8 | alternative channels vs assurance | resolved (D-137) |
| 9 | Finance vs Membership and Election | resolved (D-132); FeePaid direction is a separate ARB act |
| 10 | Adjudication vs Governance | resolved (D-67, D-71) |
| 11 | isolation vs central identity | resolved (D-111) |
| 12 | import vs registration and claim | resolved (D-107, D-113, D-135, D-139); the legacy code is non-conforming (operational) |

## 8. Open questions
- **Strategic, platform:**
  - F-1 (reading confirmation);
  - F-4 (closure confirmation; DR-07b act);
  - FeePaid direction (ARB act; blocks only D-132 A);
  - HD-8 tenancy owner;
  - XD-PROV erasure;
  - independent review of the consolidated baseline (advisable).
- **Organisation-specific UNKNOWN:**
  - NRNA's constitution (districts, divisions, candidacy timing, arrears, secret-ballot wording, incompatible positions);
  - authorised channels and witness waivers;
  - secrecy minimums;
  - certified-roll authorities.

## 9. Tactical DDD readiness
| Context | Can proceed? | Notes |
|---|---|---|
| Identity | **yes** | I-TDD-01; I-TDD-02 on D-113 (claim names the organisation's reference; binding not merge; one bound reference per organisation) plus D-138's "binding as known at issuance" |
| Organisation | **yes** | §49–§51 with the IR-OTD-01-C confirmation; people register rev. 4 now has all inputs (D-129, D-130, D-131/D-139, D-125/D-126) |
| Membership | preserve | ratified; only the C4 conformance fix (`PBDIGIT-318`) |
| Election | **after Identity's claim contract** | to design: snapshot contents; division and voter-relationship reads; per-channel issuance with assurance records (D-137); duplicate cases and holds (D-138); the Finance settlement read (D-132 B); candidacy timing (D-134) |
| Voting | after Election issuance | ballot-style keys; credential acceptance; no identity |
| Adjudication | stable | remedy routing for held rights and duplicates found after voting |
| Governance | partly | depends on F-4 (DR-07b; O-8…O-10) |
| Finance | when D-132 is needed | settlement fact; FeePaid act for A |

## 10. Principal decisions still required
1. **F-1:** confirm the reading of the ratified "ImportedVoterRegistry" as Election's election-only input, sourced from Organisation's standing voter relationship (D-109).
2. **F-4:** confirm which older open items are closed and by which decisions, and whether the DR-07b act is now due.
3. **Acceptance of the consolidated baseline** (after 1 and 2), preferably after an independent review.

**Not Principal decisions:** F-2 and F-3 (alignment and documentation); the legacy containment plan (already authorised for planning).

## Final answers
1. **Strategic discovery is complete.**
2. **The baseline is internally consistent** for the core contexts. One ratified-text reading (F-1) and one stale open list (F-4) must be confirmed.
3. **Ready for Principal acceptance:** after F-1 and F-4.
4. **Can proceed now:** Identity and Organisation; Membership is preserved as ratified.
5. **Still to design:** Election's snapshot, issuance per channel, duplicate cases, the division, voter-relationship and settlement reads; Voting's credential acceptance.
6. **Principal decisions:** F-1, F-4, and accepting the baseline.
7. **Organisation-specific rather than platform gaps:** NRNA's rules, channels, waivers, minimums, roll authorities.

## Mobile and installable app (PWA)
- **Not part of the strategic baseline** (disagreement 5). It is a product capability that rests on the baseline.
- **The baseline already fixes its limits:**
  - one's own organisation-scoped data only (D-111);
  - the claimed channel only (D-123);
  - nothing about ballots, credentials or "has voted" on the device (VO-1, §22);
  - secrecy described honestly (D-128).
- **Its design waits for Identity's claim contract (I-TDD-02), which can proceed now.** The installable membership area therefore follows Identity and Organisation tactical design, not Election's.

## 11. Update to D-164 (2026-11-11)

**Sources added:** decision record §11.51 … §11.69 (D-140 … D-164); `2026-11-11-whole-baseline-strategic-coherence-assessment.md`; `2026-11-11-integrated-baseline-coherence-review-and-completion.md`; `2026-11-11-multi-database-boundary-tactical-design.md` (PROPOSED); ADR-0001 status annotation (`:320`); the ratified Membership artefact's CR-02 note (`:203`). **This section builds on those reviews and does not repeat them.**

### 11.1 Record integrity (FACT, checked 2026-11-11)
- **Every ID from D-1 to D-164 appears exactly once as a decision entry, with no gaps.** The only repeated match is still the consistency-table row at `:254`.
- **The uncommitted changes to the record are additions only** (346 lines added, none removed). Section headings §11.1 … §11.69 are unique.
- **Two numbering notes** explain the parallel-session shifts (§11.50: D-137 … D-139; §11.52: D-141 … D-143).
- **Explicit amendments and supersessions:**
  - D-161 amends D-153 (suspension now applies) and the audience rule of the D-158 design;
  - two recorder notes on D-161 rename its "good standing" to **`InForceUnrestricted`**, with the meaning unchanged;
  - D-144 supersedes ADR-0001's location clause, which is annotated at ADR-0001 `:320`.
- **The only change to a ratified text** is the additive CR-02 note in the ratified Membership artefact (`:203`), made under the ARB act D-161. The Organisation baseline is unchanged.
- **No correction is needed.**

### 11.2 Status of this review's earlier findings
| # | Status | By |
|---|---|---|
| F-1 | **closed** (interpretation; ratified text unchanged) | D-141 |
| F-2 | **open, tactical**; now outside release 1, since Election is outside the minimum package (D-146) | — |
| F-3 | **open, documentation**; widened in §11.6 | — |
| F-4 DR-07b | **closed** | D-142 |
| F-4 O-14 | **closed** (glossary note) | D-143 |
| DR-07a (not on the F-4 list; stale in the context map) | **closed**: Governance owns committees | D-144 |
| F-4 HD-8 | **physical tenancy closed** (D-164); naming the tenancy *owner* remains open, with **no business effect** | D-164 |
| F-4 XD-4, XD-5, O-8, O-9, O-10 | **settled in substance** (audit, no new ruling). The content of O-8 … O-10 is organisation-specific | D-102, D-104, E-12; D-93, D-82; D-1b, D-97; CR-01 C3; D-38, D-65, D-97, D-115 … D-119, D-136 |

### 11.3 What D-140 … D-164 add
| Area | Decisions | Effect on the baseline |
|---|---|---|
| **Product scope and release 1** | D-146, D-147, D-160 | **The minimum package is Membership, Newsletter and Membership Forum.** Release 1 has units and placement by act; area rules and Geography come later. **Release 1 serves ledger organisations only.** Scope only; no ownership changes |
| **Structure** | D-140, D-145, D-154, D-155, D-159 | An overridden lower allocation resumes when the higher one ends. A committee ends with its unit. Structural changes need a designated body. Equal-highest bodies must agree before a scheme's map version moves. Existing units are recorded from evidence |
| **Governance** | D-144, D-148, D-149, D-150, D-154, D-157, D-163 | **Governance owns committees, positions, appointments and terms** (D-144). Bootstrap uses a founding registrar named by act. Admission, moderation, structural change, renewal and suspension follow "nobody until designated" when the rules are silent. All authority is checked through AuthorityCheck |
| **Membership** | D-160 … D-163 | **C1/C2 carry `inForce` and `suspended`** (CR-02, ARB). The C1/C2 implementation specification is accepted, and its build is gated on the ledger-clock proofs. **Renew, Suspend and Restore are in release 1.** Each admission records its term per the organisation's rules, with no platform default. **Payment stays outside PublicDigit** |
| **Deliberation and Communication** | D-150 … D-153, D-158, D-161 | Newsletters are opt-out per series. Forum access follows unit levels (confirms D-56). **Only `InForceUnrestricted` members may write in forums and receive newsletters**; lapsed and suspended members can still read |
| **Physical tenancy** | D-164 | **One central platform database plus one database per organisation.** Central holds only identity, login, sessions, the organisation registry, the Account-to-organisation association (routing only) and shared reference data. **All business data lives in the organisation's own database.** The association grants nothing |
| **Tactical acceptances** | D-156, D-157, D-158, D-162 | Organisation §49–§51 (the **§51 build is gated on an independent review**), the Governance thin slice, Newsletter and Forum, and the C1/C2 specification. **All are reviewed only by their author or by one model; none is independent** |

### 11.4 Changes to §3 (ownership) and §4 (relationships)
- **Governance row:** add **committees (organs), positions, appointments and terms of office**, with status **ARB D-144**. The Membership committee code is a frozen behavioural reference until Governance reaches parity.
- **Membership row:** add **Renew, Suspend and Restore in release 1** (D-163). C1/C2 publish `inForce` and `suspended` (D-161).
- **Platform / tenancy row:** physical layout **decided (D-164)**. Naming the owner remains open (HD-8 remainder). Database routing is tactical.
- **Identity row:** unchanged in ownership. **Under D-164 it lives in the central database**, as does the Account-to-organisation association (the D-113 binding, per the multi-database design). **There is no identity projection in organisation databases** (D-111).
- **Relationships to add:**
  - Membership → Organisation, Deliberation and Communication: C1/C2 with `inForce` and `suspended` (ARB D-161).
  - Governance → Membership and Organisation: AuthorityCheck answers for admission, renewal, suspension, restoration and structural acts (D-149, D-154, D-163).
  - Finance → Membership: **not in release 1** (D-163). The FeePaid direction is needed only later.
- **Unchanged:** every other row of §3 and §4. Election and Voting have no new decisions after D-139.

### 11.5 Consistency of the new decisions
| # | Check | Result |
|---|---|---|
| C-1 | D-164 vs D-111 | **Consistent.** D-111 precision (2) left physical separation open, and D-164 closes it in the same direction |
| C-2 | D-164 vs D-123 / D-138 (issuance uses Identity's binding "as known at issuance") | **Consistent.** The binding is central and the electorate lives in the organisation's database, so issuance reads across one boundary. **Tactical, for Election**, once Election is in scope |
| C-3 | D-161 vs the ratified `Standing` (dues compliance) | **Resolved by the recorder notes:** the term is `InForceUnrestricted`. **New texts must not say "good standing" or "Active".** The D-143 glossary should carry the term (documentation) |
| C-4 | D-153 vs D-161 | **Explicit amendment**; read the two together |
| C-5 | D-144 vs D-142 vs ADR-0001 | **Consistent.** Committees go to Governance; `CommitteeAssociation` stays with Organisation and Membership; the ADR is annotated |
| C-6 | D-160 vs organisations already on the platform | **Consistent; product consequence.** An organisation still on the legacy membership store cannot use release 1 until it passes cutover gates C-1 … C-10. Which live organisations are on the legacy store was **not verified here** |
| C-7 | D-163 vs D-132 and FeePaid | **Consistent.** Release 1 needs no payment fact; FeePaid matters only for D-132 arrangement A |
| C-8 | D-146 / D-147 vs D-93 and §51 | **Consistent.** Geography stays ratified and dormant, and the area rules are designed and gated |
| **H-1** | **Silence defaults vs D-1a / D-1b** | **Needs a Principal reading (§11.8).** Details below |
| C-9 | Demo mode under D-164 | **Open, tactical.** The platform's demo (`organisation_id` NULL; "Platform / tenancy … demo" in §3) has no place in D-164's list, and the multi-database design does not mention it. **It must not be solved by putting demo business data in the central database**, which holds "only" the listed items. The natural tactical answer is a demo organisation with its own database |

**H-1: what the record says.**
- **D-1a:** "Platform configuration creates no authority."
- **D-1b:** "there are no platform-wide default constitutional rules."
- **Earlier reviews apply this strictly:** "PublicDigit may hold no default" (governance procedural-validity discovery `:56`); "organisation rules are never platform defaults" (mobile baseline K-11); claim expiry is an "organisation rule with a platform maximum" (I-TDD-02 scope G-11).

**The rulings for silent rules fall into two families:**
- **(a) "Nobody until designated"**: D-58, D-149, D-150, D-154(1), D-163.
  - These create no authority and assume none.
  - **They are consistent with D-1a and D-1b.**
- **(b) Substantive fallbacks**: D-56 (read access), D-140, **D-145** ("the Highest Committee is competent for the area until a new committee is established"), D-152, D-153 (members may write), **D-155** ("a binding default"), and the D-161 product rule.
  - Each is a later direct ruling, so each binds.
  - **No entry says how they relate to D-1b.**
  - **D-145 assigns competence**, which is close to what D-1a forbids.
  - **D-161 does not say whether an organisation's rules may provide otherwise**, while D-153 and D-145 do.

**Proposed reading (P):**
- D-1b forbids platform content that stands in for an organisation's constitution.
- Family (b) are **platform fallbacks**:
  - applied only while the organisation's written rules are silent;
  - displaced by any written rule;
  - recorded on each act or record they affect as **"platform fallback (D-nn)"**, so nobody reads them as the organisation's own rule.
- D-145's interim competence is a fallback of this kind, and it ends as soon as the organisation acts.
- **D-161 is read in the same way** (the organisation's rules may provide otherwise), unless the Principal intends it as a fixed product rule.

**The alternative:** list the family (b) rulings as **closed exceptions** to D-1b.

**Classification:** a clarification of the record; it touches no ratified text. **PRINCIPAL.**

### 11.6 Documentation corrections (not decisions; this review edits nothing)
- **Context map:** it is behind the record in these places:
  - **Organisation "PROPOSED (candidate context)"** at `:90` (ACC D-106);
  - **DR-07a / DR-07b "UNRESOLVED"** at `:52`, `:78`, `:92`, `:248`, `:249` (D-142, D-144);
  - **O-14** at `:93`, `:251`, `:294` (D-143);
  - **XD-4** at `:82`, `:250` (settled in substance);
  - **HD-8** at `:360`, whose physical part is closed by D-164.

  §16's K-1 (`:387`) already flags §4 as behind. A versioned regeneration (option C at `:322`) fits now.
- **Status record:** rows O-6 (`:76`) and O-14 (`:81`) are superseded by D-144 and D-143. The append-only note at `:248` covers D-144 but not D-143.

### 11.7 Open items through D-164
- **Principal:**
  - H-1;
  - acceptance of the consolidated baseline;
  - an independent, non-Claude review before freezing (advisable).
- **Registered ARB items outside release 1:**
  - the FeePaid direction;
  - naming the tenancy owner (HD-8 remainder);
  - the Organisation → Election and → Authorization mechanisms;
  - XD-PROV erasure;
  - dissolution of a whole organisation (B-5);
  - challenges where no tribunal organ is recorded (B-6).
- **Gates and conditions (not questions):**
  - **§51 build:** an independent review first (D-156);
  - **C1/C2 build:** starts with the ledger-clock proofs (D-162);
  - **newsletter launch:** counsel confirms the legal basis (D-151 [LQ], D-158);
  - **committee migration:** the M-1 … M-5 review (D-144);
  - **committee records:** classify the existing rows (O-16);
  - **committee precedence:** Round 7 stays a proposal (D-157).
- **Tactical:** C-2 (Election), C-9 (demo), F-2.
- **Organisation-specific UNKNOWN:** the items in §8, plus:
  - the body that admits members;
  - membership terms and renewal rules;
  - forum moderators and newsletter editors;
  - the decision naming the registrar.

### 11.8 Tactical readiness (replaces §9 for release 1)
| Area | State | Next |
|---|---|---|
| Membership | C1/C2 specification **accepted** (D-162). The Renew / Suspend / Restore design is **PROPOSED rev. 3**, self-reviewed | independent check, then the Principal's tactical acceptance |
| Organisation | §49–§50 **accepted** (D-156); people register rev. 4 has all inputs | implementation planning (EP-01) |
| Governance | thin slice **accepted** (D-157); AuthorityCheck contract reviewed | implementation planning (EP-01) |
| Deliberation and Communication | Newsletter and Forum **accepted** (D-158) | counsel's confirmation before the newsletter launches |
| Identity | I-TDD-02 **scope proposed**, design not written | write I-TDD-02 (claim and binding) |
| Tenancy | multi-database boundary design **PROPOSED** | the Principal's tactical acceptance; demo (C-9) |
| Election, Voting | **outside the minimum package**; deferred, not blocked | §9's list plus C-2, when they come into scope |

### 11.9 Updated verdict and Principal decisions
- **The baseline is consistent through D-164.** Every fact has one owner, contracts are checked, there is one time model, and authority is never inferred.
- **One clarification remains: H-1.**
- **Ready for Principal acceptance** after H-1 is read, ideally after an independent review.
- **What acceptance covers:**
  - **Strategic baseline:** decisions D-1 … D-164; the ratified Membership artefact with CR-01 and CR-02; ratified Reference Geography; the context map's §16 ownership table as corrected by §11.4.
  - **Tactical baselines, accepted separately and not re-accepted here:** D-156, D-157, D-158, D-162.

**Principal decisions:**
1. **H-1:** confirm the reading of the silence fallbacks (§11.5), or list them as closed exceptions to D-1b.
2. **Accept the consolidated strategic baseline.**

**Not Principal decisions:** C-2, C-9, F-2 (tactical); §11.6 (documentation).

### 11.10 Mobile and installable app (update)
- **The minimum package is the installable membership area's content:** membership status, forums and newsletters. **Release 1 and the PWA now point at the same scope.**
- **D-164:** the app signs in centrally and works with **one organisation's database at a time**. Anything kept on the device comes from one organisation only.
- **D-161 holds on the device too.** A lapsed or suspended member reads but cannot post. **Cached standing must carry its "as known at" time**, and posting must be checked on the server.
- **It still waits for I-TDD-02.**
