*Report of reviewer D1 (Opus), returned as hand-back text, verbatim (saved by the Professor, 2026-10-20; the reviewer's Write call was refused).*

I could not write REPORT-D1.md. The harness refused it ("Subagents should return findings as text"), and I did not try to get around that. The full report is below, ready to save as W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-D1.md. I edited nothing else.

**Summary (10 lines)**
1. **R2 (b) and the race (R2 (c)) hold.** I give proof-step tables for both. But R2's setting contradicts D's append guard: if each application's record is in D, the union is already closed under citation and (a)/(d) are empty. Check 18's application records are not in D (408/600) (D1-01, high).
2. **Same identity, different fields.** I15's recorder and auth fields and tombstones break "the union absorbs duplicates". The duplicate either escapes an ending (CE* = T) or the union leaves D (D1-02, high).
3. **S0969 L1245–1258 (Experiment 15, "Expected: OrderingUncertain") supports the rival F26 (b)** against a content tie rule, inside the very range the book cites in support (D1-03, high).
4. **R4's "plane iff" is not derived.** It has unstated premises (K2, F26 ≠ (a)), drops "and the evaluator CE" from (P), settles what §9.6.4 left open, and omits the escrow/ownership rival (D1-04, high).
5. **R3 misses three requirements:** D's unique-identifier rule, §9.6's "one mutation owner" example, and law 21's completeness/coordination (CALM) (D1-05, high).
6. **R5 is sound only under the Professor's reading.** It equates "fields citations rely on" with "every field D requires", which goes beyond the Principal's words (D1-06, high). With marks in the output, past-cut answers do change (D1-07).
7. **The Ext→Src quotes are all verbatim.** But S1519 L234 is quoted selectively, and several Src rows were missed: S0915 on CRDT and happened-before, S0991 L1143 (D1-12).
8. **Prior work missing:** invariant confluence (Bailis et al. 2015), CALM, bounded counters (D1-13).
9. **R7's condition (ii) excludes world assumptions by definition**, conflates "listed as input" with "read", and is undefined while Q4 has no reference definition (D1-14).
10. **R6 is properly labelled as recommendations;** its 20a reason is weak and law 26 is missing. Model check 18's cut test is close to a tautology (D1-09). Scratch script: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t7d1_checks.py

---

# Turn 7, commission D — report of reviewer D1 (Opus)

*2026-10-20. Object: §9.16 v1 (R1–R7, §9.16.4–9.16.9), the Ext→Src corrections (§9.8.4, §9.10.9, §9.11.6, §9.12.8), the turn-7 pointers, `ce_model.py` v7 (check 18), CON-0194, EXT-084–086, and the THEORY-MAP rows F22, F26, F36.*
- *I read COMMON.md and BRIEF-D-REASSESS.md first. I read no other REPORT-*.md, no basis file, and nothing sealed.*
- *Corpus files opened: S0943, S0990, S0916, S0534, S0915, S0969, S1519, S0991. All are listed in CORPUS-ALLOWED.tsv, and each sha256 matched before reading.*
- *Outside works were checked through public abstract pages only, with generic search terms.*
- *Scratch script: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t7d1_checks.py`, run with `python3 -I`. It imports `ce_model.py` v7 unchanged.*

*Labels: Fact = read in a file or in the model's output; Ded = derived here; Int = my judgement.*

## Verdict table

| Object | Verdict | Main findings |
|---|---|---|
| R1 (dependency table) | **Mostly right.** Four rows depend on defects below. | The rows "mergeable now defined", Lemma D, T1 and law 21 rest on R2 (a)/(d), which D1-01 and D1-02 break. The row on §9.11 R1 step 5 overstates "answers unchanged" (D1-07). These rows are right as far as I checked: result 2, F26, H4/H5, result 5, Q1, Q3, Q4, I1–I15, G4. I did not verify §9.14's "untouched" row. |
| R2 (b), proof | **Adequate under its premises** (table 1). | The premise that identities are unique in the union fails under I15 and tombstones (D1-02). Under ties by identity, the race's outcome depends on the identifier names (D1-08). |
| R2 (c), the race | **Adequate; a genuine countermodel** (table 2). | With act times crowded into {1, 2}, my random sample also finds position dependence (4/400) (D1-09). |
| R2 (a), (d) and §9.16.4 | **Inconsistent as stated.** | If each application's record is in D, the union is closed under citation, so (a)'s example cannot occur. Check 18's application records are not in D (D1-01). "Consistent cut" is overstated (D1-10). |
| R3 | **Incomplete.** | It misses D's own identifier uniqueness, §9.6 K4's "one mutation owner", and law 21 together with completeness up to a cut (D1-05). |
| R4 | **"Plane iff" is asserted, not derived.** | Its premises are unstated. It changes the meaning of (P) and settles what §9.6.4 left open. The escrow rival is missing (D1-04). |
| R5 | **Sound only under the Professor's broad reading** (table 3). | That reading goes beyond the decision's words (D1-06). Once marks are part of the output, the full output at a past cut changes (D1-07). |
| R6 | **Properly labelled as recommendations.** | Two reasons are weak (D1-15). |
| R7 | **Coherent, but its main gain is analytic.** | D1-14. |
| Ext→Src | **Every quote is verbatim at its line.** | One cited range supports the rival (D1-03). One quote is selective, and some cases were missed (D1-12). |
| Turn-7 pointers | **Consistent with §9.16.** | They inherit D1-02 (§9.10 O1) and D1-04 (§9.6.6a item 4). |
| ce_model v7, check 18 | **The code is correct.** | The cut test is close to a tautology, and only values are compared (D1-09). |
| EXT-084–086 | **Within their public descriptions.** | Closer prior work is missing (D1-13). |
| CON-0194; THEORY-MAP F22/F26/F36; index row | **Faithful to §9.16.** | They carry D1-01, D1-04 and D1-05. |

## Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| D1-01 | **R2's setting is inconsistent with D's append guard.** Per §9.13.2, an entry may be appended iff its citations name entries already present. So if each record Rᵢ is in D, each Rᵢ is closed under citation, and so is their union. Then (a)'s example ("an entry of R₁ may cite an entry that only R₂ holds") cannot occur. Every cross-record cut is then in D, so (d)'s "iff" is empty. If the Rᵢ are not in D, as check 18 builds them, no record exists for the guard to check the causal premise against. That contradicts §9.16.4: "D's append guard refuses it at the entry … checkable, not assumed." | high | Fact: §9.13.2; §9.16.4 bullet 3; check 18 splits one history at random. Model (scratch check 6): the per-application records, with the root added, are outside D in 408/600 cases. | State whether each application's record must be in D. If yes, correct (a)'s example and (d), and re-run check 18 with records in D. If no, name the check that enforces the causal premise across records (or call it an assumption), and withdraw "checkable". | §9.16, §9.16.4 and check 18 agree on which records are in D. |
| D1-02 | **Identity collisions after I15 and tombstones.** R2 and R3 assume a duplicate act is the same entry. Under I15 every entry carries a recorder and an auth status, and a tombstone keeps the identity while dropping the content. (i) If the identity covers the recorder, one act recorded by two applications gives two entries. An ending of one does not reach the other, so CE* = T where F is due. (ii) If the identity excludes the recorder, the union holds two entries with one identity, and it leaves D. A tombstone in one record plus the original in another does the same. So brief Q1(b)'s premise ("same identity, so the same entry") fails. | high | Model (scratch): 2a, ending of g1 only, gives CE*(c, 3) = T. 2b, same identity with a different recorder, gives `identifiers not unique`. Check 3, a tombstone plus its original, gives the same. | Fix which fields the identity is derived from (exclude recorder, auth and `removed`). Add a commutative merge rule for entries with the same identity (e.g. the tombstone wins; recorder and auth are joined). Add both to R2's premises and to the dependent R1 rows. | R2 states both, and a model check shows that a tombstone plus its original stays in D, with the same answers in every order. |
| D1-03 | **The cited corpus range supports the rival.** R2 cites "S0969 L1215–1264, in meaning" for a partial order of events. That range ends with Experiment 15, "false global ordering": "System invents e₁<e₂. Expected: OrderingUncertain." A content tie rule invents exactly such an order. This passage supports F26 (b), not (c). | high | Fact: S0969 L1245–1258. | Cite it as Src for F26 (b) in R2's Src paragraph, in its costs and in §9.16.5. | Done in all three places. |
| D1-04 | **R4's "plane iff" is not derived.** (1) It needs K2 = content-derived and F26 ≠ (a): §9.6 line 223 says K4 = yes for positions under (a), and §9.16.5 calls a global sequencer a plane by construction. Neither premise is stated. (2) §9.6.4 line 128 left open whether K4-d's check is a kernel candidate. R4 puts it in (C) without argument. (3) §9.6's (P) reads "serialization point … **and the evaluator CE**", and R4 drops CE. (4) Brief Q4: (C) does survive K4-d, but only if the contract includes the deterministic aggregate, which R4's contract list omits. (5) Missing rival: prevention without a shared point per write, by giving one owner (or an escrow of rights) per key, with coordination only to transfer rights. | high | Fact: §9.6 lines 127–128, 188–189, 223. Ext: Balegas et al., SRDS 2015, the bounded counter (public abstract). | Write the biconditional as a proof with its premises named (K2, the F26 value, writers that cannot be partitioned). Justify the change to (P). Add the aggregate to the contract. Add the escrow rival with its cost. | R4 has a proof-step table and the rival. |
| D1-05 | **R3 misses requirements.** (i) D's "unique identifiers, one position each" is itself a uniqueness across applications (D1-02). (ii) §9.6 K4's example "one mutation owner per shared work item" is a count bound and is not listed. (iii) Law 21 (K5) is a uniqueness of answers. Without a shared point it holds only for equal entry sets. §9.7 line 113 requires "completeness up to the cut checkable". CE* is non-monotone in R, so answering "now" without retraction needs coordination or a sealed cut (CALM). So "evaluation needs none" holds for a fixed cut only. On brief Q3: plural bases are indeed no violation (see also S0991 L1143), and law 21 is a uniqueness of answers, not of entries. | high | Fact: §9.13.2; §9.6 line 119; §9.7 line 113; law 21. Ext: Hellerstein and Alvaro 2019 (CALM). | Add (i)–(iii) with a status for each. Restrict "evaluation needs none" to a given cut. | R3 places each one under K4-p, K4-d, or "not a K4 law". |
| D1-06 | **R5 rests on a reading broader than the decision.** The decision says the tombstone keeps "identity, kind and the fields citations rely on". §9.13.2 reads that as "(every field D requires)". No citation relies on an adoption's item or validity. Under the literal reading, a tombstoned adoption leaves D (missing fields), so "no answer changes" fails. Under the broad reading, R5 is true by construction, but a removal then drops only fields outside D. Actor and holder (personal data) stay, so the "partial answer to F30" is weaker than it sounds. Practice 5 asks for "no more and no less". | high | Fact: the decision as quoted in COMMON.md; the §9.13.2 removal bullet. Model (scratch check 5): a strict tombstone of an adoption gives `undefined: missing field item, vfrom, vto`. | Mark the reading as the Professor's, return it to the Principal, and state what a removal actually removes. | The reading is confirmed, or R5 is restated under the literal one. |
| D1-07 | **"Answers unchanged at every cut" conflicts with the v6 output.** The output now includes marks. Recomputed at a past cut after a tombstone, it carries "rests on removed content", which the original answer did not. So the value is reproducible but the output is not. Both the R1 row and the §9.11 step-5 pointer say "answers". Step 5 is about prefixes, and an in-place tombstone (as `tombstone()` performs) also makes a past cut not a prefix of the current record. A signed entry's auth status cannot be re-verified once its content is gone. | normal | Fact: §9.13.3 Output; `tombstone()` edits in place; §9.11 line 80. | Write "values unchanged, marks added". State whether a tombstone is an appended entry or an edit. Extend the K5 premise to signatures. | The R1 row and §9.11 say "values", and the tombstone's form is stated. |
| D1-08 | **The tie rule's costs are understated.** (i) "Ties by identity: F in both" holds only for the names x and e. With names a and z the result is T in both, so the text should say "the same in both". (ii) Whoever controls a free content field can grind a content-derived identity and win same-time races. (iii) "Endings first" is a preorder, not a full tie rule: two endings can still tie. Strict comparison keeps it order-independent, but this must be stated or the rule completed. | normal | Model (scratch check 1): F,F for (x, e); T,T for (a, z). (ii) and (iii) are Ded. | Correct the race sentence. Add steerability to the costs. Say whether a preorder is admitted. | The text and the F26 θ entry say so. |
| D1-09 | **Check 18's limits.** (i) The cut is a subsequence of an order already in D, so `in_D` can fail only on a dangling citation; `closed == inD` is nearly true by construction. (ii) Only values are compared, not the marks and reasons v6 adds to the output. (iii) With act times crowded into {1, 2}, ties by position differ in 4/400 histories, and ties by identity never differ. | normal | Code read; scratch check 4. | Add a crowded-time sample, and compare marks and reasons too. | Reported in the v8 run or a note. |
| D1-10 | **"Exactly the consistent-cut condition (EXT-085)" is overstated.** A consistent cut is closed under happened-before. Citation closure is closure under a sub-relation of it: every consistent cut is citation-closed, but not conversely. "Exactly Lemma D's condition" is right, given the premises of D1-01 and D1-02. | normal | Ded. | Call it "the citation analogue of a consistent cut" in R2 (d), §9.16.7 and CON-0194. | Changed in all three places. |
| D1-11 | **Homonyms inside §9.16.** K2 means both §9.6's identity mechanism and §9.15's single view. K4 means both §9.6's uniqueness and §9.15's one shared record. K5 means both §9.7's "one answer whoever asks" (law 21) and §9.15's sound integrity check (R5's premise). | normal | Fact: §9.16 lines 60, 62, 67, 74, 138, 189. | Qualify or rename one set. | No symbol carries two meanings. |
| D1-12 | **Ext→Src: one selective quote, several cases missed.** (i) S1519 L234 says "0/11 invariants are definitions", but the same line says the authority-uniqueness bound (§92.45) is "correctly stated". R3 quotes only the first part. (ii) Missed Src: S0991 L1143, "For others, multiple authorized decision-makers are intentional." S0915 L439–485 (commutativity, associativity, idempotence, CRDT) against EXT-086. S0915 L66–91 (Lamport's happened-before) against EXT-084. S0915 L814–827 (a RuleSnapshot is required) against the pinned CE* version. (iii) §9.10.9's replay row leaves out "knowledge changed", the first item at S0916 L1353. All other quotes are verbatim at their lines. "Regulative" does not occur in S0534. On brief Q7: S0943's slogans are fairly set beside Alpern and Schneider, since the row keeps the formal definitions as Ext. | normal | Fact: the lines named. | Quote L234 in full, add the missed Src, and add "knowledge changed". | §9.16.7, R3 and §9.10.9 are corrected. |
| D1-13 | **Closer prior work is missing (brief Q8).** EXT-084 and EXT-086 are described within their public descriptions. For EXT-085, the abstract speaks of a "global state"; "consistent cut" is the later name. Missing, and closer: invariant confluence (Bailis et al., PVLDB 8(3), 2015), a necessary and sufficient condition for coordination-free preservation of invariants, which is R3's exact question. Also CALM (Hellerstein and Alvaro 2019/2020) and the bounded counter (Balegas et al. 2015). | normal | Ext: arXiv 1402.2237, 1901.01930 and 1503.09052 (public pages). | Add them as benchmarks. | EXT entries exist, and R3 cites invariant confluence. |
| D1-14 | **R7.** (a) Condition (ii) makes every world assumption non-primitive by definition, so "over-generation removed" is a stipulation, not a result. That is acceptable only because R7 labels itself a definitional choice. (b) It conflates "among I1–I15" with "read": §9.13.2 says I2 recording time, I12 and I14 are not read. (c) It is undefined for candidates read only by Q4, which has no reference definition, or by Q3, which is not modelled. (d) The result depends on how candidates are individuated ("act time" is read; "timing" is not). Every RESEARCH-PURPOSE candidate passes (ii), so it decides nothing among them. The stated rival is fair. A second rival, "needed by" a reference definition, is missing. | normal | Fact: R7; the §9.13.2 input table. | Use "read by". Mark the test partial while Q4 has no reference. State the individuation rule. | R7 is revised accordingly. |
| D1-15 | **R6.** It is properly labelled Int, and the decision is reserved. Two weak points: law 20a's reason ("no existence argument") does not exclude option (c), an enabledness condition. And law 26, one of R3's two K4 laws, has no row. | normal | Fact: R6; §9.11 R3. | Argue against (c) explicitly, and add law 26 or a pointer to R4. | R6 covers both. |

## Proof-step tables (derivations judged adequate)

**Table 1. R2 (b).** Premises: identities are unique in the union, and every entry passes D's per-entry rules.

| Step | Claim | Justification | Checked |
|---|---|---|---|
| 1 | Order is read only through the key and D's citation rule | §9.13.3. In the model, `rec.pos` is used only in `Record.key` (tie='pos') and in `in_D` | Fact |
| 2 | Under a content tie rule, the key is a function of the entries | definition | Ded |
| 3 | Every linear extension passes the citation rule | definition | Ded |
| 4 | So Φ, its least fixed point (= WF) and current-authority are the same for every extension | steps 1–3; §9.13 R1/R3 | Ded; model 600/600, and mine 400/400 |
| Gap | Unique identities in the union | fails under I15 and tombstones (D1-02) | must be added as a premise |

**Table 2. R2 (c), the race.**

| Step | Claim | Justification | Checked |
|---|---|---|---|
| 1 | x and e, both at act time 3, cite neither each other | construction | Fact |
| 2 | Both merge orders are linear extensions, and both are in D | step 1 | model |
| 3 | Under ties by position, the answer is T or F depending on the order | the basis clause key(e) < key(x) | model: T / F |
| 4 | Under ties by identity, the answer is the same in both orders | table 1 | model: F,F (names x, e) or T,T (names a, z) |

**Table 3. R5, under the broad reading.**

| Step | Claim | Justification | Checked |
|---|---|---|---|
| 1 | Φ and the currency rule read only kind, citations and D's required fields | §9.13.3; `FIELDS`, `basis`, `current` | Fact |
| 2 | Brief Q5: `content` is read by nothing; `removed` and `auth` are read only by `marks`; `ok` is unchanged | `tombstone()`, `marks()` | Fact |
| 3 | Values are unchanged at every cut; only the marks change | steps 1–2 | Ded; check 17 |
| Premise | The integrity check is defined on the tombstone | stated for integrity, not for signatures (D1-07) | — |

**R2 (a).** The causal premise gives acyclicity, and so the existence of a linear extension. "A sub-relation of happened-before" adds a further premise: that citing an entry requires having received it (Int; plausible under content-derived identities). The step is adequate.

## Coverage

- **Read in full:** §9.16, §9.11, §9.13. Model functions: Record, in_D, basis, phi, wfs, current, u_reasons, alternatives, ce_star_explained, tombstone, support, marks, linear_extension. Checks 17–18 and the v7 run output.
- **Read in part:** §9.6 (the K-items, the (C)/(P) table, §9.6.6a); §9.7 (K5, condition 3); §9.15 (G/K table, tombstone line); §9.12 lines 75, 109, 113; §9.10 lines 109 and 260–261; §9.8 line 64; CON-0194; EXT-084–086; THEORY-MAP F22, F26, F36; the index row.
- **Corpus:** read at the cited lines and at the hits of targeted searches only, not whole.
- **Not checked:** §9.14; §9.4 C2's rival; the text of the §9.8.8 F30 row; the READ-LOG scan rows; the quote-check run; helly_check; the full Chandy–Lamport paper.

