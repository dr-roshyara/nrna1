# PublicDigit strategic baseline: coherence review (Organisation, Identity, Membership)

| | |
|---|---|
| **Status** | **Review.** Decides nothing; ratifies nothing; rewrites no baseline. |
| **Commission** | "PublicDigit Strategic Baseline Coherence Review", Principal, 2026-11-10. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`. The Organisation baseline and its findings are **untracked** (another session). The Identity documents of 2026-11-09/10, the PBDIGIT-341 plan and the decision record (D-100…D-102) are **uncommitted**. |
| **Independence** | Claude wrote the Identity documents and the PBDIGIT-341 plan reviewed here; the Organisation baseline was written in another session. **Option (d) of P-I1 is not validated here** (the external review is pending), and nothing in this review should be read as that validation. |
| **Not done (by instruction)** | no session-log, CONTEXT or plan updates |

**Labels:** **FACT** (read 2026-11-10) · **RAT** (ratified; ARB acts per D-94) · **WD** (binding working decision / Principal ruling) · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Executive verdict
- **The three contexts form a coherent business architecture in principle.** D-100, D-101 and D-102 (**WD**), with the ratified Membership architecture and XD-3 (**RAT**), give every business fact examined one owner.
- **The Organisation baseline (revision 2b) is not yet ready for acceptance, because of one material omission and two related coherence defects:**
  1. **It overlooks the ratified Membership → Organisation contract.** CR-01 (D-73, **RAT**; `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md:197-201`) already fixes the contract: the event `MembershipCoverageResolved` (C1) and the query `CoverageKnownAt` (C2), with "Organisation never interprets Membership facts". The baseline says there is "no Membership–Organisation information contract" and carries "Membership supplies only *membership ended*" as a provisional proposal (§8, §12; findings `:83`, `:95`). The ratified contract supersedes that proposal's open part. The baseline cites D-73 only for the shared τ (`:206`).
  2. **"Coverage" means two different things.** In Membership it is the **time** covered by a membership (INV-COV, `…-ratified.md:92, :122`); in Organisation it is the **territory** a unit serves (D-80; baseline §3, §6). It is a ubiquitous-language collision across two baselines that will be integrated.
  3. **The affiliation capacity "Member" is undefined against Membership truth.** The baseline lists "Member" among affiliation capacities (User, Member, Worker, Guest, voter; §3, D-101) and also says "only Membership states membership truth" (§5). Whether a "Member" affiliation is a **reflection of Membership's facts** or an **independent organisational record** is not stated. If independent, there are two sources for "is a member". **PRINCIPAL.**
- **Identity's responsibility boundary is clear** (D-102, **WD**). Its first capability (claim) is **blocked by P-I1**, and P-I1 by the external review of option (d).
- **PBDIGIT-341 shows that the current code violates the baseline in at least six places** (§6). It also shows a structural gap: **D-101's organisation-local person profile does not exist in the code at all.** Every organisation sees the central `users` name and e-mail everywhere, not just in voter import.
- **Readiness:** Organisation **ready with explicit open seams**, after the three corrections above; Identity **not ready** (P-I1); Membership integration **ready with explicit open seams** (its contracts are ratified; the Organisation side is undesigned).
- **Recommended next capability:** **the organisation's own register of people and affiliations** (organisation-local person record + affiliation, holding a `PersonRef` slot). It is unblocked by P-I1 if the reference is opaque, is the precondition of Identity's claim and of the PBDIGIT-341 permanent fix, and is what a member sees on a phone (§10).

## 2. What is established
| Settled | Status | Source |
|---|---|---|
| Identity owns Person identity and identity equivalence | **RAT** | Membership act §11 |
| Canonical Person; a different `PersonRef` per organisation; only Identity resolves; Person ≠ Account; no e-mail linking; C-1…C-5 | **RAT** | XD-3 (D-75) |
| Membership owns membership truth; it grants no entitlement; `Eligibility` is Election's | **RAT** | `…-ratified.md:47, :50, :110` |
| Membership → Organisation (C1 event, C2 query); → Governance (C3); → Election (C4); one shared τ | **RAT** | CR-01 (D-73), D-77 |
| A platform record is not an Organisation; account creation is not affiliation | **WD** | D-100 |
| Each organisation owns its affiliations and its own person profile, including address | **WD** | D-101 |
| Identity's responsibility boundary; authorisation stays with the owning context | **WD** | D-102 |
| Address never establishes belonging; coverage never decides belonging | **WD** | D-79, D-80 |
| Official geography is Reference Geography's | **RAT** | D-93 |

## 3. Organisation baseline revision 2b: assessment

**Note on the version:** the commission names revision 2a. The file's current surviving text is **revision 2b** (final audit, the same day); 2a's changes survive inside it. The status line still says "revision 1" (stale label, minor).

### A. Ownership and concepts
| Concept | Baseline | Assessment |
|---|---|---|
| Organisation | §2–§3; registry E-0 | coherent with D-100 |
| Unit vs organ | §4.1: distinct, related only through mandate and scope; no cardinality | **sound**; prevents the most common conflation in party structures (district unit vs district committee) |
| Unit belonging | Organisation, strategic [AC]; detailed rules provisional | sound as ownership; see B |
| Person profile | Organisation (D-101) | sound; **not implemented anywhere** (§6) |
| Affiliation and capacities | Organisation (D-101) | **defect 3** (§1): "Member" capacity vs Membership truth |
| **Workspace access** | not named as a concept; implied in §8 (permission checks → Authorization, scope open) and D-102 ("follows that organisation's rules") | **gap**: who grants access to an organisation's workspace, by what act, is not stated. Today it is the legacy `user_organisation_roles` row (FACT), which PBDIGIT-341 shows being created silently. REC: name it as an Organisation fact (an affiliation capacity or its own fact), enforced technically by Authorization. |
| Relationship to Membership | §8: "provisional proposal: only membership ended" | **defect 1** (ratified C1/C2 overlooked) |
| Relationship to Governance | §7: rules → decider → Governance record → Organisation structure | sound; the D-29 exchange is correctly left provisional |
| Geographic units vs belonging vs membership | §3, §5, §6 | kept apart, **except** the word "coverage" (defect 2) |

### B. I5 evidence (E-4)
- **The fact recorded:** a person's place in a unit or wing (belonging), with capacity, basis act and period.
- **Who creates it:** Organisation, from decisions under the organisation's rules (D-79: individual acts; residence rules only where adopted).
- **Authoritative:** Organisation.
- **Consumers:** Election (electorate at τ); Governance (competence checks); Deliberation (forum scope, D-56).
- **What must not be inferred:** membership, authority or entitlement.
- **Evidence quality:**
  - Revision 2a correctly split "Membership does not own belonging" from "Organisation owns it".
  - The citation for the first part is weaker than stated. I5 (`…-ratified.md:150`) maps a **list** (honorary … units, committee roles …) to "a type, a provenance, another relationship or another organisation" **without pairing** units to "another relationship".
  - **Stronger ratified evidence exists:** CR-01 C1/C2. Membership supplies **membership time coverage** to Organisation, and "Organisation never interprets Membership facts". That presupposes that Organisation owns what it does with that coverage (belonging).
  - REC: cite CR-01 for E-4 and keep I5 as supporting evidence.

### C. §13: separate rulings
- **What a "ruling" is here:** a Principal decision about **PublicDigit's platform architecture** (D-79, D-80, D-97…D-101), made with the product and ARB authority of D-94. It is **not** an organisational decision of any party, and it does not record any organisation's decision.
- **Why separate acts:** each keeps its own provenance, question and history. Folding them into a baseline acceptance would make a later change to one ruling look like a change to the baseline. **No duplication results:** the baseline references them and does not restate them.
- **Principal decision:** yes, as part of the acceptance (§13 B). **REC: keep them as separate acts.**

### D. §14: evidence audit, classified
| Class | Items |
|---|---|
| **Business invariants** (would hold without software) | E-1 (an organisation is a real self-governing body), E-3 (no disclosure between organisations), E-6 (unit ≠ organ), E-7 (PublicDigit creates no authority), E-9 (coverage never decides belonging), E-10 (only Election decides entitlement) |
| **Ownership conclusions** (architectural) | E-0, E-4, E-5, E-8, E-11, E-12, E-15 |
| **Classification rule for legacy data** (migration guidance, not strategy) | E-13 |
| **Open question, not a conclusion** | E-14 (move it to §11; it does not belong in an evidence audit) |
| **Administrative** | none; the audit is lean |

**Minor:** E-15 is listed before E-14.

## 4. Identity: responsibility and reference model
**Responsibility table:** every row of the commission's table matches the records: D-102 (**WD**) for Identity's list; D-101 for profile and affiliation; Membership (**RAT**); Election (**RAT** boundary); Governance (**WD**); Voting (**RAT** VO-1).
- **Application layer:** orchestrates and holds no Identity rules (D-102).
- **Selecting an organisation:** a working context only. The Organisation baseline §8 places "Working Organisation context" under the **tenancy** platform capability (owner open, HD-8). **Consistent.**
- **Account creation ≠ affiliation or membership:** consistent in the records; **violated in code** (`RegisterController.php:41-63`; PBDIGIT-341 F-5, F-6).
- **No Registration context; Keycloak is not a domain owner:** consistent (D-102).

**Nine concepts:** human; Identity Person; organisation person record; organisation-scoped `PersonRef`; Account; Claim; affiliation; Membership; Election reference. The separation is coherent if a `PersonRef` is only a reference, never the affiliation (adversarial review §3.1). Neither baseline contradicts that: the Organisation baseline §9 says "a `PersonRef` is not proof of identity or authorisation".

**What depends on P-I1:**
| Depends on P-I1 | Does not |
|---|---|
| Identity's claim capability (what a claim names; INV-ID-6's form) | the Organisation baseline's acceptance (it decides no Person lifecycle; its §5 calls one canonical Person "the aim, not a guarantee") |
| when an imported person's `PersonRef` is issued, and by whom | the organisation's own register of people (it can hold an opaque `PersonRef` slot; the Membership kernel already takes `PersonRef` as an opaque string, `AdmitMemberHandler.php:37`) |
| merge frequency, and the merge process's urgency | D-102's boundary |

**Discovery:** the **responsibility** is Identity's (D-102, Organisation baseline E-15); the **mechanism** (L1/L2) is open (P-I2). They are correctly kept apart in both baselines.

## 5. Cross-context ownership and collaboration
| Business fact | Creates | Owns lifecycle | Authoritative | Consumes | Decided elsewhere | Must not cross |
|---|---|---|---|---|---|---|
| Person (canonical) | Identity | Identity | Identity (**RAT**) | nobody outside Identity | merges: Identity's explicit act | to any organisation |
| `PersonRef` | Identity (when: P-I1) | Identity (binding); organisation holds it | Identity for resolution | its own organisation's contexts | — | another organisation's references |
| Account, session | Identity | Identity | Identity (D-102) | the application | — | account data into organisation records |
| Working organisation | the session (tenancy capability) | — | none (grants nothing) | the application | access: Organisation | — |
| Workspace access | Organisation (**gap**: not named) | Organisation | Organisation's rules | Authorization (enforces) | — | from authentication or selection |
| Person profile | Organisation | Organisation | Organisation (D-101) | its own contexts | — | to other organisations; into Identity |
| Affiliation (capacities) | Organisation | Organisation | Organisation (D-101) | Identity's discovery mechanism (minimised, C-4) | "Member" capacity vs Membership: **PRINCIPAL** | membership or entitlement |
| Unit belonging | Organisation | Organisation | Organisation [AC] | Election, Governance, Deliberation | basis acts: by the organisation's rules (Governance records them) | membership, authority |
| Membership facts and time coverage | Membership | Membership | Membership (**RAT**) | Organisation (C1, C2), Governance (C3), Election (C4) | admission: organisation decision + declaration (RA) | entitlement |
| Structure (units, wings) | Organisation from decisions | Organisation | Organisation | Governance, Election | the decision: competent organ (Governance records) | that a unit is an organ |
| Organs, positions, mandates, decisions | Governance | Governance | Governance | Organisation, Election | — | authority from platform permissions |
| Eligibility, electorate, rights | Election | Election | Election (**RAT** boundary) | Voting (credentials only) | — | inferred from affiliation, belonging, profile, address |
| Ballots | Voting | Voting | Voting (**RAT** VO-1) | results | — | any identity |
| Official geography | Reference Geography | Geography | Geography (**RAT** D-93) | Organisation (coverage), profile resolution | — | organisation areas as official |

## 6. PBDIGIT-341 as a boundary stress test
| Finding (plan F-n; code reading, not run) | Boundary violated |
|---|---|
| account existence revealed (F-1, F-3, F-4) | XD-3 C-1 / §2.4 (**RAT**); D-101 |
| silent linking of an existing account (F-5) | XD-3 §2.6 (**RAT**): no linking by matching e-mail; D-101: affiliation is the organisation's own record |
| workspace access created silently (F-6) | D-102: access follows the organisation's rules; the "workspace access" gap of §3 |
| voter entry without acceptance (F-5) | Election's electorate formed from an affiliation the person never had |
| central name and e-mail shown (F-8, F-12) | **D-101: the organisation should see its own profile, not Identity's or another organisation's data**; structural, since no organisation profile exists anywhere |
| platform-wide last-login IP (F-8…F-10) | C-1; data from activity outside the organisation |
| "verified" without verification (F-7) | D-102: login-address verification is Identity's |

**Challenge to the plan's containment (agreed with the Principal's review):**
- **C-4 only reduces bulk exposure.** The on-demand IP-capture call still returns the **platform-wide** last-login IP. True non-disclosure needs an organisation-scoped IP source (plan PR-3), or must accept and record the residual disclosure for genuinely affiliated voters.
- **Central name and e-mail exposure remains**, because the organisation has **no own profile** in today's data. That is not fixable in the import alone; it is D-101's missing implementation.
- **R-1 (account-bound acceptance) is a second linking mechanism** beside the future claim. To avoid two competing mechanisms: either build R-1 **as** the first minimal form of Identity's claim (same concept, same invariants), or, until the claim exists, **refuse online addition of existing accounts** in election-only import with a neutral message. **PRINCIPAL**, PBDIGIT-341.
- **PR-2 is a business decision about the electorate**, not an interface detail.

**For the baseline:** the baseline already contains the rules these violations break; nothing in it needs to change because of PBDIGIT-341. **It does need the "workspace access" concept named** (§3 A), because the worst violation (F-6) is an access grant without an owner.

## 7. Genuine Principal decisions
| # | Question | Choices → consequence | REC | Blocks |
|---|---|---|---|---|
| **B-1** | Approve three corrections to the Organisation baseline before acceptance: cite CR-01 C1/C2 (and drop "no contract"); disambiguate "coverage" (for example "unit coverage" vs "membership time coverage"); state the "Member" affiliation capacity's relation to Membership | approve → consistent baseline; leave → two baselines contradict a ratified contract | approve | **baseline acceptance** |
| **B-2** | What is an affiliation in the capacity "Member"? | (a) **a reflection** of Membership's facts (C1/C2), never set independently; (b) an independent organisational record | **(a)**: one source of membership truth (RA) | baseline acceptance |
| **B-3** | Name "workspace access" as an Organisation fact (granted under the organisation's rules; enforced by Authorization) | name it / leave it implicit | name it | baseline acceptance (recommended), not strictly |
| **B-4** | §13 B: keep D-79, D-80, D-97…D-101 (and D-102) as separate acts referenced by the baseline | separate / ratify inside the baseline | separate | baseline acceptance |
| **B-5** | P-I1 (Person and `PersonRef` lifecycle) | (a)–(d) | **after** the external review of (d) | Identity's claim capability only |
| **B-6** | PBDIGIT-341: containment now; the interim mechanism (claim-shaped vs refuse); PR-2 electorate; PR-3 IP source | — | separate track | nothing in the baseline |

## 8. Questions that can safely stay open
- P-I2 (L1/L2), P-I3 (recovery, sessions), P-I8 (Election reference), P-I9 (equivalence);
- HD-8 (tenancy owner), XD-4 (Authorization scope), FeePaid;
- DR-07a (committee ownership), O-14 vocabulary **except "coverage"** (B-1);
- statute-dependent UNKNOWNs (NRNA's structural authority, O-8…O-10, sections);
- challenges for organisational acts (E-14);
- the D-29 exchange, D-3 and D-22 details.

Each has a named seam in the baselines.

## 9. Readiness for tactical design
| Context | Verdict | Why |
|---|---|---|
| **Organisation** | **READY WITH EXPLICIT OPEN SEAMS** (after B-1…B-3) | ownership and invariants clear; open items are named seams; the corrections are textual, not redesign |
| **Identity** | **NOT READY** (for its first capability) | the claim capability needs P-I1, which needs the external review of (d); no other Identity capability gives member value first |
| **Membership integration** | **READY WITH EXPLICIT OPEN SEAMS** | contracts C1–C4 ratified (CR-01, D-77); the Organisation consumer side and the kernel cutover (Slice 3–4) are design and implementation work, not strategy |

## 10. Recommended next capability
**The organisation's own register of people and affiliations**: an organisation records the people it knows (its person profile, D-101) and their relationships in capacities. Each record has a slot for the `PersonRef`, opaque to Organisation.

**Why this one:**
- **It needs no P-I1 decision.** Organisation treats the reference as opaque, as the Membership kernel already does; who issues it and when is a seam that P-I1 fills later.
- **It is the precondition of Identity's claim:** a claim connects an account to *the organisation's* person record, which must exist first.
- **It is the permanent fix for half of PBDIGIT-341:** organisations see their own names, not central account data (D-101); election-only import writes the organisation's records instead of linking accounts.
- **It brings the B-2 and B-3 answers into design** ("Member" capacity; workspace access).
- **For phones:** "my profile in this organisation" and "my affiliation" are what a member sees in the membership area. It is the natural base for the Membership pilot and the PWA start page.

**Dependencies:** B-1…B-3 accepted. The legacy profile data on `users` is a migration question, decided in the capability's design rather than before it.
