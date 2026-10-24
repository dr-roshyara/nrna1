# Theory turn 8: settlement (record identity and anchoring, K1/K2)

*2026-10-24. By the Professor, under Rulebook v1.4 §6.4 and §12b. Draft `ca75864ef`. Reports saved verbatim with their sha256 at receipt (`SHA256-REPORTS.txt`):*
- *A (Opus) `5376b28c…`;*
- *B (Sonnet) `ec527992…`;*
- *step-1 inventories A `9f110b17…` and B `4971bf58…` (`SHA256-STEP1.txt`).*

## 0. The theory deliverable, then what it gained (v1.4 §10)

**Deliverable** (a step of the derivation chain: K1 and K2 restated, any new assumption named, with independence models): **met, in a narrower form than the draft.**

| Change | Before | New evidence or derivation | After | Still unproven | Review needed |
|---|---|---|---|---|---|
| **Record vs carrier** (definition) | "the record" undefined as to its store (CON-0239: D cannot supply it) | R1, validated by both reviewers | record = accepted entries; carrier = store; designation = an act naming the carrier | — | — |
| **K2** (kernel assumption) | readers agree on the entries of the cut | M2 (validated by A; B: valid for reading 1 only); K2-r ⇒ old K2 (validated by both) | K2-d presupposition, K2-w, K2-r; old K2 follows within one designation; K2-w, K2-r and K1 independent | K2-r's refutation from inside | yes, as part of the next consolidation |
| **L11** (legitimacy assumption) | — | designation as an act | L11 record designation, with the root-designation regress located there | its scope (competence per record) | the Professor's definitional choice, for review |
| **Membership** (law) | "does D need the resolution in force at each write?" (§8.19.3) | two-history proof; τ as a shared order source; closing list, seal | CON-0264: membership by acceptance; conservative classification across a carrier change; CON-0262 INVALID as stated | whether τ is trusted and shared (T9-08 c) | — |
| **Detection before retiring a carrier** (law, architecture) | CON-0243, 0257, 0259 as separate concepts | model v2, 384,000 worlds; C1–C5 proofs | CON-0265: the two-operand gate; CON-0263 INVALID as stated; K1's cell gains coverage by a non-carrier-derived commitment | the check–act interval (prevention only) | — |

## 1. Findings and their disposition

*Both: found by A and B. One-only: accepted when checked against the section and the sources. Each row has a "resolved when" observable in the book.*

| Finding(s) | Sev. | Disposition | Resolved when (observable) |
|---|---|---|---|
| **A-01** (loss counts foreign entries; premises missing) = **B-03** (loss scope; exactness; source-side loss; the model tautological) | **critical** (A, F1: §9.15 K1 cell, CON-0263 and the Ch. 8 answers rested on it); high (B) | **accepted (both).** Loss is redefined over members (§9.17.2). Exactness is a premise of the gate. Source-side loss before any read is out of reach (C3). Model v2 replaces v1. CON-0263 INVALID as stated | §9.17.2 "Loss"; R4 C1–C5; `anchor_model-run-20261024-v2.txt`; register CON-0263 INVALID, CON-0265 |
| **A-03** (A1's two readings; A2 derivable under coverage) = **B-02** (the same; the "iff"; the interval inconsistency) | high | **accepted (both).** One gate. Independence is the means to coverage, not a condition. No "iff" over three independent conditions. Re-taking narrows the interval; it does not close it | R4 "The independence condition is the means…"; register CON-0265 meaning; §8.21.3 and §8.18.3 pointers |
| **A-02** (A3 dropped "not only"; two gates in S0300) | high | **accepted (A only; checked against S0300 L941–947 and CON-0259).** The two-operand gate G2. The casualty comparison detects coverage failure (model C1) | R4 gates G0/G1/G2; register CON-0259 follow-up |
| **A-04** = **B-05** (R3 (d) reintroduced CON-0252; PREMISE 2 is the real reason) | high | **accepted (both).** R3 (d) restates CON-0254 as stated: (i) a fact about hashes, (ii) the estate's placement under PREMISE 2 (S0300 L801–829; S0253 RV-1) | §9.17 R3 (d); §8.20.3 pointer; register CON-0254 follow-up |
| **A-05** (one-store proof impossible in D; τ; "only if" false; not a decision) = **B-04** (the same; E exact unstated) | high | **accepted (both).** Two-history proof. τ scope stated. E exact and no loss before t_E are premises. "Conservative classification". Sealing and trusted τ are other routes. CON-0262 INVALID as stated | §9.17 R3 (a)–(c); register CON-0262 INVALID, CON-0264 |
| **B-01** (membership "iff appended while designated" misclassifies carried-across entries) | high | **accepted (B only; checked against S0300 Phase 3 and §8.20 C2).** Record ≠ carrier. Members are entries accepted into the record; copies stay the same members | §9.17.2 "Member"; CON-0264 |
| **A-06** = **B-06** (K2-d homonym, no independence row, K3 parity, regress) | high | **accepted (both).** K2-d becomes a presupposition with no independence claim. L11 carries competence and locates the regress at a root designation. R1 states why designation is not in K3 | §9.17 R1 "What this means for D", R2; §9.15 K2 row and L11 |
| **A-07** (designation "outside every record" contradicted by S0300 L1315) | high | **accepted (A only; checked against S0300 L1315).** Definition corrected | §9.17.2 "Designation" |
| **A-08** (kernel vs application; K1 cell cited store removal; must-do) | high | **accepted.** R4 is labelled an architecture result; K1's cell keeps only coverage by a non-carrier-derived commitment. **B-09** (no must-do) is the same point: the must-do paragraph is added | §9.17 R4 opening, R5; §9.15 K1 cell; R2 "Must-do" |
| **A-09** (rival "identity folded into K1" unanswered) | normal | accepted; answered in R2 | §9.17 R2 rival paragraph |
| **A-10** (hash binding secures the comparison, not coverage) | normal | accepted | §9.17 R4 "Equality of entries" |
| **A-11** (the resolver gives K2-w only on code paths) | normal | accepted | §9.17 R2 boundary table |
| **A-12** = **B-11 (i)** (resolution includes the predicate) | normal | accepted (both) | §9.17.2 "Resolution" |
| **A-13** = **B-07** (R1's Src items are analogies; one line of support) | normal | accepted (both). Re-labelled "related, not on point"; S0300 and S0253 one line; S0137/0142/0146 one chain; S0300 a specification never executed | §9.17 R1 and R4 corpus paragraphs |
| **A-14** (M2's estate illustration: empty store, outside D; "any cut") | normal | accepted | §9.17 R2 M2 |
| **B-08** (CON-0251's "if" half comes from D's citation rule, not K2) | normal | accepted (B only; checked against §9.13.2) | §9.17 R3 (e); register CON-0251 meaning; §8.20 C2 L59 (change log 08.99) |
| **B-10** + A's CON-0258 note (G may not name x's values; quantifier) | normal | accepted as an open item, not a re-verdict: both PROVISIONAL | register CON-0258 follow-up (T3-27) |
| **B-11 (ii)** (ρ and S2055's H_t/K) | normal | **rejected for this turn, with a reason:** S2055 is a commission with no result, and §9.17 does not use its state model. Recorded as not taken | — |
| **B-11 (iii)** (L11 scope) | normal | accepted as open | §9.17.5; T9-08 (e) |
| **B-12** (PREMISE 2's application is a design choice) | normal | accepted | §9.17 R3 (d) |
| Stale "L1–L10" lines (A appendix) | normal | accepted: §9.15 L204 noted; L265 to L1–L11 | §9.15 L204, L265 |
| Model witness print (B: A2 and A3 witnesses identical without flags) | normal | moot: v2 replaces v1 and prints counts per gate | `anchor_model.py` v2 |

**Disagreements, settled.**
- **M2** (A: valid; B: equivocates, since K1 and K2 refer to different stores). Settled as both:
  - under reading 1 of old K2, M2 is a valid countermodel;
  - under reading 2, old K2 already is K2-r.
  - So the restatement makes the referent explicit. It is a re-axiomatisation, not a derivation (B's point, stated in R2).
- **CON-0262** (A: PROVISIONAL with defects; B: INVALID as stated). Settled **INVALID as stated**:
  - the "iff" misclassifies carried-across entries (B-01);
  - "only if a closing list" is false (A-05, B-04).
  - Both are demonstrated defects (v1.4 §6.4: INVALID means a demonstrated defect).
- **CON-0162** (A: VALIDATED; B: order-dependence for symmetric dimensions, from the register only). Kept **PROVISIONAL**: one reviewer's VALIDATED with an unresolved objection from the other is not an independent verdict. The order-dependence goes to T3-21.

**Severity check (v1.4 §6.4).**
- A-01 is critical by F1: §9.15's K1 cell and two Ch. 8 answers rested on it. It is not "F4 only". The dependants are corrected in this settlement.
- No other finding is critical.

**Running measure:** 26 findings (A 14, B 12). 1 critical (A-01, with B's B-03 at high). Material omissions: B-01, A-02, A-07. Defects found later in work that had passed review: none.

## 2. Verdicts

| Concept | Verdict | Basis |
|---|---|---|
| R1 (identity not a function of entries) | validated by both (within CON-0261) | — |
| K2-r ⇒ old K2 | validated by both (within CON-0261) | — |
| CON-0261 | PROVISIONAL | A and B |
| L11 | PROVISIONAL | A and B |
| CON-0262 | **INVALID as stated**, restated CON-0264 (PROVISIONAL) | B; A's defects; see disagreements |
| CON-0263 | **INVALID as stated**, restated CON-0265 (PROVISIONAL) | A and B |
| CON-0251, 0254, 0257, 0258 | PROVISIONAL, unchanged | A and B |
| CON-0162 | PROVISIONAL | see disagreements |
| Ch. 3 re-verdicts (0008, 0041, 0043, 0065) | consistent with the rule (A) | — |

**Register after the settlement:** 265 concepts, 45 VALIDATED, 124 PROVISIONAL, 96 INVALID.

## 3. R3, line by line

*`inbound_citations.py` on each correction. Line numbers are as after the settlement.*

| Target | Corrected | Unaffected | Reassess |
|---|---|---|---|
| **CON-0261** | §9.15 L75 (K2 row); register 262; map 361; outline 176; T9-08 (TODO L18) | §8.18 L211 (its pointer answers the question as before); register 240 (CON-0239 follow-up); change log 09.99 L61 (history) | — |
| **CON-0262** | §9.17 L129 tag; register 263 (INVALID) and 265 (CON-0264); map; outline | §9.17 L265 (states its invalidity) | — |
| **CON-0263** | §9.17 L181 tag; register 264 (INVALID) and 266 (CON-0265); map; §9.15 K1 cell (L74; no longer cites it) | §9.17 L266; change log 09.99 L62, L65 (history) | — |
| **CON-0254** | §9.17 L165, L172 (R3 (d) as stated); register 255 follow-up | §8.00 L34; §8.20 L62, L67, L92, L101, L246, L263; register 253 (CON-0252), 263 (CON-0262, INVALID); map 189; outline 224 | — |
| **CON-0251** | register 252 meaning (the "if" half from D's citation rule); §8.20 L59 (logged in 08.99) | §8.00 L34; §8.20 L17, L52, L62, L65, L92, L96, L112, L114, L116, L264, L268, L269; §8.20 L245 (pointer already corrected); §9.17 L138, L174, L176, L283; register 253, 255; map 189 | — |
| **§9.15 K1, K2** (as phrases) | — | no inbound lines by phrase. Citations are by "§9.15 K1/K2" in §8.18–§8.21 and §9.13–§9.16, which use K1's content and K2's agreement, both unchanged in force | — |
| **Ch. 8 pointers** (§8.18.3, §8.19.3, §8.20.3 ×2, §8.21.3) | all five rewritten to the settled results | — | — |

## 4. Checks at the settlement

- **Quote check:** §9.17 7 found, 0 not found. §9.15 and §8.18–§8.21: 0 not found.
- **R1:** 241 rows with a home. 1 flag, CON-0133, inherited and outside this turn.
- **R2:**
  - tier A `TURN8`: explained, the review-folder identifier;
  - tier D in §9.17: `survivor-derived`, defined in R4's setting; the rest are ordinary words (irreversible, contribution, vacuously, restatement, implicit, intact, replaced, safe, claimed, lack, presuppose, fourth, append, agree, rival, mean, below).
- **Model:** `anchor_model.py` v2, run `anchor_model-run-20261024-v2.txt`. v1 and its run stay in git as the reviewed object.

## 5. Measures (per turn; v1.4 §10)

| Measure | Value |
|---|---|
| Reviewer harness time | A 174 s (step 1) + 539 s (steps 2–3) = 713 s; B 165 s + 471 s = 636 s; **total 1,349 s** |
| Professor | research question 01:46:37Z (`18c37dde3`) → draft `ca75864ef` (18:57Z) → settlement (this commit). **The wall clock includes an idle gap of about 17 hours** (the session paused between about 02:00Z and 18:50Z). The Professor's harness time is not available to the Professor; it is not estimated |
| Sources | six, read whole as topic-full: S0300 1,440 lines, S0917 1,628, S0904 1,599, S1375 1,269, S2055 1,155, S0253 280; together 7,371 lines |
| Depth | topic-full for all six (Professor). Both reviewers read all six whole at step 1 |
| Findings by severity | critical 1 · high 13 · normal 12 (A 14: 1/7/6; B 12: 0/6/6; before merging) |

## 6. Process record

- **The session scratchpad was lost** mid-turn (between the draft's corpus reads and the book search). Lost:
  - the scan outputs;
  - the 36-file selection list;
  - the register helper (re-created).

  The scan patterns are in the read log and can be re-run. Nothing committed was affected.
- **S1375** was read by judgement below the selection rule's threshold, a recorded deviation (§9.17 References).
- **The previous settlement's changes** (consolidation stage 2, `1404bb448`) were given to both reviewers as changed theory.
  - A checked CON-0162, CON-0258 and the Ch. 3 re-verdicts.
  - B checked CON-0258 and CON-0162 from the register.
  - Neither covered Ch. 2. Recorded: that remainder rides with the next consolidation (due after S0175).

## 7. Questions reserved for the Principal (none blocks)

1. **Designation as an admitted kind of D** (T9-08 b): it changes D.
2. **Recording time τ** (T9-08 c): should it be trusted and shared with designations, which makes membership decidable by epoch?

Neither stops reading: §9.17 states both conditionally.

## 7a. Commit

This settlement, §9.17 settled, §9.15, §8.18–§8.21 pointers and §8.20 C2, the register, the map, the outline, EXTERNAL-SOURCES, Ch. 9 index, TODO and change log, Ch. 8 change log, `anchor_model.py` v2 and its run.
