# Organisation strategic discovery completion: Principal decision brief

| | |
|---|---|
| **Status** | **Decision brief.** Decides nothing; changes no other document or decision record; freezes nothing. Not independently reviewed (Claude family). |
| **Commission** | "Organisation Strategic Discovery Completion and Principal Decision Brief", Principal, 2026-11-10. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`. Uncommitted or untracked: decision record (D-100…D-111), business-rules amendment note, Organisation baseline (accepted D-106), people register rev. 3, Session B model §49/§50, tactical entry assessment, IR-OPR-01 commission. No code changed. |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Business summary
The **Organisation** context is where each organisation keeps:
- **its own structure** (units, wings) over time;
- **where its people are placed** in that structure (unit belonging);
- **its own register of the people it knows**, with its own profile of each and the relationships it records (Worker, Guest, voter; "Member" only shown from Membership);
- **who may use its digital workspace.**

It never decides who is a member (Membership), who may vote (Election), who holds authority (Governance), what was paid (Finance), or who a human is across organisations (Identity).

**Every organisation's data is its own and invisible to every other organisation** (D-111). A person registers once and uses one account for all their organisations (D-110).

## 2. Strategic baseline that can be relied upon
| Item | Status |
|---|---|
| Membership architecture; CR-01 (C1, C2; D-73, D-77) | **RAT** |
| Identity XD-3 (D-75): canonical Person, per-organisation `PersonRef`, only Identity resolves, 0..1 Account, no e-mail linking, C-1…C-5 | **RAT** |
| Reference Geography (D-93) | **RAT** |
| Election owns electorate, eligibility, entitlement (`…-ratified.md:47, :110`); VO-1 | **RAT** boundary |
| Finance owns payment and settlement; "fees reference periods" (`…-ratified.md:155`) | **RAT** |
| Organisation strategic baseline rev. 2c, its eight §13 conclusions | **accepted (D-106)** |
| D-100…D-105, D-107…D-111 | **WD** (provenance in §3) |
| BR-3…BR-7, BR-11, BR-13, BR-13a; BR-2, BR-8, BR-12 as amended by D-107 | **APP** |
| BR-10 (eager Person creation at import) | PO direction, **open** with P-I1 |
| Session B model §49 (structure), §50 (person belonging); tactical entry assessment (T-1…T-8); people register rev. 3 | **P** |

## 3. Decision provenance (D-103…D-111)
**Source:** the conversation messages in which the Principal sent the Principal DDD Architect's notes together with the sentence **"I accept the recommendations written in the document."**

| Decision | Exact recommendation text in the accepted note | Established? |
|---|---|---|
| D-103, D-104, D-105 | "My recommendation: accept B-1, B-2 option A, B-3 and B-4." | **On record**; explicit |
| D-107 (O-1) | §8 table: "O-1: Bootstrap User affiliation — A — Account creation only; no affiliation" | **On record**; explicit |
| D-108 (O-2) | "O-2: User capacity — Drop as a standard affiliation capacity" | **On record**; explicit |
| D-109 (O-3) | "O-3: Voter records — A — Organisation records voter-related relationship; Election decides entitlement" | **On record**; explicit |
| D-110 | "My recommendation: preserve one registration and one account, with multiple independently managed organisational relationships." | **On record**; explicit |
| D-111 | "Agreed. This is now an accepted working architecture direction (WD) for PublicDigit." (sent twice, the second time with the acceptance sentence) | **On record**; explicit |

**Assessment:** each ruling's wording appears explicitly in a note that the Principal accepted in full. The doubt raised in the latest review concerns *form* (a blanket acceptance), not *content*. **Conflicting rules:** BR-2/BR-8/BR-12 (resolved by D-107, amendment note appended); D-101's "User" (D-108). **If withdrawn:** D-107 → the bootstrap "User affiliation" returns, conflicting with D-100; D-108 → "User" again overlaps workspace access; D-109 → the voter list's owner becomes open again. **One confirmation by the Principal closes this** (§7, Q-0).

## 4. Unresolved business decisions

### Q-1 (O-7): standing or per-election voter relationship (election-only organisations)
| Scenario | Standing relationship (Organisation) + Election's snapshot at τ | Per-election relationship |
|---|---|---|
| a person takes part in several elections | recorded once; each election takes its own snapshot | recorded again for each election |
| added after an election is announced | added to the standing list; **Election's cut-off (τ) rule** decides whether it counts for that election (D-41…D-43 spirit) | added to that election's list; the same cut-off question remains |
| removed from the organisation's list | later elections no longer see it; **past snapshots are untouched** (Election's facts) | must be removed per election |
| elections with different electorate rules (regional posts, categories) | Election filters the standing list per its rules (`PBDIGIT-336`) | each list is assembled by hand to match the rules |
| known to the organisation but not eligible for one election | stays on the standing list; Election excludes them for that election | not added for that election |
| **Effect** | Organisation keeps one fact; Election keeps electorate authority; Governance sets the rules (DD-11) | blurs Organisation and Election: a per-election list **is** an electorate draft, which is Election's (RAT) |

**FACT:** today's import is per election (`organisations/{organisation}/elections/{election}/voters/import`), writing `election_memberships`.
**REC: standing relationship**, consumed by Election at τ. It never guarantees eligibility (D-109).

### Q-2: may a person be a member without any unit belonging?
The Session B model §50.12 frames it correctly: Membership states **admission**; Organisation states **belonging**.
- **A. Platform-wide:** every admission is "incomplete" until a unit belonging exists. It protects parties that require a unit, but is wrong for bodies with central membership (common for diaspora associations); they would carry permanently "incomplete" members.
- **B. The organisation's registered rules decide** (§50.12 recommendation). Where the rules require a unit, the member shows "awaiting unit" and is counted in no unit list. Where they do not, organisation-level membership is complete. Consistent with D-97 and D-106.

**Examples:**
- **national-level membership** (central register): B → complete without a unit;
- **geographically organised party:** B → "awaiting unit" until placed;
- **organisation-wide roles** (national committee members): their place is a position (Governance), not a unit; B handles them.

**REC: B.** NRNA's own rule is **UNKNOWN** until its constitution is on file. **[PK] V-23** ("never without a unit") describes the organisations under discovery, not a platform rule.

### Q-3 (new): do unit-level capacities and organisation-level affiliations overlap?
- **Two capacity lists exist** (FACT; entry assessment T-6):
  - unit belonging: member, staff, guest, observer (§50.4);
  - affiliation with the organisation: Worker, Guest, voter (D-101, D-108).
- **Business question:** if Ram works for the Kathmandu district unit, is he also a **Worker of NRNA**?
  - (a) **independent facts**, each recorded by its own act;
  - (b) one is **derived** from the other;
  - (c) **one level only** for each kind.
- **Risk:** under (a) without care, "staff at a unit" and "Worker of the organisation" record the same reality twice and drift apart.
- **REC: (a)**, with a vocabulary rule (O-14) giving them distinct names, and with the tactical design required to show for each capacity which level records it. A business confirmation is needed because the answer depends on how organisations actually employ and invite people. **UNKNOWN** for NRNA.

## 5. Unresolved architecture questions

### 5.1 P-I1 and BR-10: a narrowing finding
- **New finding:**
  - Membership identifies a member by `PersonRef` (RAT: RA; D-77: `CoverageKnownAt` by `personRefs[]`).
  - Unit belonging identifies the person by `PersonRef` (§50.3, P).
  - Election and Finance would refer through it too.
  - **So every person who enters Membership, belonging or an electorate needs a `PersonRef` from the moment of import.**
- **Consequence:**
  - **option (b) "lazy, with an organisation-local placeholder" is unworkable** without re-keying ratified Membership facts;
  - **every viable option issues the reference at import, so BR-10's "issues the reference" part holds in all of them.**
  - What remains open is **only whether a central Person is created at import** (BR-10, options a/c) **or only when someone claims** (option d).
- **Correction to the people register rev. 3:** its "the slot may be empty" holds only for the short time before Identity issues the reference. It must be qualified: anyone entering Membership, belonging or an electorate needs it (no change to the aggregates).

**The two readings of "canonical Person":**

| Reading | Meaning | Supports | Consequence |
|---|---|---|---|
| **R1: identity-resolution record for any known human** | Identity holds a bare Person for everyone any organisation records, registered or not | BR-10 (eager); XD-3 §2.1 "One canonical Person exists" | Identity holds bare records about non-consenting people (C-2: bare `PersonId`); duplicates across organisations are routine and need merges (no matching allowed) |
| **R2: platform identity of a registered human** | a Person exists once someone claims or registers; organisations hold references meanwhile | option (d); D-111 ("Identity owns the platform identity"); data minimisation | no central records about non-registered people; no routine cross-organisation duplicates; a new "unbound reference" concept; leans discovery toward a stored link for claimed references |

**Neither reading follows from the word "Identity".** XD-3 §3 explicitly leaves K4 (exactly this) open. **PRINCIPAL, after the independent review.**

**Privacy weight (domain expertise):**
- For political parties, membership and affiliation data reveal **political opinions**: a **special category under GDPR Art. 9**.
- Any central record that ties a human to organisations, even an index, carries that weight.
- This favours minimisation (R2), and it makes the legal assessment (OQ-3a-1) a precondition for production, whichever reading is chosen. **[TU]: a legal assessment, not an architecture decision.**

### 5.2 Finance's person reference
| Case | Who is referenced | REC (P) |
|---|---|---|
| membership fee | the **Membership period** (RAT: "fees reference periods") | no person reference needed beyond Membership's |
| fee paid by someone else (a parent, a relative) | the payer, as a counterparty | if the payer is in the organisation's register: their **`PersonRef`**; otherwise **Finance's own counterparty record** (Finance-owned, organisation-local) |
| payment without Membership (donation, event fee) | the payer | as above |
| refund or correction | the original payment | Finance-internal reference |
| period-specific payment | the period | Membership period |

**Why not `OrganisationPersonId`:** it is the Organisation aggregate's internal identifier; using it would couple Finance to Organisation's internal model. `PersonRef` is the published, stable cross-context reference (XD-3) that Membership already uses.

**Remaining evidence:** which payment kinds organisations actually have (NRNA: **UNKNOWN**); the FeePaid ownership question (open). **Not decided.**

### 5.3 Logical and physical isolation
- **D-111's logical isolation is not yet met by the code (FACT):**
  - central `users` names, e-mails and last-login IP are shown across organisations (PBDIGIT-341 F-8, F-12);
  - a cache key shared across members was found and fixed (PBDIGIT-339);
  - election-only import links accounts across organisations (F-5).
- **Physical tenancy stays open.** Evidence needed to choose between one database, separate schemas or separate databases:
  - the number of organisations in 1–3 years;
  - whether any organisation **requires** separate hosting (legal or political reasons, Art. 9);
  - backup and restore per organisation;
  - the cost of migrations across many databases for one developer;
  - whether platform features that must see several organisations (sign-in discovery, deletion eligibility, BR-13-S) work across separate databases;
  - the cost of encryption at rest per organisation.
- **Not a business rule; no decision here.**

### 5.4 "Only registration and login data"
Read literally, it would remove the canonical Person, `PersonRef`, claims and discovery from Identity, **which conflicts with XD-3 (RAT)**. D-111's recorded precision keeps them. **No change needed;** the precision is already in the record.

## 6. Cross-context consistency and tactical readiness
| Item | Consistent? | Depends on | Can continue now? |
|---|---|---|---|
| §49 Structure (units, wings, change sets, Geography references) | yes (D-93, D-80…D-83, D-106) | none of Q-1…Q-3, P-I1 | **yes** |
| §50 Person belonging | yes; keyed by `PersonRef` (5.1) | **Q-2**; Q-3 (vocabulary); P-I1 only for *when* references exist (all viable options: at import) | yes, except §50.12 |
| People register rev. 3 (`OrganisationPerson`, `Affiliation`, `WorkspaceAccess`) | yes; three lifecycles and authorities shown by scenarios | Q-1 (voter), Q-3; the slot qualification (5.1) | **conditional**: after Q-1 and Q-3 |
| Tactical entry assessment T-1…T-8 | yes; T-6 is Q-3 | Q-3 | yes |
| Identity claim capability | — | P-I1 + independent review | **no** |
| **Tactical assumption turned rule** | §47 turned [PK] V-23 into a platform invariant; **§50.12 already flags it** | Q-2 | — |
| **Contradiction with a ratified boundary** | none found | — | — |

**People register aggregates, evidence per boundary:**
- **A person without any affiliation:** yes (D-108; scenario 8.1 without a capacity).
- **An affiliation without workspace access, and access changed without touching membership:** yes (D-104; 8.3).
- **"Member" from Membership only:** yes (D-103; 8.11).
- **Claims connect without exposing another organisation's profile:** yes (8.4, 8.10).
- **Duplicates:** three cases separated (8.5).

The three aggregates remain **P** until the independent review (IR-OPR-01).

## 7. Exact Principal rulings required
| # | Question | REC | Blocks |
|---|---|---|---|
| **Q-0** | Do D-103…D-111 stand as recorded (§3)? One word: **"confirmed"**, or name any to withdraw | confirm | everything built on them |
| **Q-1** | Election-only organisations: is the voter relationship **standing** (Election snapshots it per election), or **per election**? | standing | people register voter design; PBDIGIT-341 permanent fix |
| **Q-2** | May a person be a member without a unit belonging: **A** (never, platform-wide) or **B** (the organisation's rules decide)? | B | §50.12 |
| **Q-3** | Are unit-level capacities (staff, guest at a unit) and organisation-level affiliations (Worker, Guest) **independent facts** (with distinct names), **derived**, or **one level only**? | independent, distinct names | people register and §50 vocabulary (O-14) |

**Not asked now:** P-I1 (after the review); Finance reference (evidence first); tenancy (evidence first); the D-106 amendment note (separate approval, already pending).

## 8. Independent review evidence pack (P-I1 / BR-10)
**Use IR-OPR-01** (`./2026-11-10-organisation-people-register-independent-review-commission.md`, ready to hand over, fingerprinted sources), **with these additions**:
1. **Make option (d) explicit.** IR-OPR-01 §F item 4, "creation only at claim", must be read as "**reference issued at import (unbound); Person created at claim**". Otherwise it collapses into lazy creation.
2. **The narrowing finding of §5.1:** a `PersonRef` is needed at import by Membership (RAT; D-77), by belonging (§50.3) and by the electorate, so option (b) fails.
3. **Sources to add with excerpts:**
   - XD-3 §2.1–§2.3 and §3 (K4 open);
   - XD-3b §1 (K4 options i–iii; duplicates);
   - XD-3a C-2, C-4;
   - BR-10 and its amendment note ("resolves" impossible without matching);
   - `…-ratified.md:157` (Identity: equivalence; Membership reconciles through explicit facts);
   - CR-01 / D-77 (`personRefs[]`);
   - §50.3 (belonging by `PersonRef`);
   - D-110, D-111 (with precisions);
   - the adversarial review §3 (options a–d).
4. **The two readings R1/R2** (§5.1), with the GDPR Art. 9 weight noted as a legal question, not an architecture verdict.

## 9. What happens after the rulings
- **Q-0…Q-3 ruled:** close the strategic baseline for Organisation (an amendment note for D-106 if the Principal approves).
- **Hand IR-OPR-01 (with §8's additions) to a non-Claude reviewer.**
- **Then** continue tactical design: §49 now; §50 after Q-2; the people register after Q-1 and Q-3.

**For the participant's phone experience:** the member's view ("my details, my unit, my membership in this organisation") draws on exactly these three sources:
- the person record (Organisation);
- belonging (Organisation);
- the "Member" view (Membership).

Settling Q-1…Q-3 fixes what that membership area shows.
