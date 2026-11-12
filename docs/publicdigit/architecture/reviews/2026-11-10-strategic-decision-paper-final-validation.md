# Strategic decision paper: final adversarial validation

| | |
|---|---|
| **Status** | **Validation report.** Records no ruling; edits neither the decision paper nor the decision record. Written by Claude, **not independent**. |
| **Commission** | "Final Adversarial Validation of the Strategic Decision Paper", Principal, 2026-11-10. |
| **Validates** | `./2026-11-10-principal-strategic-baseline-decision-paper.md` (the paper) **and the rulings already made on it**: D-123…D-135, decision record §11.48 (WD, direct, 2026-11-10). |
| **Sources** | the paper, dossier, challenge report, IR-OPR-01; decision record D-41…D-48, D-71, D-75, D-107, D-109, D-113, D-123…D-135; tactical model §22 (P); CR-01 C4; code `app/Services/VoterImportService.php:268-278`, `routes/election/electionRoutes.php:30-49` |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. Disagreements (stated first)
1. **The decisions are no longer pending.** The note was written before the Principal ruled.
   - On 2026-11-10 the Principal answered every open question directly, and the answers are recorded as **D-123…D-135** (WD).
   - This report therefore does not present an unruled decision register. It identifies where **three recorded rulings need amendment** (D-123, D-124, D-131) and asks the Principal to rule on those amendments.
   - The note's four issues are real. None reverses a ruling; each **narrows or completes** one.
2. **Issue B is consistent with the snapshot, but it reveals a harder limit the note does not mention.**
   - Rights are blind credentials (§22, P). **Once a credential is issued, Election cannot revoke that specific credential**, because it cannot recognise it without breaking anonymity.
   - So every duplicate safeguard must act **before issuance**. After issuance, only recording and Contestation remain.
3. **The counting error is acknowledged.** The paper's summary said "eight" while Part 3 had nine. It is moot now that all were ruled, but Part E below separates the decision kinds properly.

Otherwise I agree with the note.

## Part A. Executive assessment
- **Established (WD after today's rulings):**
  - entitlement is separate from the voting channel;
  - electoral divisions are Organisation's;
  - only affected races are held;
  - secrecy is described honestly, layer by layer;
  - archiving only when nothing is active;
  - independent capacities;
  - both fee arrangements;
  - one fixed electorate decision;
  - no central account field decides voting.
- **Strategic discovery:** complete.
- **Blocking consolidation:** three amendments (Part B: B-1, B-2, B-3) that make recorded rulings exact. They are wording-level rulings, not new discovery.
- **Tactical DDD:** may begin once those three are ruled and the baseline is consolidated.

## Part B. Corrections

### B-1. D-123: alternative channels need an assurance model
- **Evidence:** D-123 allows "a one-time credential to contact details the organisation has verified, or in-person issuance", but states no identity evidence, duplicate control or audit.
- **Business consequence:** where a family shares an e-mail, an address is stale, a number has been reassigned, an import has a wrong contact, or a committee member issues twice, the wrong human may vote or one human may vote twice.

**Ten concepts kept apart:**
1. the organisation's electorate entry;
2. entitlement (Election);
3. canonical Person (Identity);
4. Account;
5. claimed `PersonRef`;
6. voting channel;
7. **issuance authorisation**: permission to obtain a credential;
8. the **credential** itself (a blind credential, §22);
9. credential issuance;
10. ballot submission (Voting).

**Credential delivery is not paper voting.** In every channel the voter still obtains the blind credential and submits the ballot online. An in-person handover delivers only the **issuance authorisation** (for example a one-time code) and touches neither the credential nor the ballot.

| | Claimed Person (default) | Organisation-verified contact | In-person issuance |
|---|---|---|---|
| authorised by | the platform default | the written rules (Governance, R-6a) per election | same |
| identity evidence | login plus claim (organisation-issued claim code plus verified login address, D-113) | **only control of the contact**; it proves possession, not identity | an identity document checked by the issuer |
| who verifies | Identity | **the organisation**, under its rules, recorded as basis | an authorised committee member, **with a second member as witness** (REC) |
| one right per entry | Election records "authorisation issued" per entry, for any channel | same | same |
| audit | issuance record: entry, channel, time to the day (§22), basis; **never** the credential | same plus the contact's verification basis | same plus issuer and witness |
| anonymity | blind credential: Voting never sees the entry | same | same |
| dispute (wrong contact, voter says "not me", two claimants) | Identity (claim disputes) | **hold** that entry's authorisation; committee decides under the rules; Contestation | same |

**Recommended amendment to D-123:**
> "Every authorised channel states its assurance model: the identity evidence it relies on, who verifies it, and how issuance is recorded. Contact-based channels prove only control of the contact, and PublicDigit says so. In-person issuance requires the issuer's identification check and a witness. Whatever the channel, Election records one issuance authorisation per entry, and the credential and ballot remain anonymous. A disputed authorisation is held."

**Identity → Election contract:** it covers only the claimed channel (binding at issuance). For other channels the evidence is **the organisation's recorded contact verification** (Organisation → Election, as part of the entry) plus **Election's own issuance record**. No Identity contract applies. Interface names are not proposed.

**What PublicDigit guarantees:** one authorisation per entry; the anonymity of the credential and ballot; an honest statement of each channel's assurance.
**What remains the organisation's responsibility:** the correctness of its contact data and its identity checks in person.

### B-2. D-124: an Identity merge after the cut-off
- **Evidence:**
  - D-124 says "a disputed merge follows Identity's answer at issuance";
  - the paper says "merge after T, before issuance: one right";
  - D-42 says the snapshot uses only facts recorded by T.
- **Analysis, for the sequence "fixed at 10:00, merge at 11:00, issuance at 12:00":**
  - **Entitlement does not change.** Both entries stay entitled in the snapshot (D-42, D-133). The merge is not entered into it.
  - **Issuance is a separate later act with its own rule:** at most one right per claimed Person (D-123). Issuance therefore reads the binding **as known at issuance**. That is intentional: it limits how many rights are issued and never which races.
  - If the two entries have different races, a **held-right case is opened after T** and recorded as such (D-124 applies).
  - **Consistency:**
    - D-42 holds: the snapshot is untouched.
    - E-2/D-133 holds: the races per entry are unchanged.
    - VO-1 holds: no vote is involved.
    - The single sealed submission holds: either one right or a held right.
- **Merge after one right was issued but before it was used:** the credential is blind and **cannot be revoked individually**. The second entry gets no authorisation; the first credential stays valid. That is acceptable, because the human still has only one usable right.
- **Merge after both were used:** **undetectable in the votes and uncorrectable by identity** (VO-1). Election records that two authorisations went to entries now known to be one Person. Contestation may consider whether the margin was affected (D-71). The remedy is decided by Adjudication; it is never the removal of a vote.

**Recommended amendment to D-124:**
> "The electorate snapshot is never changed by later identity knowledge. Issuance applies 'at most one right per claimed Person' using Identity's binding as known at issuance; where that would require choosing between different races, the right is held. Once issued, a credential cannot be revoked individually, so duplicate safeguards act before issuance; duplicates found afterwards are recorded and may be contested, never corrected by identifying a vote."

### B-3. D-131: the import lifecycle depends on the organisation's mode
- **Evidence:** D-131 lists "voter relationship confirmed" as a universal stage, yet D-131 (P5) and addendum 2 §2.1 say membership-mode organisations have no voter relationship. **This is an internal contradiction in the recorded ruling** (FACT).
- **Corrected variants:**

| Stage | A. Membership mode | B. Election-only |
|---|---|---|
| 1 | register entry recorded | register entry recorded |
| 2 | `PersonRef` issued | `PersonRef` issued |
| 3 | claimed (where the voter uses the claimed channel; **not a condition of entitlement**, D-123) | same |
| 4 | **membership facts held by Membership** (admission is Membership's act; **an Organisation import never creates membership**) | **standing voter relationship confirmed** |
| 5 | entitled at T (C4 at τ = T, plus division or unit) | entitled at T (voter relationship at τ, plus division or unit) |
| 6 | issuance authorisation through a channel (B-1) | same |
| pending | — (no voter-relationship step) | "person recorded, voter relationship pending" is never a voter |

- **Is an additional state needed?** One: **issuance authorisation held** (from B-1 disputes and D-124). It is Election's state, not an import state.

**Recommended amendment to D-131:**
> "The stages apply per organisation mode: in membership mode, step 4 is Membership's own facts (an import never creates membership); in election-only mode, step 4 is the standing voter relationship. Claiming is a channel requirement, not an entitlement condition."

### B-4. Duplicate protection across channels (completes D-124)
| # | Case | Who can know | Outcome |
|---|---|---|---|
| 1 | two claimed entries, one Person | Identity | one right; different races: held (D-124) |
| 2 | one claimed and one unclaimed entry, same human | **only the organisation** | the organisation's duplicate check; if it opens a case before issuance, both are held as one; otherwise residual |
| 3 | two unclaimed entries, same human | only the organisation | as 2 |
| 4 | two Persons for one human | nobody until Identity merges | residual |
| 5 | different divisions | — | held when known (D-124) |
| 6 | identical races | — | one right when known |
| 7 | unresolved at T | — | snapshot fixed, case open; held at issuance |
| 8 | found after issuance | — | B-2: no revocation; recorded; Contestation |

**Responsibilities:**
- **Organisation:**
  - checks its own register for likely duplicates **before issuance opens**, using only its own data (name, date of birth, its membership number, contact; never another organisation's, D-111);
  - opens a case;
  - decides it under its rules.
  - **PublicDigit may flag likely duplicates but never declares sameness.**
- **Identity:** merges Persons (D-75); tells the organisation about its own references.
- **Election:** one authorisation per entry; one right per claimed Person; holds for open cases; records everything except the ballot.

**Guarantee wording (refines D-124):**
> "At most one issuance authorisation per electorate entry, across all channels, and at most one per canonical Person where entries are claimed. Known duplicate cases are held before issuance. PublicDigit does not guarantee one right per human where the organisation has not detected duplicate entries or Identity has not merged duplicate Persons; once issued, a right cannot be withdrawn without breaking anonymity."

### B-5. Finance (D-132): what remains
- **B is fully decided** apart from the contract text (Finance → Election settlement fact), which is a tactical consequence.
- **A** is decided in principle but **waits for the separate ARB act on the FeePaid direction**.
- C4 is unchanged. NRNA's choice is UNKNOWN.

### B-6. The legacy import (D-135): verified
- **FACT:**
  - `VoterImportService.php:268-278` creates an Account (`User::firstOrCreate` by e-mail) with a random password and `email_verified_at = now()`, then links it to the organisation;
  - voting requires a signed-in, verified account (`electionRoutes.php:30-49`).
- **Business effect:**
  - the import **asserts an e-mail verification nobody performed** and creates an identity that D-107 says only registration creates;
  - whoever controls the inbox (through a password reset) becomes the voter. That is the weakest form of B-1's contact channel, and it is unrecorded.
- **The rule is unchanged** (D-107, D-113, D-135). This is a **conformance defect**, and containment is a **release condition** (D-135). Identity is not to be redesigned around it.

## Part C. Invariants (status)
| Invariant | Status |
|---|---|
| votes not linkable to voters | RAT VO-1 |
| entitlement independent of Account; channel per the rules | WD D-123 |
| **each channel states its assurance; one authorisation per entry; disputes held** | **P (B-1 amendment)** |
| at most one right per claimed Person | WD D-123 |
| **snapshot never changed by later identity knowledge; safeguards act before issuance; no revocation after** | **P (B-2 amendment)** |
| guarantee limited to detected duplicates | WD D-124, **wording refined (B-4)** |
| **mode-specific lifecycle; an import never creates membership or an Account** | **P (B-3 amendment)**; Account part WD D-131 |
| divisions grant no rights; one per scheme unless rules allow | WD D-125, D-126 |
| only genuine overlaps hold, and only the affected races | WD D-127 |
| secrecy per layer; no operational override | WD D-128 |
| archive only with nothing active | WD D-129 |
| no capacity implies another; one voter relationship per entry | WD D-130 |
| disputed payment never assumed | WD D-132 |
| one fixed decision for display, preview, issuance, validation; no central field decides | WD D-133 |

## Part D. Contract register
| Contract | Owner → consumer | Business fact | Time | Status | Remaining |
|---|---|---|---|---|---|
| division assignments | Organisation → Election | assignments of scheme S | asOf, τ | WD D-125 (to define) | tactical |
| voter relationships | Organisation → Election | standing relationships | asOf, τ | P (T-8); applies under D-131 | tactical |
| unit belonging | Organisation → Election | effective members of a unit or below | asOf, τ | WD R-5 (D-32) | — |
| **contact verification basis** | Organisation → Election | the entry's verified contact and its basis, for contact channels | as known at issuance | **P (B-1)** | Principal (with B-1) |
| membership predicates | Membership → Election | C4 | asOf, τ | RAT | conformance of today's code |
| payment evidence | Finance → Membership | settlement of a fee period | asOf, τ | outline RAT; **direction: separate ARB act** | ARB act |
| settlement fact | Finance → Election | fee period P settled for reference R | asOf, τ | WD D-132 (to define) | tactical |
| claim and binding, merge notices | Identity → Organisation | its own references only | event time | WD D-113 | tactical |
| binding at issuance | Identity → Election | is this Person bound to this entry's reference | **as known at issuance** (B-2) | WD D-123; **timing P (B-2)** | Principal (with B-2) |
| election rules incl. authorised channels | Governance → Election | rules in force | asOf, τ | WD R-6a; channels WD D-123 | — |

## Part E. Decision register
| Kind | Items |
|---|---|
| **Principal decisions now (amendments)** | **B-1** (assurance per channel; witness for in-person; disputes held) · **B-2** (issuance timing; no revocation) · **B-3** (mode-specific lifecycle) · **B-4 wording** (may be ruled with B-2) |
| **Already decided (WD, no action)** | D-123…D-135 except as amended above |
| **Separate ARB act** | FeePaid direction (needed for D-132 A only) |
| **Organisation-specific UNKNOWN** | Part F |
| **Operational** | D-135 containment; C4 conformance (`PBDIGIT-318`); `users.region` replacement; the earlier backlog permissions |
| **Later tactical detail** | snapshot and issuance records; credential mechanics per channel; division scheme model; duplicate-flagging heuristics; held-right release |

**Business consequences of the amendments:**
- **B-1:** organisations may use contact or in-person channels, but must record how they verified the person.
- **B-2:** a late merge never rewrites the electorate; it can only reduce rights to one, or hold them.
- **B-3:** membership organisations are not forced to keep a voter list.

**Architectural consequences:** B-1 adds one contract; B-2 fixes a contract's timing; B-3 corrects an invariant. No boundary changes.

## Part F. Remaining UNKNOWN (not blocking)
- NRNA's constitution: districts, divisions, candidacy timing, arrears arrangement, secrecy wording;
- which channels each organisation authorises;
- each organisation's secrecy minimum;
- postal or paper voting (out of scope);
- erasure (XD-PROV);
- real data mismatches.

## Part G. Tactical DDD entry criteria
1. B-1, B-2 and B-3 ruled (B-4 with B-2).
2. Strategic baseline consolidated from D-1…D-135 (plus the amendments), the ratified Membership artefacts and CR-01, with Part F kept open.
3. FeePaid direction: decided, or explicitly recorded as blocking only arrangement A.
4. Advisable: one independent (non-Claude) review of the consolidated baseline.

## Mobile and installable app (PWA)
- **B-2 makes claiming early matter.** Duplicate safeguards act only before issuance, so the installable membership area should encourage claiming well before an election. A claimed Person is the strongest channel and the only one Identity can deduplicate.
- **B-1 on the phone:**
  - an in-person or contact-delivered **one-time code** can be redeemed on any phone browser, without installing anything and without an account;
  - the device then obtains the blind credential and submits the ballot;
  - the PWA is therefore **not** required to vote, which keeps members without the app included.
- **The installable membership area (stage 2)** shows status, unit, division, entitled races after T, or "held" with the reason (B-2, B-4). It never shows another person's data.
- **Never stored on the device:** issuance codes after use, credentials, ballots, "has voted" status.
- **The app shows each channel's assurance honestly** (B-1): "this code proves you received it, not who you are" is the organisation's statement to make, and PublicDigit displays it.

## Conclusions
1. **Strategic discovery is complete.** No new business area or context emerged. Every issue was a refinement of an existing rule.
2. **The strategic baseline is not yet ready for consolidation.** Three recorded rulings need amendment: channel assurance (B-1), the timing of identity safeguards (B-2) and the mode-specific lifecycle (B-3). They are short decisions.
3. **Tactical DDD must wait** only for those three rulings and the consolidation. The FeePaid act blocks only dues arrangement A.
