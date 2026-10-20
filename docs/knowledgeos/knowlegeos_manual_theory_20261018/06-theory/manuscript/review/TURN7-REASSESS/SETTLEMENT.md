# Theory turn 7 (the reassessment; §9.13 v6, §9.14 v3, §9.15 v3 in full; §9.16 new): settlement

*2026-10-20. By the Professor. The last turn of the critical-path chain. The review was split into four commissions, as the Principal allowed, and all four are settled here. No reviewer saw another's report. The Professor read all eight reports in full before settling.*

**Saving the reports.** Seven reviewers' Write calls were refused by the harness ("subagents should return findings as text"). Each returned its full report as hand-back text, and the Professor saved it verbatim from the agent transcript (`save_handback.py`). Each saved file says so in its first line. Only B2 and C2 wrote their own files.

| Commission | Object | Reports |
|---|---|---|
| A | §9.13 v6, in full | `REPORT-A1.md` (Opus, 19 findings: 4 high, 15 normal); `REPORT-A2.md` (Sonnet, 10 findings: 3 high, 7 normal) |
| B | §9.14 v3, in full | `REPORT-B1.md` (Opus, 13 normal); `REPORT-B2.md` (Sonnet, 6 findings: 3 normal, 2 low, 1 coverage note) |
| C | §9.15 v3, in full | `REPORT-C1.md` (Opus, 13 findings: 4 high, 9 normal); `REPORT-C2.md` (Sonnet, 11 findings: 5 high, 6 normal) |
| D | §9.16 and the Ext→Src corrections | `REPORT-D1.md` (Opus, 15 findings: 6 high, 9 normal); `REPORT-D2.md` (Sonnet, 12 findings: 4 high, 8 normal) |

*Outside works verified before entering the book (Crossref):*
- Lamport 1978;
- Chandy and Lamport 1985;
- Shapiro, Preguiça, Baquero and Zawirski 2011;
- Bailis et al. 2014 (invariant confluence);
- Hellerstein and Alvaro 2020 (CALM);
- Balegas et al. 2015 (bounded counters).

## 0. Deliverable status

**Met, with its main results corrected at settlement.** §9.16 v2 reassesses what depends on the decisions of turns 4–6:
- a dependency table of Ch. 9's results;
- the history result: Lemma D across records; tombstones give values unchanged and marks added;
- the trust result: §9.15 v4 with K2, K4, K5 and G1, G3, G4 restated.

**Where F22 and K4 belong:**
- K4 belongs to the guards, in Req, of the laws whose invariant merging can violate. It does not belong to the evaluator at a given cut.
- A kernel law of that kind, under prevention, forces a kernel coordination point. With ties by position, as now, the order itself needs one.
- This is a sufficient condition, **not an iff with F22**, because §9.6's (P) also places CE in the kernel.

The F22 decision is returned to the Principal.

**The reviews found material defects in version 1 of §9.16:**
- R2's cut condition was vacuous for records in D;
- identity and the merge of copies were undefined;
- R3's "only count bounds" was not shown;
- R4's "plane iff" was asserted;
- R5's "answers unchanged" held for values only.

They also found defects in §9.13 v6 and in §9.15 v3:
- §9.13 v6's marks support **missed entries an answer rests on**, the unsafe direction. Two reviewers found this independently;
- §9.15 v3: R4's "⇔ L2 ∧ L3" failed in both directions, and the L1a cell contradicted D.

All are corrected. §9.14 v3's findings were all normal or low.

**The chain ends here, as instructed. Reading resumes at S0101.**

## 1. Commission A (§9.13 → v7)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| A1-01 / A2-01 (a) | the marks' support follows only live rules; a cited basis whose holder, window or covers fails is never reached, so an answer resting on an unsigned or transcribed entry goes unmarked (probes P1, P2); "superset" was false | high | Accepted. The support is now a closure under citations, annulments and endings, independent of live rules (`support` v8). Soundness is model check 19: of 9,443 entries outside the support of 1,500 random records, removing any one or changing its holder changed no answer. P1 and P2 are now marked | check 19 passes; P1 and P2 are marked; "superset" is replaced by "sound, conservative" | **yes** |
| A1-02 / A2-01 (b) / A2-08 | over-marking; two senses of "rests on"; F by absence | normal | Accepted. The marks are declared **conservative** (the Professor's reading, returned). F by C1 has empty support and no mark; its dependence on completeness is L4. Strict versus conservative is open | §9.13.3 says so | **yes**; reading with the Principal |
| A1-03 / A2-04 | the tombstone keeps "every field D requires", more than "the fields citations rely on", and that is what makes "no answer changes" true | high (A1) / normal (A2) | Accepted. Labelled **the Professor's reading**, with the literal reading's consequence (a tombstoned adoption leaves D; an annulment may lose its target). **Returned to the Principal** | §9.13.2 labels it; §9.13.10 lists it | **yes**; reading with the Principal |
| A1-04 / A2-02 | the integrity check on a tombstone is undefined; a tombstone could bring a failing entry back into D | high | Accepted. A tombstone keeps its integrity result, and its own check must be defined on what remains: a premise (§9.15 K5). Tombstoning a failing entry does not restore D (check 17b). How a removal is recorded and by whom is open | §9.13.2 states the premises; check 17b | **yes** |
| A2-03 | a tombstone must keep its position, which is read | normal | Accepted. Position is kept, and stated | §9.13.2 | **yes** |
| A1-05 | check 17's tombstone test was vacuous (no content) | normal | Accepted. Check 17b uses an entry with content | the run shows it | **yes** |
| A1-06 / A2-07 (a) | line 44 contradicted the tombstone rule | normal | Accepted. Now "without a tombstone" | fixed | **yes** |
| A1-07 / A2-05 | I15's combinations; signed-as-other with recorder = actor admitted; recorder unread | normal | Accepted. Stated; whether D should restrict the combinations is open | §9.13.2, §9.13.10 | **yes** |
| A1-08 | the status is a declared label | normal | Accepted. The status is a recorded claim; that it equals the verification result is part of K5 (§9.15 v4) | §9.13.2; §9.15 K5 | **yes** |
| A1-09 / A2-10 | the defaults hid the marks from random checks | normal | Accepted. Check 19 covers marks soundness; check 20: random recorder and status change no value in 1,500 records. P's boundary cases were traced by A1 and hold | the run shows checks 19 and 20 | **yes** |
| A1-10 / A2-06 | the inputs table had no I15 and listed I12 as not read | normal | Accepted. Rows added | the table | **yes** |
| A1-11 / A2-09 | the tie note was overstated ("must"); called the tie rule a θ value; ties by identity are arbitrary; the F26 (b) rival was not considered | high (A1) / normal (A2) | Accepted. The note is now conditional, restricted to citation-respecting orders, a choice within F26 (I9), with S0969's rival | §9.13.2 | **yes** |
| A1-12 | position read beyond the key: row 4, Q3's h, Θ_f, CON-0191 | normal | Accepted. Row 4 and Q3 are noted under each tie rule. The tie rule is listed as open rather than as a Θ_f row, since it is not chosen | §9.13.5, §9.13.10 | **yes** |
| A1-13, A1-14 | the reason may not name the whole component; (ii) was worded wider than the code | normal | Accepted. Both reworded | text and `u_reasons` agree | **yes** |
| A1-15 / A2-07 (b, c) | wrong model version; broken sentence | normal | Accepted. Model v8 is cited; the sentence is fixed | fixed | **yes** |
| A1-16 / A2-07 (d) | CON-0191 stale | normal | Accepted. Updated | the register row | **yes** |
| A1-17 | R2's list of "undefined" causes was incomplete | normal | Accepted. Completed | §9.13.4 | **yes** |
| A1-18 | the model's bound ratio was n + 2 | normal | Accepted. Checks 2 and 2b now divide by n + 1 and pass | the run | **yes** |
| A1-19 | marks only for items | normal | Accepted. Stated; acts and Q3 are open | §9.13.3 | **yes** |

**Counts, A:** 29 findings (A1 19, A2 10) → 19 rows. Found by both: 8 rows. One reviewer only: 11 (A1 9, A2 2). No critical finding.

## 2. Commission B (§9.14 → v4)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| B1-02 / B2-01 | the new row's cases are not exhaustive. Infinite orders with bounded finite intervals have h = K + 2 (ℚ×{0,1}: 4). "Unbounded when …" is exact, but stated only as "when". The strict-only claim fails if MUST_NOT of a strict threshold is admitted | normal | Accepted. The exact formula h = max(2, sup |J| + t(J)) with closed thresholds, with B1's derivation (Ded; checked by the Professor). The strict-only claim gets its premise | the row, R5 (c) and CON-0192 give the formula | **yes** |
| B1-01 | "an order that is partial" is false as worded | normal | Accepted. "Some partial orders"; product orders and trees keep h = 2 (check 8, cap 4, upper bounds by R2 and the subtree Helly property); cyclic orders only with arcs of every length. §2.3 (d) pointer changed too | both lists | **yes** |
| B1-03 | R5 (c)'s interior-value claim needs closed thresholds | normal | Accepted | R5 (c) | **yes** |
| B1-04 | "adds one member to each minimal set" is false | normal | Accepted. The exact statement (F ∪ {C} minimal iff F satisfiable without the coupling; h rises by at most 1) | §9.14.2 | **yes** |
| B1-05 | the boundary between coupling and rule was unclear across three sites | normal | Accepted. Defined; the three sites aligned | one rule | **yes** |
| B1-06 / B2-02 | citations claimed more than the checks compute (dense case; ℤ window; check 7's cap n; check 6's tautological line unmarked in the tool) | normal | Accepted. Citations corrected; the tool marks the line; cap n stated as exact by R4 | the citations and the tool | **yes** |
| B1-07 | the §9.14.7 table was out of step; "closest rival"; the gloss went beyond the abstract | normal | Accepted. Rows EXT-081–083 added; wording aligned; EXT-076 gloss in the abstract's words | the table, §9.14.5 and EXT-076 agree | **yes** |
| B1-08 | Chepoi et al.: "exactly when" needs P ≠ NP | normal | Accepted. The dichotomy is stated | §9.14.5 | **yes** |
| B1-09 | van Beek–Dechter stated more strongly than the abstract | normal | Accepted. The abstract's words are used (also EXT-083) | §9.14.5 | **yes** |
| B1-10 | stale "unbounded attribute" (CON-0192, §2.3 (d)) and tool docstring | normal | Accepted. All three fixed | grep finds none | **yes** |
| B1-11 | R5 (c) states an equality where only ≤ is proved | normal | Accepted. The premise (cylinders) is stated | R5 (c) | **yes** |
| B1-12 / B2-05 | line 36 understated R1 (c); "sound" means exact | normal / low | Accepted. For languages the two notions coincide; "sound" here means exact | §9.14.2 | **yes** |
| B1-13 / B2-03 | §9.16's "reads no record / no trust assumption" was imprecise | normal | Accepted. The §9.16 R1 row is restated: records enter only through A′ and the base; §9.14.6's own assumptions | the row | **yes** |
| B2-04 | R4's degenerate cases | low | Accepted. One line added | §9.14 R4 | **yes** |
| B2-06 | Beeri et al. and van Beek–Dechter not confirmed on a public page (U) | coverage | Noted. B1 checked the van Beek abstract pages. Beeri et al. rests on its Crossref record (turn 6) | — | **noted** |

**Counts, B:** 19 findings (B1 13, B2 6) → 15 rows. Found by both: 4 rows. One reviewer only: 11 (B1 9, B2 2). No critical or high finding.

## 3. Commission C (§9.15 → v4)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| C1-01 / C2-02 | no K or L covers the marks: the status is a recorded value that no clause checks; R3 holds for the value only | high | Accepted. K5 now covers "the recorded status is the true verification result" and tombstones. G1 covers values and marks, with marks true of the entries only under K5. R3's scope is "values and marks"; the marks read claims | K5, G1 and R3 state it | **yes** |
| C1-02 | R4's "⇔ L2 ∧ L3" invalid both ways | high | Accepted. One direction, with premises: L2, L3 per entry, L8 unforgeability (new), K5. The proof is rewritten. Turn-6 row C1-01 is thereby repaired | R4's row and proof | **yes** |
| C1-03 | the L1a cell contradicted D (a denial puts the record outside D; Q1 refuses) | high | Accepted. The cell is rewritten, with the blocking cost under K1 | the L1a cell | **yes** |
| C1-08 / C2-03 | K4 and G4 inconsistent with their notes and with the criterion; G4 false under K4-d; within one application G4 rests on K6 | high | Accepted. K4 is now "coordination per kernel law whose invariant merging can violate, under prevention; needed by no CE* answer on D now". G4 is restated by case | K4 and G4 carry the conditions | **yes** |
| C2-01 | key binding on both sides of the K/L line | high | Accepted. K5 is the checker's soundness relative to the recorded binding; L2 is that the binding is true of the world | K5's cell | **yes** |
| C2-07 | is "unsigned" reachable in D, given I13's "authentic"? | high | Accepted. For an unsigned entry the check is intactness only (K5) | K5's cell | **yes** |
| C2-05 / C1-05 | marks cover only the recorded modes; "transcribed" read as signed-as-other; "superset" | high (C2) / normal (C1) | Accepted. The reading is stated (the recorder drives no mark). The marks are conservative. The signed-as-actor-without-P-perf case is listed as open | R6 | **yes** |
| C1-09 / C2-04 | the K2 note needs a cut defined by entries, and the tie rule not yet chosen | normal | Accepted. K2 is restated per tie rule; cuts are named by entries | K2's cell | **yes** |
| C1-10 / C2-11 | tombstones missing from K5 and G3; R5 by construction; who may tombstone | normal | Accepted. K5 and G3 are amended; how and by whom is open (§9.13.10) | K5, G3 | **yes** |
| C1-11 | the criterion was not exclusive (L4 met the kernel clause) | normal | Accepted. The rival wording is adopted; the I11 dependence is noted | §9.15.2 | **yes** |
| C1-12 | the signed-as-other row assumed L1a always | normal | Accepted. L1a only in the affirmation branch | the row | **yes** |
| C1-13 | K labels homonymous with §9.6 | normal | Accepted. A labels note in §9.15.2 and §9.16.2, rather than renaming | the notes | **yes** |
| C1-06 / C2-06 / C2-08 / C2-10 | R6 and CON-0193 stale; part of the gap remains (who may transcribe for whom; recorder–key relation) | normal | Accepted. R6 is recast as "decided (v3); what remains"; CON-0193 updated | no sentence falsified by I15 | **yes** |
| C1-07 / C2-09 | R8 (c) and §9.9's pointer said "no test stated" | normal | Accepted. "Proposed (§9.16 R7), not adopted", in R8 (c), §9.9 and §9.15.10 | the three places | **yes** |
| C1-04 | R3 definitional under DB-1 | normal | Accepted. Labelled | R3's heading | **yes** |

**Counts, C:** 24 findings (C1 13, C2 11) → 15 rows. Found by both: 7 rows. One reviewer only: 8 (C1 5, C2 3). No critical finding.

## 4. Commission D (§9.16 → v2; Ext→Src corrections)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| D1-01 / D2-01 | R2 (a), (d) vacuous for records in D; check 18's application records were not in D | high | Accepted. The setting now has each application's record in D (holding copies of what it cites); the cut condition is automatic (check 18b: 1,200 of 1,200 cuts in D). Version 1's figures are kept as the fragment case. "Checkable" is restricted to records in D | R2, §9.16.4 and check 18b agree | **yes** |
| D1-02 / D2-02 | identity, tombstones and I15 against "the union absorbs duplicates" | high | Accepted. Identity is derived from the fields CE* reads (not recorder or status). Merge rule: conflicting copies refused; tombstone wins; weakest status; set of recorders (`merge`, check 18d: the same values and marks in both orders) | R2's setting; check 18d | **yes** |
| D1-03 / D2-10 | S0969's experiment 15 ("OrderingUncertain"), inside the cited range, supports F26 (b) | high (D1) / normal (D2) | Accepted. Cited as Src for the rival in R2, §9.16.5 and §9.13.2 | the three places | **yes** |
| D1-04 / D2-04 | R4's "plane iff" not derived: the position branch, CE in (P), the aggregate in the contract, the ownership rival | high | Accepted. R4 is restated as a sufficient condition: the position branch, the contract list, "not an iff", ownership (EXT-089) | R4 | **yes** |
| D1-05 / D2-03 | R3 incomplete (count bounds are a special case); law 21's completeness; D's identifier uniqueness; mutation owner; once-only handling; acting under ended authority | high | Accepted. R3 uses invariant confluence (EXT-087). It classifies by class (a sketch; law by law is open) and gives law 21 at a given cut only (CALM, EXT-088) | R3 | **yes** (by class; law by law open) |
| D1-06 / D2-06 | R5 rests on a reading broader than the decision; check 17 vacuous | high (D1) / normal (D2) | Accepted. Conditional on the Professor's reading (returned) and the premises; definitional; check 17b | R5 | **yes** |
| D1-07 / D2-07 | "answers unchanged" conflicts with the marks; the positions/cut row is missing | normal | Accepted. "Values unchanged, marks added"; the H2/positions row added; cuts named by entries | R1, R5, §9.11 R1 step 5 | **yes** |
| D1-08 / D2-05 | "F in both" depends on names; steerability; endings-first is a preorder; backdating | normal | Accepted. "The same in both"; the costs are added | R2 costs | **yes** |
| D1-09 | check 18's limits; no crowded-time sample; marks not compared | normal | Accepted. Check 18c (crowded times: 2 of 400 by position, 0 by identity); check 18b compares marks | the v8 run | **yes** |
| D1-10 | "exactly the consistent-cut condition" overstated | normal | Accepted. "The citation analogue" | R2 (d), §9.16.7 | **yes** |
| D1-11 | homonyms K2, K4, K5 | normal | Accepted. Labels note; each use names its section | §9.16.2 | **yes** |
| D1-12 / D2-09 | S1519 L234 quoted in part; S1519 L215 misapplied to law 22; S0915 L1315 overreached; S0943's "fairness"; missed Src (S0991 L1143; S0915 L68, L489, L521, L814–827; "knowledge changed") | normal | Accepted, all. L234 quoted whole; L215 withdrawn from R6; S0915 L1315 "an analogue only"; S0943 "starvation freedom"; the missed Src added | each row says what its line says | **yes** |
| D2-12 | S0916's third axis (knowledge time) | normal | Accepted. Added in §9.10.9 and §9.12.8 | both rows | **yes** |
| D1-13 / D2-11 | invariant confluence, CALM and bounded counters missing | normal | Accepted. EXT-087–089 verified (Crossref) and used | rows exist; R3 cites them | **yes** |
| D1-14 / D2-08 | R7 partly circular; "none is read" false (the integrity result and act time are read); undefined for Q4; listed versus read; individuation | normal | Accepted. R7 is revised: "read" means consulted by a clause; the stipulation is stated as such; the evidence of key binding and timing is read; partial while Q4 has no reference; the individuation rule; a second rival ("needed by") | R7 | **yes** |
| D1-15 | R6: law 20a's reason against (c); law 26 missing | normal | Accepted. Both added; law 22's withdrawn reason replaced | R6 | **yes** |

**Counts, D:** 27 findings (D1 15, D2 12) → 16 rows. Found by both: 11 rows. One reviewer only: 5 (D1 4, D2 1). No critical finding.

**Total:** 99 findings → 65 rows. No critical finding; 26 rated high in the reports (A 7, C 9, D 10; B 0).

## 5. Checks run on the corrections

- **Quote check (entry 60):** 0 not found in every changed section:
  - §9.06 14; §9.08 20; §9.09 13; §9.10 4; §9.11 10; §9.12 5;
  - §9.13 10; §9.14 2; §9.15 13; §9.16 14;
  - §2.03: no checked-form quotes.

  The whole book's NOT FOUND lines stay within the inherited baseline (re-run before commit).
- **Models:**
  - `ce_model.py` v8: all assertions passed (`ce_model-run-20261020-v8.txt`), with new checks 17b, 18b–18d, 19 and 20;
  - `helly_check.py` v4: all assertions passed (`helly_check-run-20261020-v4.txt`), with new check 8.
- **Register:** CON-0191–0194 updated (meaning, basis, follow_up, history).
- **Map:** THEORY-MAP F22, F26, F36 updated; F30 unchanged.
- **Index:** the Ch. 9 index rows for §9.13–§9.16 updated.

## 6. Returned to the Principal

1. **K4-p or K4-d** for each law that is not merge-safe (single use, law 26, role exclusivity, once-only handling, acting under ended authority), when its writers are several applications. Is any role exclusivity, or §9.6's mutation ownership, a kernel law? (DB-3 restated; this decides whether a kernel coordination point is required.)
2. **The tie rule within F26:** identity, endings first completed by identity, or F26 (b) (unordered, U). Under ties by position, as now, the order itself needs a shared point (§9.16 R4 (ii)).
3. **The tombstone's retained fields:** do "the fields citations rely on" include the required fields of kinds no citation names (the Professor's reading)? How is a removal recorded, and by whose authority?
4. **"Rests on" for the marks:** the conservative closure (now) or a strict set? Marks for acts and Q3.
5. **Admission of consumption into D**, with a CE* clause.
6. **The F36 options** per law (§9.16 R6 recommends).
7. **Adoption of §9.16 R7's test** (a stipulated line between a recorded field and what it is about).
8. *(Carried over:)* P conjunctive or disjunctive; whether D should restrict the (actor, recorder, status) combinations; who may transcribe for whom.

## 7. Open items after the chain

| Item | Role | Next action |
|---|---|---|
| the eight questions of §6 | **Principal** | decide |
| law-by-law merge-safety check (R3 was by class) | Professor | when a later turn or consolidation reopens it |
| P-perf: which acts are performed by recording | Professor / Principal | not addressed in the chain; stays open |
| sealed cuts across applications (law 21 "now") | architecture | outside the theory |
| §9.13 v7, §9.14 v4, §9.15 v4, §9.16 v2: corrected, not re-reviewed | reviewers | the next consolidation (around S0125) |
| reading | Professor | resumes at S0101 |
