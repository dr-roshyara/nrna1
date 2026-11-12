# Governance completion: membership-ending authority and decision records (tactical design)

| | |
|---|---|
| **Status** | **PROPOSED tactical design, revision 2. For independent review.** Revision 1 preserved as `…-rev1.md` (SHA-256 `763f82f90eb0d0cf8ddd3ffabb37fadb1e902776c9e7cd7c914b6a869dd2a306`) It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, not independent |
| **Commission** | Principal, 2026-11-12, "Governance tactical completion after D-201 and D-202"; strategic result approved in principle with the §8 clarification (`2026-11-12-governance-completion-strategic-result.md`) |
| **Binding** | D-12; D-65 under **D-181**; D-70; D-71; D-148; D-157 (thin slice, accepted) and its notes and additions through D-196; D-160; D-167; D-190; D-193; **D-194**; D-200; **D-201**; **D-202**; ratified Membership architecture (**RA**: fact table, provenance line 72, Annul / Void / Correct); Adjudication (frozen, D-67) |
| **Starting point** | **accepted:** thin slice **GTS** §3 (competence evidence), §6.1 (aggregates), §6.2 (AuthorityCheck) and correction notes 1–4. **Proposed:** governance model **GM** §20.1, §33, §40–§41 |

## Revision 1 → revision 2 (Principal DDD review of revision 1)

| # | Finding | Change | Section |
|---|---|---|---|
| G-1 | **exclusion by an office broadened D-194** ("only by the body the rules name") | **office path removed.** Exclusion only by **an organ the rules name, or one a competent body designates**. An office may only **record or transmit** that organ's decision (recorder, G-6) | §2.2 |
| G-2 | `ownAct` must not be accepted by assertion | **presented as an explicit Principal choice** (§2.3); not ratified | §2.3 |
| G-3 | **GD-12 not guaranteed by a clock read under a lock** | **log-head row keeps `lastRecordTime`; record time = max(`clock_timestamp()`, `lastRecordTime`) under the lock; a backward clock is flagged, never written as a smaller time** | §3.2, §5 T-4 |
| G-4 | pull vs push unclear | **two patterns stated separately** (C-1a, C-1b) | §4 |
| G-5 | resignation vs withdrawal | **separate subject shapes, original End reference and effective-date constraint** | §2.2 |
| G-6 | no decision records for category B | confirmed (§3.4, GC-9); unchanged | — |

**Labels:** [AD] accepted decision · [RAT] ratified · [ACC] accepted tactical text · [P] proposed model text · [AC] architectural conclusion · [TD] tactical choice made here · [TU] proof · [LQ] legal.

## 0. In business language

Governance answers two questions for the rest of PublicDigit:
- **may this person do this act?**
- **what exactly did a body decide, and can we still show it later?**

**This design adds:**
1. **three request kinds for ending a membership,** one per kind of ending, because each rests on a different source of authority:
   - a member resigning uses **their own right**;
   - an exclusion needs **a competent body's decision**;
   - recording a death needs **recording authority and evidence**.
2. **a precise decision-record contract** carrying the eight guarantees you confirmed (D-202). It is drawn from the existing model, not redesigned.

**A resignation and a recorded death are not Governance decisions** (strategic result §8). They get a Governance answer about who may record them, never a manufactured decision.

## 1. What is already accepted and reused unchanged

| Element | Source |
|---|---|
| AuthorityCheck query: (organisation, actor `PersonRef`, act kind, scope, at d, as known at τ) | **[ACC]** GTS §6.2 |
| **Answer = six ratified fields {actor, capacity, ruleRef, policyVersion, result, checkedAt} + basis {outcome, evaluatedFor, competenceKind, decisionRef, evidence}**; `requestRef` for subject-specific acts; `structureRef` | **[RAT]** RA line 72; **[ACC]** GTS correction notes A-2, 1, 2, 3, 4 |
| `outcome`: Authorised · NotAuthorised(reason) · Undetermined(reason); **Undetermined ≠ No**; rules silent → Undetermined (D-167, D-181) | **[ACC]** GTS note 1; [AD] |
| competence kinds: worked out · **asserted (four elements)** · gap declared · contradiction · not established | **[ACC]** GTS §3; D-181 for gap declared |
| committee decisions are **pushed** with the decided change; office-holders' acts are **pulled** at the act | **[ACC]** GTS note 1 |
| Resolution immutable (GD-1); competence per part on the decision date (D-12); DecisionLog (GD-11, GD-12); RecordingAuthority (V-59, recording ≠ deciding, G-6) | **[ACC]** GTS §6.1 (GD rules *named* there; defined only in GM: closed in §3 below) |
| a pending challenge changes nothing; tribunal remedies are applied facts, never Resolutions | [AD] D-71; [P] GM §40.1, §41.5 |

## 2. Request kinds for ending a membership (D-194, D-201)

### 2.1 One parameterised kind or three kinds? [TD]

| Option | For | Against |
|---|---|---|
| one `end-membership(endKind)` | one name | **hides three different authority sources behind one parameter**; a consumer could pass the wrong kind and receive an answer evaluated under the wrong rule |
| **three act kinds (chosen)** | each name states its authority source; a 1:1 match to the ratified closed list (RA; Membership register D-3) | three names |

**Chosen: three act kinds.** **`legacy` gets none:** it is attested migration only, outside release 1 (D-160).

### 2.2 The three act kinds

| Act kind | Actor / capacity | Subject (`requestRef`) | `evaluatedFor` | Authorised when | Rules silent | Category (strategic result §8) |
|---|---|---|---|---|---|---|
| **`record-resignation`** (records a resignation **or its withdrawal before effect**, D-201) | **(a) self-service:** the member's own `PersonRef`, capacity = "the member concerned"; **(b) recorder:** an officer's `PersonRef`, capacity = their recording tenure | **two shapes:**<br>- **resign:** (member's `PersonRef`, period ref, declared effective date ≥ declaration date, D-201) + request id;<br>- **withdraw:** (member's `PersonRef`, period ref, **reference of the original resignation End**) + request id. **Valid only if the act's date is before that End's effective date**, as known at τ | the declaration (or withdrawal) date | **(a)** actor = subject (both resolved by Identity R-1 in this organisation; Governance compares `PersonRef`s only). **(b)** the actor holds a recording office the rules or a designation empower **to record membership declarations** | **(a) still Authorised:** the member's own right needs no designation (D-194); **(b) Undetermined** | **B: recorded fact** (the member's act). No decision record |
| **`exclude-member`** | **only an organ:** the organ the rules name for exclusions, **or one a competent body designates** (D-194). Pushed with the Resolution part that excludes (actor = the organ, capacity = its sitting). **No office exercises it** (an office may record or transmit the organ's decision as recorder only, G-6) | (member's `PersonRef`, period ref) + **the Resolution part reference** | the decision date (D-12) | **the organ is competent for exclusions in a scope covering the member, under the rules in force on the decision date** (worked out or asserted, GTS §3) | **Undetermined: nothing ends** (D-167, D-181) | **A: Governance decision** (D-202 applies in full) |
| **`record-death`** | an officer's `PersonRef` / recording tenure | (member's `PersonRef`, period ref) + request id; evidence reference | the recording date | the actor holds a recording office the rules or a designation empower **to record deaths**, **and** evidence is cited | **Undetermined: the fact waits** | **B: recorded fact** |

### 2.3 The self-service answer: a contract addition, **for the Principal's decision (not ratified)**

**For `record-resignation` (a), the authority is the member's own right, not a competence of any body.** The accepted `competenceKind` values (worked out, asserted, contradiction, interimRole) do not express this.

**Proposed addition to the answer:**
- `competenceKind = ownAct`, meaning **the actor is the subject and the act is one the subject is entitled to by D-194**;
- `ruleRef` = the organisation's provision on resignation **if its rules have one**, otherwise **"D-194 (platform rule recognising the member's right)"**;
- `policyVersion` = that provision's version, or "D-194".

**It assigns no authority to anyone else** (D-167).

**Options (the Principal decides):**

| Option | Meaning | Compatibility |
|---|---|---|
| **O-A (recommended)** | add the value **`ownAct`** to `competenceKind`, valid **only** for `record-resignation` self-service (actor = subject) | additive. **Consumers that switch on `competenceKind` must treat `ownAct` as authorised only for that act kind**; `competenceVerified` = true (the right was evaluated by Governance, as for asserted, GTS note 2) |
| O-B | reuse **`asserted`** with ruleRef = D-194 | **invalid:** asserted requires four elements (GTS §3), including a sitting and an organ, which a personal act has not |
| O-C | Membership checks actor = subject itself and records **no Governance answer** | **conflicts with the ratified decision provenance** (RA line 72: authority evidence is required) **and with D-194** (Governance gains the act kinds) |

**Only O-A is compliant.** It needs the Principal's explicit approval, then ratification with this design's acceptance (route as D-190 / D-193, D-196 / D-200).

### 2.4 Why Membership needs a Governance answer even for self-service

- **The ratified decision provenance is: actor + authority evidence {six fields} + person declaration** [RAT, RA line 72].
- **A resignation recorded with that provenance has the evidence the ratified model requires.**
- **[AC] preliminary, to be verified in M-TDD-01:** this is also what makes the resignation **a fact with decision provenance**, which **is the target the ratified `Annul` requires**. So D-201's withdrawal before the effective date may be an `Annul` of that End. **Not concluded here.**

## 3. Decision-record contract (D-202), precise

**Applies to category A only:** Resolutions and their parts.

### 3.1 Records

| Record | Fields | D-202 item | Source |
|---|---|---|---|
| **Resolution** | organ (decider); **sitting ref or method** (D-33); decision date; procedure evidence refs (minutes, extract); recorder and recording authority; parts | 1, 4, 5 | [ACC] GTS §6.1; [P] GM §20.1 |
| **Part** | part id; **type and content (what was decided)**; **effective date**; dependencies; indivisible group (if any); **competence evaluation** | 2, 5, 6 | [P] GM §20.1 |
| **CompetenceEvaluation** (per part, value object) | kind (worked out / asserted / gap declared / contradiction); **for asserted, the four elements** (provision cited in a rules document on file with its **version and fingerprint**; sitting; recorder and authority; organ's unit attachment); for worked out, grant (provision, version) and evaluated conditions; **evaluated on the decision date under the rules in force then, as known at recording** | 2, 3 | [ACC] GTS §3; [P] GM §20.1 |
| **DecisionLog entry** (per organisation) | log position; record time; what was recorded (Resolution, correction, remedy application, recorded fact) | 7 | [ACC] named, GTS §6.1; defined here |
| **Correction** | corrected record ref; corrected values; correcting evidence; recorder and recording authority; log position | 8 | [P] GM §41.4 kind `Correction` |

**The CompetenceEvaluation fields are the per-part `ruleRef` / `policyVersion` that Identity (R9-1 predicate 4) and Platform needed (X-3).** They are fixed once recorded (GD-1).

### 3.2 GD rules defined (until now only named in the accepted text)

| Rule | Definition |
|---|---|
| **GD-1** | **A Resolution, its parts and their competence evaluations are recorded once and never changed.** No update and no delete. A different view comes only through a new decision, a determination (Adjudication) or a correction of the *record* (§3.3) |
| **GD-11** | **Each organisation's DecisionLog has positions 1, 2, 3, … with no gaps:** every committed Governance record takes the next position; a rolled-back transaction takes none |
| **GD-12** | **Record time comes from the database clock and never decreases with position.** **Mechanism:** the per-organisation log-head row holds `lastPosition` and `lastRecordTime`. Under its lock: record time = **max(`clock_timestamp()`, `lastRecordTime`)**. If the clock reads earlier than `lastRecordTime`, the stored time is reused and a **clock anomaly is flagged** for operations. **A smaller time is never written** |

### 3.3 Corrections and challenges (D-202 item 8)

- **A correction** fixes **only the record**, to match the evidence (e.g. a mistyped date of the sitting, a wrongly cited article).
  - It is a new fact referencing the original, made under recording authority (V-59, G-6).
  - **The original stays visible.**
  - **It may not change what was decided** (type, content, decider, outcome). **That needs a new decision.**
  - A correction that would change the competence evaluation's inputs **creates a new evaluation record alongside the original**, and is never silently re-run (GM §20.1 "never re-run automatically").
- **A pending challenge changes nothing** (D-71).
- **Tribunal remedies** are applied by Governance as `RemedyApplied` / `RemedyInapplicable` facts with their own log positions, citing the Determination ([P] GM §40.1, §41.5). **Adjudication stays frozen and never writes Governance facts.**

### 3.4 Category B facts (resignation, death) inside Governance

- **Governance keeps no decision record for them.**
- **Membership** records them with ratified decision provenance (actor + Governance answer + declaration or evidence).
- **Governance's part:**
  - the AuthorityCheck answer;
  - **where Governance itself is affected** (e.g. a resigned or deceased member's tenure ends), a change with basis kind **`RecordedFact`** (fact type, evidence, recorder authority) and its own DecisionLog position ([P] GM §41.4).

## 4. Cross-context contracts

| # | Between | Direction | Content | Failure |
|---|---|---|---|---|
| C-1a | **Membership asks → Governance answers (pull)** | Membership **requests** AuthorityCheck at the act: `record-resignation` (self-service or recorder), `record-death` | the answer (six fields + basis); Membership records it as provenance (RA line 72) | refused; **Undetermined ≠ No**; **Membership records nothing** without `Authorised` |
| C-1b | **Governance sends → Membership applies (push)** | **an organ's exclusion:** Governance records the Resolution (D-202), then **delivers the decided part** to Membership (outbox → inbox, idempotent per part); **Membership does not ask** | the part reference with its competence evaluation as authority evidence | Membership records the outcome (applied / inapplicable with reason, e.g. period already ended) and reports it back (GM §33.3) |
| C-2 | Identity → Governance (via the caller) | the caller supplies the actor `PersonRef` from R-1 | **Governance never resolves identity** | no actor → refused |
| C-3 | Governance → Identity | `manage-claims` answers (D-196) | unchanged | as D-200 |
| C-4 | Governance → Platform | `designate-platform-contacts`, `give-case-grant` answers (D-190); **their per-part `ruleRef` / `policyVersion` now come from §3.1** | **closes X-3 at design level** | fail closed (D-193) |
| C-5 | Organisation ↔ Governance | unit ancestors supplied by the caller (`structureRef`); structural change sets pushed | unchanged (GTS notes 1, 3) | — |
| C-6 | Membership → Organisation | `End(kind)` with its effective date via C1 / C2 | coverage ends at the effective date | as C1 / C2 |

## 5. Temporal and consistency rules

| # | Rule |
|---|---|
| T-1 | **Decision date ≤ record time** (a decision is recorded at or after it is taken). A part's **effective date** is the part's own and may differ (D-12: competence on the decision date; content applicable on its effective date) |
| T-2 | **Competence is evaluated on the decision date under the rules in force then, as known at recording.** A later rule change never changes it (GD-1) |
| T-3 | **One Resolution (all parts) and its DecisionLog entry commit in one transaction.** Applying parts to their target aggregates is separate, per part, keyed by part, with recorded outcome (GM §33.3); indivisible groups via GroupApplication |
| T-4 | **DecisionLog position and record time are allocated under a per-organisation log-head lock (`FOR UPDATE`)**: position = `lastPosition` + 1 (GD-11; a rollback releases it unused because the head update rolls back too); record time per GD-12 (max with `lastRecordTime`) |
| T-5 | an AuthorityCheck answer is evaluated **at one τ** with the caller's other reads (D-73); `checkedAt` = τ |
| T-6 | **a correction never rewrites knowledge:** "as known at τ" before the correction's log position returns the original |

## 6. Refusal and failure semantics

| Situation | Result |
|---|---|
| rules name no competent organ (exclusion) or no empowered recording office (death, office-recorded resignation) | `Undetermined(reason)`; **nothing ends** |
| competence gap declared | `Undetermined` (D-181) |
| rules contradictory | `Undetermined(contradiction)` (D-70) |
| asserted without all four elements | not established → refused (GTS §3) |
| self-service resignation, actor ≠ subject | `NotAuthorised(not the member concerned)` |
| `record-death` without evidence | refused |
| Governance unavailable | **no answer → the consumer refuses** (never partial) |
| a Resolution with an invalid part | **the whole Resolution is refused** (T-3); parts are not recorded piecemeal |

## 7. Proof obligations (all NOT EXECUTED)

| # | Property |
|---|---|
| GC-1 | `record-resignation` (a): Authorised iff actor `PersonRef` = subject `PersonRef`, with `competenceKind = ownAct`, **even when the rules are silent** |
| GC-2 | `record-resignation` (b): Undetermined when no recording office is empowered; the self-service path remains available |
| GC-3 | `exclude-member`: Undetermined when no competent organ; an organ's exclusion carries its Resolution part reference and full D-202 record |
| GC-4 | `record-death`: refused without evidence; Undetermined without recording authority |
| GC-5 | GD-1: no update or delete path for Resolution, Part or CompetenceEvaluation |
| GC-6 | GD-11 / GD-12: concurrent recordings in one organisation get consecutive positions with non-decreasing times; rollbacks leave no gap |
| GC-7 | a correction leaves the original readable "as known at" earlier positions, and cannot change type, content, decider or outcome |
| GC-8 | per-part `ruleRef` / `policyVersion` are retrievable for any part, as recorded, after later rule changes (closes X-3 for Platform and Identity) |
| GC-9 | **no decision record is created for a resignation or death** (category B) |
| GC-11 | **GD-12 under a backward clock:** record time never decreases; the anomaly is flagged |
| GC-12 | **withdrawal of a resignation:** refused on or after the original End's effective date; refused without the original End reference; accepted by the member (self-service) or a recorder |
| GC-13 | **no office can exercise `exclude-member`**; an organ's exclusion arrives only by push with its part |
| GC-10 | a pending challenge leaves the decision effective; a remedy appears as an applied fact with its own log position |

## 8. Open

| # | Item | Class |
|---|---|---|
| O-1 | `competenceKind = ownAct` (§2.3): **Principal decision** (O-A recommended) | [OQ] |
| O-2 | legal effective time of a resignation | [LQ] (D-201) |
| O-3 | withdrawal representation (`Annul`) | [TD] → M-TDD-01 (§2.4) |
| O-4 | GM's other proposed parts (delegation, RuleEffect, round 7 precedence) | **out of scope; unchanged** |

## 9. Input and scope for M-TDD-01

**M-TDD-01 must cover:**
- admission with an explicit term (D-163);
- `End(resignation | exclusion | death)` with the §2 answers;
- **future-dated resignation (D-201)**: confirm the ratified model's `effectiveAt` accepts a future date, and that C1 / C2 (`closedThrough`, knowledge time) behave correctly when an ending is recorded before it takes effect;
- **withdrawal before the effective date**: verify against RA line 72 and the Annul rules (RA lines 28–30, 63, 163) **whether the End has decision provenance and Annul is valid**. If not, derive a compliant alternative **without changing the ratified model**;
- corrections under Annul / Void / Correct;
- the actor from Identity (R-1);
- the consequences via C1 / C2;
- **declarant vs recorder** (D-194, I-TDD-02 §7.1).

**Kept for counsel:** the legal effective time (D-201).

**Author's statement:**
- **Proposed; for independent review; not accepted.**
- **No ratified rule changed.** The only contract addition (O-1) is authorised by D-194 and awaits ratification.
- **No implementation authorised.**
