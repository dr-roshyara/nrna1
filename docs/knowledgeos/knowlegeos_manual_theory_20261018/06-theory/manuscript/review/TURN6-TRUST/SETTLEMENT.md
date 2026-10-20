# Theory turn 6 (the trust boundary; §9.13 v4 and §9.14 v2 in full): settlement

*2026-10-20. By the Professor. The review was split into three commissions, as the Principal allowed, and all three are settled here before turn 7. No reviewer saw another's report. The Professor read all six reports in full before settling.*

| Commission | Object | Reports |
|---|---|---|
| A | §9.13 v4, in full | `REPORT-A1.md` (Opus, 10 findings: 3 high, 7 normal); `REPORT-A2.md` (Sonnet, 11 findings: 1 high, 8 normal, 2 low) |
| B | §9.14 v2, in full | `REPORT-B1.md` (Opus, 12 normal); `REPORT-B2.md` (Sonnet, 11 findings: 1 high, 10 normal) |
| C | §9.15, the trust boundary | `REPORT-C1.md` (Opus, 15 findings: 2 critical, 7 high, 6 normal); `REPORT-C2.md` (Sonnet, 14 findings: 4 high, 10 normal) |

*Outside works verified before entering the book:*
- Beeri, Fagin, Maier and Yannakakis 1983 (Crossref);
- Chepoi, Creignou, Hermann and Salzer 2010 (HAL record);
- van Beek and Dechter 1995 (the author's page);
- the Crossref record of Schneier and Kelsey 1999, which gives 2(2): the book had 1(3), from the authors' page.

## 0. Deliverable status

**Met.** §9.15 v2 states what the kernel guarantees (G1–G6) and on what each guarantee rests: the **kernel assumptions** K1–K7 (exclusive write path, single view, the inputs, one shared record, integrity check, implementation, evaluator version).
- **The two named assumptions are located.** Under DB-1 and C1 no answer of CE* depends on "recorded acts occurred" or on "every act was recorded" (R3). Both are **legitimacy assumptions** (L1–L7), located by recording mode (R4).
- **Result 5.** §9.9's result 5 is **dissolved for the kernel's answers**. Whether occurrence is a kernel primitive stays **not decided**; no test for that has been stated (R8).
- **Commissions A and B:** §9.13 is settled as v5, §9.14 as v3.

**Returned to the Principal (three questions):**
1. **Decision 3's reading.** "An act that annuls any link of its own authorization chain gives U": is that "**when** such an act is U, the reason names the act and the link" (as now applied), or "U by rule"? Φ gives T when the act has another intact basis and F when the link was already defeated (check 15d). Also: does a root count as a link? Does an unneeded cited basis?
2. **The recorder field.** Should D require a recorder field and an authentication status apart from the actor? And should Q1 mark, or refuse, answers that rest on entries without them (§9.15 R6)?
3. **Dangling citations** (information, not a decision asked). Under v5 a dangling citation puts the record outside D, the Professor's literal reading of "citations of earlier entries only". So a recorded removal of a cited entry makes Q1 refuse the whole record until loss entries are read (I12). If the Principal prefers another treatment, it is open.

## 1. Commission A (§9.13 → v5)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| A1-01 / A2-07 | decision 3 as worded ("gives U") is false of Φ: the act is T with a second intact basis, F when the link is already defeated. Root and unneeded-basis readings are unflagged | high | Accepted. R2 and §9.13.5 row 6 apply it as "when U, the reason names the act and the link"; check 15d shows T and F. **The reading and the two link questions are returned to the Principal** | text says "when U"; the Principal's reading recorded | **yes**; reading with the Principal |
| A1-02 | "a dangling citation is F, the safe direction" is false for negative acts | high | Accepted. **A dangling citation puts the record outside D** (v5; the Professor's literal reading); §9.13.2 and §9.13.7 corrected; check 13 has the case | §9.13.2 no longer calls it safe; check shows refusal | **yes** |
| A1-03 / A2-01 | D's citation rule was stated three ways that disagree; the real expulsion was a later arrival of a cited id | high | Accepted. Since dangling citations are refused, the **append guard** (each new entry's citations name present entries) and the **static test** (every citation names an earlier entry present) agree; availability bullet corrected | one consistent statement | **yes** |
| A1-04 / A2-03 | the reason for D holds in record order only: act-time retroactivity and forward targets are admitted | normal | Accepted. Scoped to record order; retroactivity in act time and forward targets are named as open (§9.13.10) | §9.13.2 states the scope | **yes** |
| A1-05 / A2-02 | the reason was quoted as the Principal's words; the record shows an accepted recommendation and a second reason; the cost of D_acyclic overstated | normal | Accepted. The decision record's wording is quoted verbatim, with its second reason (which v5 now also meets). The incremental check for D_acyclic is stated (a difference of cost, not of kind). *The Principal also stated the reason in their message of 2026-10-20* | §9.13.2 quotes the record exactly | **yes** |
| A1-06 / A2-04, A2-05, A2-06 | reasons over-included (cycles reached through F bodies, dead alternatives); "each cycle" was "each component"; a self-annulling act mislabelled a ring; the queried-scope reason spurious on dead alternatives; check 15b could not detect wrong names | normal | Accepted. The walk now starts from U alternatives and follows only U literals of U bodies. Strongly connected components are named. A self-annulling act gets its own label, and a ring needs at least three acts. The covers reason applies only on U alternatives. **Check 15c** (soundness): 68 named acts, all U | definition and code agree; 15c passes | **yes** |
| A1-07 | P's "conjunctive" is partial at its boundary | normal | Accepted. The four cases are stated in Θ_f; P is reserved to the Principal | the P row states them | **yes** |
| A1-08 / A2-09 | "tight" used for "no citation cycle"; n counted inconsistently | normal / low | Accepted. "No citation cycle (which makes the program tight)"; n = non-root acts | one convention | **yes** |
| A1-09 / A2-08 | §9.09 T1 lacked the merge condition | normal | Accepted. Added | §9.09 states it | **yes** |
| A1-10 | step 8 and R2 (a) each skipped one step | normal | Accepted. The greatest unfounded set (the union) is in step 8; the fixed point is named in R2 (a) | both stated | **yes** |
| A2-10 | the claim about mutual annulment's fixed points under υ = U was not shown by the run | low | Accepted. Check 3b enumerates them: (F, T), (T, F), (U, U) | the run prints them | **yes** |
| A2-11 | S1761 read as support for D's citation-order rule | normal | Accepted. Labelled Int: it supports exogenous roots, not the order rule over D_acyclic | R3 labels it | **yes** |

**Counts, A:** 21 findings (A1 10, A2 11) → 12 rows. Found by both: 7 rows. One reviewer only: 5 (A1 3: A1-02, A1-07, A1-10; A2 2: A2-10, A2-11). No critical finding.

## 2. Commission B (§9.14 → v3)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| B2-01 / B1-01, B1-02, B1-03, B2-02, B2-09 | the new row's family is empty only on a domain with a least element; on a dense order h = 3; "n on n values" was unproved and adds nothing on a finite domain; "pairs suffice: no" was wrong for n ≤ 2; the example is an enforcement threshold | high (B2) / normal | Accepted. Row and proof restated: n on n values (R4, reached by the complements alone); unbounded when the order has finite intervals of every size (ℕ with one threshold, ℤ with two, m + 2); **3 on a dense order** with closed thresholds (proof), 2 with strict ones; column "only if n ≤ 2"; neutral example, no book rule found. Check 7 | the row, R5 (c), the status line and CON-0192 agree | **yes** |
| B1-04 | with thresholds, one interior forbidden value already gives h = 3 | normal | Accepted. R5 (c) states it; check 7 (interior 3, endpoint 2) | R5 (c) | **yes** |
| B1-05 / B2-03, B2-04, B2-05 | R5 (d)'s words were wider than proved: "ordered" must be total and non-cyclic; "unbounded" should be "infinite", a declared open domain; "three or more values" contradicts h = 0 on infinite domains; MUST_NOT of an interval or a conjunction leaves the class | normal | Accepted, all four. R5 (d)'s list is restricted; the "h > 2" list adds partial and cyclic orders (check 7); the §2.3 (d) pointer is the same | R5 (d) and §2.3 (d) agree with R3 | **yes** |
| B1-06 / B2-06 | "sound" undefined; the heading lacked the realisability premise | normal | Accepted. Sound = reproduces the set-wise report; the weaker point-flag property is distinguished; heading carries the premise | §9.14.2 defines it | **yes** |
| B2-07 | "coupling in X, or equivalently a rule" is not equivalent for h | normal | Accepted. Convention fixed: the coupling is part of X | one convention | **yes** |
| B1-07 / B2-10 | the tool supported less than cited: check 6's interval line tautological, "n on n" computed nowhere, check 3's single-rule branch never exercised, its result sampled | normal | Accepted. Check 7 computes n on n values; check 3 adds a class with ∅, boxes, and required and forbidden values on a Boolean; the text says "sampled" and marks the tautological line | the run shows them | **yes** |
| B1-08 / B2-11 | closer rivals missing; the Dechter–van Beek description went beyond the abstract | normal | Accepted. Added after verification: Beeri, Fagin, Maier and Yannakakis 1983 (pairs suffice for acyclic schemes; Crossref), Chepoi et al. 2010 (the Helly property and 2-clause satisfiability; HAL), van Beek and Dechter 1995 (row convexity). Dechter–van Beek is restricted to the abstract's words. EXT-081–083 | §9.14.5 names them | **yes** |
| B1-09 | EXT-074's title misspelt | normal | Accepted: "cristallographical" | the records match | **yes** |
| B1-10 / B2-08 | every MCS size in S1539 is relative to background logic; S0922's cardinality is h = 2 as written | normal | Accepted. Both rows are restated | the rows say so | **yes** |
| B1-11 | S0922's line range missed the box | normal | Accepted: L21–58 | the range | **yes** |
| B1-12 | h may be ∞; R5 (a) computes regions, not only yes/no | normal | Accepted: "supremum, possibly ∞"; R5 (a) reworded | both phrases | **yes** |

**Counts, B:** 23 findings (B1 12, B2 11) → 11 rows. Found by both: 6 rows. One reviewer only: 5 (B1 4: B1-04, B1-09, B1-11, B1-12; B2 1: B2-07). No critical finding.

## 3. Commission C (§9.15 → v2)

| IDs | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| C1-01 / C2-06 | R3's self-recorded proof assumed "the act is the writing of the entry" (P-perf), which C1 does not give and §1.6 and §1.12-1 deny. The mode was defined by the fact in question; the assistant-with-key case contradicted the definition | **critical** | Accepted. Modes are defined by what the record shows (signed-as-actor, signed-as-other, unsigned); **P-perf** is stated as a premise (Int) with its tension to §1.12-1; under it, signed-as-actor attribution ⇔ L2 ∧ L3; the assistant case is a custody failure (L3) | §9.15.2 and R4; §9.15.4 agrees | **yes** |
| C1-02 / C2-02 | R7 did not resolve result 5: no CE* answer depends on occurrence at all; "not a kernel primitive" needs an unstated test; R7 (c) and W1 mis-cited §9.12 B3 | **critical** | Accepted. **R3** (new): no CE* answer depends on occurrence, attribution or completeness (Ded from G1). **R8:** result 5 dissolved for the kernel's answers; the primitive question **not decided**; the B3 citations corrected (U under as-fact puts the claim in doubt, does not refute it; T under recognized) | R8 agrees with G1; CON-0193 corrected | **yes** |
| C2-01 | an asymmetric criterion for "depends on" (W1 against W5) | high | Accepted. One criterion stated (§9.15.2): kernel assumption against legitimacy assumption, applied to every row | R3, R5, R7 and R8 use it | **yes** |
| C1-03 / C2-04, C2-09 | the world list was incomplete; §9.13.7's removed-entry case met the second falsifier | high | Accepted. **K1–K7** replace W4, W7, W8: exclusive write path (with durability), single view (no fork), the inputs, one shared record, integrity check, implementation, evaluator version. Each has its refutability. The falsifier is restated against K1–K7 | the list covers the removal case and the inputs | **yes** |
| C1-04 / C2-03 | G6 listed as internal two items that rest on the transcriber's declaration or on a second source; "unauthorized act: F" is an authorization result | high | Accepted. G6 is now the record alone (integrity, contradiction); the other two items are "visible only with a further source"; the authorization item removed | G6's sentence is true | **yes** |
| C1-05 | "refutable inside?" cells wrong for W1 and W8 | high | Accepted. L1a's cell follows B3 and D's admitted kinds; K4 and K6 note double consumption | the cells agree with B3 | **yes** |
| C1-06 / C2-10 | the transcribed case went beyond T2.2's scope; the actor's affirmation was not placed | high | Accepted. W1 is split into **L1a** (actor's testimony) and **L1b** (transcriber's fidelity); T2.2's scope stated; a signed affirmation reduces L1b, never L1a | R4 names both layers | **yes** |
| C1-07 / C2-07 | R4 is definitional; its falsifier unsatisfiable; not per cut; act time of transcriptions | normal | Accepted. **R5** "by definition"; the falsifier replaced with §1.6's empirical one; per cut stated; the default act time is the time of receipt (S0103) | R5 and §9.15.1 | **yes** |
| C1-08 | timing and capacity left out of the reduction | high | Accepted. L6 (trusted time, as-fact) and L7 (capacity, duress) are added; the "except" clause removed | R4 and R7 | **yes** |
| C1-09 / C2-05, C2-08 | R5's estate reading too broad; I13's unnamed author is the sharper gap; "fail-closed" was a label | normal | Accepted. **R6** names I13's gap. The estate's practice varies by transition (S0078 STARTs: 6 of 37 documented, 35/35 count difference U; S0431 STOP; S0206 grants). "Attribution default", with the real fail-closed option returned to the Principal | R6 says so | **yes** |
| C1-10 | the corpus selection could not be reproduced; S0091 and S0206 were missed | high | Accepted. The rule re-applied: **37 files**, each accounted for in §9.15.8; S0091, S0206 and S0431 read at their sections, logged | the not-read list covers all 37 | **yes** |
| C1-11 / C2-12 | EXT-078's volume and issue; RFC 6962's counting is an intent; forking in EXT-079; the performative application is the book's | normal | Accepted. EXT-078 is 2(2) per Crossref (discrepancy recorded); RFC 6962 reworded; forking added; the Austin application labelled Int | EXTERNAL-SOURCES and §9.15.8 | **yes** |
| C1-12 | S0336 L56 places the ground in a prior record; the estate's model is closer to represent | normal | Accepted. Labelled Int; both noted | R4's paragraph | **yes** |
| C1-13 / C2-14 | R2 holds only under A1 recognized; H4 has three parts | normal | Accepted. R2 restated: occurrence, attribution, entitlement; the as-fact case stated | R2 | **yes** |
| C1-14 / C2-13 | bookkeeping: §9.11.5 not mapped; W6 overlap; §9.8 and §9.9 not pointing to §9.15; the estate outside D | normal | Accepted. Mapping in R7; L5 as the designation norm plus L1–L3; pointers added in §9.8 and §9.9; §9.15.6 covers the estate | the mapping and pointers exist | **yes** |
| C1-15 / C2-11 | stronger counterexamples and rivals | normal | Accepted. §9.15.4 adds the out-of-band removal, the honest false time, the silent late transcription and duress. §9.15.5 adopts recognition/legitimacy and the verification-status modes; recorder-as-actor and the institutional duties are recorded as compatible framings | both sections | **yes** |

**Counts, C:** 29 findings (C1 15, C2 14) → 16 rows. Found by both: 11 rows. One reviewer only: 5 (C1 4: C1-05, C1-08, C1-10, C1-12; C2 1: C2-01). Critical: 2 (C1-01, C1-02).

**Total:** 73 findings → 39 rows, 2 critical.

## 4. Checks run on the corrections

- **Quote check (entry 60):**
  - §9.13 10 found, 0 not found;
  - §9.14 2 found, 0 not found;
  - §9.15 13 found, 0 not found;
  - §9.08 and §9.09 unchanged against the baseline (16, 13 found);
  - §2.3 and §2.6: no checked-form quotes.
- **R1:** H2 0 (the H4 flag is inherited, CON-0133).
- **R3:** CON-0191, CON-0192 and CON-0193 updated, with the citing sections:
  - §9.07–§9.10;
  - §9.08's H4/H5 paragraph and §9.09 result 5 (pointers);
  - the §2.3 (d) and §2.6 pointers;
  - the Ch. 9 index; BOOK-OUTLINE.
- **Models:**
  - `ce_model.py` v5: all assertions passed (`ce_model-run-20261020-v5.txt`);
  - `helly_check.py` v3: all assertions passed (`helly_check-run-20261020-v3.txt`).
- **Read log:** S0091, S0206 and S0431 sections logged.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| **Decision 3's reading** ("when U" or "U by rule"; root and unneeded basis as links) | **Principal** | confirm or revise |
| **Recorder field and authentication in D; whether Q1 marks or refuses unlabelled answers** | **Principal** | decide |
| dangling citations refused (v5) and loss entries (I12) | Professor (information to the Principal) | when I12 is read |
| P conjunctive or disjunctive (A1-07) | Principal (P reserved) | when P is decided |
| K1 and K2 provision (external anchor; no fork) | architecture | outside the theory |
| P-perf: which acts are performed by recording | Professor / Principal | turn 7 or later |
| a test telling a primitive from a world assumption | Professor | turn 7 (reassessment) |
| §9.13 v5, §9.14 v3, §9.15 v2: corrected, not re-reviewed | reviewers | turn 7's reviewers, in full |
| corpus searches for turns 2 and 3 | Professor | turn 7 |
