# Settlement of theory turn 4 (trust: a primitive or derivable? F23), two reviewers

*2026-10-19. Reviewer A (Opus) and reviewer B (Sonnet), each with a blind step 1 and one step-2 message; neither saw the other's report. Harness time: A step 1 251 s, steps 2–3 325 s, total 576 s; B step 1 111 s, steps 2–3 333 s, total 444 s. The brief listed the C17 settlement changes (step 9).*

**Re-checked in the sources:** S0973 L297, L538, L557, L590, L828, L1001, L1727, L1794, L1843, L1851; S0913 L223, L732, L758; S0129 L96 (blank), L97, L102. All hold. **All findings of both reviewers are accepted.** No critical finding; nothing blocks.

## Comparison of the two reviews
| Point | A | B | Raised by |
|---|---|---|---|
| T1 is definitional; its content is the premise list | 3 | 3 | both |
| Hidden premise: adjudication of outcomes (A: root evaluator or prior; B: channel J); regress beside F31 | 2 | 2 | both |
| T2 overclaims; H4 splits; integrity/authenticity reduce to anchors; argument incomplete (B: the corroboration horn) | 4 | 1 | both |
| Trust in a knowledge item (S0973's artifact-level TrustAssessment) missing; more senses | 1 | 4(d), 8 | both |
| Must-do gap; the trivial case (constant f) | 7 | 5 | both |
| L5 restated / relabelled | 5 | 6 | both |
| L4 not a model; transitivity by explicit referral policy | 8 | 7 | both |
| L1 scope (trust may condition a rule act); S0973's authority input dropped from f | 6 | 4(b) | both |
| Truster arity; reliability/trust containment reversed between sources | 8 | 4(a), 4(c) | both |
| L2 scoped to the same source; L3 "by definition" and S0913's general trustworthiness | 9, 10 | 7 | both |
| Crypto and temporal limits: compromise window, backdating, no threat model, Beta model dropped | 11 | 9 | both |
| S0973 puts identity, not trust, in its kernel list | — | 9 | B only |
| T2 ↔ F33 | — | 5 | B only |
| Changed theory: S0129 anchor L97 and "existing principles" distortion | 12 | — | A only |
| Changed theory: 1.13.1-3 property 3 mis-scoped; §5.7 C4 cross-reference missing; CON-0151 register text | appendix (a)–(c) | — | A only |
| Changed theory: §8.10 C10 chain counts as one object; "silently"; §8.10 C2 Int an analogy; §9.9.5 omits C5's and C10's caveats | — | 10–12 | B only |

*Count:* 11 points raised by both, 2 by A only (one with three sub-items), 3 by B only.

## Measures
| Measure | Value |
|---|---|
| New concepts | 2 (CON-0160, CON-0161) |
| Findings | A: 12 (0 critical, 7 major, 5 minor); B: 12 (0 critical, 6 major, 6 minor) |
| INVALID share | 1 of 2 concepts INVALID as stated (CON-0161); result 1 ("two senses") and L5 INVALID as stated |
| Material omissions | 4 (T-c; the adjudication premise; the must-do; crypto limits) |
| Changed theory (C17) | A: VALIDATED as answers except finding 12 and three appendix items; B: three minor items |
| Reviewer time | A 576 s; B 444 s |

## Changes
- §9.9 rewritten: three senses and further uses; T1 a conditional, definitional reduction with premises (v) faithful outcome records and (vi) adjudication, the regress fork F34, f unconstrained, the Beta model dropped, T1's narrowing of S0973 stated; L1–L5 rescoped and relabelled, L4 with two models, L5 as independence of the evaluator; the trivial-case test and must-do candidates (Hyp); T2 split into integrity, authenticity and occurrence, with the corroboration horn closed by §8.6 C1 and §8.10 C7, refutability, and "an outside act names a root"; T-c as a Hyp; M-T1 marked trivial; S0973's kernel list; the LR condition as an open note; §9.9.11 lists what did not hold.
- Register: CON-0160 restated; CON-0161 INVALID as stated, restated; CON-0151's meaning column aligned (C17 finding 8).
- Map: F23 row updated; F34; the TT4 block. BOOK-OUTLINE: the Trust row.
- Changed theory: §9.8 M5 and result 6, §1.13.2-3 (S0129 L97; "existing principles"); §1.13.1-3 (property 3's scope); §5.7 C4 (cross-reference to §8.10 C10); §8.10 C10 (two chains, possibly different objects; not "silently"); §8.10 C2 (an analogy, with act and recording times).

**Settlement self-check:** quote check §9.9 12/0, §9.8 16/0, §1.13 6/0, §8.10 29/0, §5.7 23/0; `integration_check.py` on §1.13: tags 0, INVALID 0.
**Dependency graph (§12d):** CON-0161 → §9.9 only; CON-0160 → §9.9; CON-0151 → §8.9, §8.10 (consistent with the restated text).
**For the Principal (not blocking):** F34 (the root of source assessments); whether the kernel should state the occurrence part of trust in the record as an explicit axiom beside A1.
