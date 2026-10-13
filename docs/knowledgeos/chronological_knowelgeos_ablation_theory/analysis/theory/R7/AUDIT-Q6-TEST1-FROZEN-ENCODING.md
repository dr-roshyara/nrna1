# R7 · Audit Q6 test 1: independent re-encoding and frozen row encoding

**Analysis only; decides nothing; draft r1, 2026-10-13.** r1 applies the review of r0 (PASS_WITH_LIMITATIONS: every number reproduced, the interpretation overclaimed); the changes are listed in §10. This is RESEARCH-AUDIT-1 r1.1 §2 Q6 test 1 ("reconcile the independent encodings; freeze one encoding"). It adopts, amends, ratifies, selects and authorizes nothing. The frozen encoding proposed here is an **analyst proposal [E]**. It becomes a reference only by a human act. The K-c criterion, its rules 1–5 and 2a, and the choice among S, S′ and S″ stay unratified and unselected (E2b §A2.2; F-LOG-0271).

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Reproduction:** `R7/AUDIT-Q6-TEST1-scripts/blind_enc.py`. Run `python3 blind_enc.py` from that folder (it reads `../RESEARCH-AUDIT-1-scripts/enc.py` and `q2r1.py`); runtime is about 12 s. Part 1 of the script is the Phase A blind encoding. Part 2 holds the comparison, the frozen encoding FZ, the variants and every number below.

## 0. Summary

**What this test can and cannot show.** FZ takes its conventions from T3's K-c rules (F6–F12), and those rules produced the ratified lists. So **"the lists hold under FZ" is close to a tautology:** the match is expected if the rules are applied correctly. The test shows two narrower things:
- T3's rules **re-apply consistently at concept level**, row by row, when applied independently to the T2 text;
- the **reading errors** found in the four encodings do not move any host.

It does **not** show that the lists are correct readings of T2. That would need an encoder who has not seen T3 or the lists (§7).

| question | result |
|---|---|
| Disagreements among the four encodings (A0, A1, RV, BL), measured against FZ | 62 of 83 rows differ in at least one encoder. **184 row × encoder instances, classified (a) 68, (b) 103, (c) 13.** These are **descriptive judgements made with the targets in view**, and 53 of the (b) instances are one issue (C9 omitted for a generic act; §4). None of the (b) instances changes a P-c crossing |
| Rows whose P-c crossing status differs among A0, A1, RV and BL | 13: R12, R27, R28, R51, R52, R56, R60, R61, R62, R67, R68, R69, R80 |
| FZ: P-c crossings, rows T-A / T-A + T-B / all | S: 17 / 24 / 46. S′: 12 / 17 / 36. S″: 12 / 17 / 36. These equal T3 r1.7 §1a (c) and RESEARCH-AUDIT-1 B1 |
| Ratified host lists (E2b r1.3 §A1.3 / §A1.4; F-LOG-0290, R-a) against FZ | identical row by row under S and under S′ / S″, as expected by construction (above) |
| E3-REL (E3 r1.3 §2.3, 13 / 8) against FZ | identical |
| Alternatives that move ratified hosts | among the conventions the encoders disagreed on: the class rule off (R12), Adopted read through (R27, R28, under S), R80's missing clause read as a negation (R80). Among conventions **all encoders share**: guard rows reading nothing moves **R11, R14, R15, R16, R17, R21**, and **E3-REL drops to 9 / 4**. Named clauses not read through moves R38 (under S). The (c) reading of R80 ("acts exist" as a joint statement) moves R80 from "none" to CB23. No other (c) variant moves a host (§5, §6) |
| Blind encoding BL against the ratified lists (42 T-A + T-B rows) | S: 38 / 42 agree (κ 0.81). S′ / S″: 40 / 42 (κ 0.91). E3-REL identical. **This is good agreement between two Claude-family encoders who share T1. It is not validity** (§6a) |
| P-c's exhaustive rank on T-A + T-B (FZ) | no 9│14 cut has **strictly fewer** crossings, under FZ and under all of its variants. Exact P(≤ 17) = 1 / 817,190 under FZ (P-c is the only cut at 17). Being the **unique** minimum depends on the mapping of low-volume concepts: ties are 1–2 across FZ's variants, and 21 under BL's value mapping, where 6 cuts are strictly lower (§5.3) |
| Act needed? | **None.** Freezing FZ is **not** offered as a standalone act, because it would ratify the K-c rules by the back door. Preconditions for any freeze are in §7 |

## 1. Procedure and blindness record

- **Phase A ("blind by assertion").** BL was made by a fresh subagent instance (this one). It was instructed to read only T2 r1.4 (rows R1–R83), T1 r1.6 (C1–C28) and FORMAL-THEORY-R4 r3.1 (Defs 1.1–1.11, 3.1, 3.2, 4.9, 5.1, Lem 1.12), and not to open the excluded files. **The shared session scratchpad held `enc.py`, copies of T3 and act files at the time**, so the excluded material was technically reachable. Blindness was by instruction only and **cannot be verified**. BL is therefore **independent by assertion**, not by isolation. The encoding and its conventions Q1–Q11 were written before the instance opened any comparison source. The snapshot has sha256 `0a8d1f40f6de96b8ab7bfe02e37b428ce4fb3d92ee25694b70cf2fa19d58b89c`, which proves the content but not the timing. Part 1 of the script is that file minus its `__main__` block. Two edits were made inside Phase A, before the snapshot: the omitted row R81 was added, and a tuple-unpack bug was fixed.
- **Phase B.** Read RESEARCH-AUDIT-1 r1.1 (§0–§2 Q2, Q6, §3, §4, Appendices A–C), `RESEARCH-AUDIT-1-scripts/enc.py` (A0 = `aud`, RV = `mine`) and `q2r1.py` (A1 = `audc`). To know the K-c method that produced the ratified lists, T3 r1.9 §1a (rules 1–5 and 2a, the crossing table, and the VP12 re-check (a)–(g)) was also read.
- **After Phase A, before FZ was written.** Read E2b r1.3 §A1.3, §A1.4, §A1.9a and §A2.2, and E3 r1.3 §2.3 and §2.4. **The FZ author therefore knew the ratified lists and T3's crossing table when fixing FZ.** FZ's conventions were taken from T3's stated rules, and its row content from the Phase B disagreement analysis. The host lists were then compared mechanically. The comparison in §6 is **not blind to the target**; only Phase A (BL) was blind.
- **Validation of the machinery.** Run on the audit's own encodings, the script reproduces RESEARCH-AUDIT-1 exactly:
  - totals 46 / 36 / 36 for A0, A1 and RV;
  - exhaustive T-A + T-B rank 1 / 8, 0 / 3, 0 / 1 (strictly fewer / equal, including P-c);
  - T-A rank 228, 92, 9;
  - null means 32.0, 34.7, 33.6.

## 2. Conventions

### 2.1 Phase A conventions (BL, blind; recorded verbatim in the script)

| id | BL convention |
|---|---|
| Q1 | encode the statement column only |
| Q2 | a named record → C13; r → C14, c → C15, k → C16 (+actor), Ref → C17, con / mode → C18 |
| Q3 | issue act → C8, adoption act → C9, generic act / E → C8 + C9; **prep → C8**; d → C11; f → C12 |
| Q4 | **defined predicates read through** to their definition (Adopted, Chg, conforms, independent, performatively ambiguous, absence / not-evidenced), keeping their own C-row; named Def 3.1 clauses replaced by their text |
| Q5 | **class membership (H-D, H-B, R_B) is not expanded** unless the row names the clauses |
| Q6 | actor flags α, α_W, k, Cap, 𝒜. Every actor flag reads C1, and **Cap names an actor** |
| Q7 | **a row that is itself a negation reads the negated constraint's concepts**; a contrast clause inside a positive statement is not read |
| Q8 | τ → C3; **v, V, P → C4**; σ / "in force" → C6; C5 only where the norm is spoken of as an entity |
| Q9 | an attribute named without its bearer being quantified does not read the bearer |
| Q10 | **a reference to one other row reads that row**; set references and parenthetical references are not read |
| Q11 | C22 for rows about the distinctness of governance times; C26 for an additional observable; C28 for evidential limits |

### 2.2 Frozen conventions (FZ) and why each was chosen

Principle: where T3 §1a states a rule, FZ follows it, because the ratified lists are outputs of that rule set and a frozen encoding that silently changed the method would change the lists by stealth. Where T3 is silent, FZ takes the most literal reading of the T2 statement text. The BL alternatives are kept as visible sensitivity runs (§5.2).

| id | FZ convention | source / reason | replaces |
|---|---|---|---|
| F1 | encode the T2 r1.4 "statement (working label)" cell, including the clauses it names | T3 rule 1; all four encoders agree | — |
| F2 | coordinate → concept per RESEARCH-AUDIT-1 A.0: **v → C5**, V / P → C4, **prep and Adopted → C10**, Chg → C6 + C7, O → C13 only | A.0 shared vocabulary; T3 rule 2. **No effect on any named cut** (P-a, P-b, P-c, P-d, the sort cuts), because C4 / C5 and C8 / C10 are on the same side in each. **It does affect the exhaustive rank** (§5.3): the mapping decides which concepts are low-volume and can be moved at no cost | BL Q3 (prep), Q8 (v) |
| F3 | C13 whenever a record is named (ρ, "record(s)", "corpus record"); an attribute alone (r, c, "text") does not add C13 | A1 K1 | — |
| F4 | generic "act", E, a ∈ E, con(a) → C8 + C9; "issue act" → C8; "adoption act" → C9 | A1 K3. Reading "a ∈ E" as an issue act only misreads Def 1.4 | A0 and RV practice |
| F5 | "conforms" / "violates" / B-conf → {C3, C4, C6, C19, C20, C21} | A1 K2; Def 1.10 | — |
| F6 | **named Def 3.1 clauses are read through** (D-chg, D-st, D-rec, B-chg, B-conf, B-recW, B-rec) | T3 rule 1 | — |
| F7 | **class rule:** a row that asserts or uses membership of H-D, H-B or R_B, or separation between them, reads the 𝐑₀ clause union CL (actor flag N). It does not apply to T-B rows | T3 rule 3; A1 K4. The ratified MW1 host of R12 rests on it | BL Q5 |
| F8 | **derived predicates named as concepts count at their own placement:** Adopted → C10, Cap → C2, independent → C25, absence → C23, ambiguity → C27, without reading them through | T3 rule 2 [r1.7]; T3 §1a (e) discloses the alternatives | BL Q4 |
| F9 | **relational clause:** a row that reads Ref or con also reads the acts / work events they relate (unspecified: E ∪ W). Applied uniformly | T3 rule 2. FZ applies it also to R15 and R72, which T3's table did not | — |
| F10 | actor flag only where the text names an actor (𝒜, α, α_W, k, keeper, norm-maker, executor, role or office). **Cap named alone does not name an actor.** Flags: N (act / work actor or Cap holder), R (keeper), B (both), U (unlocated) | T3 rule 2 [r1.7] and rule 2a S″ locations | BL Q6 |
| F11 | negations: a contrast clause ("with no Cap condition", "not an absence in capture") is not read; a guard row reads the items whose tie or order it denies; **a T-B row that states no clause (R80) reads nothing** | T3 rules 1 and 5. R80 is the *absence* of H-B's record clause, not a guard denying a tie. Reading the absent clause would make "H-B has no record clause" count as a record crossing, the opposite of what it states. **Disclosed asymmetry:** F11 treats denials unequally. Guard rows (R11, R14–R17, R21) read the items whose tie they deny, while R80 reads nothing. Both alternatives are run: SH (guard rows read nothing) and SF (R80's negated clause read), §5.2 | BL Q7 for R80 |
| F12 | row references are not read through (R71, R73, R75, R76, R77, R78) | T3 rule 4 | BL Q10 |
| F13 | C22, C25, C26, C28 are unplaced (rule 5). C22 is recorded for R14 and R15 | T3 rule 5; T1 C22. No effect | — |
| F14 | spanning rules S / S′ / S″ as in T3 rule 2a, implemented exactly as `enc.py: cross()` (S: any actor flag → both sides; S″: N → CB23, R → CB1, B → both, U → none) | T3 rule 2a; not selected | — |

## 3. The frozen encoding FZ (83 rows)

Concept ids are T1 C-ids. C1 is carried by the actor column. CL is the 𝐑₀ clause union {2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21}. "CB1" and "CB23" mean internal to that side; "none" means no placed item is read.

| row | tier | concepts read (C-ids, C1 excluded) | actor | P-c S | P-c S′ | P-c S″ |
|---|---|---|---|---|---|---|
| R1 | T-A | 3, 5, 6, 8, 12 | – | CB23 | CB23 | CB23 |
| R2 | T-A | 8, 9, 13, 17, 18 | – | crosses | crosses | crosses |
| R3 | T-A | 8, 9, 11, 13, 14, 18 | – | crosses | crosses | crosses |
| R4 | T-A | 8, 9 | – | CB23 | CB23 | CB23 |
| R5 | T-A | 3, 4, 5, 8, 9, 10, 11, 12 | N | crosses | CB23 | CB23 |
| R6 | T-A | 3, 19, 20 | N | crosses | CB23 | CB23 |
| R7 | T-A | 13, 14, 15 | – | CB1 | CB1 | CB1 |
| R8 | T-A | 13, 16 | R | crosses | CB1 | CB1 |
| R9 | T-A | 8, 9, 13, 17, 18, 19 | – | crosses | crosses | crosses |
| R10 | T-A | 2 | N | crosses | CB23 | CB23 |
| R11 | T-A | 3, 4, 5, 6, 8, 9, 12 | – | CB23 | CB23 | CB23 |
| R12 | T-A | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21 (class rule, F7) | N | crosses | crosses | crosses |
| R13 | T-A | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21 (named clauses, F6) | N | crosses | crosses | crosses |
| R14 | T-A | 11, 12, 14, 22 | – | crosses | crosses | crosses |
| R15 | T-A | 8, 9, 11, 12, 14, 18, 20, 22 | – | crosses | crosses | crosses |
| R16 | T-A | 8, 9, 13, 17, 19, 23 | – | crosses | crosses | crosses |
| R17 | T-A | 5, 6, 8, 9, 19 | – | CB23 | CB23 | CB23 |
| R18 | T-A | 3, 4, 6, 19, 20, 21 | – | CB23 | CB23 | CB23 |
| R19 | T-A | 2, 8, 9, 10, 11 | N | crosses | CB23 | CB23 |
| R20 | T-A | 3, 6, 7 | – | CB23 | CB23 | CB23 |
| R21 | T-A | 6, 12, 15 | – | crosses | crosses | crosses |
| R22 | T-A | 8, 9, 13, 19 | U | crosses | crosses | crosses |
| R23 | T-A | 2, 3, 5, 6, 8, 9, 10, 11, 12, 13, 16, 17, 18, 19, 20 | B | crosses | crosses | crosses |
| R24 | T-A | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 18, 19, 20, 21, 27 (class rule, F7) | N | crosses | crosses | crosses |
| R25 | T-B | 2, 3, 5, 6, 7, 8, 12 | N | crosses | CB23 | CB23 |
| R26 | T-B | 3, 5, 6, 7, 8, 12 | – | CB23 | CB23 | CB23 |
| R27 | T-B | 8, 10, 12 | – | CB23 | CB23 | CB23 |
| R28 | T-B | 8, 10, 12 | – | CB23 | CB23 | CB23 |
| R29 | T-B | 8, 9, 13, 17 | – | crosses | crosses | crosses |
| R30 | T-B | 3, 5, 6, 7, 8, 12, 19, 20 | – | CB23 | CB23 | CB23 |
| R31 | T-B | 3, 5, 6, 7, 8, 12, 19, 20 | – | CB23 | CB23 | CB23 |
| R32 | T-B | 3, 4, 6, 19, 20, 21 | – | CB23 | CB23 | CB23 |
| R33 | T-B | 3, 4, 6, 19, 20, 21 | – | CB23 | CB23 | CB23 |
| R34 | T-B | 3, 4, 6, 19, 20, 21 | – | CB23 | CB23 | CB23 |
| R35 | T-B | 13, 17, 19 | – | crosses | crosses | crosses |
| R36 | T-B | 8, 9, 13, 17, 19 | – | crosses | crosses | crosses |
| R37 | T-B | 5, 6, 7, 8, 12, 19, 20 | – | CB23 | CB23 | CB23 |
| R38 | T-B | 2, 3, 5, 6, 7, 8, 12 | N | crosses | CB23 | CB23 |
| R39 | T-B | 8, 9, 13, 18 | – | crosses | crosses | crosses |
| R79 | T-B | 8, 9 | – | CB23 | CB23 | CB23 |
| R80 | T-B | ∅ | – | none | none | none |
| R83 | T-B | 8, 9, 13, 17, 19, 23 | – | crosses | crosses | crosses |
| R40 | T-C | 12, 13, 14 | – | crosses | crosses | crosses |
| R41 | T-C | 8, 9, 13, 17, 19, 23 | – | crosses | crosses | crosses |
| R42 | T-C | 11, 14 | – | crosses | crosses | crosses |
| R43 | T-C | 11, 12, 14 | – | crosses | crosses | crosses |
| R44 | T-C | 8, 9, 13, 18 | – | crosses | crosses | crosses |
| R45 | T-C | 8, 9, 13, 18 | – | crosses | crosses | crosses |
| R46 | T-C | 6, 15 | – | crosses | crosses | crosses |
| R47 | T-C | 6, 8, 9 | – | CB23 | CB23 | CB23 |
| R48 | T-C | 4, 6, 19 | – | CB23 | CB23 | CB23 |
| R49 | T-C | 8, 9, 13, 19, 23 | – | crosses | crosses | crosses |
| R50 | T-C | 6, 7, 19 | – | CB23 | CB23 | CB23 |
| R51 | T-C | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21 (class rule, F7) | N | crosses | crosses | crosses |
| R52 | T-C | 8, 9, 15, 18, 27 | – | crosses | crosses | crosses |
| R53 | T-C | ∅ | – | none | none | none |
| R54 | T-C | 5 | – | CB23 | CB23 | CB23 |
| R55 | T-C | 8, 9 | – | CB23 | CB23 | CB23 |
| R56 | T-C | 2 | – | CB23 | CB23 | CB23 |
| R57 | T-C | ∅ | – | none | none | none |
| R58 | T-C | 5, 8, 9 | – | CB23 | CB23 | CB23 |
| R59 | T-C | 13, 28 | – | CB1 (touches CB4) | CB1 (touches CB4) | CB1 (touches CB4) |
| R60 | T-C | 2 | – | CB23 | CB23 | CB23 |
| R61 | T-C | 2, 3, 5, 6, 7, 8, 10, 12 | – | CB23 | CB23 | CB23 |
| R62 | T-C | 13, 25, 26 | – | CB1 (touches CB4) | CB1 (touches CB4) | CB1 (touches CB4) |
| R63 | T-C | 8, 9 | N | crosses | CB23 | CB23 |
| R81 | T-C | 6, 7, 19 | – | CB23 | CB23 | CB23 |
| R64 | T-D | 5 | – | CB23 | CB23 | CB23 |
| R65 | T-D | 8, 9 | – | CB23 | CB23 | CB23 |
| R66 | T-D | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 16, 17, 18, 19, 20, 21, 26 (class rule, F7) | B | crosses | crosses | crosses |
| R67 | T-D | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21, 26 (class rule, F7) | N | crosses | crosses | crosses |
| R68 | T-D | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21, 26 (class rule, F7) | N | crosses | crosses | crosses |
| R69 | T-D | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 19, 20, 21, 26 (class rule, F7) | N | crosses | crosses | crosses |
| R70 | T-D | 3, 8, 9, 13, 16, 17, 18, 25 | B | crosses | crosses | crosses |
| R71 | T-D | 2, 3, 5, 6, 7, 8, 12, 19, 20 | N | crosses | CB23 | CB23 |
| R72 | T-D | 2, 6, 8, 9, 11, 12, 16, 17, 18, 19, 20 | B | crosses | crosses | crosses |
| R73 | T-D | 3 | – | CB23 | CB23 | CB23 |
| R74 | T-D | 28 | – | none (touches CB4) | none (touches CB4) | none (touches CB4) |
| R75 | T-D | ∅ | – | none | none | none |
| R76 | T-D | 2 | U | crosses | CB23 | CB23 |
| R77 | T-D | 8, 9, 13 | – | crosses | crosses | crosses |
| R78 | T-D | 8, 9, 13, 15, 18 | – | crosses | crosses | crosses |
| R82 | T-D | 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 17, 18, 19, 20, 21 (class rule, F7) | N | crosses | crosses | crosses |

**[r1]** R11 now reads C4 (σ : T → V^𝒯; review F9). **Borderline noted (F9):** R70's "executor on τ" refers to the actor of a work event on τ. Under F10 / Q9, FZ does not read C19 for it. Reading C19 would change no P-c result (C19 is on CB23, and R70 crosses anyway). R72's α_W is treated the same way.

FZ differs from A1 in these rows, none of which changes any P-c crossing:
- R11, R14 (C22 only), R15, R17, R20, R23, R37, R48, R50, R52 (+C27), R58, R61, R63, R70, R72, R73, R81, R83, R41, R78;
- the flags of R23 and R72 (→ B).

## 4. Disagreement log (Phase B)

**Counting unit:** one instance = (row, encoder, issue cluster), measured against FZ. Across 62 rows there are **184 instances: (a) 68, (b) 103, (c) 13.** By encoder:

| encoder | (a) | (b) | (c) |
|---|---|---|---|
| A0 | 6 | 52 | 2 |
| A1 | 6 | 15 | 2 |
| RV | 10 | 36 | 6 |
| BL | 46 | 0 | 3 |

How to read these counts:
- **They are descriptive judgements, not measurements.** The classification was made by the FZ author with the ratified lists and T3 in view (§1).
- **53 of the 103 (b) instances are a single issue:** C9 omitted where the statement reads a generic act (b1).
- BL's "(b) = 0" partly reflects construction: FZ was made by the same instance as BL (§8).
- A0 and A1 share most items, because A1 is A0 corrected.
- **Classification inconsistency (disclosed):** the same question, whether text in the statement cell beyond the working label is part of the statement, is classified (b) for R83 (the stated condition, b5) and (c) for R41 (the ground sentence, c6). Treating both as (c) gives (b) 101 and (c) 15. Treating both as (b) gives (b) 106 and (c) 10. No result depends on it (VC6).

### 4.1 (a) Convention differences (68): resolved in FZ by F1–F14

| # | issue | instances (encoder: rows) | FZ resolution and reason | P-c effect |
|---|---|---|---|---|
| a1 | v → C4 (BL) vs C5 | BL: R1, R5, R11, R13, R23, R25, R26, R30, R31, R37, R38, R61, R71 (13) | C5 (F2; A.0 shared vocabulary) | none (same side in every tested cut) |
| a2 | prep → C8 (BL) vs C10 | BL: R5, R23 (2) | C10 (F2; T3 rule 2) | none |
| a3 | "norm value" / "authorization" mapped to σ, V or v | A0, A1, RV, BL: R17; A0, A1, RV: R48 (7) | read both σ and the value: R17 {5, 6}; R48 {4, 6} (literal) | none |
| a4 | class rule (BL Q5: off) | BL: R12, R24, R51, R66, R67, R68, R69, R82 (8) | rule on (F7; T3 rule 3) | **yes:** R12, R51, R67, R69 (and R68 under S′ / S″) do not cross without it; T3 discloses this (total 41 without it under S) |
| a5 | Adopted read through (BL Q4) | BL: R13, R27, R28, R61, R68 (5) | own placement C10 (F8; T3 rule 2) | **yes, under S only:** R27 and R28 cross; this is T3 §1a (e)'s disclosed borderline |
| a6 | Cap names an actor (BL Q6) | BL: R56, R60, R61, R76 (flag) (4) | no (F10) | **yes, under S only:** R56, R60, R61 cross (T3 §1a (e)); R76 S″ unaffected |
| a7 | "independent" read through Def 4.9 | BL: R62, R70 (2) | own placement C25 (F8) | **yes:** R62 crosses (T3 §1a (e)) |
| a8 | absence (C23) read through to Ref | BL: R49 (1) | own placement (F8) | none |
| a9 | Chg reads τ when not spelled out | BL: R37 (1) | C3 only where τ is spelled out (R20) | none |
| a10 | O → C13 only, or C13 + C14 + C15 | RV: R23; BL: R23, R24, R82 (4) | C13 (F2; T3 rule 2) | none |
| a11 | relational clause (Ref / con → related items) | RV, BL: R52; A0, A1, RV, BL: R15, R72 (10) | applied uniformly (F9) | **yes:** R52 crosses only through it (T3 K2 sensitivity) |
| a12 | R80: the negated clause read (BL Q7) | BL (1) | reads nothing (F11) | **yes:** R80 would cross |
| a13 | single row reference read through (BL Q10) | BL: R73 (1) | not read through (F12; T3 rule 4) | none |
| a14 | an attribute reads its bearer | RV: R42 (+C8) (1) | no (Q9) | none |
| a15 | "text" alone adds a record (C13) | RV, BL: R52 (2) | no (F3, K1) | none |
| a16 | C22 recorded for the time guards | A0, A1, RV: R14, R15 (6) | recorded (F13) | none (unplaced) |

### 4.2 (b) Reading errors by one encoder (103): corrected in FZ

| # | error | instances | why it is an error | P-c effect |
|---|---|---|---|---|
| b1 | a generic act (a ∈ E, "act", con(a)) read as an issue act only | A0: 30 rows (the 25 "+9 only" rows of A.2, plus R2, R3, R58, R66, R82); A1: R58; RV: 22 rows (R2, R3, R11, R12, R13, R24, R29, R36, R39, R44, R45, R47, R49, R51, R58, R67, R68, R69, R70, R78, R79, R82) (53) | Def 1.4: E = E^iss ⊔ E^adp. Both encoders use {8, 9} elsewhere (A0: R4, R9; RV: R16, R17, R22, R23, R41, R66, R77, R83), so each is internally inconsistent | none (C9 is on CB23 with C8) |
| b2 | named record without C13 | A0: R2, R3, R40 (3) | disclosed in RESEARCH-AUDIT-1 §0 item 10 | none |
| b3 | "conforms" not read through, inconsistently | A0: R33, R34; RV: R33, R34 and the B-conf part of its CL (R12, R13, R24, R51, R66, R68, R69, R82) (12) | each encoder reads it through elsewhere (R32) | none |
| b4 | class rule / flag misapplied | A0: R24 (rule omitted), R66 (N, not B), R82 (no flag) (3) | disclosed (§0 item 10) | none |
| b5 | an item explicit in the statement omitted, or read through a row reference | R11 τ, v, f "σ(f(a))(τ(a)) = v(a)" (A0, A1; RV uses C4 for v only here); R15 con "except d = r under constitution" (A0, A1); R20 τ "(t, τ)" (A0, A1); R23 "their attributes" τ, v, prep, t(w) and the keeper flag B (A0, A1, RV); R37 "with its value" (A0, A1); R50, R81 σ in "norm change(s)" (A0, A1; Chg → C6, C7 as A0 itself does in R20); R52 C27 "performative ambiguity" (A0, A1); R61 the act-disjunct text of the named B-chg (A0, A1); R63 "the ARB Chief issues" → C8 (A0, A1); R70 "act on τ" → C3 and "referenced records" → C17 (A0, A1, RV); R72 keeper with actors → flag B (A0, A1, RV); R73 C7 taken from R31 (A0, A1; contrary to rule 4); R83 the stated condition → C23 (A0, A1) (32) | the statement text names the item | none |

### 4.3 (c) Genuine ambiguities in the T2 text (13): kept as variants

| # | row and phrase | readings | instances | variant | P-c effect |
|---|---|---|---|---|---|
| c1 | R62 "certification of a corpus record as independent **for a target**" | part of C25's label (FZ) / τ → C3 (RV) | RV (1) | VC1 | **yes:** +1 crossing (T-C), all rules; this is T3's K6 borderline |
| c2 | R46 "self-asserted **force**" | σ (FZ) / f (RV) | RV (1) | VC2 | none |
| c3 | R61 "B-chg's act disjunct also requires …" | the act disjunct only (FZ) / the whole R-k clause, including the work disjunct (RV, BL) | 2 | VC3 | none |
| c4 | R58 "transition rules for **norms** or acts" | norm (C5, FZ) / norm state (C6: RV, BL) | 2 | VC4 | none |
| c5 | R63 "only the Decision Authority may adopt" | role only (FZ) / a capability constraint, + C2 (BL) | 1 | VC5 | none |
| c6 | R41: is the ground sentence ("would license inferring non-occurrence from absence") part of the statement? | yes, + C23 (FZ, BL) / no (A0, A1, RV) | 3 | VC6 | none (C23 with C13 on CB1) |
| c7 | R78 "for **real texts**" | + C15 (FZ, BL) / not read (A0, A1, RV) | 3 | (by inspection) | none (CB1; the row crosses anyway) |
| c9 | R80 "'acts exist' is read as a joint statement about both hypotheses" | reads nothing (FZ, T3) / reads acts, C8 + C9 (review) | — (no encoder used it) | VC8 | **no crossing change, but a ratified host cell changes:** R80 becomes internal to CB23 (ratified: "none"), under every rule |
| c8 | R24's host (D12): "hosted at CV4" vs "hosted on both" | not an encoding question. R24 crosses under every encoding, rule and variant, so the encoding is consistent with both D12 readings | — | — | E3-REL 13 / 8 (CV4) or 14 / 9 (both); D12 stays open item (g) |

### 4.4 A correction to RESEARCH-AUDIT-1 (analysis only)

**A.3 only.** RESEARCH-AUDIT-1 A.3 says "A1 and RV agree on every P-c crossing". **That is false for the crossing sets.**
- RV crosses at **R62** (c1) and not at **R52** (a11). A1 does the reverse.
- The differences cancel, so the totals agree (46 / 36 / 36).
- Both rows are T-C, so no T-A or T-B host is affected.

B1 ("A1 and RV: identical to A0") is a statement about counts, and as a count statement it is true.

The A1 residual list (Z6) should be extended: see §4.2 b5, R11's C4 (F9), and F9's relational clause for R15 and R72. No reported figure changes, except that the A1 tie count on T-A + T-B (3) drops to 1 under FZ, as `rr_k.py` already found for three of the fixes.

## 5. Recomputed numbers

All figures come from `AUDIT-Q6-TEST1-scripts/blind_enc.py` (r1). FZ includes the R11 fix.

### 5.1 P-c crossings (rows T-A / T-A + T-B / all 83)

| encoding | S | S′ | S″ |
|---|---|---|---|
| A0, A1, RV (validation) | 17 / 24 / 46 | 12 / 17 / 36 | 12 / 17 / 36 |
| BL (blind by assertion, own conventions) | 16 / 26 / 48 | 11 / 17 / 32 | 11 / 17 / 32 |
| **FZ** | **17 / 24 / 46** | **12 / 17 / 36** | **12 / 17 / 36** |
| VC1 (R62 → C3) | 17 / 24 / 47 | 12 / 17 / 37 | 12 / 17 / 37 |
| VC2–VC6, VC8 | as FZ | as FZ | as FZ |

**S vs S′ row set.**
- FZ: **10** rows (R5, R6, R8, R10, R19, R25, R38, R63, R71, R76), as RESEARCH-AUDIT-1 B2.
- With named clauses not read through (SI): **8**. R38 and R71 drop out.
- With BL's full conventions: **16**.
- So the range is **8–16**, as RESEARCH-AUDIT-1 states. r0's "narrows to 10–16" is withdrawn.

### 5.2 Convention alternatives applied to FZ (sensitivity)

| alternative | conventions the encoders disagreed on? | S | S′ / S″ | ratified host cells that change |
|---|---|---|---|---|
| SA: Adopted read through | yes (BL) | 17 / 26 / 49 | 12 / 17 / 36 | R27, R28 cross under S (ratified CB23) |
| SB: Cap names an actor | yes (BL) | 17 / 24 / 49 | 12 / 17 / 36 | none (T-C rows only) |
| SC: "independent" read through | yes (BL) | 17 / 24 / 47 | 12 / 17 / 37 | none (T-C) |
| SD: class rule off | yes (BL) | 16 / 23 / 42 | 11 / 16 / 31 | R12 internal to CB23 (ratified crossing, MW1) |
| SE: relational clause off | yes (RV, BL in R52) | 17 / 24 / 44 | 12 / 17 / 34 | none (T-C) |
| SF: R80's negated clause read | yes (BL) | 17 / 25 / 47 | 12 / 18 / 37 | R80 crosses (ratified none) |
| SG: R24 without the class rule | yes (A0, BL) | as FZ | as FZ | none |
| **SH: guard rows read nothing** (R11, R14–R17, R21) | **no: all encoders share the guard convention** | **13 / 20 / 42** | **8 / 13 / 32** | **R14, R15, R16, R21** (ratified crossing) → none; **R11, R17** (ratified CB23) → none. **E3-REL becomes 9 / 4** (S: R2, R3, R5, R6, R8, R9, R10, R19, R22; S′ / S″: R2, R3, R9, R22) |
| **SI: named clauses not read through** (R38, R71 read only Chg) | no (shared) | 17 / 23 / 44 | 12 / 17 / 36 | R38 internal to CB23 under S (ratified crossing); S′ / S″ unchanged |

### 5.3 P-c's exhaustive rank among the 817,190 cuts of size 9│14

Placed concepts only (23); C1 is not counted. The **exact** P is (cuts with ≤ P-c's crossings) / 817,190. The Monte Carlo null (8,000 draws, seed 20261013) is kept for its mean only.

| encoding | T-A + T-B: P-c / strictly fewer / equal (incl. P-c) / exact P(≤) / null mean | T-A: P-c / strictly fewer / equal / exact P(≤) |
|---|---|---|
| A0 (validation) | 17 / 1 / 8 / 9 / 817,190 / 32.0 | 12 / 228 / 1254 / 1482 / 817,190 |
| A1 (validation) | 17 / 0 / 3 / 3 / 817,190 / 34.7 | 12 / 92 / 520 / 612 / 817,190 |
| RV (validation) | 17 / 0 / 1 / 1 / 817,190 / 33.6 | 12 / 9 / 78 / 87 / 817,190 |
| BL | 17 / **6** / 21 / 27 / 817,190 / 36.1 | 11 / 40 / 232 / 272 / 817,190 |
| **FZ** | **17 / 0 / 1 / 1 / 817,190 / 35.4** | **12 / 1 / 31 / 32 / 817,190** |
| VC1–VC5, VC8, SA, SB, SC, SE, SG | 17 / 0 / 1 | 12 / 1 / 31 (SG: 12 / 2 / 82) |
| VC6 (no C23 in R41, R83) | 17 / 0 / 2 | 12 / 1 / 31 |
| SD (class rule off) | 16 / 0 / 1 | 11 / 2 / 82 |
| SF (R80 read) | 18 / 0 / 2 | 12 / 1 / 31 |
| SH (guard rows read nothing) | 13 / 0 / 1 | 8 / 0 / 41 |
| SI (named clauses not read through) | 17 / 0 / 2 | 12 / 1 / 31 |

**Reading.**
- **Under FZ and every FZ variant, no 9│14 cut has strictly fewer T-A + T-B crossings than P-c.** Under FZ, exact P(≤ 17) = 1 / 817,190.
- **"Unique" minimum is not robust.** Ties are 1–2 across FZ's variants. Under BL, 6 cuts are strictly lower and 21 tie. Those cuts move C5 and C10 (sometimes C2, C11) into the records side. Under BL's mapping (v → C4, prep → C8) these concepts are almost unread, so moving them costs nothing. Restoring F2's value mapping alone in BL gives 0 strictly fewer and 3 ties.
- **The R11 fix alone** (one CB23-internal row gaining C4) changes the FZ T-A rank from 9 / 56 (r0) to 1 / 31. Rank figures are sensitive to the content of single internal rows, even where no named cut changes.
- On T-A alone, P-c is not minimal under any encoding except SH, which has 0 strictly fewer but 41 ties. This is consistent with RESEARCH-AUDIT-1 Z4.

## 6. Host-list comparison against the ratified lists

Ratified lists: E2b r1.3 §A1.3 (T-A) and §A1.4 (T-B), ratified per reading by F-LOG-0290 (R-a). The comparison covers four categories per reading: internal CB1, crossing (on both, or at MW1 / CV4), internal CB23, none. The MW1 and CV4 designations are taken from T3 and E2b; the encoding checks only that these rows cross.

| encoding / alternative | S | S′ / S″ | E3-REL (13 / 8) |
|---|---|---|---|
| FZ | identical | identical | identical |
| VC1–VC6 | identical | identical | identical |
| VC8 (R80 "acts exist") | R80: CB23 (ratified none) | R80: CB23 | identical |
| A0, A1, RV | identical | identical | identical |
| SB, SC, SE, SG | identical | identical | identical |
| SA | R27, R28 | identical | identical |
| SD | R12 | R12 | identical |
| SF | R80 (crosses) | R80 | identical |
| **SH** | **R11, R14, R15, R16, R17, R21** | **R11, R14, R15, R16, R17, R21** | **9 / 4** |
| SI | R38 | identical | identical |
| BL | R12, R27, R28, R80 | R12, R80 | identical |

**Verdict.**
- The ratified lists are identical under FZ. **This is expected by construction (§0) and is not evidence that they are correct readings of T2.**
- What the test adds:
  - (i) T3's rules re-apply consistently at concept level;
  - (ii) no reading error (b) and no (c) variant except VC8 moves a ratified host.
- The lists depend on these conventions:
  - the class rule (R12);
  - Adopted not read through (R27, R28 under S);
  - an absent clause reads nothing (R80; and VC8 for its "acts exist" reading);
  - named clauses read through (R38 under S);
  - **the shared guard-row convention**, which carries four of the 13 / 8 E3-REL rows.

## 6a. What BL shows

BL's host categories against the ratified lists, over the 42 T-A + T-B rows:

| reading | agree | Cohen κ | disagreements |
|---|---|---|---|
| S | 38 / 42 | 0.81 | R12, R27, R28, R80 |
| S′ / S″ | 40 / 42 | 0.91 | R12, R80 |

E3-REL is identical under BL.

This is **good agreement between two Claude-family encoders who share T1's concept list** and, through it, T3's concept placement. It is **not validity**. Reasons:
- Shared family habits and the shared vocabulary would produce agreement whether or not either reads T2 correctly (Λ7).
- BL's independence is by assertion only (§1).
- BL shares the guard-row convention with T3, so the four E3-REL rows that rest on it were not independently tested.

## 7. Implications

- **Ratified lists (F-LOG-0290, R-a).** This test gives no reason to revisit them. It also gives no independent confirmation that they read T2 correctly (§0, §6). Their dependence on unratified K-c conventions, already disclosed in E2b §A2.2, is now itemized by row:
  - class rule: R12;
  - Adopted: R27, R28;
  - absent clause: R80;
  - named clauses: R38;
  - guard rows: R11, R14–R17, R21, of which R14, R15, R16 and R21 carry E3-REL.
- **Accepted E3 outputs (F-LOG-0297).** E3-REL (13 / 8) holds under FZ and every disagreed-upon convention. **It is not robust to the shared guard-row convention:** 9 / 4 without it. D12 is not decidable by encoding, so 13 / 8 against 14 / 9 stays open item (g).
- **Acts.** **No act is required.**
  - **Freezing FZ is not offered as a standalone act.** F6–F12 restate T3's K-c rules, so freezing FZ would ratify the K-c method by the back door.
  - **Preconditions before any freeze (encoding or method):**
    - (i) an encoder who has not seen T3 or the ratified lists, ideally a human or a different model family, re-encodes the 83 rows;
    - (ii) the guard-row alternative (SH) and the R80 alternatives (SF, VC8) are recorded alongside the class-rule, Adopted and named-clause alternatives;
    - (iii) the decision is taken together with the K-c method question (E2b §A2.2).
- **Analysis-only follow-ups.**
  - Correct RESEARCH-AUDIT-1 A.3 and extend Z6 (§4.4).
  - Q6 test 2 can use FZ as a working encoding, labelled as such.

## 8. Limitations

- **Λ7: Claude-family.** Every encoder (A0, A1, RV, BL) and the FZ author are Claude-family. Shared interpretive habits would not be detected.
- **Blindness by assertion.** BL's blindness was by instruction only, in a shared scratchpad that held `enc.py`, T3 copies and act files (§1). It cannot be verified.
- **The freezer is the blind encoder.** Where T3 is silent, FZ's "literal reading" was chosen by the BL author. That BL has no (b) instances is partly by construction.
- **Not blind to the target.** T3 r1.9 §1a, E2b §A1.3 / §A1.4 / §A2.2 and E3 §2.3 were read before FZ was written. The (a) / (b) / (c) classification was made with the targets in view.
- **Agreement by design.** FZ follows T3's rules, so reproducing the lists is expected (§0).
- **Counting unit.** The 184 / 68 / 103 / 13 figures depend on the chosen unit and cluster boundaries, and on one disclosed classification inconsistency (R41 / R83, §4).
- **Scope.** Only T-A and T-B hosts are ratified, so only they were compared. The MW1 and CV4 designations were taken as given.
- **Statistics.** Rank figures are exact counts over all 9│14 cuts. The null mean is descriptive. P-c was chosen post hoc (F-LOG-0265), and no multiple-comparison correction is applied (RESEARCH-AUDIT-1 Z8).
- **No new evidence on H-D vs H-B.** Standing qualifier: "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable".

## 9. Script

`AUDIT-Q6-TEST1-scripts/blind_enc.py` (r1). Part 1 is BL. Part 2 holds:
- the comparison;
- FZ, with R11 → C4 (r1);
- the variants VC1–VC6 and VC8 (r1);
- the alternatives SA–SG, with SH and SI added in r1;
- the BL κ (r1);
- the exact rank, which in r1 prints P(≤).

## 10. r1 changes (review of r0: PASS_WITH_LIMITATIONS)

| review item | change |
|---|---|
| F1 near-tautology | §0 now opens with "what this test can and cannot show". §6 verdict and §7: "lists hold under FZ" is stated as expected by construction. The test shows rule consistency at concept level and that reading errors do not move hosts, not that the lists are correct readings of T2. r0's "can be reproduced from the T2 text … do not need revisiting" is replaced by the qualified wording |
| F2 Phase A provenance | §1 and §8: BL was made by a fresh subagent instance under instruction; the shared scratchpad held `enc.py`, T3 copies and act files; blindness by instruction only, unverifiable; BL is "independent by assertion" |
| F3 shared guard-row convention | new alternative SH (§5.2, §5.3, §6): R11, R14–R17 and R21 move, and E3-REL drops to 9 / 4 (recomputed; matches the reviewer's figure). F11's asymmetric treatment of denials (R80 reads nothing, guard rows read their items) is disclosed in §2.2. "Only three conventions" is restricted to conventions the encoders disagreed on (§0) |
| F4 R80 "acts exist" reading | new (c) row c9 and variant VC8: R80 becomes internal to CB23 under every rule. A ratified host cell changes ("none"), but no crossing does |
| F5 F2 effect / rank wording | §2.2 F2: no effect on named cuts, but an effect on the exhaustive rank. §0 and §5.3: "no 9│14 cut has strictly fewer crossings under FZ and its variants". "Unique" depends on the value mapping (ties 1–2 under FZ variants after the R11 fix, 21 under BL). Exact P(≤ 17) = 1 / 817,190 replaces the Monte Carlo figure |
| F6 counts as judgements | §0 and §4: the counts are descriptive judgements made with the targets in view; 53 of the (b) instances are the C9 omission; the R41 / R83 classification inconsistency is disclosed with both alternative tallies |
| F7 named-clause alternative | SI run: R38 and R71 internal, so S vs S′ gives 8 rows. The range stays 8–16, and r0's "narrows to 10–16" is withdrawn. R38 moves a ratified S host |
| F8 RESEARCH-AUDIT-1 correction | limited to A.3. B1 is acknowledged as true as a count statement. The Z6 extension is kept |
| F9 R11, R70 | FZ R11 now reads C4 (σ : T → V^𝒯). This changes the FZ T-A rank from 9 / 56 to 1 / 31 and no host. The R70 "executor" vs C19 borderline is noted in §3 (no P-c effect) |
| "What BL shows" | new §6a: 38 / 42 (κ 0.81) under S and 40 / 42 (κ 0.91) under S′ / S″; E3-REL identical; agreement, not validity |
| Acts | §7: none required. Freezing FZ is not offered as a standalone act (it would ratify K-c by the back door). Preconditions: an encoder blind to T3 and the lists (ideally a human or another model family), the SH / SF / VC8 alternatives recorded, and a joint decision with the K-c method question |
