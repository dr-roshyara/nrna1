  <!-- markdownlint-disable MD013 -->
  # Reference Geography G-6c: D-90 governance notices: design for Principal review

  **Status:** PROPOSED, **revision 3. DESIGN ONLY: nothing is implemented. Implementation starts only after the Principal explicitly accepts this revision,** because it changes the accepted G-4 publication write path. **Story:** PBDIGIT-346.

  | Revision | Commit | What changed |
  |---|---|---|
  | 1 | `926359ee8` | first proposal |
  | 2 | `64cb0acd2` | the Principal's conditions T-1 to T-7, verified against the code |
  | **3** | this file | the Principal review of revision 2: **(A) reversal impact detection** (before and after components, with a correctness argument); **(B) one finding lifecycle** (correction resolves; open defined once); the **correction relation** set out as a Principal decision (§9); one genuinely open question (§8.4) |

  **Consumes, unchanged:**
  - v3.11 §6, §8 (five cases, D-90 K, the confirmation table), §10, §15, §16;
  - D-84, D-84a, D-88, D-89, D-90, D-225, D-226;
  - G-3 (`excluded(r)`, E(v)), G-4 (the lock, PO-1 to PO-7), G-5 (`Knowledge`, the read model, the feed).

  ## 0. Facts from the code (verified)

  | Fact | Where |
  |---|---|
  | **E(v) is transitive:** union-find over every merge ≤ v not reversed ≤ v | `StewardRoster::knownSameAt` |
  | **A reversal may arrive before its merge;** that merge then never counts | `recordReversal`, `knownSameAt` |
  | **The idempotency checkpoint exists:** a roster entry unique by (merge id, kind); a replay returns `null` | `recordMerge`, `recordReversal` |
  | **Case B at publication:** counted approver X must not be known-same as anyone in `excludedPersons()` at the publication's roster version | `PublicationGovernance::mayPublish` |
  | **The publication row stores** `roster_version`, `governance_rule` and the counted approver; `kind ∈ {revision, notice}` | `refgeo_publications` |
  | **Merges are recorded by `StewardRosterStore::writeUnderLock`,** which cannot append a publication row | `IdentityNoticeConsumer` |
  | **There's no public "component of a person at version v"** (the union-find is internal to `knownSameAt`) | `StewardRoster` (the implementation adds `componentAt`, §5.1) |

  ## 1. In business terms

  **Sometimes PublicDigit learns after publication that an approval wasn't as independent as it looked.**
  - **The fact stays in force** (D-90 K).
  - **The record says so openly:**
    - a **finding**;
    - then exactly one resolution: **confirmation** by a genuinely independent person, a **correction** (a new revision that replaces the facts), or **closure** (the defect stops being true, for example a merge reversed).
  - **Nothing earlier is rewritten.** "The approval status as known at p" is complete and repeatable.

  ## 2. Accepted rules (not reopened)

  | Rule | Source |
  |---|---|
  | Case B: structural, era B, second person; the counted approver X becomes known-same as someone in `excluded(r)` | v3.11 §8 |
  | D-90 K: facts stay in force, flagged; **confirm or correct**; never suspend or void | D-90 |
  | The finding is system-triggered; case E closes it | v3.11 §8 |
  | Confirmation: the exact content version; eligible at the **current** roster version; not excluded; conflict declaration (D-84a) | v3.11 §8 |
  | A notice is a stream entry: own position and `recordedAt`, no claims, under the one lock | v3.11 §8, §15, §16 |
  | PO-2: one write path | v3.11 §6 |

  ## 3. Ownership

  - **Owner:** Reference Geography. Identity only notifies (D-225).
  - **Domain (pure):** `ApprovalDefects`, `FindingLifecycle`, `GovernanceNotice`, `PublishedApproval`, `ApprovalStatus`; `StewardRoster::componentAt` (pure, additive).
  - **Application:**
    - `IdentityNoticeConsumer` (under the ledger lock);
    - `ConfirmPublication`;
    - `PublishRevision` (optional `corrects`);
    - `PublishedGeography::approvalStatus`.
  - **Infrastructure:** `PostgresPublicationLedger`, the only writer of notices and of the publication-person index.
  - **No read of Identity, Organisation or Membership.**

  ## 4. Domain model

  | Element | Kind | Content and invariants |
  |---|---|---|
  | `PublishedApproval` | value | publication p, revision r, content version v and digest, **counted approver** (X at publication; **the confirmer after a confirmation**, §8.4), `excluded(r)`, v0 |
  | `ApprovalDefects` | domain service | `evaluate(roster, w, candidates, lifecycle)` returns new findings and closures (§5, §8). Pure, deterministic, ordered by position |
  | `FindingLifecycle` | domain value | for one publication: its findings and their resolutions, as at a position (§8). It answers `openFinding()`, `resolved(f)` and `corrected()` |
  | `GovernanceNotice` | value | finding (p, merge basis, w) · confirmation (finding f, confirmer, digest, w) · closure (finding f, roster entry that ended it, w) |
  | `ApprovalStatus` | read value | status + the ordered history (§10) |

  ## 5. T-1 / correction A: the atomic act and the affected publications

  ```
  IdentityNoticeConsumer::merged(m, S) / ::reversed(m)        S = the persons named by merge m
    └─ PublicationLedger::withLock(L)                          ← the one lock (refgeo_sequence FOR UPDATE), one transaction
        0. before  = { roster.componentAt(s, w-1) : s ∈ S }  ← w-1 = the committed version before this act
        1. w = L.recordRoster(r ⇒ r.recordMerge/Reversal(m))  ← null ⇒ already applied (THE checkpoint): return, write nothing
        2. after   = { roster.componentAt(s, w) : s ∈ S }
        3. P = persons(before ∪ after)                       ← §5.1
        4. candidates = L.approvalsInvolving(P)              ← publication-person index; publications not corrected (§8)
        5. (findings, closures) = ApprovalDefects.evaluate(roster@w, w, candidates, L.lifecycleFor(candidates))
        6. L.appendNotice(each), in position order           ← next positions; recordedAt as G-4
        7. commit                                            ← roster entry + every notice, or nothing
  ```

  **For a reversal, S is read from the stored merge entry m.** If m was never recorded (a reversal before its merge), S is empty, nothing can change, and only the reversal entry is written.

  ### 5.1 Why P is complete (correctness argument)

  - **What can change.** A publication's case depends only on whether its counted approver a and some e ∈ excluded(r) are known-same. That status can change at w only if E(w−1) and E(w) differ on the pair (a, e).
  - **Where a change can come from.** E(w) differs from E(w−1) only by m's links: added (a merge) or removed (a reversal). **If a and e are joined after but not before** (a merge), every joining path uses a link of m, so a lies in the component, after the change, of some s ∈ S. **If they were joined before but not after** (a reversal), every former path used a link of m, so a lay in the component, before the change, of some s ∈ S.
  - **Therefore,** if a ∉ ⋃_{s∈S} (componentAt(s, w−1) ∪ componentAt(s, w)), then a's equivalence with **every** person is unchanged, and so is its publication's case. **P = that union, so every publication whose status can change has its approver or an excluded person in P.** The index query on P returns a superset of exactly those publications; `ApprovalDefects` then decides each one exactly.
  - **The A–B–C–D example:** reversing B–C. `before` = {A, B, C, D}; `after` = {A, B} ∪ {C, D}. **P contains A and D,** so a publication involving A and D is re-evaluated.
  - **Several independent paths:** both are in the same "before" component, so they are in P. The evaluation finds A~D still holds through the other path, so **no closure.**
  - **No full scan:** P is bounded by the size of the touched components. A full scan is needed only if the index is missing (it's written with every second-person publication, §11).

  ### 5.2 Guarantees

  | Guarantee | Why |
  |---|---|
  | No merge or reversal without its findings or closures, and the reverse | steps 0 to 7 are one transaction |
  | The checkpoint never advances alone | it **is** the roster entry, in the same transaction (no second table) |
  | Redelivery writes nothing | step 1 returns `null` |
  | Every affected publication | §5.1 |
  | Serialised with publication and with closes through τ | the same `FOR UPDATE` row. A publication after the merge goes through `mayPublish` (case A) |

  ## 6. T-2: historical evaluation

  **`ApprovalDefects` judges each candidate p at w under E(w),** with p's **counted approver** (§4) and `excluded(r)`. Its result depends on p's lifecycle (§8).

  | Situation | Result |
  |---|---|
  | A merge after publication makes the counted approver known-same as some e | a **finding**, if p has no open finding and isn't corrected |
  | A merge before publication | case A, at `mayPublish`; no finding |
  | Case C (era A / non-structural), case D (others merged) | none |
  | A chain completes the link | a finding; basis = the merge ids on the joining path(s) |
  | Two independent paths; one reversed | still defective: **no closure** |
  | The only path reversed | **closure** of the open finding |
  | A reversal before its merge | no change |
  | Defective again after a closure | a **new** finding (new episode) |
  | One merge or reversal touching several publications | each is evaluated; notices in position order |

  ## 7. T-3: confirmation

  `ConfirmPublication::confirm(f, d)`. The actor comes from `ActingPerson`, and the act runs under the lock. **Refused unless all hold at the current version w:**
  1. f is a finding and is **open** (§8.1);
  2. d = the digest of p's content version;
  3. the actor `mayActForAt(r, w)`;
  4. the actor isn't known-same at w as anyone in excluded(r), **nor as the counted approver whose defect f records**;
  5. a D-84a declaration exists at w, without a material conflict.

  **It's appended as a confirmation notice concerning f.** It changes no claim and isn't a publication approval.

  ## 8. Correction B: the finding lifecycle (one rule, used everywhere)

  ### 8.1 States and events

  ```
                  ┌── confirmation notice (concerns f) ─────▶ CONFIRMED  (resolved)
  FINDING (open) ─┼── closure notice (concerns f) ──────────▶ CLOSED     (resolved)
                  └── PUBLISHED revision q, corrects_position = p, q > f
                                                          ──▶ CORRECTED  (resolved; resolves EVERY open finding of p)
  A correcting revision that is drafted, submitted or approved but NOT published: no event; f stays open.
  ```

  **One definition, as at position k:** f is **open** at k iff f ≤ k and **no** confirmation of f, **no** closure of f, and **no** published correction q of p with f < q ≤ k.
  - `FindingLifecycle` computes this.
  - The ledger's `openFindingFor(p)` (k = now), the read model's status (k = the knowledge position), `ConfirmPublication` and duplicate prevention **all use the same definition.**

  ### 8.2 The rules

  | # | Question | Rule |
  |---|---|---|
  | 1 | When is a finding open? | §8.1 |
  | 2 | When is it resolved? | by its confirmation, its closure, or a **published** correction of p after it, **whichever comes first** (one resolution per finding) |
  | 3 | Is correction its own notice? | **No.** The correcting revision's **publication row** (`corrects_position = p`) is the resolution event. No extra notice is needed; it's already a stream entry at its own position |
  | 4 | Duplicates | no new finding for p while p has an open finding |
  | 5 | Confirmation after correction | **refused** (f is no longer open) |
  | 6 | Correction and closure | **the earlier wins:** f is resolved once. A correction linking to p is refused if p has **no** open finding (the link exists only to resolve D-90 findings); the revision can still be published as an ordinary revision without the link |
  | 7 | Correction and confirmation | the same: the earlier wins; the other is refused |
  | 8 | After a correction, later merges or reversals | **depends on §9:** with full coverage (recommended), p's facts are replaced, so **p is no longer evaluated** (corrected publications are excluded from candidates). With minimal overlap, p would keep partly in-force facts and would still need evaluation (§9, the consequence) |
  | 9 | After a closure | p is evaluated again; a recurring defect gives a **new** finding (a new episode) |
  | 10 | After a confirmation | **the confirmer becomes p's counted approver** (they supplied the independent judgement D-88 requires); see §8.4 |

  ### 8.3 Status at a knowledge position

  See §10. It uses §8.1 with k = the knowledge position, so a later notice or correction never changes an earlier answer.

  ### 8.4 Open question for the Principal: a confirmer later found not independent

  **D-90 doesn't say what happens if, after a confirmation, a merge shows the *confirmer* was known-same as someone excluded.**
  - **(a) Treat the confirmation like the approval it replaced (recommended):** the confirmer is p's counted approver from then on (§8.2 row 10). A later defect gives a **new finding** (a new episode), resolvable the same three ways. *Business consequence:* the same openness for confirmations as for approvals; no loophole.
  - **(b) A confirmation is final:** no further evaluation of p. *Business consequence:* simpler, but a defective confirmation would stand unflagged.

  **Recommendation: (a).** It applies D-88's independence rule uniformly. **Until decided, the design assumes (a); the tests mark this.**

  ## 9. T-5: the correction relation (Principal decision: disagreement with keeping minimal overlap)

  **D-90:** "…a valid second person **confirms** (appended) or a **correction replaces them**" ("them" = the flagged facts).

  | Option | Rule | Prevents | Doesn't prove | Consequence |
  |---|---|---|---|---|
  | **(1) Minimal overlap** (revision 2) | the correcting revision has ≥ 1 claim in a slot of p | an unrelated revision being linked | that p's facts were replaced | **some of p's facts may stay in force on the defective approval, yet the finding is resolved and the status says "corrected": the remaining defect is hidden** (§8.2 row 8 must then keep evaluating p) |
  | **(2) Full coverage** (**recommended**) | for **every** claim c of p, the correcting revision has a claim in c's slot whose interval covers c's interval | the above, **and** a partial correction passing as a full one | that the new values are *right* (that's normal validation and approval) | **p's facts are all superseded where they applied.** "Corrected" means what it says; p leaves evaluation. A partial fix is still possible as an ordinary revision without the link: p stays flagged until confirmed or fully corrected |

  **Recommendation: (2).** It's the literal reading of "replaces them", and the only reading under which "corrected" never hides a still-defective approval.
  - **Test:** `PublishRevision` with `corrects: p` is refused when any claim of p isn't covered (slot and interval), and accepted when all are.
  - **Mutation:** "coverage checks one claim only" must be killed.

  ## 10. T-6: historical status

  `approvalStatus(int $position, Knowledge $k): ApprovalStatus`, which reads **only entries ≤ k.position.** As at τ means `knowledgeAtTime(τ)` (G-5: close through τ, map it to the highest position recorded by τ, ties by position).

  **Derivation for p, with entries ≤ k:**
  1. **a published correction q ≤ k (with option 2):** corrected (q);
  2. **else no finding:** approved;
  3. **else the latest finding f:**
    - open (§8.1) gives approval-defective;
    - resolved by a confirmation gives confirmed;
    - resolved by a closure gives finding-closed.

  **The history** lists every finding, its resolution and the correction, in position order. **Every episode is kept.**

  ## 11. T-7: schema, immutability, write path

  **`refgeo_publications`, additive columns:** `notice_kind`, `concerns_position` (FK), `merge_basis jsonb`, `ended_by text`, `confirmed_by text`, `confirmed_digest text`, `corrects_position` (FK).

  | Row | Must have | Must not have |
  |---|---|---|
  | revision | revision fields; optional `corrects_position` (an earlier **revision** row) | notice fields |
  | finding | notice_kind, concerns_position (a revision row), merge_basis | revision fields, confirmed_*, ended_by, corrects_position |
  | confirmation | notice_kind, concerns_position (a **finding** row), confirmed_by, confirmed_digest | revision fields, merge_basis, ended_by, corrects_position |
  | closure | notice_kind, concerns_position (a **finding** row), ended_by | revision fields, merge_basis, confirmed_*, corrects_position |

  - **CHECKs:** field combinations; `concerns_position < position`; `corrects_position < position`.
  - **BEFORE INSERT trigger:** the referenced row's kind.
  - **Unique:** one confirmation per finding; one closure per finding.
  - **"At most one resolution per finding"** and **"no duplicate open finding"** are enforced by the ledger under the lock, through `FindingLifecycle` (one definition, §8.1), and **proven by tests.** A partial unique index can't express "open".
  - **`refgeo_publication_persons`** (position, person_ref, role ∈ {approver, excluded, confirmer}): append-only and immutable; written by the ledger at second-person publications **and at confirmations** (the new counted approver, §8.4 (a)); backfilled in its migration.
  - **Unchanged G-4 safeguards:** the append trigger (gap-free positions), immutability, the clock refusal. Notices take positions like revisions.
  - **Write path:** only `PostgresPublicationLedger`; **the no-bypass guard gains a rule** for `appendNotice`, `notice_kind` and `refgeo_publication_persons`.

  ## 12. Tests to write first (design only: none run)

  ### Reversal impact detection (correction A)

  | # | Test | Level |
  |---|---|---|
  | A1 | Merge then reversal (single link): finding, then closure | domain + PG |
  | A2 | **A–B–C–D chain; publication on A and D; reverse B–C:** re-evaluated and closed (P ⊇ {A, D}) | domain + PG |
  | A3 | Two independent paths A~D; reverse one: **no closure** | domain |
  | A4 | Reverse the only remaining path: closure | domain |
  | A5 | Reversal before merge: no finding; only the reversal entry | domain + PG |
  | A6 | One reversal affecting several publications: one closure each, in position order | domain + PG |
  | A7 | Replay of a merge or reversal: nothing written | PG |
  | A8 | **Property test:** for random rosters and changes, `P` ⊇ the approvers of every publication whose case flips (against a full-scan oracle) | domain |
  | A9 | Concurrent publication and reversal: serialised; the result equals one serial order | PG (forked) |
  | A10 | A forced failure after the roster write: neither the entry nor any notice | PG |

  ### Finding lifecycle (correction B)

  | # | Test | Level |
  |---|---|---|
  | B1 | A finding is open | domain |
  | B2 | Confirmation resolves it; a later confirmation refused | app + PG |
  | B3 | Reversal closes it | domain + PG |
  | B4 | **A drafted, submitted or approved correction doesn't resolve it** | app + PG |
  | B5 | **A published correction resolves every open finding of p** | app + PG |
  | B6 | Confirmation after correction refused; correction after confirmation refused as a link | app + PG |
  | B7 | No duplicate finding while open | domain + PG |
  | B8 | Closed, then defective again: a new episode | domain |
  | B9 | §8.4 (a): the confirmer becomes the counted approver; a later defect is a new finding *(marked: pending the Principal's decision)* | domain |
  | B10 | Status at each knowledge position before and after each transition; a later entry never changes an earlier answer | app + PG |
  | B11 | Correction coverage (§9 option 2): partial refused, full accepted *(marked: pending the Principal's decision)* | app + PG |

  ### Carried over from revision 2

  The five cases A to E; confirmation eligibility; schema combinations; notice immutability; gap-free positions with notices; no-bypass.

  **Mutation intentions** (each must be killed by an assertion, **and the run must show the intended test executed**):
  - P built from "after" components only (A2 must kill it);
  - a correction not counted as a resolution;
  - a draft counted as a correction;
  - coverage checking one claim;
  - a closure while another path holds;
  - a merge committed without its findings;
  - a confirmation by an excluded person;
  - the status reading beyond k.

  ## 13. Implementation sequence (after acceptance; each RED → GREEN → guide → commit)

  1. **G-6c-1:** schema and `refgeo_publication_persons` (with backfill).
  2. **G-6c-2:** domain: `componentAt`, `ApprovalDefects`, `FindingLifecycle`, `GovernanceNotice`, `ApprovalStatus` (A1 to A8, B1, B3, B7 to B9).
  3. **G-6c-3:** the ledger: `recordRoster`, `appendNotice`, the person index; `IdentityNoticeConsumer` under `withLock` (A5 to A7, A9, A10).
  4. **G-6c-4:** `ConfirmPublication` (B2, B6).
  5. **G-6c-5:** the correction link and coverage (B4 to B6, B11).
  6. **G-6c-6:** the read model and feed fields (B10); no-bypass; mutation run; acceptance package.

  ## 14. Administrator implications

  - **New Reference Geography migrations,** not for production until the G-4 migration-approval control exists.
  - **No configuration, queue or scheduler.**

  ## 15. Decisions, assumptions, risks

  | Item | Kind |
  |---|---|
  | D-90, the cases, eligibility | ACCEPTED |
  | Correction A (before and after components; the argument in §5.1) | TECHNICAL |
  | Correction B (one lifecycle; correction resolves via its publication row) | TECHNICAL, derived from D-90 "confirm **or** correct" |
  | **§9: the correction relation: (2) full coverage recommended, (1) minimal overlap** | **PRINCIPAL DECISION** |
  | **§8.4: a confirmer later found not independent: (a) re-evaluate (recommended), (b) final** | **PRINCIPAL DECISION** |
  | **Final acceptance of revision 3 before implementation** | **PRINCIPAL DECISION** |
  | The person-index cost (one row per approver, excluded person or confirmer) | technical cost, accepted in revision 2 |

  **No new business rule is introduced without a decision. G-4's conditions, lock and single write path are kept.**

  **Traceability:** v3.11 §6, §8, §10, §15, §16 · D-84, D-84a, D-88, D-89, D-90, D-225 · G-3, G-4, G-5 · the Principal reviews of 2026-11-13 (T-1 to T-7; corrections A and B) · PBDIGIT-346.

## 16. Principal decision (recorded 2026-11-14)

> **Revision 3 ACCEPTED, with two decisions:**
> - **§9 the correction relation: option (2), full coverage.** A correction resolves a D-90 finding only if it replaces every claim of the defective publication over the complete validity interval of each claim. A partial correction is an ordinary revision; the publication stays flagged.
> - **§8.4 a confirmer later found not independent: option (a), re-evaluate.** The confirmation can itself become the basis of a new finding. The original approver applies before the confirmation, the confirmer after it, and this stays historically traceable.
>
> **Correction A (before and after components) and correction B (one `FindingLifecycle`) accepted.**
> **Technical clarification required:** the implementation guide defines interval coverage precisely (finite and open-ended intervals; whether several claims in a slot may jointly cover one original interval), with tests.
> **Design acceptance only:** no production migration or publication authority is granted; the G-4 restrictions are unchanged.

**The Principal's words:** "I accept the recommendations written in the document" (the Principal review of revision 3, 2026-11-14: "ACCEPTED WITH TWO PRINCIPAL DECISIONS").

## 17. Implementation correction (technical, found before any code, 2026-11-14)

- **§0 said the publication row stores the counted approver. It does not:**
  - `refgeo_publications.independence` holds only a sentence ("independent as known to Identity at roster version v");
  - `GovernanceDecision::secondPerson` is never persisted;
  - publication rows are immutable, so no column can be added and backfilled on them.
- **Resolution (no business change):**
  - the counted approver X of a publication is **derived deterministically** by replaying `PublicationGovernance::mayPublish(revision, roster, v0)` at the stored roster version v0. Published revisions are closed (G-4f), and the roster is versioned, so the replay always yields the same X;
  - **`refgeo_publication_persons` records every approver** of the published content version (role `approver`), the excluded persons (role `excluded`) and, later, confirmers (role `confirmer`). **That's a superset of the persons §5.1 needs, which is sufficient: candidate selection may over-select; `ApprovalDefects` decides exactly;**
  - **no column `counted_approver` is added;** §11's other columns stand.
