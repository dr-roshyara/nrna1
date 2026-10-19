# Settlement of theory-turn review TT2 (the evaluator CE, §9.7)

*2026-10-19. One reviewer, topic-review mode, one commission and one step-2 message. Harness time: step 1 130 s, total 418 s.*

**Re-checked:** §1.14.1 L159 ("one global, gap-free sequence"); S0128 L48 (`seq` dense "in every one of the 16 records", per record). Both hold. **All 12 findings are accepted**, and the appendix items are applied.

**Do the critical findings block the run?** No. Both can be settled from the evidence: the affected results become conditional, and the order question becomes a fork (F26) for later work. Nothing dependent was built on the withdrawn results: the dependency graph shows only §9.6 (§9.6.6a items 2–4) as a direct user, and that is qualified.

## Measures
| Measure | Value |
|---|---|
| New concepts | 2 (CON-0142, CON-0143) |
| Findings | 12: 2 critical · 6 high · 4 normal; appendix 4 |
| INVALID share | 1 of 2 new concepts (CON-0142 INVALID as stated; restated form PROVISIONAL) |
| Material omissions | 5 (findings 6, 7, 8, 11, 12) |
| Reviewer time | 418 s |

## Changes
| Finding | Change |
|---|---|
| 1 (critical) | §9.7 Analysis 2 step 2: the order of acts is a uniqueness; the three-interleaving case; **fork F26** (global order or partial order with U); §9.6.6a items 2–4 qualified (K4 = yes for positions under F26(a)) |
| 2 (critical) | Analysis 2 step 5: execution is not yet delegable; CE undefined for any θ (covers, actor–holder link, oscillating υ, recursive σ); §9.7.7 result 2 |
| 3 | K5 restated over (item, scope, time, axis); "the reading" withdrawn; scope excludes the asking application; case 10 conditional on ρ; **fork F27** (ρ policy or query; reserved decision 2) |
| 4 | §9.7.6 C3: the missing axis is recording time; S0916's acceptance possibly evidential (RC-37a) |
| 5 | step 4: one authoritative, versioned home, not a kernel home; contract time and versioning of the definition |
| 6 | input table: `seq` per record; rows for recording time, adoptions, root, covers and the actor–holder link |
| 7 | Analysis 3 condition 3: a declared cut, CE(c, t; θ, R≤s); CE not monotone in R |
| 8 | Analysis 3 condition 4: a must-do, soundness and completeness relative to evidenced acts |
| 9 | C5: denying the antecedent removed; the conclusion rests on the input table |
| 10 | "not computable" → "uninformative (U/F)" |
| 11 | conflict outcomes as an open question (F24) |
| 12 | the root as a hidden condition of K5; a root row in the input table |
| Appendix | ι unclassified (open question); plain-language overgeneralisation fixed; producer ≠ verifier restated (execution only, rests on §8.6 C5); S0916 note in C3 |

**Register:** CON-0138 PROVISIONAL (delegation clause restated); CON-0142 INVALID as stated, restated form PROVISIONAL; CON-0143 PROVISIONAL, restated, its dependency corrected from CON-0136 to CON-0138.
**Map:** F26, F27; a TT2 block. **Outline:** a row for the second boundary test.

**Settlement self-check (grep of my own edits against the findings):** the old phrases "recomput…" (unqualified), "deterministic", "kernel must own", "not computable", "acceptance time", "mergeable" survive only in §9.7.9 as withdrawn text, or qualified. Quote check: §9.7 15/0, §9.6 14/0. Strength scan: every remaining "only/necessary" is qualified or inside a quote.

**Dependency graph (§12d):** `--down CON-0142` and `--down CON-0138`: the direct dependents are §9.7 and §9.6; §6.6 cites §9.6.6a for K3 only (unaffected), and its "git as single serialization point" agrees with F26(a). Nothing else is reopened.

**For the Principal (not blocking):** K5; F27.
