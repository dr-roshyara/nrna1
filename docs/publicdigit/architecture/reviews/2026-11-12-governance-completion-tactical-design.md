# Governance completion: membership-ending authority and decision records (tactical design)

| | |
|---|---|
| **Status** | **PROPOSED tactical design, revision 5. Independently reviewed (IR-GM-01, GPT-6: READY AFTER SPECIFIC CORRECTIONS); this revision applies A-01.** Earlier revisions preserved: rev 1 (`763f82f9…`), rev 2 (`9a52b75d…`), rev 3 (`fa5b38a8…`), rev 4 `…-rev4.md` (`7903c028…`). Revision 1 preserved as `…-rev1.md` (SHA-256 `763f82f90eb0d0cf8ddd3ffabb37fadb1e902776c9e7cd7c914b6a869dd2a306`) It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, not independent |
| **Commission** | Principal, 2026-11-12, "Governance tactical completion after D-201 and D-202"; strategic result approved in principle with the §8 clarification (`2026-11-12-governance-completion-strategic-result.md`) |
| **Binding** | D-12; D-65 under **D-181**; D-70; D-71; D-148; D-157 (thin slice, accepted) and its notes and additions through D-196; D-160; D-167; D-190; D-193; **D-194**; D-200; **D-201**; **D-202**; ratified Membership architecture (**RA**: fact table, provenance line 72, Annul / Void / Correct); Adjudication (frozen, D-67) |
| **Starting point** | **accepted:** thin slice **GTS** §3 (competence evidence), §6.1 (aggregates), §6.2 (AuthorityCheck) and correction notes 1–4. **Proposed:** governance model **GM** §20.1, §33, §40–§41 |

## Revision 4 → revision 5 (IR-GM-01, GPT-6)

| # | Finding | Change | Section |
|---|---|---|---|
| A-01 | **a tribunal remedy was grouped with Category A** (Governance decisions), contradicting D-202 item 8 and §3.3 | **two routes split:** a competent body's **Annul Resolution** is Category A (D-202 applies); a **tribunal remedy** is a **separate applied fact** (`RemedyApplied` / `RemedyInapplicable`, citing the Determination), **no Resolution manufactured, no Resolution competence evaluation applied**. **The Membership Annul still carries RA §9 decision provenance:** the Resolution part, or the remedy application with the Determination reference | §2.5 |
| B-03 | GC-8 coverage | GC-8 covers **every competence-evaluation kind**, incl. gap declared and contradiction | §7 |

## Revision 3 → revision 4 (author's review of 2026-11-12, `2026-11-12-m-tdd-01-rev2-and-governance-rev3-author-review.md`; D-206, D-207)

| # | Finding | Change | Section |
|---|---|---|---|
| F-2 | GC-12 still required the original End reference | GC-12 rewritten for the scheduled declaration (D-204) | §7 |
| F-3 | `ownAct` still shown as a Principal choice | **status: approved by D-203** (§11.102); ratified with this design's acceptance | §2.3, §8 |
| F-4 | no authority for correcting membership records | **act kinds `correct-membership-record`, `void-membership-record` (pulled, recording authority with evidence) and Annul by push (D-206)** | §2.5 |
| F-8 | no authority to place non-member capacities | **act kind `place-person(capacity, unit)`** (D-207; pattern of D-154 / D-172) | §2.6 |

## Revision 2 → revision 3 (author's corrections, 2026-11-12)

| # | Finding | Change | Section |
|---|---|---|---|
| G-7 | **GD-12 as written in revision 2 adjusted timestamps** (reused `lastRecordTime`) | **Corrected to the ratified D-73 participant contract** (Reference Geography v3.11 PO-6: "Refusal on clock regression; no timestamp is ever adjusted"; PO-8: every D-73 participant meets the same contract). **If the clock reads earlier than `lastRecordTime`, the recording is refused (and retried); a time is never adjusted** | §3.2, §5 T-4, GC-11 |
| G-8 | **D-204** realises D-201 without future-dated ledger facts | withdrawal no longer references an End: it **cancels a scheduled resignation declaration** held by Membership; **§2.4's Annul reading is withdrawn** | §2.2, §2.4, §9 |
| — | **[EXT] noted, not changed:** Membership's accepted ledger clock (D-162, spec §4 :74) takes `max(appClock, last + 1 µs, closedThrough + 1 µs)`, **the known PO-8 non-conformance** recorded at D-93 | **unchanged here**; carried to M-TDD-01 as an open gate | §8 |

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
| **`record-resignation`** (records a resignation **or its withdrawal before effect**, D-201) | **(a) self-service:** the member's own `PersonRef`, capacity = "the member concerned"; **(b) recorder:** an officer's `PersonRef`, capacity = their recording tenure | **two shapes:**<br>- **resign:** (member's `PersonRef`, period ref, declared effective date ≥ declaration date, D-201) + request id;<br>- **withdraw (D-204):** (member's `PersonRef`, period ref, **reference of the scheduled resignation declaration**) + request id. **Valid only while the declaration is still scheduled** (its effective date not yet reached and no End recorded) | the declaration (or withdrawal) date | **(a)** actor = subject (both resolved by Identity R-1 in this organisation; Governance compares `PersonRef`s only). **(b)** the actor holds a recording office the rules or a designation empower **to record membership declarations** | **(a) still Authorised:** the member's own right needs no designation (D-194); **(b) Undetermined** | **B: recorded fact** (the member's act). No decision record |
| **`exclude-member`** | **only an organ:** the organ the rules name for exclusions, **or one a competent body designates** (D-194). Pushed with the Resolution part that excludes (actor = the organ, capacity = its sitting). **No office exercises it** (an office may record or transmit the organ's decision as recorder only, G-6) | (member's `PersonRef`, period ref) + **the Resolution part reference** | the decision date (D-12) | **the organ is competent for exclusions in a scope covering the member, under the rules in force on the decision date** (worked out or asserted, GTS §3) | **Undetermined: nothing ends** (D-167, D-181) | **A: Governance decision** (D-202 applies in full) |
| **`record-death`** | an officer's `PersonRef` / recording tenure | (member's `PersonRef`, period ref) + request id; evidence reference | the recording date | the actor holds a recording office the rules or a designation empower **to record deaths**, **and** evidence is cited | **Undetermined: the fact waits** | **B: recorded fact** |

### 2.3 The self-service answer: a contract addition, **approved by D-203** (§11.102); ratified with this design's acceptance

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

**Only O-A is compliant. The Principal approved O-A (D-203).** It is ratified with this design's acceptance (route as D-190 / D-193, D-196 / D-200).

### 2.4 Why Membership needs a Governance answer even for self-service

- **The ratified decision provenance is: actor + authority evidence {six fields} + person declaration** [RAT, RA line 72].
- **A resignation recorded with that provenance has the evidence the ratified model requires.**
- ~~*Annul reading of withdrawal*~~ **withdrawn by D-204:** a future-dated resignation is a **scheduled declaration outside the ledger**; the End is recorded (with this answer as provenance) on its effective date; withdrawal cancels the declaration.

### 2.5 Correcting membership records (D-206)

**The ratified correction algebra (RA §9) is unchanged.** These act kinds supply its authority evidence.

| Act kind | Pattern | Actor / capacity | Subject | Authorised when | Rules silent | Category |
|---|---|---|---|---|---|---|
| **`correct-membership-record`** | pull | officer / recording tenure | (membership fact ref, corrected value, evidence ref) + request id | the actor holds a recording office the rules or a designation empower **to record membership facts**, **and** evidence is cited | **Undetermined: nothing corrected** | B: a record correction (no decision record) |
| **`void-membership-record`** | pull | officer / recording tenure | (membership fact ref, evidence ref) + request id | as above. **Reported to the organisation; challengeable** (D-206, as D-148 / D-159) | **Undetermined** | B |
| **Annul** of a membership fact, **route 1** | **push** | **the body competent for the original act**, by a new Resolution part (`annul-membership-act`, subject = the fact ref) | the fact ref | competence for the original act, on the new decision's date | **Undetermined: nothing annulled** | **A: a Governance decision** (D-202 applies) |
| **Annul** of a membership fact, **route 2** | **push** | **a tribunal determination remedy** (Adjudication, D-67, frozen), **applied by Governance as a `RemedyApplied` fact** with its own DecisionLog position, citing the Determination (GM §40.1, §41.5) | the fact ref + Determination and remedy-part ref | the remedy is final and addressed to this fact | — (no competence evaluation) | **not a Resolution and not a category A record** (D-202 item 8). Membership's Annul carries the remedy application as its decision provenance (RA §9) |

### 2.6 Placing non-member capacities (D-207)

| Act kind | Pattern | Actor / capacity | Subject | Authorised when | Rules silent |
|---|---|---|---|---|---|
| **`place-person(capacity, unit)`**, capacity ∈ {Worker, Guest, …non-Member capacities of D-101} | pull, or pushed with a structural / placement decision | officer or organ, as for `place-member` (D-154 / D-172) | (`PersonRef`, capacity, unit, valid from) + request id | a designated body or office is competent for placements of that capacity in a scope covering the unit | **Undetermined: no belonging** |

**A pending applicant reads forums only where placed as Guest by this act** (D-207). The capacity **Member** is never placed by it (D-103).

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
| **GD-12** | **Record time comes from the database clock and never decreases with position.** **Mechanism:** the per-organisation log-head row holds `lastPosition` and `lastRecordTime`. Under its lock: record time = **`clock_timestamp()`**. **If it is earlier than `lastRecordTime`, the recording is refused** (rolled back, retried later) and a clock anomaly is flagged. **No time is ever adjusted** (D-73 participant contract; Reference Geography PO-6 / PO-8). **A smaller time is never written** |

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
| T-4 | **DecisionLog position and record time are allocated under a per-organisation log-head lock (`FOR UPDATE`)**: position = `lastPosition` + 1 (GD-11; a rollback releases it unused because the head update rolls back too); record time per GD-12 (**refuse on regression; never adjust**) |
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
| GC-8 | per-part `ruleRef` / `policyVersion` are retrievable for any part, as recorded, after later rule changes, **for every competence-evaluation kind (worked out, asserted, gap declared, contradiction)** (closes X-3 for Platform and Identity) |
| GC-9 | **no decision record is created for a resignation or death** (category B) |
| GC-11 | **GD-12 under a backward clock:** the recording is refused and retried; **no stored time differs from the clock reading**; no time decreases; the anomaly is flagged |
| GC-12 | **withdrawal of a scheduled resignation (D-204):** requires the **scheduled declaration's reference**; refused once the effective date has started (organisation-local, D-5(a)) or when the declaration is not `scheduled`; accepted by the member (self-service) or a recorder |
| GC-13 | **no office can exercise `exclude-member`**; an organ's exclusion arrives only by push with its part |
| GC-14 | `correct-membership-record` / `void-membership-record`: Authorised only with recording authority and evidence; a Void is reported to the organisation; Undetermined when no recorder is empowered |
| GC-15 | **Annul only by push** (a competent body's Resolution part, or a determination remedy); no pull path annuls |
| GC-17 | **both Annul routes, with RA §9 provenance asserted on Membership's Annul (IR-GM-01-R1 B-R1-01):**<br>(1) **competent-body route:** the Resolution part is the Annul's decision provenance;<br>(2) **tribunal route:** the `RemedyApplied` fact with the Determination and remedy-part reference is the provenance;<br>(3) the tribunal route creates **neither a Resolution nor a CompetenceEvaluation**, and appears with its own log position |
| GC-16 | `place-person` never places the capacity Member; Undetermined when no competent body |
| GC-10 | a pending challenge leaves the decision effective; a remedy appears as an applied fact with its own log position |

## 8. Open

| # | Item | Class |
|---|---|---|
| O-1 | `competenceKind = ownAct` (§2.3): **approved by D-203**; ratification at acceptance | [AD] |
| O-2 | legal effective time of a resignation | [LQ] (D-201) |
| O-3 | ~~withdrawal representation~~ **settled by D-204** | — |
| O-5 | Membership ledger clock adjusts timestamps (PO-8 known non-conformance, D-93) | **[EXT] implementation gate**, carried to M-TDD-01 |
| O-4 | GM's other proposed parts (delegation, RuleEffect, round 7 precedence) | **out of scope; unchanged** |

## 9. Input and scope for M-TDD-01

**M-TDD-01 must cover:**
- admission with an explicit term (D-163);
- `End(resignation | exclusion | death)` with the §2 answers;
- **future-dated resignation and withdrawal (D-204):** a **scheduled resignation declaration** outside the ledger; the End is recorded on its effective date (no future-dated ledger fact, D-5(b)); withdrawal cancels the declaration;
- corrections under Annul / Void / Correct;
- the actor from Identity (R-1);
- the consequences via C1 / C2;
- **declarant vs recorder** (D-194, I-TDD-02 §7.1).

**Kept for counsel:** the legal effective time (D-201).

**Author's statement:**
- **Proposed; for independent review; not accepted.**
- **No ratified rule changed.** The only contract addition (O-1) is authorised by D-194 and awaits ratification.
- **No implementation authorised.**

---

**Status note (2026-11-12, D-208):**
- **Revision 5 is ACCEPTED WITH CONDITIONS** (decision record §11.107).
- **Accepted text:** this file at SHA-256 `a29821dabf7ca48b9dbb82a8c14bde2a32dd78906884e8b4bf3ba0c51d513f2c`, which is the reviewed `04e41258…` plus the GC-17 test wording IR-GM-01-R1 requested. **This note is the only later change.**
- **The Governance addition note is ratified** into the thin slice.
- **Build gates:** GC-1 … GC-17 (not executed).
- **No implementation authorised.**
