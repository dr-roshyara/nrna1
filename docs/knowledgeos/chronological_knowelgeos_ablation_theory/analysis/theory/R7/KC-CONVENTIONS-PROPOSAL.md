# R7 · K-c counting conventions: options, consequences and a draft Human ARB act

**PROPOSED; for a Human ARB act; draft r1.1** (2026-10-13).

**Review history.**
- r0 had a fresh independent review: PASS_WITH_LIMITATIONS. It found 7 material findings (F1–F7) and 7 minor (F8–F14), and every number reproduced.
- r1 applies all fourteen; §8 maps each finding to its fix.
- r1 had a fresh verification: PASS_WITH_LIMITATIONS, all numbers reproduced, 4 medium findings (G1–G4) and 8 minor / low (G5–G12). r1.1 applies all twelve (§8, rows G1–G12).
- **r1.1 is not re-reviewed.**

**Authority.** F-LOG-0300 (Human ARB direction, 2026-10-13): "Settle conventions first". The direction is to prepare a K-c convention proposal that gives the options and consequences for each convention. A review follows, then a human act. The conventions in scope are:
- guard rows;
- the class rule;
- reading "Adopted" through;
- named clauses;
- absent clauses (the R80 readings);
- "any others the analysis finds".

F-LOG-0300 is direction only. It does not authorize settling anything, and it does not authorize E4 or E5.

**Also read:**
- F-LOG-0271: no spanning rule is selected, and results are reported under S, S′ and S″.
- F-LOG-0290: under R-a the host lists are ratified per reading, while K-c, rules 1–5 and rule 2a stay unratified.
- F-LOG-0292: RESEARCH-AUDIT-1, including L6 and C6.
- F-LOG-0297: E3b is complete. E3-REL is accepted at 13 / 8 under (α).
- F-LOG-0299: audit Q6 test 1.

**What this proposal does not do.**
- It decides, adopts, ratifies, selects and authorizes nothing.
- It selects no spanning rule. S, S′ and S″ are reported throughout; the spanning rule is not a convention in scope here (F-LOG-0271).
- It decides no VP, and it resolves neither D12 nor C-γ.
- Analyst readings are marked [E].

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Reproduction (r1, F11).** The scripts live in `R7/KC-CONVENTIONS-scripts/`. The sha256 prefixes are those of the r1 files.

| file | sha256 prefix | role |
|---|---|---|
| `kc.py` | `a697c384f5fcc29a` | the main computation (r1: F12 fixes and F2 variants; r1.1: K13 / VC6 added, G6) |
| `kc_cat.py` | `a89b42d073edc59f` | five-category totals |
| `cvar.py` | `e1fcf7141db7dfc4` | the reviewer's C-parse variants (F2) |
| `restson.py` | `264e0a61ec7863d9` | the reviewer's "rests on" column alternative (F8) |
| `chk.py` | `b4e8a6c6830628b4` | the reviewer's guard / T-C pairing check (FZ against FZ, so **not independent**: G8) and a brute-force S rank written independently of `kc.rank` (F1, F9). Runtime about 100 s; output `chk.out` |

The outputs are in `kc.out`, `kc_cat.out`, `cvar.out`, `restson.out` and `chk.out`.
- To run: `python3 -B kc.py ..` from that folder takes about 15 s. **Use `python3 -B`** (G12), so that no `__pycache__` is written into the repository folder.
- The scripts import `R7/AUDIT-Q6-TEST1-scripts/blind_enc.py` unchanged (`03343c88c5d9773d`).
- On FZ they reproduce AUDIT-Q6-TEST1 r1: crossings 17 / 24 / 46 and 12 / 17 / 36; host lists identical to the ratified lists; S′ rank 17 / 0 / 1 on T-A + T-B and 12 / 1 / 31 on T-A.

---

## 0. Summary

| question | result |
|---|---|
| Conventions found | **14 in scope** (K1–K14, §1), plus **5 disclosed gaps** (K15–K19, §1.1). **Nine** of the in-scope conventions have alternatives that move at least one P-c crossing or host: K1–K7, K11 and K12. K8, K9, K10 and K13 move only the exhaustive rank (G6). K14 (the spanning rule) is out of scope (F-LOG-0271) |
| What can "reads" mean? (§2) | **Δ (literal semantic dependence) is unusable for K-c** for most T-A rows. A rule that holds in every history has no variable whose change can flip it, so those rows would read nothing (Proposition 1, softened). Three syntactic principles are each coherent: **M** (mention), **C** (content: the signature of the asserted formula) and **K** (contribution: the signature of the formula the row adds, prohibits or asks about) |
| Is T3's rule text one principle? | **No [E].** It mixes M and C at two clear points: **A1**, denials (the F11 asymmetry), and **A3**, typing (Ref / con read their acts, Cap does not read its actors). It is ambiguous at a third: **A2′**, whether "Chg → C6, C7" reads Chg through. The read-through of "conforms" comes from FZ F5, not from T3's text (F7) |
| Does any principle explain F11? | **Only conditionally [E].** K reproduces T3's results for guard rows and R80 **given** two inputs: T2's (unadopted, analyst) "guard form" tier label, and the empty-clause role for R80. K therefore *relabels* the F11 stipulation as a role distinction rather than removing it. For: T2 pairs each guard row with a T-C row, and reading each guard as its paired T-C formula gives T3's category for all 6 guard rows (`chk.out`; FZ against FZ, a consistency check, not independent; R15 / R42 inferred via X5; G8). Against: T2's tier column labels R14–R17 "non-constraint; guard form" (R11 and R21 "guard form" only), which favours C (G7) |
| Convention sets | **T3** (ratify rules 1–5 as written; a first-class option); **K**; **C**; **M**; M0 (boundary case only) (§2.5) |
| E3-REL (13 / 8 accepted) | T3 13 / 8. K 13 / 8. **C 9 / 4 to 11 / 6**, depending on how R15 and R14 are parsed (F2). M 13 / 8. M0 13 / 8. Under the other D12 reading each is +1 / +1 |
| Ratified host lists (E2b §A1.3 / §A1.4): row differences | T3: none. **K:** none under S′ / S″; under S, R27 and R28 move to crossing. **C (K1c parse):** R14 and R16 move to none, R17 to none, R15 to CB23 and R21 to CB1, under every reading; under S, R27 and R28 also move to crossing. **M:** R79 and R80 move to crossing, under every reading. M0: as M, plus R12 → CB23 under every reading and R38 → CB23 under S |
| P-c crossings, T-A / T-A + T-B / all; S, then S′ = S″ | T3: 17/24/46, 12/17/36. K: 17/26/53, 12/17/39. C: 13/22/49, 8/13/35 (parse variants: up to 15/24/51, 10/15/37). M: 17/26/46, 12/19/36. M0: 16/24/40, 11/18/31 |
| Exhaustive rank, T-A + T-B, S′ | No 9 │ 14 cut has strictly fewer crossings than P-c under T3, K, C, M or M0. Under the vocabulary alternatives K8 and K9, one cut does |
| Exhaustive rank under S (F9) | Under S, P-c is not the minimum. Under T3, 10 cuts are strictly fewer, and P(≤) = 88 / 817,190 ≈ 1.1 × 10⁻⁴. This qualifies F-LOG-0299 and RESEARCH-AUDIT-1 Q2 for S only. It is an **observation for the human**, not a decision input |
| H-D asymmetry rows | Under S: T3 and M give R25, R38; K and C give **R25, R27, R28, R38** (widened to D-st). Under S′ / S″, no set has a change-clause or status-clause asymmetry |
| R-i / R-i′ record-clause asymmetry | It holds under T3, K and C. **It disappears under M and M0.** That contradicts RESEARCH-AUDIT-1 F2 (high confidence) and F-LOG-0292 ("real under every convention"), whose tested conventions all shared K5 (a) (F5) |
| C-γ (F4, G1, G2) | **Entangled.** The guard-row question (K1) is in substance the C-γ question under every option. T3 §1b's grouping criterion uses the same verb "reads". Only option C with the K1c or SH parse would make guard rows cross no grouping; under C-R15ref, R15 still crosses groupings, and under C-R15ref-R14, R14 does too. Under T3, K and M no answer follows from the conventions: an explicit yes / no is needed. Deciding C-γ in KC-1 widens F-LOG-0300's listed scope |
| D12 | It does not fall out of any convention: R24 crosses in every set and under every reading |
| Recommendation [E] (§4) | **No single recommendation.** Ranking the sets needs a criterion, and that is a meta-question for the human. Single principle → M, K or C (M needs no role labels and no guard parse; K is conditional on T2's labels; C needs a parse). Fidelity to T3's rule text → M (10 of 12 conventions). Fidelity to T2's labels → C, mildly. Output preservation / minimal amendment → T3; this is offered to the human as a candidate criterion, not applied by the analyst |
| Acts (§5) | **KC-1** ratifies a set, or a per-convention selection, together with the criterion and the C-γ choice. Consequential acts, each its own: **KC-2** E2b; **KC-3** E3; **KC-4** T3; **KC-5** the asymmetry records. No spanning rule is selected; D12 is not decided; E4 and E5 are not authorized |

---

## 1. Inventory of counting conventions

**Scope.** These are the conventions that affect whether a T2 r1.4 row "reads" a T1 concept, and therefore its P-c crossing (K-c) and its host.

**How "moves" is computed.** "Moves" lists the rows whose P-c category changes when only that one convention is switched away from FZ, the frozen encoding that reproduces T3.
- The categories are X (crossing), internal CB1, internal CB23, and none.
- S′ and S″ give identical results for every row in every alternative and every set (computed).
- Moves cover all 83 rows; host effects concern T-A and T-B rows only.
- Source: `kc.out`.

| id | convention | current T3 r1.9 rule text (quoted) | alternatives | rows moved (S; S′ = S″) | ratified host cells moved | E3-REL |
|---|---|---|---|---|---|---|
| **K1** | **Guard rows** (R11, R14–R17, R21) | rule 1: "A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies. For example, R14 denies an equation between f, d (CB23) and r (CB1), so it crosses." | (a) as T3; (b) guard rows read nothing (SH); (c) guard rows read only the object-level parts of their statement (K1c; parse in §2.4 item 4) | (b): R11, R17 CB23 → none; R14, R15, R16, R21 X → none. (c): R14, R16 X → none; R17 CB23 → none; R15 X → CB23; R21 X → CB1. All readings | (b) 6 cells; (c) 5 cells; all readings | (b) 9 / 4; (c) 9 / 4 to 11 / 6 (§3.5) |
| **K2** | **Class rule** | rule 3: "a statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' 𝐑₀ clauses (Def 3.1) … The rule does **not** apply to a T-B row that states a clause or reading of a hypothesis" | (a) on; (b) off (SD) | S: R12 X → CB23; R51 X → none; R67, R69 X → CB23. S′: also R68 X → CB23 | R12, all readings | 13 / 8 |
| **K3** | **"Adopted" read through** | rule 2 [r1.7]: "A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count at their own placement and are not read through their definitions." | (a) own placement; (b) read through (SA) | S only: R27, R28, R61 CB23 → X | R27, R28 (S) | 13 / 8 |
| **K4** | **Named clauses vs row references** | rule 1: "(… including the clauses it names)"; rule 2 [r1.7]: "the text of a Def 3.1 clause that the statement names counts as read"; rule 4: "a row that refers to other T2 rows (by id or as a set) is assessed by the items its own statement reads, not through those rows"; (e): "where a statement names a Def 3.1 clause and also cites a row id, the clause name takes priority" | (a) T3; (b) neither read through (SI); (c) both read through (K4b: R71 → R25–R38; R73 → R31; R75 → the T-A set; r1, F12: R76 → R63; R77 → R4, R5, R19, R25–R29; R78 → R39) | (b): S: R38, R71 X → CB23. (c): S: R75 none → X; S′: R71 CB23 → X, R75 none → X (R76–R78 do not move) | (b) R38 (S); (c) none | 13 / 8 |
| **K5** | **Absent or negated clauses** | rule 1: "A **T-B row** is judged by its clause text only"; rule 2 [r1.7]: "Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68"; rule 5: "A row reading no placed item (an absent clause …) is "none"" | (a) not read; (b) R80's negated record clause read (SF); (c) R80's "acts exist" read (VC8); (d) contrast clauses read (R26 +C2; R30 +C2, C10; R79 +C13, C17; R68 +C23) | (b): R80 none → X. (c): R80 none → CB23. (d): R79 CB23 → X. All readings | R80 or R79 | 13 / 8 |
| **K6** | **Cap named alone** | rule 2 [r1.7], as K3 | (a) no actor; (b) actor through the typing "Cap ⊆ 𝒜 × …" (SB) | S only: R56, R60, R61 → X | none | 13 / 8 |
| **K7** | **Relational clause** | rule 2: "Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to." | (a) on; (b) off (SE) | R45, R52 X → CB1 | none | 13 / 8 |
| **K8** | **Generic act → C8 + C9** | rule 2 gives only "acts, obj, prep, Adopted → C8–C10" (a range). FZ F4 and A.0: generic act, E, a ∈ E, con(a) → C8 + C9 | (a) C8 + C9; (b) C8 only (approximate reconstruction, §6) | none (provably: C8 and C9 are on the same side) | none | 13 / 8 |
| **K9** | **Value mapping** | rule 2 does not map v, nor the act target τ(a) (it maps τ(w) with t(w) → C19, C20; G10); it maps "prep, Adopted" to "C8–C10". The A.0 / FZ F2 mapping is v → C5, τ → C3, V / P → C4, prep → C10 | (a) A.0; (b) BL: v → C4, prep → C8 (approximate) | none (provably, for every named cut) | none | 13 / 8 |
| **K10** | **"conforms" / violation read-through** | rule 2: "conformance / P → C21, C4" (own placement). **The read-through is FZ F5, taken from A1 K2, not from T3's text** (F7) | (a) through (FZ); (b) own placement: R33 → {C4, C19, C20, C21}, R34 → {C19, C21} | none (all CB23) | none | 13 / 8 |
| **K11** | **Other derived predicates** (independent C25, absence C23, ambiguity C27) | rule 2 [r1.7]; FZ F8 | (a) own placement; (b) "independent" read through (SC) | R62 CB1 → X | none | 13 / 8 |
| **K12** | **What names an actor** | rule 2 [r1.7]: "… a keeper, norm-maker or executor, or a role or office." | (a) roles and offices name an actor; (b) they do not | S only: R63, R76 X → CB23 | none | 13 / 8 |
| **K13** | **Statement scope: R41's ground sentence, R83's condition** | rule 1: "statement (working label)" column | (a) included; (b) excluded (VC6) | none (rank only: §1 table, G6) | none | 13 / 8 |
| **K14** | **Spanning rule S / S′ / S″** (rule 2a) | rule 2a, "not selected" | **Held open by F-LOG-0271; not decided here** | — | — | — |

**Rank effects (minor, F10).** These are T-A + T-B ranks, given as P-c crossings / cuts strictly fewer / cuts with equal crossings including P-c.

| alternative | S | S′ | notes |
|---|---|---|---|
| T3 (FZ, baseline) | 24 / 10 / 78 | 17 / 0 / 1 | — |
| K8 (generic act → C8) | 24 / 53 / 131 | 17 / 1 / 4 | decides whether the S′ minimum is unique |
| K9 (BL value mapping) | 24 / 36 / 90 | 17 / 1 / 4 | decides whether the S′ minimum is unique |
| K10 (conforms at own placement) | 24 / 14 / 92 | 17 / 0 / 1 | no P-c crossing moves |
| K13 (VC6: R41, R83 without C23) | 24 / 26 / 83 | 17 / 0 / 2 | no P-c crossing moves; the S′ minimum becomes tied (G6) |

### 1.1 Disclosed gaps (F8)

These conventions are not inventoried with alternatives above. They are listed so the act knows they exist.

| id | convention | status |
|---|---|---|
| K15 | **Attribute vs bearer:** does an attribute read its bearer? Do r, c and "text" read C13? (FZ F3 / BL Q9: no) | not computed as a set alternative. AUDIT-Q6-TEST1 a14 and a15 record no P-c effect for the instances found |
| K16 | **O → C13, or O → C13 + C14 + C15** | not computed. AUDIT-Q6-TEST1 a10: no P-c effect (all CB1) |
| K17 | **Glosses inside statement cells:** R25's D-st gloss ("with D-st, R27, those acts are also adopted"); R15's "(R3)"; R77's list of rows | K13 covers only R41 and R83. R15's "(R3)" under C is computed (§3.5: E3-REL 10 / 5). R25's gloss and R77's list are not. R25 already crosses under S. R77 crosses under every reading |
| K18 | **Source of ρ:** the statement cell (rule 1), or T2's "rests on … · C-ids" column | computed by `restson.py`. With the column: E3-REL **6 / 3**, and 14 (S) or 10 (S′) ratified host cells differ. This is weak evidence: **7 rows lack the column** (R23, R51, R53, R57, R58, R61, R75), and the column lists support concepts, not readings |
| K19 | **Actor-location typing for S″** (N / R / B / U flags, e.g. R70's "executor on τ", R23 and R72 → B) | not inventoried. S″ = S′ on every row in every set, so no P-c crossing depends on it here |

**Out of scope (not K-c reading conventions):**
- the hosting conventions: MW1 and CV4, D12, and where "none" rows are hosted;
- the concept placement (VP14, VP10);
- cell content (open item (g)).

---

## 2. Logical analysis

### 2.1 What can "reads" mean?

**Notation.** For a T2 row x:
- txt(x) is its statement cell;
- φ_x is the R4 formula about histories that the row asserts, would activate, prohibits or asks about;
- ρ(x) is the set of concepts it reads, plus an actor flag.

The K-c criterion is: x crosses iff ρ(x) meets CB1 and CB23, with C1 counted by S, S′ or S″.

**Δ (semantic dependence: essential variables).** x reads coordinate c iff there are histories h and h′, differing only in c, with φ_x(h) ≠ φ_x(h′).

**Proposition 1 [E], softened in r1 (F14).**
- Take a class 𝒦 over which φ_x is valid. Over 𝒦, φ_x has no essential variables, because its truth value is constant.
- T-A rows are "model-entailed constraints" (T2 §1): typing rows, frame axioms, definitions and lemmas, all valid over 𝓗 or over the classes their proofs cover. **So under Δ evaluated over 𝓗, those rows read nothing**, and the T-A crossings collapse.
- Evaluating over a wider class rescues the rows whose validity depends on frame axioms (F1, F2: R1–R3). It may also rescue the lemmas whose proofs rest on assumptions rather than definitions: **R24 rests on A3, A8 and A9 (Lem 5.2), so over a class without those assumptions it may have essential variables**. Whether it does was not checked.
- The pure definitions (R18–R20) and definitional typing are not rescued.
- **Consequence:** literal dependence answers a different question (which coordinates constrain histories). It is not a workable hosting criterion for T-A. Its usable form is C below, which is syntactic.
- This is an argument by cases, not a row-by-row computation.

**The three usable principles.** Each is defined for every row.

| principle | definition of ρ(x) |
|---|---|
| **M (mention / aboutness)** | The T1 concepts named in txt(x), or whose coordinate symbols occur there, **at the level named**. T1-named concepts (Adopted, Cap, conforms, independent, Chg) count at their own placement. A name with no T1 concept (a Def 3.1 clause name; H-D / H-B / R_B) is an abbreviation and is replaced by its R4 text. A row id is a catalogue reference and is not replaced. **Polarity-blind:** a concept named inside a denial, contrast or negation is read |
| **C (content / signature)** | The primitive coordinates in the signature of the formula the row **asserts about histories**, after replacing every defined symbol by its definiens (Def 1.10, Def 4.9, Def 3.1 / 3.2; a row reference to a formula is that formula). Bound variables count by their sort: Ref and con bind a ∈ E, Cap binds x ∈ 𝒜. A part that asserts no formula about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits φ_x. **T-D questions (F12):** C reads the formula(s) the question is about, as K does. T-C rows are read as the formula they state |
| **K (contribution)** | As C, but the formula is **what the row contributes in its role**: the formula it asserts or would activate (T-A constraints, T-B slots); the formula it **prohibits** (rows T2 labels "guard form"; T-C exclusions); or the formula(s) it **asks about** (T-D). The empty clause contributes nothing |

**K's input (F1).** K needs a row-role assignment: which rows are "prohibitions". It takes that from T2's tier label "guard form", which is the same list as T3 rule 1 (R11, R14–R17, R21). Two cautions:
- **T2 is an unadopted analyst artifact.** Its N-column also calls other rows "guards": R2, R3, R9, R18 and R24 (the five T2 §6 self-check line 172 lists as guards besides R11, R14–R17, R21), and further R1, R6, R19 and R20 (G7). None of these is a guard-form row by T2's tier column, and every set reads them as constraints.
- So K **relabels** T3's guard stipulation as a role distinction; it does not derive it.

### 2.2 Each convention tested against each principle

These are the analyst's applications of the §2.1 definitions [E].

| convention | T3 (text; FZ where T3 is silent) | M | C | K |
|---|---|---|---|---|
| K1 guard rows | read the denied items | read | object-level parts only | read the prohibited formula |
| K2 class rule | on | on (abbreviation) | on (definiens) | on |
| K3 Adopted | own | own | through | through |
| K4 clause names / row ids | clauses yes, ids no | clauses yes, ids no | both | both |
| K5 absent / negated | not read | **read** | not read | not read |
| K6 Cap alone | no actor | no actor | actor | actor |
| K7 relational | on | **off** | on | on |
| K8 generic act | C8 + C9 | C8 + C9 | C8 + C9 | C8 + C9 |
| K9 value mapping | A.0 (FZ; T3 silent) | not settled | not settled | not settled |
| K10 conforms | **text: own** (rule 2); FZ: through | own | through | through |
| K10′ Chg | **ambiguous** ("σ, Chg → C6, C7") | own (C7) | through (C6, C7) | through |
| K11 independent etc. | own | own | through | through |
| K12 roles → actor | yes | yes | yes | yes |
| K13 statement scope | as T3 | as T3 | as T3 | as T3 |

**Agreement with T3's text (F13: K9 is not settled by any principle, so it is counted for none; K10′ is ambiguous and is not counted).** Over the 12 conventions K1–K8 and K10–K13:

| principle | agrees with T3 at | count |
|---|---|---|
| M | K1, K2, K3, K4, K6, K8, K10 (text), K11, K12, K13 | 10 |
| C | K2, K5, K7, K8, K12, K13 | 6 |
| K | K1, K2, K5, K7, K8, K12, K13 | 7 |

### 2.3 Mixing points in T3's rule text (restated in r1, F7)

| id | the two treatments | rows with a P-c effect | which principle removes it |
|---|---|---|---|
| **A1** (F11, denials) | **Guard rows (M):** R14 "the frame never equates f with r or d" reads f, d and r. **Absent and contrast clauses (C):** R80 "H-B carries no record requirement" reads nothing, and R79's "not that a record references it" is not read. R16 ("does not require that every act … is referenced by some record") and R80 have the same surface form: a report that a requirement is missing | guard rows: 4 of the 13 E3-REL rows. R79, R80: their hosts | **M** reads both kinds. **C** reads neither. **K** keeps T3's results, *given* the guard label and R80's empty-clause role, i.e. by relabelling (F1) |
| **A3** (typing) | **Ref / con (C):** read their acts and work events (bound sort). **Cap (M):** does not read its actors, although Cap ⊆ 𝒜 × {iss, adp} binds an actor just as Ref ⊆ R × (E ∪ W) binds an act | R45, R52 (relational); R56, R60, R61 under S (Cap) | M: both off. C, K: both on |
| **A2′** (ambiguous, Chg only) | rule 2 maps "σ, Chg → C6, C7": either Chg → C7 alone (own placement, as Adopted → C10) or Chg → C6 + C7 (read through to σ). "conformance → C21" is own placement in T3's text; the read-through of "conforms" is FZ's (F5, from A1 K2), not T3's | none (all CB23) | M: own; C, K: through |

**Reading [E].**
- **A1 is the point that matters for accepted outputs.**
- A3 affects only T-C rows.
- A2′ affects no crossing. Adopted at own placement is *consistent* with T3's text for "conformance". The Adopted question (K3) therefore stands on its own and is not an inconsistency of T3's text. K's and C's claim to "remove A2" (r0) is withdrawn; they simply read every Def 1.10 notion through.

**T3's local justifications.**
- For R80, T3 rules 1 and 5 (as AUDIT-Q6-TEST1 F11 quotes them) give a content argument: reading it would make "H-B has no record clause" count as a record crossing.
- For the guard rows, T3 rule 1 gives an aboutness argument.
- M and C each satisfy one of these and not the other.

### 2.4 What no principle settles

1. **The purpose of K-c for guard rows, K vs C. This question is entangled with C-γ (F4).**
   - If a crossing measures **coupling of constraint content**, a guard row adds no constraint, so it couples nothing. That is C.
   - If a crossing measures **relevance for hosting**, a guard against tying f to r concerns both sides. That is K, and also M.
   - **Evidence for C:**
     - T2's tier column labels R14–R17 "T-A (non-constraint; guard form)". R11 is labelled "T-A (guard form)" and R21 "T-A (guard form; modelling choice)" (G7).
     - The two outputs that use crossings most directly are coupling uses: E3-REL is called "cross-module rows", and the VP14 cut evaluation (F-LOG-0265) compares cuts by crossings.
   - **Evidence for K:**
     - E2b §A1.3 uses crossings for hosting ("hosted on both").
     - T2 pairs each guard row with a T-C row that states the formula the guard keeps out: R14 / R43, R21 / R46, R11 / R47, R17 / R48, R16 / R49, and **R15 / R42, inferred via X5** (R42 carries no explicit pairing to R15; G8). Reading each guard row as its paired T-C formula gives T3's category for all 6 guard rows (`chk.out`: R14 X, R16 X, R17 CB23, R11 CB23, R21 X, R15 X). **This comparison is FZ against FZ, so it is a consistency check, not independent evidence (G8).** It makes K's guard rule mechanical, but it still depends on T2's labels and pairings.
   - **This is a choice, not a derivation.**
2. **R80's role.** There are three readings: the empty clause (none), "acts exist" (VC8: internal CB23), or a guard-form denial (crosses, SF). This is a genuine text and role ambiguity (AUDIT-Q6-TEST1 c9).
3. **Vocabulary (K9).** v(a) has the sort of a deontic value (C4), but it is the value part of the issued norm (C5). T1 lists the prepared flag in both C8 and C10. No principle chooses.
4. **The parse of mixed guard rows under C (F2).** This is K1c, row by row:

   | row | text read as asserting a formula about histories | K1c concepts | alternative parse | effect |
   |---|---|---|---|---|
   | R11 | "σ : T → V^𝒯 is total" (typing) | C3, C4, C6 | SH: ∅ | host CB23 or none |
   | R14 | none ("never equates … imposes no inequality") | ∅ | "f may coincide with d or r in some histories" read as asserted: {C11, C12, C14} | none or crossing |
   | R15 | "t(w) as a work attribute" (typing) | C20 | C's own row-reference rule applied to "except d = r under constitution (R3)": + R3's formula | CB23 or crossing |
   | R16 | none | ∅ | — | none |
   | R17 | none | ∅ | — | none |
   | R21 | "a self-assertion … is a value of c" | C15 | SH: ∅ | CB1 or none |

   E3-REL under C therefore ranges from **9 / 4** (K1c or SH) to **10 / 5** (R15 reads R3) to **11 / 6** (also R14's "may coincide") (§3.5).
5. **The spanning rule (K14):** held open by F-LOG-0271.
6. **The ranking criterion among the sets (F3)** is a meta-question for the human (§4).

### 2.5 Convention sets

| set | principle | differs from T3 at | encoding (`kc.py`) |
|---|---|---|---|
| **T3** (as written) | rules 1–5 as written; A1 and A3 kept as stipulations; A2′ fixed as FZ reads it | — | FZ |
| **K** | K (§2.1) | K3, K4 (row ids), K6, K11 | FZ + SA + SB + SC + K4b |
| **C** | C (§2.1) | as K, plus K1 (K1c; variants SH, R15ref, R15ref-R14) | K + K1c |
| **M** | M (§2.1) | K5 (read: SF + K5c), K7 (off), K10 (own: **R34 only**, because R33 names B-conf, "formally identical to B-conf", which is expanded; r1, F12) | FZ + SE + K5c + SF + R34 own |
| M0 (boundary) | surface mention, no abbreviation expansion | M + SD + SI | — |

K9 is held at A.0 in every set. R80 is reported with VC8 as a sub-variant of K and C.

---

## 3. Consequences per convention set (computed)

### 3.1 Method

- **Encodings and crossings.** FZ comes from `blind_enc.py`, and each set is built from FZ plus the row changes in §2.5. Crossings use `blind_enc.crosses`:
  - S: any actor flag counts on both sides.
  - S′: flags are ignored.
  - S″: N → CB23, R → CB1, B → both, U → none.
- **Host diffs and E3-REL.**
  - Host diffs are taken against `blind_enc.RAT`, the ratified E2b §A1.3 / §A1.4 lists.
  - E3-REL is the set of crossing T-A rows, excluding R12, R13, R23 and R24.
- **Rank over all 817,190 cuts of the 23 placed concepts into 9 │ 14.**
  - S′: concepts only.
  - S″: N adds C2's side and R adds C16's side.
  - S: a row with any actor flag crosses every cut. This uses the assumption of RESEARCH-AUDIT-1 Lemma 1, that C1 spans any cut. **Only its application to the rank is new (F9).**
  - The reviewer's brute force (`chk.py`, written independently of `kc.rank`; about 100 s) reproduced the S rank for T3 (24 / 10 / 78) and K (26 / 64 / 304) (`chk.out`).

### 3.2 P-c crossings (rows T-A / T-A + T-B / all 83)

| set | S | S′ = S″ | rows that differ between S and S′ |
|---|---|---|---|
| T3 | 17 / 24 / 46 | 12 / 17 / 36 | 10 |
| K, K-VC8 | 17 / 26 / 53 | 12 / 17 / 39 | 14 |
| C (K1c), C-SH, C-VC8 | 13 / 22 / 49 | 8 / 13 / 35 | 14 |
| C-R15ref | 14 / 23 / 50 | 9 / 14 / 36 | 14 |
| C-R15ref-R14 | 15 / 24 / 51 | 10 / 15 / 37 | 14 |
| M | 17 / 26 / 46 | 12 / 19 / 36 | 10 |
| M0 | 16 / 24 / 40 | 11 / 18 / 31 | 9 |

- In every set, S′ = S″ on every row.
- The five-category totals are in `kc_cat.out`. Rows that touch CB4 are counted under CB1 there, whereas T3 counts R59 and R62 as "CB4 touch". The crossing counts agree.

### 3.3 Exhaustive rank among the 9 │ 14 cuts

Each cell gives P-c's crossings / cuts with strictly fewer / cuts with equal crossings including P-c.

| set | T-A + T-B: S | S′ | S″ | T-A: S′ | T-A: S″ |
|---|---|---|---|---|---|
| T3 | 24 / 10 / 78 | 17 / 0 / 1 | 17 / 0 / 1 | 12 / 1 / 31 | 12 / 0 / 21 |
| K | 26 / 64 / 304 | 17 / 0 / 1 | 17 / 0 / 1 | 12 / 1 / 31 | 12 / 0 / 21 |
| C | 22 / 46 / 234 | 13 / 0 / 1 | 13 / 0 / 1 | 8 / 0 / 32 | 8 / 0 / 22 |
| C-SH | 22 / 80 / 204 | 13 / 0 / 1 | 13 / 0 / 1 | 8 / 0 / 41 | 8 / 0 / 30 |
| C-R15ref | 23 / 53 / 283 | 14 / 0 / 1 | 14 / 0 / 1 | 9 / 6 / 116 | 9 / 6 / 99 |
| C-R15ref-R14 | 24 / 116 / 409 | 15 / 0 / 1 | 15 / 0 / 1 | 10 / 47 / 283 | 10 / 41 / 229 |
| M (r1) | 26 / 147 / 417 | 19 / 0 / 2 | 19 / 0 / 2 | 12 / 1 / 31 | 12 / 0 / 21 |
| M0 (r1) | 24 / 177 / 529 | 18 / 0 / 3 | 18 / 0 / 2 | 11 / 2 / 82 | 11 / 1 / 63 |

**Reading.**
- **Under S′ and S″, in every set, no cut has strictly fewer T-A + T-B crossings than P-c.** The minimum is unique under T3, K and every C variant, and tied under M and M0.
- Under K8 or K9 (§1), one cut is strictly lower. This agrees with AUDIT-Q6-TEST1 §5.3 **in direction only**: under BL's full encoding, 6 cuts are lower.
- **Under S, P-c is not the minimum (F9).** For T3, 10 cuts are strictly fewer and 78 equal, so P(≤) = 88 / 817,190 ≈ 1.1 × 10⁻⁴. P-c remains in the extreme low tail.
- This qualifies, **for S only**, F-LOG-0299's "no 9 │ 14 cut has strictly fewer crossings" and RESEARCH-AUDIT-1 Q2's rank statements, which were S′-type. It is routed to the human as an observation and decides nothing.
- M and M0's S ranks changed in r1 because of the F12 fix (r0: 158 / 483 and 210 / 597).

### 3.4 Host lists against the ratified E2b §A1.3 / §A1.4

| set | under S | under S′ / S″ |
|---|---|---|
| T3 | identical | identical |
| **K** | R27, R28: CB23 → crossing | identical |
| K-VC8 | as K; R80: none → CB23 | R80: none → CB23 |
| **C (K1c)** | R14, R16: crossing → none; R17: CB23 → none; R15: crossing → CB23; R21: crossing → CB1; R27, R28 → crossing | R14, R16, R17 → none; R15 → CB23; R21 → CB1 |
| C-SH | R11, R17: CB23 → none; R14, R15, R16, R21 → none; R27, R28 → crossing | R11, R17, R14, R15, R16, R21 → none |
| C-R15ref | as C, except R15 stays crossing | as C, except R15 stays crossing |
| C-R15ref-R14 | as C-R15ref, except R14 stays crossing | as C-R15ref, except R14 stays crossing |
| **M** | R79: CB23 → crossing; R80: none → crossing | same |
| M0 | as M; R12 → CB23; R38 → CB23 | as M; R12 → CB23 |

**New hosting questions.**
- Under C, the T-A list gains a "none" category, which E2b §A1.3 lacks.
- Under K, R27 and R28 cross under S. That is the E2b §A2.7 reopen row "an S-only borderline applied (R27, R28; T3 §1a (e))".

### 3.5 E3-REL (accepted 13 / 8, F-LOG-0297)

| set | S | S′ / S″ | other D12 reading |
|---|---|---|---|
| T3, K, M, M0 | R2, R3, R5, R6, R8, R9, R10, R14, R15, R16, R19, R21, R22 (13) | R2, R3, R9, R14, R15, R16, R21, R22 (8) | 14 / 9 |
| C (K1c), C-SH, C-VC8 | R2, R3, R5, R6, R8, R9, R10, R19, R22 (9) | R2, R3, R9, R22 (4) | 10 / 5 |
| C-R15ref | the 9 above, plus R15 (10) | the 4 above, plus R15 (5) | 11 / 6 |
| C-R15ref-R14 | the 10 above, plus R14 (11) | the 5 above, plus R14 (6) | 12 / 7 |
| statement-cell source replaced by T2's "rests on" column (K18) | R3, R5, R6, R14, R15, R22 (6) | R3, R14, R15 (3) | — (weak; 7 rows lack the column) |

**E3-REL moves only under C** (9 / 4 to 11 / 6, by parse) **or under K18.**

### 3.6 H-D / H-B clause rows and the asymmetry

| clause (row) | T3 S / S′ | K S / S′ | C S / S′ | M S / S′ | M0 S / S′ |
|---|---|---|---|---|---|
| D-chg (R25) | X / CB23 | X / CB23 | X / CB23 | X / CB23 | X / CB23 |
| D-st (R27) | CB23 / CB23 | **X** / CB23 | **X** / CB23 | CB23 / CB23 | CB23 / CB23 |
| D-rec (R29); B-recW (R35); B-rec (R36); R-h (R83) | X / X | same | same | same | same |
| B-chg (R30); B-conf (R32, R33); R-e (R26) | CB23 / CB23 | same | same | same | same |
| R-i (R79) | CB23 / CB23 | CB23 / CB23 | CB23 / CB23 | **X / X** | **X / X** |
| R-i′ (R80) | none / none | none (VC8: CB23) | none (VC8: CB23) | **X / X** | **X / X** |
| R-j (R38) | X / CB23 | X / CB23 | X / CB23 | X / CB23 | CB23 / CB23 |
| R-d (R28) | CB23 / CB23 | **X** / CB23 | **X** / CB23 | CB23 / CB23 | CB23 / CB23 |

**Asymmetry by set.**

| set | change and status clauses at 𝐑₀ under S | under S′ / S″ | R-i / R-i′ record clauses |
|---|---|---|---|
| T3 | H-D-asymmetric: R25 (R38 under R-j) | symmetric | H-D-asymmetric |
| K, C | widened to D-st: R25, R27 (R28 under R-d; R38 under R-j) | symmetric | H-D-asymmetric |
| M | as T3 | symmetric | **symmetric** |
| M0 | R25 only | symmetric | **symmetric** |

**Conflict with earlier records (F5).**
- RESEARCH-AUDIT-1 F2 says the R-i / R-i′ asymmetry "**holds.** It is substantive and the same under S, S′, S″, P-d and the coordinate-level criterion" (confidence: high).
- F-LOG-0292 records "The R-i/R-i′ record-clause asymmetry is real under every convention".
- **Both claims hold only within the conventions they tested.** All of those shared K5 (a): absent and contrast clauses not read. Under M or M0 (K5 (b) + (d)), the asymmetry disappears.
- Choosing M would therefore contradict two records, and both would need restating (KC-5).
- None of this is evidence about H-D or H-B.

### 3.7 Cut status in E3 (accepted under (α), F-LOG-0297)

| E3 r1.3 site | T3 | K | C (K1c; variants in brackets) | M |
|---|---|---|---|---|
| §2.2 / §8.2 T-A placement (1 / 6 / 13 / 4; 2 / 10 / 8 / 4) | unchanged | unchanged | S: 2 / 6 / 9 / 4, plus none 3; S′: 3 / 10 / 4 / 4, plus none 3 [R15ref, R14: shared 10–11 / 5–6] | unchanged |
| §2.3 / §8.2 E3-REL | unchanged | unchanged | 9 / 4 [10 / 5; 11 / 6] | unchanged |
| §4.2 E3-EV3 "R14 crosses (all readings)" | unchanged | unchanged | R14 none [crosses under R15ref-R14] | unchanged |
| §5 E3-XG (the ten rows) | unchanged | unchanged | unchanged | unchanged |
| §6 R27, R28 | unchanged | cross / internal | cross / internal | unchanged |
| §6 R79, R80 | unchanged | unchanged (VC8: R80 internal) | unchanged (VC8: as K) | cross / cross |
| §2.5 / §8.2 inactive slots that move | unchanged | R25, R27, R28, R38 (S) | as K | unchanged |
| §8.2 83-row totals (46; 36) | unchanged | 53; 39 | 49; 35 [50–51; 36–37] | 46; 36 (different rows) |
| §2.5 / §8.2 asymmetry rows | unchanged | widened under S | as K | R-i / R-i′ symmetric |
| §3.2 "unlabelled guard crossing" (grouping level, C-γ) | unchanged | unchanged | K1c / SH: guard rows would cross no grouping. C-R15ref: R15 still crosses groupings (C11 in CG3; C14, C18 in CG4; C20 in CG5). C-R15ref-R14: R14 also (C11, C12 in CG3; C14 in CG4) (F4, G1) | unchanged |

### 3.8 Amendments each set would need

The E2b §A2.7 reopen rows involved:
- (r1) "the K-c criterion or rule 2a revised → §A1.3, §A1.4, §A2.1, §A2.2";
- (r2) "an S-only borderline applied (R27, R28; T3 §1a (e)) → the §A2.1 per-reading cells".

| set | E2b | E3 | T3 | other |
|---|---|---|---|---|
| **T3** | **No host change.** The E2b §A2.2 ratification note ("The ratified lists are therefore a ratified output of an unratified method") and §A1.10 bullet 1 are updated **by a recording ordered within KC-1** (F6) | none | record the ratified status in §1a (KC-4, recording only) | — |
| **K** | §A1.4 S row (+R27, R28); §A2.1 cells (r2); r1 engaged | §6, §2.5 / §8.2 slot rows, totals 53 / 39, asymmetry rows | rules 1–4 restated (contribution principle; guard role; Adopted, Cap and C25 read through; row ids read through); (e) applied; §1a (c) and (d) restated | KC-5 (widened under S) |
| **C** | §A1.3 (both readings; a new "none" category), §A1.4 (R27, R28 under S), §A2.1; r1, r2 | E3-REL 9 / 4 to 11 / 6 (the F-LOG-0297 box would need a new acceptance); §2.2, §4.2, §6, totals | as K, with rule 1's guard sentence replaced and the guard-row parse fixed | C-γ (F4); R17's host (E2b D1); KC-5 |
| **M** | §A1.4 (R79, R80, both readings); §A2.1; r1 | §6, asymmetry rows | rules 1, 2 and 5 restated | **KC-5:** F-LOG-0265's limitation, T3 §1a (d), E2b §A1.9a, E3 §2.5 / §8.2, **RESEARCH-AUDIT-1 F2 and F-LOG-0292's Q1 finding** |

---

## 4. Ranking the options [E], kept separate from the evidence (rewritten in r1, F3)

Ranking the sets needs a criterion. **Which criterion governs is a meta-question for the human.** Under each candidate criterion:

| criterion | best-fitting option(s) | why | caveat |
|---|---|---|---|
| **(i) A single semantic principle** | **M, K or C** (G4) | each is one rule for all rows | **M** needs no role labels and no guard parse, so it is the least input-dependent. **K** needs T2's guard labels and R80's empty-clause role as input, and so relabels the F11 stipulation. **C** needs a parse of the mixed guard rows (E3-REL 9 / 4 to 11 / 6). The guard-row purpose (§2.4 item 1) separates C from M and K |
| **(ii) Minimal amendment / output preservation** | **T3** (zero host, E3 or E2b changes; a recording only) | the ratified lists and accepted outputs stand as they are | **Offered to the human as a candidate criterion; the analyst does not apply it** (G5). It keeps two stipulations, A1 and A3, by ratification. A1 is the guard / absent-clause asymmetry. A legitimate first-class option, not a fallback |
| **(iii) Fidelity to T2's own labels** | **C leans**; K is close | T2's tier column labels R14–R17 "non-constraint; guard form" (R11 and R21 "guard form" only; G7), and the main uses (E3-REL, VP14) are about coupling | T2 also pairs each guard row with its T-C formula, which reproduces T3 and K for all 6 (`chk.out`; FZ against FZ, not independent; G8). T2 is unadopted [E] |
| **(iv) Fidelity to T3's rule text** (G4) | **M** | M agrees with T3's text at 10 of 12 conventions, the most of any option (§2.2); K 7, C 6 | it departs at K5 (absent / negated clauses read) and K7 (relational clause off) |

**Notes on M (F3, G4).** M is a coherent principle, and its consequences are stated without weighting:
- **content consequence:** under M, R79 and R80 cross, so the R-i / R-i′ record-clause asymmetry disappears. That is a fact about M's counting, not a cost. That three records (F-LOG-0265, RESEARCH-AUDIT-1 F2, F-LOG-0292) would need restating is an amendment burden of the same kind as criterion (ii), and is **not** used against M;
- **polarity-blind reading:** M counts a denial as a crossing. **T3 and K do the same for guard rows** (R14 "never equates f with r or d" crosses under both). Only C counts no denial anywhere.

Whether counting denials is right depends on the purpose question (§2.4 item 1), not on T3's own rationale.

**No single recommendation is made.** Output preservation (criterion ii) is offered to the human, not applied against any option by the analyst (G5).

**Uncertainty.**
- The classifications in §2.2 are the analyst's application of the definitions and are not formally verified.
- Λ7 applies throughout.
- K was formulated after it was known which conventions move E3-REL (a post-hoc risk).

**Choices KC-1 must make in any case:**
- the criterion, or a direct choice of option;
- the guard-row purpose, together with C-γ (F4);
- R80's role;
- the vocabulary (K9).

**C-γ (F4).** T3 §1b's grouping criterion reads "a T-A row 'crosses' if it reads attributes of concepts placed in two or more groupings". That is the same verb. Whether a ratified "reads" also governs §1b is not stated anywhere.
- If it does, the effect depends on the option and, under C, on the parse (G1). Groupings from T3 §1b: CG3 holds C8–C12, CG4 C13–C18, CG5 C19–C20.
  - **C with K1c or SH:** R14, R16 and R17 read nothing and cross no grouping; R11, R15 and R21 read one grouping each. Guard rows then cross no grouping, so C-γ becomes empty.
  - **C-R15ref:** R15 reads C11 (CG3), C14 and C18 (CG4) and C20 (CG5), so it still crosses groupings. C-γ is not empty; it concerns R15.
  - **C-R15ref-R14:** R14 (C11, C12 in CG3; C14 in CG4) crosses too. C-γ concerns R14 and R15.
  - **T3, K, M:** guard rows keep crossing groupings. The conventions say which rows cross, **not whether a crossing guard row is a "cross-grouping constraint"**. That is a labelling question, and it needs an explicit yes / no (G2).
- **Analyst suggestion [E]:** decide C-γ in KC-1 by an explicit answer, or state in KC-1 that the ratified "reads" applies to K-c only and that C-γ stays returned. **Deciding C-γ in KC-1 widens F-LOG-0300's listed scope** (guard rows, class rule, Adopted, named clauses, absent clauses, "and any others the analysis finds"), so it would need the human's express inclusion (G2).

---

## 5. Draft Human ARB act(s)

### Act KC-1: ratify the K-c counting conventions (select; no default)

> "I, Human ARB, acting on F-LOG-0300 and on the basis of `R7/KC-CONVENTIONS-PROPOSAL.md` r[n] (reviewed), ratify as the K-c counting rules for this model, **one corpus**:
>
> **Criterion** (optional, recorded as the reason): [ ] single principle [ ] minimal amendment [ ] fidelity to T2 [ ] none stated.
> **Option:** [ ] **T3** (rules 1–5 as written; A1 and A3 ratified as stipulations; Chg read as FZ reads it) [ ] **K** [ ] **C**, guard parse: [ ] K1c [ ] SH [ ] R15ref [ ] R15ref-R14 [ ] **M** [ ] **P**, per convention: K1 [a/b/c; if c: guard parse K1c / SH / R15ref / R15ref-R14], K2 [a/b], K3 [a/b], K4 [a/b/c], K5 [a/b/c/d], K6 [a/b], K7 [a/b], K8 [a/b], K9 [a/b], K10 [a/b], K10′ Chg [own (C7) / through (C6, C7)], K11 [a/b], K12 [a/b], K13 [a/b].
> **R80's role** (only where still open; see the dependency note below): [ ] empty clause [ ] 'acts exist' (VC8) [ ] guard-form denial.
> **Vocabulary (K9)** (only where still open): [ ] A.0 [ ] other: ___.
> **Scope with respect to C-γ** (deciding C-γ here widens F-LOG-0300's listed scope and needs the human's express inclusion): [ ] the ratified 'reads' applies to K-c only; C-γ stays returned [ ] it also governs the T3 §1b grouping criterion, **and** the label question is answered explicitly: 'guard rows that cross groupings are cross-grouping constraints': [ ] yes [ ] no. (Under C with K1c / SH, no guard row crosses a grouping and the answer is vacuous; under C-R15ref / R15ref-R14 it concerns R15 / R14; under T3, K and M it concerns every crossing guard row.)
>
> *Box dependencies (G3; drafting note, not act text):*
> - **T3** fixes R80 = empty clause (rule 5: "an absent clause … is 'none'") and K9 = A.0, as FZ applies rule 2. Neither box is open.
> - **M** fixes R80 = read (SF: its negated clause is read). The R80 box is not open; K9 is open.
> - **K** fixes R80 = empty clause by its role rule. Only the VC8 sub-variant is open ([ ] empty clause / [ ] 'acts exist'); a guard-form denial would contradict K's role assignment. K9 is open.
> - **C** fixes R80 = empty clause; the VC8 sub-variant is open. K9 is open.
> - **P:** every box is open.
>
> The ratified conventions apply under each spanning reading. **No spanning rule is selected** (F-LOG-0271), and rule 2a's reading definitions are not ratified. The gaps K15–K19 stay [E]. The asymmetry is not accepted and F-LOG-0265's premise is not withdrawn. No VP is decided. **D12 is not resolved.**
>
> **Effect on the ratified host lists:** this **partly addresses the method half** of RESEARCH-AUDIT-1 L6 / C6 (rules 1–5). Rule 2a's reading definitions and the encoding (the row-by-row application, Λ7) stay [E]. **C6's 'frozen encoding' is not ratified.** Where the selected conventions give different host cells (§3.4), the lists are not amended by this act; the E2b §A2.7 reopen row 'the K-c criterion or rule 2a revised' is triggered, and each amendment needs its own act. **Under Option T3, the E2b §A2.2 ratification note and §A1.10 bullet 1 are updated by a recording under this act** (no content change).
>
> Decided without reference to any VP1a–d outcome (N1). It authorizes **neither E4 (R8) nor E5**, and no evidence collection or implementation. Standing qualifier, verbatim: 'one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable'."

### Consequential acts (separate; each only if the option requires it)

| act | needed under | content |
|---|---|---|
| **KC-2** E2b | K, C, M, VC8 | restate §A1.3 / §A1.4 per reading; tag superseded cells in place; **also update the E2b §A2.2 ratification note**, which the §A2.7 reopen row targets (G9) |
| **KC-3** E3 | K, C, M | re-derive the affected cells (§3.7). Under C, the F-LOG-0297 E3-REL box needs a new acceptance |
| **KC-4** T3 | every option (under T3, a recording of the ratified status only) | §1a rules restated; (e) applied or struck; §1a (c) and (d) restated |
| **KC-5** asymmetry records | M, M0 (R-i / R-i′ removed); K, C (widened under S) | restate F-LOG-0265's limitation, E2b §A1.9a, E3 §2.5 / §8.2, and **RESEARCH-AUDIT-1 F2 and F-LOG-0292** (under M / M0). **Not an acceptance of any asymmetry** |

Order: KC-1, then KC-4, then KC-2, then KC-3 and KC-5. [E] E4 would wait for those the option triggers. F-LOG-0300 itself says only "E4 follows on a settled basis" (G11).

**C6 after KC-1 (G9).** RESEARCH-AUDIT-1 C6 ("Either ratify a frozen encoding and method after Q6 test 1, or downgrade the lists to 'report'") **stays open** after KC-1. KC-1 ratifies conventions, not a frozen encoding. The "downgrade the lists to report" branch stays available by its own act.

**Separate observation for the human (F9):** under S, P-c is not the exhaustive minimum (P(≤) ≈ 1.1 × 10⁻⁴). No act is proposed.

---

## 6. Limitations

- **Λ7, within-family.** The principles, the §2.2 classifications, FZ and every variant encoding are by Claude. The reviewer is Claude-family too.
- **The external-encoding option remains available.** Ratified conventions would make it well-posed: a non-Claude or human encoder, blind to T3 and the lists, re-encodes the 83 rows (AUDIT-Q6-TEST1 §7 (i)). It needs its own authorization.
- **Approximate reconstructions.**
  - K8 and K9 are reconstructions on FZ, not full re-encodings. Their P-c and host effect (none) is provable; their rank figures are approximate.
  - K10 is encoded for R33 and R34 only.
  - The Chg-own (A2′) and absence-through variants are not encoded. They move items within one side only.
- **C's parse of the mixed guard rows** is the analyst's choice. Four variants are reported (§2.4 item 4).
- **The S rank** rests on Lemma 1's assumption that C1 spans every cut.
- **Proposition 1** is an argument by cases. It is softened for R24 and not checked row by row.
- **K's role input** is T2's unadopted labels (F1).
- **Gaps K15–K19** are disclosed (§1.1). Only K18 is computed.
- **Scope.** Only the ratified T-A and T-B hosts are compared, and MW1 / CV4 hosting is taken as given.
- **Not re-reviewed.** r1 has not been re-reviewed.
- **Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 7. Self-check

| check | result |
|---|---|
| Conventions in F-LOG-0300 inventoried with a quote, alternatives and moves | yes: K1–K5. Others: K6–K13. Gaps: K15–K19 |
| Spanning rule not decided | yes: K14 held open; results under S, S′ and S″ |
| Computed, not asserted | yes: the scripts are in `R7/KC-CONVENTIONS-scripts/`, and FZ validates |
| [E] labelled; ranking separate from evidence | yes: §2 [E]; §4 ranks only conditionally and makes no single recommendation |
| Decides nothing; E4 / E5 not authorized; D12 not decided; C-γ entanglement stated | yes |

## 8. Changes: r1 (review of r0: PASS_WITH_LIMITATIONS; 7 material, 7 minor) and r1.1 (verification of r1: PASS_WITH_LIMITATIONS; 4 medium, 8 minor / low)

| finding | fix | where |
|---|---|---|
| **F1** K relabels F11 | K stated as conditional on T2's (unadopted) guard labels and R80's empty-clause role; "relabels, not removes"; T2 calls R2, R3, R9, R18 and R24 "guards" too (N-column); favourable evidence added: the guard / T-C pairing reproduces T3 for 6 / 6 (`chk.py`) | §0, §2.1 "K's input", §2.3 A1, §2.4 item 1, §4 |
| **F2** E3-REL under C is a range | C's E3-REL given as 9 / 4 to 11 / 6 (R15 via its row reference "(R3)": 10 / 5; also R14 "may coincide": 11 / 6); per-row K1c parse table; r0's "both give the same E3-REL" removed | §0, §1 K1, §2.4 item 4, §3.2–§3.5, §3.7, §6 |
| **F3** a fair recommendation | output preservation dropped as a criterion; M judged on its own terms; evidence for C added (T2's "non-constraint" label; coupling uses); T3 made a first-class option (zero amendments; A1, A3 stipulations); ranking criterion made a meta-question; per-criterion table; **no single recommendation** | §0, §4, §5 KC-1 |
| **F4** C-γ is entangled | the K1 purpose is the C-γ question; T3 §1b's criterion uses "reads"; whether the ratified notion governs §1b is unstated; KC-1 gets a scope box (K-c only, or also §1b) | §0, §2.4 item 1, §4, §5 |
| **F5** contradiction with RESEARCH-AUDIT-1 F2 / F-LOG-0292 | cited; the earlier claims scoped to their tested conventions (all K5 (a)); both added to KC-5 | §0, §3.6, §3.8, §5 |
| **F6** L6 / C6 wording | "partly addresses the method half (rules 1–5); rule 2a's reading definitions and the encoding (Λ7) stay [E]; C6's 'frozen encoding' is not ratified"; under Option T3, the E2b §A2.2 / §A1.10 notes are updated by a recording within KC-1 | §3.8, §5 |
| **F7** A2 is not T3's text | rule 2 maps conformance to own placement; the read-through is FZ F5 (from A1 K2); restated as two mixing points (A1, A3) and one ambiguous point (A2′, Chg); K's and C's "removes A2" claim withdrawn | §0, §1 K10, §2.2, §2.3 |
| **F8** inventory gaps | K15 attribute vs bearer; K16 O mapping; K17 glosses (R25, R15 "(R3)", R77); K18 rests-on source (computed: E3-REL 6 / 3, weak, 7 rows lack the column); K19 actor-location typing | §1.1, §3.5 |
| **F9** S rank framing | P(≤) = 88 / 817,190 ≈ 1.1 × 10⁻⁴; Lemma 1's assumption cited (only its application is new); qualifies F-LOG-0299 and RESEARCH-AUDIT-1 Q2 under S; routed as an observation | §0, §3.1, §3.3, §5 |
| **F10** counts | "eleven" → nine (K1–K7, K11, K12); K10 moves the S rank (10 → 14); AUDIT-Q6 §5.3 agreement "in direction only"; K8 (and K9) decide uniqueness | §0, §1, §3.3 |
| **F11** reproduction | scripts in `R7/KC-CONVENTIONS-scripts/` with r1 hashes; reviewer scripts included | header |
| **F12** script fixes | M: only R34 at own placement (R33 names B-conf and is expanded); K4b adds R76, R77, R78 (no move); C's reading of T-D questions stated; re-run: M and M0 S ranks changed (147 / 417; 177 / 529), nothing else | `kc.py`, §2.1, §2.5, §3.3 |
| **F13** attribution and count | the "unratified method" quote attributed to E2b §A2.2; the §2.2 agreement count no longer treats K9 as agreement (and is recomputed against T3's text) | §3.8, §2.2 |
| **F14** Proposition 1 | softened: R24 rests on A3, A8, A9 and may be rescued over a wider class; not checked | §2.1 |
| **G1** "C empties C-γ" only for K1c / SH | qualified per parse: under C-R15ref, R15 crosses groupings (C11, C14, C18, C20); under C-R15ref-R14, R14 too | §0, §3.7, §4 "C-γ", §5 KC-1 box |
| **G2** C-γ box "answered accordingly" | the box now requires an explicit yes / no on the label question under every option (vacuous only under C with K1c / SH); the widening of F-LOG-0300's listed scope is stated, needing express inclusion | §0, §4, §5 |
| **G3** KC-1 boxes not independent | dependency note per option (T3: R80 empty clause and K9 = A.0 fixed; M: R80 read; K and C: R80 empty clause, VC8 sub-variant open); R80 and K9 boxes apply only where open; option P gains the C guard-parse choice under K1 (c) and K10′ (Chg) | §5 |
| **G4** M not finished under F3 | M added under criterion (i) (no role labels, no guard parse); new criterion (iv) fidelity to T3's rule text (M 10 / 12, the most); "three records to restate" recast as an amendment burden and not used against M; "counts a denial as a crossing" noted to apply to T3 and K for guard rows too | §0, §4 |
| **G5** criterion (ii) wording | output preservation / minimal amendment offered to the human as a candidate criterion, not applied by the analyst | §0, §4 |
| **G6** K13 moves the rank | K13 (VC6) computed in `kc.py`: S 24 / 26 / 83 (was 24 / 10 / 78), S′ ties 1 → 2; grouped with K8–K10; added to the rank table | `kc.py`, §0, §1 |
| **G7** T2 "guard" labels | N-column also calls R1, R6, R19, R20 guards; the five listed match T2 line 172; "non-constraint" applies to R14–R17 only, R11 "guard form", R21 "guard form; modelling choice"; R17 included | §0, §2.1, §2.4, §4 |
| **G8** `chk.py` not independent | stated as FZ against FZ (a consistency check); R15 / R42 inferred via X5; `chk.out` added; runtime about 100 s | header, §0, §2.4, §3.1, §4 |
| **G9** KC-2 and C6 | KC-2 also updates the E2b §A2.2 note; C6 stays open after KC-1, with the "downgrade to report" branch available | §5 |
| **G10** K9 τ mapping | rule 2 maps τ(w) → C19, C20; only the act target τ(a) is unmapped | §1 K9 |
| **G11** E4 sequencing | "E4 would wait …" marked [E]; F-LOG-0300 says only "E4 follows on a settled basis" | §5 |
| **G12** `python3 -B` | reproduction note added; all scripts re-run with `-B` | header |
