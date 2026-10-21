# Settlement of window C21 (S0111–S0115) and of the application of the Principal's decisions after C20

*2026-10-21. By the Professor. One window reviewer, W (Opus), in the normal window form. Object: §8.14 (the main work); the Principal's decisions after C20 as applied (§9.16 R4a v7, §9.13 v12, model v13); a spot-check of the C20 settlement.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| W, step 1 (blind inventory) | `STEP1-INVENTORY.md` | `89994c85295a667f55dd6cc305ddfeadaa6a52f0d95fd310afa07e9ddb043315` |
| W, steps 2–3 | `REVIEW-REPORT.md` | `e90c9bced9e4dfce24b5a705b0b1c52cafbafc703be0b19ed707e4cf8f08eeeb` |

## 0. Deliverable status

**Met.**
- S0111–S0115 were read in order, all full, each started and closed in turn.
- §8.14 is settled as v2:
  - CON-0208 and CON-0210 are INVALID as stated, restated as CON-0213 and CON-0214;
  - CON-0209 is VALIDATED;
  - CON-0211 and CON-0212 are provisional.
- The decisions after C20 are applied and repaired: §9.16 v8 (R4a v8), §9.13 v13, §9.15 v9, model v14.

**No critical finding.** The reviewer reported 12 findings, 6 of them high. **One question goes to the Principal** (§4).

## 1. Measures (per source)

| Source | Lines | Time | Depth (Professor / reviewer, blind) | Professor's findings | Review findings bearing on it |
|---|---|---|---|---|---|
| S0111 | 75 | 10 s | full / full | 3 | W-1, W-8 |
| S0112 | 77 | 11 s | full / full | 4 | W-1, W-11 (b, d), W-7 (L48) |
| S0113 | 97 | 10 s | full / full | 5 | W-11 (a, c) |
| S0114 | 137 | 13 s | full / full | 7 | W-7 |
| S0115 | 99 | 10 s | full / full (lighter) | 4 | W-9, W-10 |

- **Time** is stop minus start, from the READ-LOG: one whole-file read plus the close.
- **Reviewer time** (harness): step 1, 113 s; steps 2–3, 508 s.
- **Correction (the Principal, 2026-10-21, after C21; additive):** the times above are **not** a measurement of time per source. They are the gap between the start call and the close call in the tool. From C22, a source is started when opened and closed after its theory is written into the book.

- **Skim:** none, and both judgements agree. S0115 is the nearest to skim in both. The §3 safeguard still cannot run (no source skimmed in 114).
- **Proportion** (the Principal, 2026-10-21): the window's own work was the five sources and §8.14. The kernel work was the application of the Principal's decisions and its repair, both required by the decisions and by the review's high findings on them.

## 2. Findings and their disposition

| ID | Finding | Sev. | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| W-1 | C1 read the NOT YET ESTABLISHED act as evidence for performance; S0112 L14 shows the decided boundary was not counted as an adopted commitment | high | Accepted. CON-0208 INVALID as stated; **CON-0213**: a decided boundary is not an adopted commitment; the act is silent on performance; grounds in S0120 | §8.14 C1, register | **yes** |
| W-2 | "above the issuer's scope" tested against the grant's scope | high | Accepted. Tested against the scope of the basis the issuance rests on; strict cover; nothing above a root issuer. A grantee of the issuer is refused, as a consequence and not by a separate rule | check 26 (A4); 24l: the grantee refused 175 times, accepted 0 times | **yes** |
| W-3 | a single-use grant used twice by annulling and then annulling the annulment | high | Accepted. **The Professor's choice, following R3a's reason:** every recorded use counts, whatever its value now; a use is accepted only if T at append (so an unauthorized use cannot block). Supersedes "count only uses that are not F" for single-use grants | check 26 (A1); 24l: a use after an annulled use refused 79 times | **yes** |
| W-4 | a second unguarded use is invisible in standing | high | Accepted. A mark "second use of a single-use grant"; G4 covers uses | check 26 (A3); §9.15 | **yes** |
| W-5 | "the kernel sees each use" moves (C + O) towards (C + K) | high | Accepted. Stated as K4-k (the kernel records accepted uses per single-use grant). The consequence is recorded as Ded for the Principal's attention: for single-use grants the kernel holds what single use needs; (C + O) differs from (C + K) in where a use is recorded and who may move it. The two-record test is **not run** (limit) | R4a v8, §9.15 v9 | **yes**, with the limit |
| W-6 | consumable grants not marked single-use lose cross-application prevention, against R3a | normal (normative) | **Returned to the Principal** (§4). §9.13's stale line is reconciled | §9.13 v13; §4 | decision with the Principal |
| W-7 | C3 merges correcting an error with withdrawing reliance; "CE* lacks purpose" overstated | high | Accepted. CON-0210 INVALID as stated; **CON-0214**: (a) correction from the start (annulment), (b) purpose-scoped withdrawal; "never false" bounded to correctly recorded claims; CE* lacks a partition of one act by claim | §8.14 C3, register | **yes** |
| W-8 | decision by invariance not proof-checked | normal | Accepted. Reported as a sketch, with the missing case | §8.14 C1 | **yes** |
| W-9 | a partial read gives F silently; L4 is needed | normal | Accepted. Added to the proposal | §8.14 C5, CON-0212 | **yes** |
| W-10 | NOT ATTESTED is a homonym | normal | Accepted. Two senses stated | §8.14 C5, CON-0212 | **yes** |
| W-11 | omissions: (a) the compensating control; (b) a self-declared subject of possession; (c) changes over time; (d) the completeness contradiction | normal | Accepted. All four are added to §8.14 (C5, C6, C7, C3) | §8.14 v2 | **yes** |
| W-12 | the estate's recorders are sessions, so L10 is unmet for them | normal | Accepted | §8.14 C6 | **yes** |

**Coverage named by W, now in §8.14:**
- the four omissions of W-11;
- the partial read;
- the two senses of NOT ATTESTED;
- the changes over time (S0112 L48 → S0114; S0113 L28 → S0114/S0115; WARN → INFO).

**Not added:**
- M2 and M5 as separate concepts. They are cited in C5's NOT ATTESTED line and in CON-0212; "receipt missing ≠ knowledge missing" is an application distinction.
- "No automatic staleness". It is application semantics.

**New forks:** added to THEORY-MAP §9 (Position, rewritten from its stale state).

**Source defects** (listed separately, not relied on): S0115 L22's "2/9" is untraceable.

**C20 rows spot-checked** by W: met. The stale "300 sequences" and the reference row are fixed.

**Verdicts:**

| Concept | Verdict |
|---|---|
| CON-0208, CON-0210 | INVALID as stated |
| CON-0213, CON-0214 | new, PROVISIONAL |
| CON-0209 | VALIDATED |
| CON-0211, CON-0212 | PROVISIONAL |

## 3. Checks

- **Model** `ce_model.py` v14: all assertions passed (`ce_model-run-20261021-v14.txt`).
  - Check 26 runs the reviewer's cases A1, A3 and A4.
  - Check 24l: 400 sequences with annulments of uses and of annulments; 194 uses accepted; transfers accepted, 175 by the issuer and 104 by an authority above; the holder and the grantee never accepted; 0 violations.
- **Quote check:**
  - §8.14: 15 found, 0 not found;
  - §9.13: 10 / 0; §9.15: 13 / 0; §9.16: 21 / 0;
  - whole book: 14, equal to the baseline.
- **R1:** 190 rows with a home; 1 inherited flag (CON-0133).

## 4. Returned to the Principal (normative)

1. **Is every consumable grant single-use?** R3a prevents single use of consumptions (laws 9b, 24). Decision 2 exempts grants not marked single-use from the owner check, so their consumptions are prevented only within one record and detected across applications. The options:
   - **(i)** the kind decides: a grant that can be consumed is single-use;
   - **(ii)** marking stays free, and an unmarked consumable grant's double use is detected, not prevented (an exception to R3a, recorded).

*For the Principal's attention, not a question (Ded, R4a v8):* under the decisions as worded, the kernel refuses a use not recorded by the owner and records each use it accepts. So for single-use grants the coordination point is in effect the kernel's. (C + O) keeps the owner's recording and the contract's control of ownership.

*Decided by the Professor, marked for review:*
- "above" against the issuer's scope;
- every recorded use counts, and a use is accepted only if T;
- the second-use mark;
- the kernel's record of uses as K4-k;
- consumptions of grants not single-use keep the in-record guard.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| consumable ⇒ single-use? | **Principal** | decide (§4) |
| P (conjunctive or disjunctive) | Principal (reserved) | unchanged |
| a two-record test of a use in flight during a transfer | Professor | next model revision |
| a failed-read and partial-read input for Q1 | Professor | next §9.13 revision |
| partition of an act by claim; a dispute as mark or act; a recorded exception | Professor / Principal | open forks (THEORY-MAP §9) |
| the earlier carried items (removal activity; act-mark soundness; inheritance channel; §9.15 bypass and timing; the attribution correction kind) | Professor | as listed in the C19 and C20 settlements |
| reading | Professor | C22 (S0116–S0120); S0120 holds the D2 decision's grounds |
