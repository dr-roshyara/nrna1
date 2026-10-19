# Settlement of review C15 (S0081, S0083–S0086; the §1.12 form rewrite; the TT2 and C14 settlement changes)

*2026-10-19. One reviewer, one commission and one step-2 message. Harness time: step 1 98 s, steps 2–3 419 s, total 517 s. First review under Rulebook step 9 (entry 51): the brief listed the TT2 and C14 settlement changes as changed theory.*

**Re-checked in the sources and the book:** §1.14.1 (the total order (t_x, s_x), act time first); S0014 L68 ("REALIZED" is there); `git show --name-only f24edf316` (747 files); S0079 L32 (KOS-CONTRACT-NEUTRALITY-001 implemented nothing); S0086 L15, L17 (the estate inside, "Agent runtime"). All hold. **All 18 findings and the appendix items are accepted.**

**The critical finding (1)** corrects the TT2 settlement, not the window. It is settled from the evidence: the claim is scoped to absent, tied or untrusted act times, and F26 gains option (c). It does not block the run.

## Measures
| Measure | Value |
|---|---|
| New concepts | 2 (CON-0146, CON-0147) |
| Findings | 18: 1 critical (on the TT2 settlement) · 11 major · 6 minor |
| Findings on changed theory (step 9) | 6 (1 critical, 3 major, 2 minor): TT2 settlement 4 (findings 1, 2, 3, 18); C14 settlement 3 (findings 4, 12, 16) |
| INVALID share | window: 1 of 2 new concepts (CON-0146 as stated); claim C2 INVALID as stated. Changed theory: CON-0144's restated form INVALID; §9.7 result 3 INVALID as stated |
| Material omissions | 5 (findings 9, 10, 11; boundary disagreement in 7; homonym and policy in the coverage table) |
| Reviewer time | 517 s |
| §1.12 rewrite | form VALIDATED (17/17 lines equal after labels); header and references corrected |

## Changes
| Finding | Change |
|---|---|
| 1 (critical) | §9.7 step 2, result 3, open questions, plain language; §9.6.6a qualification block; map F26: order is a uniqueness only where act times are absent, tied or untrusted; F26(c) act-time order with a tie rule |
| 2 | §9.6.6a item 2: "kernel-defined function" withdrawn; CON-0138 meaning column |
| 3 | §9.7 step 4, result 4, Analysis 3 condition 1, CON-0142: a common, versioned value; one home only with §1.10's premise |
| 4 | §5.7 C1: may close iff deliverable ∧ (lifecycle ∨ defect stated); should close when deliverable ∧ lifecycle; defect a permission; CON-0144 |
| 5, 6 | §6.7 C7 restated as (a)–(c); S0084's status as a specification (S0079 L32); kernel law Hyp; CON-0146 INVALID as stated, restated |
| 7, 11 | §6.7 C2: the boundary disagreement between views; S0084's adapter/core line; §0.2 comparison: no tension shown |
| 8 | §6.7 C4: the Governance Engineer as a second authority holder; the notation explanation; no contradiction shown |
| 9 | new §6.7 C9: seven verbs, no negative verbs |
| 10 | new §6.7 C8: verification never reaches acceptance; role separation with no enforcing relation |
| 12 | §5.7.1: "is recognized", not "counts" |
| 13 | §6.7 C5, CON-0147: two conditions; confirmation vs refutation |
| 14 | §6.7 lineage and C6: the co-commit inference dropped (747 files) |
| 15 | §6.7 C3: BC-7 matches in grants and handoffs only |
| 16 | §1.13.3-2: anchors S0014 L68 and S0015 L58 restored; the note labelled Int; the §1.13 header amended |
| 17 | §6.7 C7: "related to" §8.8 C2 |
| 18 | §9.7.1: only the choices shown to matter |
| Appendix | §1.12 header label pattern and references; form_rewrite.py header text; STEP8 corrections; §9.7 Analysis 3 condition 4 rephrased; §5.7 C5 duty labelled Int and restricted; coverage items added to §6.7 C2, C3, C6, C7 (engineering scope, Governance Communication homonym, policy in two places, determinability, declared expectations) |

**Settlement self-check:** quote check §6.7 33/0, §5.7 23/0, §1.13 6/0, §9.7 15/0, §9.6 14/0; strength scan of §6.7: remaining strong words are Src, scoped, or the mathematics of C7 (a)–(c); "verification never reaches acceptance" scoped to "in the views".
**Dependency graph (§12d):** `--down CON-0146`: §6.7 only. `--down CON-0144`: §5.7, and §6.7 (C9 cites §5.7 C1's trivial-solution test; consistent with the restated rule). `--down CON-0142`, `--down CON-0138`: §9.7, §9.6 (both corrected here); the rest via citation chains to §6.5, §6.6, §5.7, §8.x, not using the withdrawn clauses (checked by grep for "kernel-defined", "authoritative, versioned home", "uniqueness"). The draft of theory turn 3 uses F26 and is updated before review.
**Next review (step 9):** the brief of theory turn 3 lists this settlement's changes as changed theory.
