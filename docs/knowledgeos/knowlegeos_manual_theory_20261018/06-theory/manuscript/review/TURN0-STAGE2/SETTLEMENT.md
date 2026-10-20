# Theory turn 0: settlement of the review of stage 2 (S0100)

*2026-10-20. By the Professor. Rulebook v1.3 with entries 56–60 (rules frozen until S0150); the Principal's four practices tested through the instruction. Reports: `REPORT-A.md` (Opus, 20 findings), `REPORT-B.md` (Sonnet, 15 findings). Neither reviewer saw the other's report.*

## 0. Deliverable status

**Met.** Every item of the brief (§3 items 1–3, a sample of item 4) has a verdict from both reviewers. Every finding below has a decision, a severity, and an observable "resolved when" that is checked here.

## 1. Research question of this turn (practice 1)

*Are the stage-2 corrections sound, and did they introduce new defects?* What would show they are not: a counterexample to a corrected result, a dependant left contradicting it, or a correction that removes the named defect only in wording. Both reviewers found all three kinds.

## 2. Inputs and their limits

- **Access.** Reviewer B opened one basis file (GKC, `docs/implementation/RQ-002_General_Knowledge_Architecture_Constitutional_Model.md`, sha256 matching the READ-LOG) before the correction reached it. The cause was my brief, which wrongly extended the Principal's grant to reviewers. B reports that it discarded what it read and marked every GKC-dependent check U; no finding of B uses GKC. Reviewer A opened no basis file. Recorded in the access record (entry of this date).
- **Not read by the author** (practice 3): the chapter text of item 4b (`1862e2675`) was written by forked sessions. The reviewers sampled it (A: 14 material rows, B: 6). The rows that neither reviewer nor I have read are listed in §6. Each later turn reads the fork-written sections it builds on before using them.
- **Quote check (entry 60):** the three stage-2 failures (§1.8, §3.1, §5.4) were fixed before the commission (`b4adcb68e`). After this settlement's corrections: every changed section passes, except one inherited failure (03.01 L22 [S0016 L45–46]) listed in the baseline.

## 3. Findings and decisions

Found by: both / A / B. Severity as settled, with the reason where it differs from a report. "Holds?" checks the resolved-when condition against the text after this settlement.

| ID | Finding | Found by | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|---|
| A-1 | §9.1 C6 result 5 false as stated: a history-clearing mechanism breaks C3 inside the stated class | A | **critical** (R5 missing premise; F1 CON-0183, F3 kernel existence) | Accepted, confirmed by computation (`kernel_type_model.py` v2, assertion 5a). Result 5 now requires H empty at s₀, never shrinking, read and written by every act-consuming mechanism, with the proof and the necessity of (ii); C3's scope line and CON-0183 likewise. Prior work: the device is Abadi and Lamport's history variable (EXT-032); established, only its use as a premise of C3 is the book's | 09.01 result 5, C3 scope and CON-0183 state the three conditions; the model has a history-clearing mechanism and asserts its exclusion | **yes** |
| A-2 = B-6 | the model identified an act with its item; `revoke` never fired; T3 not checked; the history class hand-picked | both | high (A); normal (B). Settled **high**: a computation cited as evidence was degenerate | Accepted. Model v2: acts (item, k), T3w and T3 checked, the class derived; revoke reachable (assertion 2). Result 4's counterexample restated with distinct acts. Old run file kept as superseded | distinct act identities, revoke reachable, T3 asserted, result 4 not relying on item = act | **yes** |
| A-3 ≈ B-7 | "forge excluded" and "outside origin" hold only for a faithful input label | both | normal | Accepted. Result 1 names faithful labels (A1-as-form; §1.12-2; §9.9 T-b); "What it does not show" says origin is a trusted label | C6 result 1 names the premise and cites §1.12-2 | **yes** |
| A-4 = B-3 | §2.3 (d) minimality global, so conflict regions are lost | both | high (A); normal (B). Settled **high**: a reported region can be wrong | Accepted, confirmed (`conflict_minimality_check.py`). (d) now defines minimal conflicting sets point by point, with the counterexample; scope assumption (applicability independent of x) stated (B-5 part). Prior work: local vs global consistency (Mackworth 1977; Freuder 1978, 1982) and minimal unsatisfiable subsets (Liffiton and Sakallah 2008): **established, adapted**; the context/outcome split and the pointwise region are the adaptation | (d) pointwise; example flagged; §2.5 uses the pointwise region | **yes** |
| A-5 | the cost bullet misstates the quantifiers | A | normal | Accepted. Both questions have one alternation (∃∀ / ∀∃); placement in the polynomial-time hierarchy cited (Stockmeyer 1976), no completeness claim | 02.03 gives the same structure for both | **yes** |
| A-6 = B-4 | §2.6 "analysed as a set" unsound on its literal reading; §2.9 relies on it | both | high | Accepted. §2.6: point by point, the rules of the partition that apply there; :3, :5, :11 restated for sets and (c, t); π a function of (c, t); §2.9 cites the pointwise form | 02.06 and 02.09 name the pointwise test | **yes** |
| A-7 | (B) gave operational state one bearer, against §3.1's own mapping | A | high | Accepted. Under (B) operational state is split: binding strength → claim; enforcement consequence → realization; exercise history open. `Round39-MC`'s "enforced" corrected to binding strength | (B) table and re-run agree with the §2.9 mapping; CON-0185 matches | **yes** |
| A-8 (+ B-2 part) | "all six classify" covers four dimensions | both | high | Accepted. Result scoped to the four dimensions with a bearer; CON-0185 | 03.01 and CON-0185 carry the scope | **yes** |
| A-9 = B-1 | (A)'s consistency asserted, not shown; the re-run does not tell (A) from (B); the record's "holds for (A)" false | both | high | Accepted. (A)'s constraint list extended (binding strength; standing if F6 splits governance status) and its dependence on F6 stated; an (A) re-run added, with the statement that the re-run does not discriminate; "Both are consistent" removed from F37; the record corrected (§5) | (A)'s constraints conditional on F6; (A) re-run present; F37 asserts no consistency; record corrected | **yes** |
| A-10 | CON-0001 and CON-0004 rows not linked to F37 | A | normal | Accepted. follow_up "bearer: F37" on both | rows consistent with 03.01 | **yes** |
| A-11 ≈ B-9 | M-T2 is an independence model only relative to an unstated candidate set and the as-forms convention, and the construction over-generates (key binding, timing, completeness) | both | critical (A); normal (B). Settled **critical**: CON-0182 and §9.9 result 5 rested on it (F1), and it decided a primitive's independence (F3) | Accepted. §9.9.7 states P1 (the candidate set) and P2 (as-forms), gives the over-generation as the strongest counterexample, and concludes that occurrence is one of a class of world assumptions; that it is a primitive is **not shown** (Hyp). Result 5, the plain language, CON-0182, the map (F23, theory-turn-4 line) and the outline Trust row aligned; §9.9.11 records result 5's second form as not holding | M-T2 and result 5 name the set and the convention; the key-binding case decided in the text; CON-0182 carries the condition | **yes** |
| A-12 | M1 and M-T2 use different readings of A1 | A | normal | Accepted. §9.8 M1 and §9.9.7 state both results under both readings: under any one reading, one independence claim lapses | both sections state both readings | **yes** |
| A-13 | §9.6's plain language keeps the withdrawn "evaluator is the stronger kernel candidate" | A | high | Accepted. 09.06 L41 restated to agree with step 6 | 09.06 L41, step 6, the map F22 row and §9.7 agree | **yes** |
| A-14 | outline marks stale (CON-0100; fork range) | A | normal | Accepted | outline matches the register | **yes** |
| A-15 ≈ B-13 (F36) | F36 not a fork about the type; no criterion | both | normal | Accepted. F36 restated: which of H1′, origin, U are kernel invariants, decided per law by the three boundary questions (and K4 for U); the type question stated as settled by Ded | F36 names a criterion | **yes** |
| A-16 ≈ B-2, B-13 (F37) | F37 has no decision criterion; the readings are interdefinable | both | normal (A); high (B). Settled **normal**: DB-5 keeps the fork, so no decision is pending | Accepted. §3.1 and the map: on four dimensions the readings translate into each other; F37 is decided by how F6 splits governance status and whether the kernel must hold claims no carrier carries | F37 states what would decide it | **yes** |
| A-17 | §9.9.8 prejudged F37 ("the book's a claim") | A | normal | Accepted | 09.09 does not prejudge F37 | **yes** |
| A-18 | §9.7.5 condition 4 forbids a conservative U on time-out | A | normal | Accepted. E6 cases must answer U, which is conservative, not a failure | 09.07 states its treatment of time-out | **yes** |
| A-19 ≈ B-11 | CON-0186 and §1.8 point to the retired Ch. 7; paraphrases in quotation marks; S0028 L32 unanchored; CON-0186 omits L149 | both | normal | Accepted. §1.8 quotes S0028 L32 exactly, anchored; Ch. 7 pointers replaced by Part V (DB-6); paraphrase quotation marks removed (CON-0186, CON-0187 in §5.3); CON-0186 sources L32, L140–149. The book_location filename ("…is-a-generator") is kept: renaming files is outside a settlement | no live text or row points to Ch. 7; quotations exact and anchored | **yes** |
| A-20 | C6's Applications paragraph overclaims; the shared history variable is a serialization point (K4) unstated | A | normal | Accepted. C6 states the link; §9.6.4 step 3 cites it | C6 and §9.6.4 cross-reference | **yes** |
| B-5 | dependants left pairwise: §2.5 voidness attribution, §2.4 findings, §2.8 overlap; scope assumption | B | normal | Accepted. §2.8 overlap of a set of rules added; §2.4 marked; voidness attribution opened in §2.5 and T2-13; scope assumption in (d) | updated or listed open | **yes** (T2-13 open) |
| B-8 | result 5 nearly tautological; index row 9.1 stale | B | normal | Accepted. Result 5 labelled "a reduction to result 3"; index row updated | index mentions C6; result 5 labelled a reduction | **yes** |
| B-10 | record cites §8.6 C1 where the text has C3; CON-0182's history date "2026-10-19" impossible | B | normal | First part accepted (record annotated, §5). **Second part rejected:** the split was committed on 2026-10-19 at 23:18 (`e5a9435bf`); stage 2 began that evening | record agrees with the text | **yes** |
| B-12 | CON-0079 raised to VALIDATED by self-review | B | high | Accepted. Only a reviewer's verdict makes VALIDATED: CON-0079 → PROVISIONAL, its earlier INVALID withdrawn as resting on a faulty argument; gloss aligned ("demanded"). Checked: no other row was raised to VALIDATED in stage 2 (status diff 8c4601ecf → HEAD) | register shows PROVISIONAL with the reason | **yes** |
| B-14 | R3 list for P2-1 missed §0.3, §1.2 and the §3.1 header | B | normal | Accepted. Dependants named in §3.1; plain language qualified | list extended | **yes** |
| B-15 | §9.6–§9.9 results not derived from C6 | B | normal | No change beyond T9-05 (already open) | T9-05 closed | **no** (T9-05 open; carried) |

**Counts:** 35 findings (A 20, B 15) → 26 settled rows; found by both: 10 rows; one reviewer only: 16 (A 10, B 6). Critical after settlement: 2 (A-1, A-11), both resolved. One part rejected (B-10's date).

## 4. Verdicts on the brief's items, after settlement

| Item | Verdict |
|---|---|
| P2-1, bearer fork | holds with the corrections above (A-7–A-10, A-16, A-17, B-1, B-14) |
| P2-2, set-wise conflict | holds with the corrections above (A-4–A-6, B-5) |
| P4-1, kernel type | holds with the corrections above (A-1–A-3, A-20, B-8) |
| P4-4, result 5 | holds with the corrections above (A-11, A-12) |
| CON-0183–CON-0187 | corrected: 0183 (A-1, A-3), 0184 (A-9), 0185 (A-7, A-8), 0186 (A-19), 0187 (paraphrase marks) |
| F36, F37 | restated with decision criteria (A-15, A-16) |
| Real corrections, sampled | A: 11 of 14 material rows hold; P4-10 did not (A-13, now fixed); P2-4 conflicted with P2-1 (A-7, fixed); P1-6 not checked. B: P2-3, P4-5, P1-1, P1-2 hold; P3-8 with B-12 |

## 5. Corrections to the stage-2 application record

- §1 P2-1 "Resolved when … holds for (A)": **wrong.** It held for neither reading; after this settlement it holds for both on the four dimensions with a bearer, with (B) open on governance status and canon scope.
- §1 P4-4 cites "§8.6 C1": the text has "§8.6 C3" since the shared-file pass (§7 of the record). The record is history; this note is the correction.
- §1 P4-1 "Closed": result 5 was false as stated; corrected here (A-1).

## 6. Text of item 4b not read by the author and not checked by either reviewer (practice 3)

Material rows: P1-4, P1-5, P1-7, P1-8, P1-9, P1-10, P2-5, P2-6, P2-8 to P2-12, P3-2 to P3-7, P3-10, P4-3, P4-6 to P4-9, P5-2, P5-5. Most N-rows, I-rows and PS8 rows (B checked none; A checked I-04, I-06, I-11, N1-09, N3-01). The DB-8 note removals in Ch. 4–5 and Ch. 8. **Rule for the chain:** a turn that builds on one of these reads it first and records the reading in its brief.

## 7. Open items

| Item | Role | Reason | Next action |
|---|---|---|---|
| T9-05 (B-15) | Professor | §9.6–§9.9 not derived from C6 | turn 7 reassesses them against Req and C6 |
| T2-13 | Professor | findings about sets of rules; voidness attribution | turn 5 |
| F37 decision inputs (F6; claims without carrier) | Professor | F6 open | turn 3 |
| §6's unread rows | Professor | practice 3 | read before building on them, per turn |
| CON-0183, CON-0184, CON-0185, CON-0079, CON-0182: corrected, not re-reviewed | reviewers | only a reviewer's verdict validates | the chain's later reviews where they are used |

## 8. Prior work checked in this settlement (practice 2)

| Result | Prior work | Status |
|---|---|---|
| C6 result 3 (C3 for state and transition invariants) | Lamport 1977; Alpern and Schneider 1985 | established |
| C6 result 5 (history variable) | Abadi and Lamport 1991 | established device; its three conditions as a premise of C3 are the book's application |
| §2.3 (d) set-wise conflict | Mackworth 1977; Freuder 1978, 1982; Liffiton and Sakallah 2008 | established, adapted (context/outcome split, pointwise region) |
| §2.3 (d) cost | Stockmeyer 1976 | tool: placement only |

All recorded in `06-theory/EXTERNAL-SOURCES.md` (EXT-030 to EXT-037); bibliographic data verified by web search on 2026-10-20 (author or publisher pages, DBLP, journal bibliographies). No corpus text was used in any search.

*Traceability:* Rulebook v1.3 §6, §12b, entries 56–60; the Principal's instruction of 2026-10-20 (turn 0); decision record addendum 2026-10-20T07:24:00Z (four practices).
