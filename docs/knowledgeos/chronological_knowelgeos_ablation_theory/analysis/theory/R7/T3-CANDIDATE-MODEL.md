# R7 · T3: candidate domain model with traceability (draft r1.4)

**Status:** R7 task artifact T3, draft r1.4, 2026-10-11. r0 (2026-10-10) was reviewed PASS_WITH_LIMITATIONS; r1 applies every required change, r1.1 the verification correction, and r1.2 the cross-artifact audit fixes (§7). Authorized by F-LOG-0248 under the boundary of `R7-AUTHORIZATION-BRIEF.md` r3 §6; input scope per F-LOG-0249 (direct citation of R4 r3.1 statements and FROZEN R3 items; never QUARANTINED items or B10). Builds **only** on `R7/T1-CONCEPT-CANDIDATES.md` r1.4 (C1–C28, X1–X25, OQ1–OQ11, IA-*) and `R7/T2-RULE-CANDIDATES.md` r1.2 (R1–R82, tiers T-A/T-B/T-C/T-D), with R4 r3.1 used for checking. **Reviewed; corrections verified; awaiting human review (F-LOG-0250). Nothing here is adopted.**

**What this is:** the stop-condition artifact of R7 (brief §6.6; F-LOG-0248): a DDD-style *structuring* of the admissible T1 concepts under the T2 constraints. **The whole model is a set of [E] hypotheses. Every element is "[E] candidate, not adopted".** "Boundary", "grouping", "value-like" and "occurrence" are **structuring hypotheses**, not decisions about bounded contexts, aggregates, value objects or domain events. **Every name is a working label in R4 vocabulary (N8), not a canonical term.** No architecture, persistence, API, technology, implementation or canonicalization decision is made (R8; brief §6.5).

**Standing qualifier (applies to every element and row):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 0. Conventions

| item | rule used here |
|---|---|
| Element kinds | **CB** candidate boundary · **CG** candidate grouping (aggregate-like) · **CV** candidate value-like concept · **CE** candidate occurrence (domain-event-like; a dated happening, **no order and no production relation implied**) · **CS** candidate evidential slot · **MW** model-wide constraint set. All are [E] candidates, not adopted |
| Constraint tiers (T2) | only **T-A** rows are carried as *candidate model invariants* ("holds in R4's model; adequacy to the domain [E]/[I]"; never of the world). **T-B** rows appear **only** as "optional policy slot: not active, needs a human act". **T-C** rows and X-concepts appear only **labelled as excluded**: in §3a, in the §1a "not held" column, and in §2 trace rows (e.g. R62 for CB4, CV8). **T-D** rows appear as inherited open questions / variation points |
| Observability (Lem 1.12; Obs O1; T2 "checkable" column) | O sees **only record identities, recording times r and texts c**, and not completely (A07, F13, G01). Everything else (σ, f, Ref, con, d, Cap, k, 𝒜, E, W and their attributes) is **unobserved**; a constraint over it **cannot be checked or enforced from records** (R23). Column values: **obs** (observed, partially) · **unobs** · **mixed** |
| Inherited assumptions | T1 codes (IA-H, IA-A, IA-SC, IA-X, IA-T, IA-R3, IA-RD, IA-1T). **IA-CB and IA-WF apply to every element** and are not repeated. Λ1–Λ7 travel with every element (F-LOG-0249) |
| H-D / H-B neutrality | where H-D vs H-B matters, the model holds a **variation point or empty slot** (§4), never a choice. Neither direction of norm↔work dependence is built in: norm→work is absent by frame (R17, guard) and work→norm has no primitive (X1); their conditional forms exist only as T-B slots |
| Ids | element ids (CB1…, CG1…) are local to T3. T1/T2 ids are reused unchanged. "bK" = brief §2 conclusions |

## 1. Candidate model elements

### 1a. Candidate boundaries (bounded-context-like partitions; [E] candidate, not adopted)

| id | working label | T1 concepts | T-A carried (candidate model invariants) | T-B optional policy slots (not active; need a human act) | inherited open questions (OQ + T1 open status) | observability |
|---|---|---|---|---|---|---|
| CB1 | **record / capture side** | C13, C14, C15, C16, C17, C18, C23, C24, C27 | R7, R8, R9, R2, R3 (shared with CB2), R16, R21, R22 (record ids), R24 | R39 (mode-fixing convention); R29, R35, R36 (existential record clauses), R79 (R-i: act-existence clause "E ≠ ∅"), R80 (R-i′: no B-side record clause), all via VP1d | OQ2, OQ5, OQ11; C16 keeper no source; C17 codability rests on IF; C18 no reliable fill; C24 maps to no work start; C27 every pair ambiguous | **mixed:** record ids, r, c obs (partially, O1); k, Ref, con unobs |
| CB2 | **norm side** | C2, C3, C4, C5, C6, C7, C8, C9, C10, C11, C12 | R1, R4, R5, R10, R11, R14, R15, R19, R20; R2, R3 (shared with CB1) | R25, R26, R27, R28, R38 (H-D-side clauses) and R30, R31, R37 (H-B-side change clauses), all via VP1 | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11; C2, C6, C11, C12 no source; C7, C10 derived from unobserved; C3 multi-target untested; C8/C9 act identifiability | **mixed:** r enters only through R3 (d = r under constitution, shared with CB1), whose antecedent con is unobserved; all CB2 attributes (σ, f, d, Cap, acts) **unobs** |
| CB3 | **work side** | C19, C20, C21 | R6, R15, R18 (shared with CB2), R17 (guard, shared) | R32, R33, R34 (conformance clauses, via VP1c); R30, R31, R37 work disjuncts (via VP1a) | OQ1, OQ5; C19 executor unobserved; C20 no source; C21 depends on σ and t(w) | **unobs** |
| CB4 | **evidential-limitation side** (open slot: may not exist at all, VP10) | C25, C26, C28 | none (no T-A row has these as subject; R23 is model-wide, MW1) | none | OQ7; C25 not certifiable (Prop 5.4(c)); C26 no instrument shown; C28 touches X20 | **unobs** (con, k, σ, f) |
| — | **participant (shared across CB1–CB3)** | C1 | R22 (actor ids), R5, R6, R8 (typing of α, α_W, k) | none | OQ10, OQ5; C1 identity unobserved | **unobs** |

**Relations between boundaries ([E]; neutral).**

| from / to | relation held by the model | not held (and why) |
|---|---|---|
| CB2 ↔ CB1 | reference (R9) and, under constitution only, F2 (R2, R3) | "record is the act" (X4, R44, T-C); d read off r (X5, R42) |
| CB2 ↔ CB3 | conformance as a **derived predicate** (R18) only | work→norm (X1, R50: no primitive); norm→work (R17 guard; X10, R48). Their conditional forms are **slots only** (VP1a, VP1c) |
| CB3 ↔ CB1 | reference (R9); recording order of traces (C24) | work start read off recording order (R15 guard; R69) |
| CB4 ↔ others | none active; CS1 is an unrealized slot | existence of an independent source either way (X21, R62) |

**[r1] Partition disclosure: the CB1–CB3 cut is H-D-congenial ([E]; not a choice).** Cutting "acts + σ" (CB2) from "work" (CB3) makes the **H-D clauses (R25–R28, R38) internal to CB2**, while the **H-B clauses (R30–R34, R37) cross CB2↔CB3** (they read σ/Chg together with work events). The partition itself is therefore a variation point (**VP14**), not a neutral given. Alternatives, listed as options only:

| option | cut | effect on H-D / H-B clauses |
|---|---|---|
| P-a (r0 cut) | acts + σ │ work │ records | H-D clauses internal; H-B clauses cross (H-D-congenial) |
| P-b | σ + work │ acts │ records | **[r1.1]** conformance clauses (R32–R34) and the work disjunct of R30/R31 internal. **The change clauses still cross acts↔σ**: R30, R31 and R37 (R-g⁺) have an issue-act disjunct or conjunct reading f(a) and v(a). The H-D clauses (R25–R28, R38) cross acts↔σ. **Only partially H-B-congenial** |
| P-c | one undivided norm-and-work side │ records | **[r1.1]** the change and conformance clauses of both families are internal. **[r1.3]** The existential record clauses (R29 D-rec; R35 B-recW, R36 B-rec) cross to records. **Under 𝐑₀ the crossing is symmetric** (D-rec on the H-D side; B-recW/B-rec on the H-B side). **Reading-dependent:** under R-i (R79, "E ≠ ∅", which references no record) and R-i′ (R80, no B-side record clause), the H-B side has no record-crossing clause, so the crossing is asymmetric there. Neutral between H-D and H-B under 𝐑₀ |
| P-d | by observability: observed (record ids, r, c) │ unobserved (everything else) | **[r1.1]** the change and conformance clauses are internal to the unobserved side. **[r1.3]** The existential record clauses (R29; R35, R36) cross the observed/unobserved line symmetrically under 𝐑₀. Under R-i and R-i′ (R79, R80) the H-B side has no record clause, so the crossing is asymmetric there (reading-dependent). **It splits concepts by attribute** (e.g. C13: id, r, c observed; k unobserved). Neutral; follows R23 |

### 1b. Candidate groupings (aggregate-like clusters of concepts; [E] candidate, not adopted)

| id | working label | T1 concepts | T-A carried | T-B optional policy slots (not active) | inherited open questions | observability |
|---|---|---|---|---|---|---|
| CG1 | participant with capability | C1, C2 | R10 (Cap typing, constant), R22 (actor ids) | none (R26, R28's Cap dependence is via VP1) | OQ5, OQ10; C1, C2 open (Cap no source; H19 host membership is not Cap) | **unobs** (O5, Lem 1.12) |
| CG2 | governed target with its norm state | C3, C5, C6, C7 | R11 (σ primitive, not computed from acts; guard), R20 (change points); R1 crosses into CG3 | R25, R26, R30, R31, R37, R38 (via VP1a) | OQ1, OQ5, OQ6; C3, C6, C7 open | **unobs** (O4) |
| CG3 | issue act with its adoption acts | C8, C9, C10, C11, C12 | R4 (obj typing), R5, R19 (Adopted, derived), R14, R15 (guards); R1 crosses into CG2; R2, R3 cross into CG4 | R27, R28 (via VP1b) | OQ4, OQ5, OQ11; C8–C12 open. **[r1] Note:** grouping an issue act with its adoptions carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses), where adoption gates force; without them only obj typing (R4) and the derived status (R19) link the two | **unobs** (O2, O3, O5) |
| CG4 | record | C13, C14, C15, C16, C17, C18 | R7, R8, R9, R21, R22 (record ids); R2, R3 cross into CG3 | R39 (via VP2) | OQ2, OQ5; C16, C17, C18 open | **mixed:** id, r, c obs (partially); k, Ref, con unobs |
| CG5 | work event | C19, C20 | R6; R15 (guard); R18 crosses into CG2 | none directly (conformance slots sit on CV6) | OQ5; C19, C20 open | **unobs** (O8) |
| CG6 | execution trace (capture item) | C24 | R15 (recording order is not decision order or start time; guard) | none | OQ5; C24 open (maps to no t(w), O8) | **obs** (recording order only); no link to CG5 is derivable |

**Grouping finding ([E]; [r1] completed).** **Selection criterion:** a T-A row "crosses" if it reads attributes of concepts placed in two or more groupings. Constraint rows that cross: **R1** (CG2↔CG3), **R2, R3** (CG3↔CG4), **R5** (acts in CG3 typed with actor α in CG1 and target/value in CG2), **R6** (work event in CG5 typed with target in CG2 and executor in CG1), **R8** (record in CG4 with keeper in CG1), **R9** (Ref from CG4 into CG3 and CG5), **R18** (CG2↔CG5), **R19** (CG1 Cap ↔ CG3), **R22** (identity of records in CG4 and of actors in CG1). Guard / non-constraint rows that cross: R14, R15, R16, R17. R4 has no consistency, transaction or lifecycle notion (and a state/transition view is excluded, X15). **The grouping boundaries are therefore not derivable from T1/T2;** they are VP11.

**Stronger point ([r1]).** By **R23 (Lem 1.12)** no T-A constraint over unobserved coordinates can be checked or enforced from records, and every T-A row except R7 (and R22 for record ids) is over such coordinates. **An aggregate-like "enforcement unit" therefore has no operational content in this model:** the groupings are conceptual clusters only, not units within which anything could be enforced.

### 1c. Candidate value-like concepts ([E] candidate, not adopted)

| id | working label | T1 concepts | T-A carried | T-B optional policy slots (not active) | inherited open questions | observability |
|---|---|---|---|---|---|---|
| CV1 | deontic value with a permitting subset | C4 | R5 (typing), R18 (uses P) | none; **P ≠ V is a parameter** (A6), VP13 | none beyond OQ8, OQ9 | not an observed attribute (values appear only inside unobs σ, v) |
| CV2 | norm as target ↦ value | C5 | R1, R5 | none | OQ3 (richer content, extension) | **unobs** (v(a), σ) |
| CV3 | three governance times kept distinct (r, d, f), work start outside them | C22 (with C11, C12, C14, C20) | R3, R14 (no equation), R15 (no order) | none | OQ5; C11, C12, C20 open | **mixed:** r obs (partially); d, f, t(w) unobs |
| CV4 | report-vs-constitution mode of a record–act pair, **unknown-capable** (reports / is / undetermined), with mode-undetermined flag | C18, C27 | R2, R9, R24 | R39 (mode-fixing convention, via VP2) | OQ2, OQ11; C18, C27 open | **unobs** (con, Ref); R24: mode is not fixable from O |
| CV5 | prepared / adopted status (derived) | C10 | R19 | R27 (D-st), R28 (D-st⁺) via VP1b | OQ5; C10 open; status *text* is content only (CV-text, R21) | **unobs** (prep, Cap, d) |
| CV6 | conformance / violation (derived) | C21 | R18 | R32, R33 (universal), R34 (R-b) via VP1c | OQ1, OQ5; C21 open; E01 at recorded bearing only | **unobs** (σ, t(w); Rem 4.10) |
| CV7 | not-evidenced-in-capture marker (absence ≠ non-occurrence) | C23 | R16 (no completeness; guard) | none (the record clauses and their R-i / R-i′ variants are VP1d, not this element) | OQ5 | unreferenced items are **unobs by definition** (O9) |
| CV8 | record independence for a target (predicate) | C25 | none | none | OQ5, OQ7; T-D R70 (meta); C25 open | **unobs** (con, k: O6, O7) |
| CV9 | limitation kind: structural non-identifiability vs instrument unreliability | C28 | none (R23 is MW1) | none | OQ7; T-D R74, R82 | meta-level, not a record attribute |
| — | record text as content (incl. self-asserted force / status) | C15 | R21 | none | none | **obs** (c, partially); content only, never σ, f or con |

### 1d. Candidate occurrences (domain-event-like; [E] candidate, not adopted)

Each is a dated happening. **No order among them is held** beyond d = r under constitution (R3), and that antecedent is unobserved (R15; Rem 1.9a). **No occurrence is said to produce or cause another** (VP1a; X1; Rem 0.1).

| id | working label | T1 concepts | T-A carried | T-B optional policy slots (not active) | inherited open questions | observability |
|---|---|---|---|---|---|---|
| CE1 | issue occurrence (at decision time d) | C8, C11 | R5 | R25, R26, R38 (via VP1a) | OQ4, OQ11, OQ5 | **unobs** |
| CE2 | adoption occurrence (at d; object is an issue act) | C9, C11 | R4, R19 | R27, R28 (via VP1b) | OQ4, OQ11, OQ5 | **unobs** |
| CE3 | entry into force (time f, or never) | C12 | R1, R14 | R27, R28 (via VP1b) | OQ5 | **unobs** (O3) |
| CE4 | norm-state change point | C7 | R20 | **Chg-constraint slot empty:** R25/R26/R38 (H-D: change points coincide with entry into force of capable issue acts) vs R30/R31/R37 (H-B: change points **preceded in time** by work or an issue act; **temporal precedence only, not production**, X1, Rem 0.1), VP1a | OQ1 | **unobs** |
| CE5 | work start | C19, C20 | R6 | R32–R34 (via VP1c) | OQ5 | **unobs** (O8) |
| CE6 | recording | C13, C14 | R7 | none | none | **obs** (r, partially; A07) |

### 1e. Candidate evidential slot and model-wide constraint set

| id | working label | T1 concepts | constraints | inherited open questions | observability |
|---|---|---|---|---|---|
| CS1 | norm-state attestation channel (**unrealized slot**; no instrument shown; E-A5 / Protocol C not authorized) | C26 | T-D (meta) only: R66 (Cor 4.3, necessary), R67 (Prop 4.5), R68 (Prop 4.6), R69 (Lem 4.7), R82 | OQ1, OQ5; C26 open; F01–F03 disclosed gaps | would need σ- or f-dependence; none exists in O |
| MW1 | model-wide: checkability boundary and clause implications | all C-rows | T-A meta R23 (Lem 1.12); T-A implications R12 (Lem 3.6), R13 (Lem 3.3, Prop 3.4), whose antecedents (class membership, clauses) are **not asserted** | OQ1 | R23 **is** the observability criterion |

## 2. Traceability table

Columns per brief §6.2 and F-LOG-0248. Evidence class always includes [E]; IA-CB, IA-WF and Λ1–Λ7 always apply.

| element | T1 ids | T2 ids (tier) | R4 / FROZEN R3 ids | evidence class | inherited assumptions | reading sensitivity | open questions |
|---|---|---|---|---|---|---|---|
| CB1 | C13–C18, C23, C24, C27 | R2, R3, R7, R8, R9, R16, R21, R22, R24 (T-A); R29, R35, R36, R39, R79, R80 (T-B slot) | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7, O9; Lem 1.12; Lem 5.2; Cor 5.3; A02, A03, A05–A07, A11, A15, B06, B09 (caveat), B11, F02, F11, F13, F14, F17, G01, G03, G04, H11 | [F] def; [C] O partial (A07, F13, G01), mode not codable (F17, B11); [T] satisfiable (bK1) | IA-H, IA-A (A3, A7, A8, A9), IA-T | robust as primitives; R24 robust only for 𝒞 = 𝓗′ (H-D cases fail under R-j, not covered under R-d, [I]); record clauses reading-relative (R-c, R-f, R-i, R-i′) | OQ2, OQ5, OQ11 |
| CB2 | C2–C12 | R1, R2, R3, R4, R5, R10, R11, R14, R15, R19, R20 (T-A); R25–R28, R30, R31, R37, R38 (T-B slot) | Def 1.1–1.4, 1.8–1.10; Rem 0.1; Rem 1.9a; Obs O2–O5; Lem 3.7; Rem 9.7, 9.8; A01, A05–A08, A10, B07, D04, F06–F08, F17, G05, G07, H04, H12, H14, H19 | [F] def; [C] unobserved (O2–O5), act layer not codable (F17, B11; bK7); [T] (bK1) | IA-H, IA-A (A1, A2, A5, A6), IA-SC, IA-X, IA-1T | primitives robust; status→force and change clauses reading-relative (R-d, R-e, R-g, R-g⁺, R-j); multi-target untested (R-a) | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11 |
| CB3 | C19, C20, C21 | R6, R15, R17, R18 (T-A); R30–R34, R37 (T-B slot) | Def 1.5, 1.9, 1.10; Obs O8; Rem 3.1a; Rem 4.10; A03, B06, E01 (recorded bearing), G05, H10, H12 | [F] def; [C] not observed (B06, G05); E01 n = 1, confounded | IA-H, IA-A (A4, A6, A7), IA-R3 | B-conf universal vs R-b; B-chg target-tied R-a untested | OQ1, OQ5 |
| CB4 | C25, C26, C28 | R70, R74, R66–R69, R82 (T-D); R62 (T-C, excluded) | Def 4.4, 4.9; Props 4.5, 4.6, 5.4(c); Rem 4.5b, 4.6a, 4.9a, 5.4a; Lem 4.2, 4.7; Cor 4.3; D04, D16, F01–F03, F06, F17, H14 | [F] sufficient / necessary conditions only; [C] NOT_DETERMINABLE (D04), gaps (F01–F03) | IA-A (A5), IA-H, IA-X | Cor 4.3 reading-scoped (Rem 4.3s); fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′, R-j | OQ7, OQ5, OQ1 |
| participant [r1] | C1 | R5, R6, R8 (typing of α, α_W, k), R22 (actor ids) (T-A) | Def 1.2, 1.4–1.6, 1.7a; Obs O8, O9; Lem 1.12; A05, A06 (role names in texts only) | [F] def; [C] identity unobserved; [T] satisfiable (bK1) | IA-H | robust: readings change clauses, not primitives (Def 9.1) | OQ5, OQ10 |
| CG1 | C1, C2 | R10, R22 (T-A) | Def 1.2, 1.4–1.6, 1.7a; Obs O5, O8, O9; Lem 1.12; G07, H19 | [F]; [C] Cap unobserved (G07) | IA-H, IA-SC, IA-X | Cap role reading-relative (R-d: Rem 9.7; R-e drops Cap) | OQ5, OQ10 |
| CG2 | C3, C5, C6, C7 | R11, R20, R1 (T-A); R25, R26, R30, R31, R37, R38 (T-B slot) | Def 1.1, 1.3, 1.10; Obs O4; Rem 0.1; Rem 9.8; A01, A10, B07, D04, F06, H14, D16 | [F]; [C] no σ channel locally (B07), elsewhere NOT_DETERMINABLE (D04) | IA-H, IA-A (A1, A5), IA-T, IA-1T | separation via σ only under Def 3.1 / M-readings (Rem 4.3s); R-a untested | OQ1, OQ5, OQ6 |
| CG3 | C8–C12 | R1, R2, R3, R4, R5, R14, R15, R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.8, 1.9, 1.10; Obs O2, O3; Lem 3.7; A05–A08, B11, F07, F08, F17, G05, H04, H12 | [F] def; [C] not observed (A07, F08), not codable (F17, B11; bK7) | IA-H, IA-SC, IA-X | Adopted reading-independent; D-st vs D-st⁺ (R-d) | OQ4, OQ5, OQ11 |
| CG4 | C13–C18 | R2, R3, R7, R8, R9, R21, R22 (T-A); R39 (T-B slot) | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7; A02, A03, A05–A07, F02, F13, F14, F17, G01, B11 | [F]; [C] partial O (O1); keeper not observed (F14) | IA-H, IA-T, IA-A (A3, A5, A9) | robust (no reading reads con) | OQ2, OQ5 |
| CG5 | C19, C20 | R6, R15, R18 (T-A) | Def 1.5, 1.9; Obs O8; B06, A03, G05 | [F]; [C] (B06) | IA-H, IA-A (A4, A7) | robust | OQ5 |
| CG6 | C24 | R15 (T-A, guard) | Obs O8; B06, A11, A15, G03 | [C] mechanical capture (B06, L1) | IA-H | robust | OQ5 |
| CV1 | C4 | R5, R18 (T-A) | Def 1.1; A6 | [F]; finite results only for V = {p,q}, {p,q,r} (U, U_P2) | IA-H, IA-A (A6 parameter) | robust as primitive | OQ8, OQ9 |
| CV2 | C5 | R1, R5 (T-A) | Def 1.3; Rem 0.1 | [F] | IA-H, IA-X | robust | OQ3 |
| CV3 | C22 (C11, C12, C14, C20) | R3, R14, R15 (T-A) | Def 1.9; Rem 1.9a; A07, G05, H04, H12 | [F]; [C] M-TIME (G05, H04: "must not be equated") | IA-H | robust | OQ5 |
| CV4 | C18, C27 | R2, R9, R24 (T-A); R39 (T-B slot) | Def 1.6, 5.1; F2 (Asm); Lem 5.2; Rem 5.2a; Cor 5.3; Obs O6; F17, B11, G04 | [F] Lem 5.2; [C] not codable (F17, B11); "even as binary" [F]-cited via IF | IA-H (F2), IA-A (A3, A8, A9), IA-RD | 𝒞 = 𝓗′ robust; H-D cases 𝐑₀-scoped (fail R-j; R-d not covered) [I] | OQ2, OQ11 |
| CV5 | C10 | R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.10; Lem 3.7; A05, A06, F07 | [F]; [C] status words in texts; F07 adoption UNKNOWN | IA-H, IA-SC, IA-T | Adopted reading-independent; force implication reading-relative | OQ5 |
| CV6 | C21 | R18 (T-A); R32, R33, R34 (T-B slot) | Def 1.10; Rem 3.1a; Rem 4.10; E01 (recorded bearing) | [F]; [C] E01 weak | IA-H, IA-A (A6), IA-R3 | universal vs R-b | OQ1, OQ5 |
| CV7 | C23 | R16 (T-A, guard) | Def 1.8; A7; Obs O9; F08, F11, F13, G01, H11 | [F] (A7; Lem 4.0a); [C] (F11, G01, H11) | IA-H, IA-A (A4, A7) | Prop 4.1(iii) clause fails literally under R-h | OQ5 |
| CV8 | C25 | R70 (T-D meta); R62 (T-C, excluded) | Def 4.9; Rem 4.9a; Prop 5.4(c); Rem 5.4a; D04, F06, H14 | [F]; [C] NOT_DETERMINABLE | IA-H, IA-A | robust as definition | OQ5, OQ7 |
| CV9 | C28 | R74, R82 (T-D); R23 (T-A meta, via MW1) | Lem 4.2; D16 (L3 formal pointer); F17 | [F]; [C] F17 | IA-A (A5) | D16 under Def 3.1 readings | OQ7 |
| CV-text | C15 | R21 (T-A) | Def 1.6; Rem 1.11a; A5; A02, A05, A06 | [F] modelling choice (A5); [C] L0 texts | IA-T, IA-A (A5) | robust | none |
| CE1 | C8, C11 | R5 (T-A); R25, R26, R38 (T-B slot) | Def 1.4, 1.9; A05, A06, A08, F17 | as CG3 | IA-H, IA-X | as CG3 | OQ4, OQ5, OQ11 |
| CE2 | C9, C11 | R4, R19 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.10; A05, A08 | as CG3 | IA-H, IA-SC | as CG3 | OQ4, OQ5, OQ11 |
| CE3 | C12 | R1, R14 (T-A); R27, R28 (T-B slot) | Def 1.4, 1.8 (F1), 1.9; Obs O3; G05, H12 | [F] def; [C] unobserved, r not an upper bound (G05) | IA-H, IA-A (A2) | robust | OQ5 |
| CE4 | C7 | R20 (T-A); R25, R26, R30, R31, R37, R38 (T-B slot) | Def 1.10 (Chg) | [F] | IA-H, IA-RD | role in D-chg / B-chg reading-relative (R-g, R-g⁺, R-j) | OQ1 |
| CE5 | C19, C20 | R6 (T-A); R32–R34 (T-B slot) | Def 1.5; Obs O8; B06, G05 | [F]; [C] | IA-H | robust | OQ5 |
| CE6 | C13, C14 | R7 (T-A) | Def 1.6, 1.9, 1.11; Obs O1; A07, G01 | [F]; [C] partial (A07) | IA-H | robust | none |
| CS1 | C26 | R66–R69, R82 (T-D meta) | Def 4.4; Props 4.5, 4.6; Rem 4.5b, 4.6a; Lem 4.7; Cor 4.3; F01–F03 | [F] sufficient / necessary only; realization an external claim | IA-A, IA-H | Cor 4.3 reading-scoped (Rem 4.3s) | OQ1, OQ5 |
| MW1 | all C-rows | R23, R12, R13 (T-A meta / implications) | Lem 1.12; Lem 3.3, 3.5, 3.6; Prop 3.4; Def 3.2; G06, H07 | [F] proof | IA-H, IA-A, IA-RD, IA-R3 | R12 fails for H-B under R-i′; R13's B-rec ⇒ D-rec fails under R-i, R-i′; D-chg ⇒ B-chg fails under R-g, R-g⁺, R-k^st ([I]) | OQ1 |

## 3. Exclusions

### 3a. Deliberately left out (T1 X-rows and T2 T-C rows; R4 Rem 0.1)

| excluded | T2 row (T-C) | element that would have hosted it | why left out |
|---|---|---|---|
| X1 causal work→norm response | R50 | CB2↔CB3 link, CE4 Chg-constraint slot | no primitive (Rem 0.1); N1 |
| X2 work outcome / feedback | R81 | CB3 | Rem 0.1; B-fb unformalized; N1 |
| X3 norm content beyond target ↦ value | R54 | CV2 | Rem 0.1; OQ3 (§3b) |
| X4 "record is the act" | R44 | CV4, CB1↔CB2 | N2; held only as unknown-capable CV4 |
| X5 decision time from recording time | R42 | CV3 | N3 (Rem 1.9a) |
| X6 f equated with / bounded by r or d | R43 | CV3, CE3 | N3, N1 |
| X7 "change only through capable acts" as invariant | (R25, T-B slot only) | CE4 Chg-constraint slot | N1: H-D as rule; slot VP1a only |
| X8 D-st / D-st⁺ as invariant | (R27, R28, T-B slot only) | CV5 | N1, N4; slot VP1b only |
| X9 "all work conforms" as invariant | (R32, R33, T-B slot only) | CV6 | N1: H-B as rule; slot VP1c only |
| X10 authorization ⇒ execution | R48 | CB2↔CB3 | H10 MAY_NOT; R17 guard |
| X11 σ computed from acts | R47 | CG2 | N1; R11 guard |
| X12 hold / withdraw / supersede / annotate as act kinds | R55 | CG3 | Rem 0.1; OQ4 (§3b) |
| X13 time-varying capability | R56 | CG1 | Rem 0.1 |
| X14 drafting time | R57 | CV3 | Rem 0.1 |
| X15 state/transition view | R58 | CG2, CG3 lifecycle | Rem 0.1; §2 WITHDRAWN |
| X16 repository access as capability | R60 | CG1 | G07, H19 |
| X17 self-asserted force as σ | R46 | CV-text, CG2 | N2, N3, N5; R21 guard |
| X18 non-occurrence from absence | R49 | CV7 | H11; R16 guard |
| X19 support / frequency / epistemic labels | R59 | CB4, CV9 | Rem 0.1; OQ7 |
| X20 classifying the actual system as H-D or H-B | R51 | whole model | N1; path A |
| X21 independent source exists / none exists | R62 | CV8, CS1 | H14 either way |
| X22 self-stating text fixes mode; Conj 5.5 as explanation | R52 | CV4 | N7 |
| X23 mode-fixing convention as observation | R45 | CV4 | N2; labelled-convention form is slot R39 (VP2) |
| X24 finite U / slice / bundle-02 values as parameters | R53 | CV1, any threshold | §2a; N4 |
| X25 universal record completeness (incl. R-h) | R41 | CV7, CB1 | N1, N4; existential clauses are VP1d slots |
| (non-X) Prop 5.4(b) bound as a rule | R40 | CV3 | N3, N1 (needs O⁺ and H-D) |
| (non-X) R-k reading (rejected) | R61 | CE4 Chg-constraint slot | rejected reading |
| (non-X) office- / role-based rules | R63 | CG1 | no role concept; OQ10 (§3b) |

### 3b. What a fuller domain model would need beyond R4

Every row: **extension beyond R4: needs new evidence or a human design decision.**

| need | source of the gap | which kind of act would close it |
|---|---|---|
| norm content: provisos, conditions, scope, rationale | X3; OQ3; R64; A01 | human design decision |
| distinct act kinds (hold, withdraw, supersede, annotate) | X12; OQ4; R65; A08, A09 | human design decision |
| named roles / offices beyond Cap | OQ10; R76; A05, A06 | human design decision |
| time-varying capability | X13 | human design decision |
| drafting time | X14 | human design decision |
| a lifecycle / state-transition view of norms or acts | X15 | human design decision |
| work outcomes and any causal response relation | X1, X2 | new evidence **and** a human design decision (N1) |
| epistemic-status / evidential-limitation information in the model | X19; OQ7; R74 | human design decision |
| a consistency / transaction notion to fix grouping boundaries | not in R4 (VP11) | human design decision |
| an order or sequence among occurrences | R15 (none in the frame) | new evidence or human design decision (N3 applies) |

### 3c. [r1] Not extensions: within R4 but untested, or evidence needs

| need | classification | source | what would close it |
|---|---|---|---|
| multi-target behaviour of norm concepts | **within R4 but untested** (Def 1.1 allows a finite 𝒯; Rem 9.8: R-a needs ≥ 2 targets, U is one-target) | OQ6; R73 | new evidence (V2, not authorized) |
| a source for the unobserved coordinates (σ, f, d, Cap, k, t(w), con, Ref, actor identities α / α_W per Lem 1.12) | **evidence need** (R4 already has the coordinates) | OQ5; R72; Lem 1.12 | new evidence (not authorized) |
| generality beyond one corpus | **evidence need** | OQ8, OQ9; R75; Λ6 | new evidence (V6, not authorized) |

## 4. Variation points (decisions needed to turn the candidate into an adopted model)

No variation point is resolved here. Default for every slot: **empty / not active.**

| id | decision | slots / rows affected | options (listed neutrally, none preferred) | what the model holds meanwhile | what could decide it |
|---|---|---|---|---|---|
| VP1a | **H-D vs H-B: norm-change regime** | CE4 Chg-constraint slot; CG2; T-B R25, R26, R38 (H-D side), R30, R31, R37 (H-B side: temporal precedence only, not production; X1, Rem 0.1); T-D R71 | adopt an H-D clause / an H-B clause / both / neither (and under which reading of Table 9.4) | no constraint on change points beyond R20 (definition) | not decidable from records (R82; bK2, bK6; path A); a σ/f channel (R66–R68) is not authorized. Only a human act, labelled normative |
| VP1b | **H-D: status → force** | CV5, CE3; T-B R27 (D-st), R28 (D-st⁺, R-d) | adopt D-st / D-st⁺ / neither | Adopted is derived only (R19); no force implication | human act; R28 leaves Lem 4.2's scope (Rem 4.2s) |
| VP1c | **H-B: work conformance** | CV6, CE5; T-B R32, R33 (universal), R34 (R-b) | adopt universal / weak / none | violation is a definition only (R18) | human act; not checkable from records (Rem 4.10) |
| VP1d | **record clauses and their reading variants** | CB1; T-B R29 (D-rec: existential record clause, shared by H-D and H-B), R35 (B-recW: existential record clause, H-B), R36 (B-rec: conjunction of existential record clauses, H-B; weaker R-c / R-f variant), **R79** (R-i: the B-side record clause **replaced by an act-existence clause "E ≠ ∅"**, which references no record), **R80** (R-i′: **no B-side record clause at all**, i.e. the option of dropping it) | adopt any / none | no completeness (R16). The universal form (R-h; X25, R41) is excluded, **[r1.2]** on the H11 / A7 ground: as an invariant it would license inferring non-occurrence from absence (T1 X18; T2 R49), not because it is universal. **[r1.5] Precision:** the A7 conflict is direct (R4 A7 admits items that no record references). The H11 conflict holds only **conditionally**: inferring non-occurrence from absence *in the corpus* also requires capture completeness, which O1, F11 and G01 deny. **Disclosed neutrality asymmetry:** R-h is an H-B reading (R4 Table 9.4), so the exclusion removes one H-B option with no matching H-D loss, justified by the H11 / A7 conflict. **Human alternative (not adopted):** a T-B optional-policy form of R-h's universal clause, with the non-occurrence inference not applied to the observed capture (it cannot be disallowed inside the model) | human act; R29, R35, R36 each encode H-D and/or H-B; R79, R80 are H-B readings (R-i, R-i′); admitting the R-h alternative is a human decision |
| VP2 | **constitution (OQ2)** | CV4, CB1↔CB2; T-B R39; T-D R78 | adopt a labelled mode-fixing convention for a class of records / keep mode unknown | unknown-capable mode (CV4); every pair ambiguous (R24) | human act (convention); fact not decidable (bK7, bK8; V5 not offered) |
| VP3 | **act identifiability (OQ11)** | CG3, CE1, CE2, CB2; T-D R77 | treat acts as identifiable in records / not | acts held as unobserved; rules over acts uncheckable | non-Claude coder or V6 (not authorized); human act |
| VP4 | **roles / offices (OQ10)** | CG1, participant; T-D R76; T-C R63 | add a role concept (extension) / keep Cap only | Cap only, constant, unobserved | human design decision |
| VP5 | **corpus specificity and adequacy (OQ8, OQ9)** | every element; T-D R75 | accept one-corpus primitives as model basis / defer to V6 | primitives satisfiable only (bK1); adequacy [E]/[I] | V6 (not authorized); human act |
| VP6 | population of unobserved coordinates (OQ5): σ, f, d, Cap, k, t(w), con, Ref, actor identities α / α_W (per Lem 1.12) | CG1–CG3, CG5, CV3–CV8; T-D R72 | name a source / leave unpopulated | unpopulated; R2–R6, R8–R13, R18–R20 uncheckable | new evidence (not authorized) |
| VP7 | norm content (OQ3) | CV2; T-D R64 | extend / keep target ↦ value | target ↦ value only | human design decision (§3b) |
| VP8 | act kinds (OQ4) | CG3; T-D R65 | distinct kinds / R4's mapping | R4's mapping | human design decision (§3b) |
| VP9 | multi-target (OQ6) | CG2, CV2; T-D R73; T-B R31 | keep R4's finite 𝒯 (multi-target allowed by Def 1.1, untested under R-a) / restrict to one target (**a restriction of R4**, not a neutral default) | R4's finite 𝒯; only one-target behaviour is tested | V2 (not authorized); a restriction needs a human design decision |
| VP10 | evidential-limitation side (OQ7) | CB4, CV8, CV9, CS1; T-D R74 | include CB4 / drop it | CB4 as an open slot | human design decision |
| VP11 | grouping granularity (aggregate-like boundaries) | CG1–CG6; T-A R1, R2, R3, R5, R6, R8, R9, R18, R19, R22 cross groupings (criterion in §1b) | e.g. merge CG2+CG3, or keep separate with cross-grouping constraints | groupings as listed, explicitly provisional, conceptual only (no enforcement unit: R23) | human design decision; R4 has no consistency notion |
| VP12 | participant: one shared concept or boundary-local roles (norm-maker / executor / keeper) | participant, CG1, CB1–CB3 | one shared participant concept / separate boundary-local concepts | one actor set 𝒜 (Def 1.2) | human design decision; interacts with VP4 |
| VP13 | permitting subset parameter (A6: P ≠ V) | CV1; R18 | P ≠ V / P = V | parameter, unset | human design decision (N5) |
| VP14 [r1] | **boundary partition itself** | CB1–CB3; T-B R25–R34, R37, R38 | P-a / P-b / P-c / P-d (§1a); none preferred. P-a (as listed) is H-D-congenial | P-a shown as a working layout only, disclosed as H-D-congenial | human design decision; must not encode H-D or H-B (N1) |
| VP15 [r1] | **accept R4's modelling choices (N5)** | σ as a primitive (R11; Def 1.3); Cap constancy (R10; Def 1.2); A5 / Rem 1.11a (R21); O as the base observation (R23; Def 1.11); F2 as an Asm (R2, R3; Def 1.8) | accept each / replace each (any replacement is an extension beyond R4) | all held as R4 modelling choices, never as corpus facts | human design decision; adequacy [E]/[I] (bK1) |
| VP16 [r1] | **trace-to-work link** (CG6 ↔ CG5) | C24, C19, C20; R15; Obs O8 | add a link (would need t(w) or a mapping, not read off recording order: N3, H12) / leave none | no link | new evidence or human design decision |

## 5. Standing qualifier

"one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 6. Self-check

| check | result |
|---|---|
| No element is adopted | yes: every element is "[E] candidate, not adopted"; §4 lists the decisions still needed; nothing resolves a VP |
| No T-B, T-C or X item is active as an invariant | yes: T-B rows appear only in "optional policy slot (not active)" columns and VP1a–d, VP2, VP14. **[r1] corrected:** T-C rows and X-concepts appear in §3a, in the §1a "not held" column (X1, X4, X5, X10, X21; R42, R44, R48, R50, R62) and in §2 (R62 on CB4 and CV8), always labelled as excluded / NOT admissible, never as carried. T-D rows only as open questions / VPs |
| Only T-A rows carried as candidate invariants | yes: R1–R11, R14–R22 on elements; R12, R13, R23, R24 as implications / meta (MW1, CV4) |
| Every element traces to T1 and T2 ids | yes **[r1] corrected:** r0 lacked a §2 row for the participant (C1); it is added. Every row of §1 and §2 now names T1 C-ids and T2 R-ids. CB4, CV8, CV9 and CS1 carry no T-A row; they trace to T-D (and T-C exclusion) rows only, stated as such |
| Every C1–C28 placed | yes: C1 participant; C2 CG1; C3, C5–C7 CG2; C4 CV1; C8–C12 CG3; C13–C18 CG4; C19, C20 CG5; C21 CV6; C22 CV3; C23 CV7; C24 CG6; C25 CV8; C26 CS1; C27 CV4; C28 CV9 |
| No X-concept used except as exclusion | yes: X1–X25 appear only in §3a / §3b, in the §1a "not held" column and as "excluded" mentions in §2, always labelled as excluded |
| H-D / H-B neutrality preserved | yes, **[r1] with disclosure:** CE4 has an empty Chg-constraint slot, and its H-B options are temporal precedence only, not production; the CB1–CB3 cut (P-a) is disclosed as H-D-congenial and made a variation point (VP14) with H-B-congenial and neutral alternatives; CG3's issue/adoption grouping is noted as meaningful mainly under H-D clauses (VP1b); CB2↔CB3 holds only the derived conformance predicate; H-D-side and H-B-side slots are listed side by side in VP1a–d with no default; **[r1.2]** one disclosed asymmetry: the universal R-h record clause (an H-B reading) is excluded (X25, R41) on the H11 / A7 ground, with no matching H-D exclusion; a T-B form with the non-occurrence inference **not applied to the observed capture** (inside the model R-h entails that inference, so it cannot be "disallowed"; a policy can only forbid applying it to the incomplete record capture) is left as a human alternative (VP1d); R11 (σ not computed from acts) and R17 (no norm→work dependence) are frame absences, used as guards, not as a choice |
| Observability stated per element | yes: only record ids, r, c observed (partially); everything else unobs (Lem 1.12; R23) |
| N-list | N1–N3, N7 touched only via guards, slots or exclusions; N4 via reading-sensitivity columns and X24; N5 via VP13, VP5; N6: no quarantined item or B10 used; N8: every name a working label |
| No architecture, persistence, API, technology, implementation or canonical-term decision | yes: "boundary", "grouping", "value-like", "occurrence" are structuring hypotheses only; no service, layer, store, interface or term is chosen |
| Waived not presented as satisfied | yes: standing qualifier; IA-CB, IA-WF and Λ1–Λ7 on every element |
| §2a finite-only values not used | yes: excluded (X24, R53); CV1 notes the finite V only as scope |

**Counts (r1.2, unchanged from r1):** **29 candidate elements** (4 boundaries + 1 shared participant; 6 groupings; 10 value-like, CV1–CV9 plus CV-text; 6 occurrences; 1 evidential slot; 1 model-wide set; r0 miscounted this as 33). **19 variation points** (VP1a–d, VP2–VP16). 28 exclusions in §3a (X1–X25 plus R40, R61, R63); **10 extensions beyond R4** in §3b; 3 items reclassified to §3c (within R4 but untested, or evidence needs).

**Where T1/T2 were insufficient to decide (disclosed):**
1. **Grouping boundaries** (VP11): ten T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and four guard rows cross the candidate groupings; R4 has no consistency, transaction or lifecycle notion; and by R23 no grouping could be an enforcement unit.
2. **Participant placement** (VP12): R4 has one actor set serving as norm-maker, executor and keeper; T1/T2 do not decide one shared concept vs boundary-local ones.
3. **Execution trace ↔ work event** (CG6): no T2 row links C24 to C19; the link is left empty (O8) and is VP16.
4. **CB4's existence** depends on OQ7 (VP10); CB4, CV8, CV9 and CS1 carry no T-A constraint at all.
5. **Occurrences:** R4 has no event notion and imposes no order (R15), so no event sequence and no production relation can be stated.
6. **Issue-act / adoption grouping** (CG3): R4 gives only obj typing (R4); whether adoptions belong with their object is undecided (part of VP11).
7. **MW1 implications** R12, R13 attach to no single element.
8. **[r1] Boundary partition** (VP14): T1/T2 give no criterion for the CB cut; the r0 cut is H-D-congenial, so the partition is left open.

## 7. r1 changes (review of r0: PASS_WITH_LIMITATIONS)

| # | review item | change |
|---|---|---|
| 1 | participant trace row; element count | §2 participant row added; count corrected to 29 (4 + 1 + 6 + 10 + 6 + 1 + 1); §6 counts and handback reconciled |
| 2 | §1b and VP11 | "consistency clusters" removed; crossing-row selection criterion stated and list completed (R5, R6, R8, R9, R22 added; guard rows R14–R17 listed); R23 point added: no aggregate-like enforcement unit has operational content |
| 3 | CE4 and VP1a | "producer slot" → "Chg-constraint slot" throughout (§0, §1d, §3a, VP1a, §6); H-B options stated as temporal precedence only, not production (X1, Rem 0.1) |
| 4 | CB1–CB3; CG3 | partition disclosed as H-D-congenial (H-D clauses internal to CB2; H-B clauses cross CB2↔CB3); alternatives P-b (r1.1: only partially H-B-congenial), P-c, P-d (neutral) listed as options; new VP14; CG3 note on D-st / D-st⁺ |
| 5 | new VPs | VP15 (accept R4's modelling choices, N5: R11, R10, R21, R23, R2/R3); VP16 (CG6 ↔ CG5 link) |
| 6 | §3b reclassification | multi-target (within R4, untested), unobserved-coordinate source (evidence need), generality (evidence need) moved to new §3c; VP9 reworded ("restrict to one target" restricts R4) |
| 7 | CB2 observability | "unobs throughout" → "mixed" (r via R3) |
| 8 | §6 self-check | count, participant traceability, and T-C / X appearances (§2 via R62; §1a "not held" column; always labelled excluded) corrected |
| — | consequential | status line → r1; §0 section cross-references corrected (§3 exclusions, §4 variation points); insufficiency item 8 added |
| r1.1 | verification review (PASS_WITH_LIMITATIONS) | §1a partition table: P-b restated as only partially H-B-congenial (the change clauses still cross acts↔σ via their act disjunct); P-c and P-d's "internal" claim scoped to the change and conformance clauses, with the record clauses crossing symmetrically; P-d's split of concepts by attribute noted. Applied by the main analyst |
| r1.2 | cross-artifact audit (F-LOG-0250 batch) | **VP1d:** retitled "record clauses and their reading variants"; R79 relabelled as the R-i act-existence clause "E ≠ ∅" (references no record) and R80 as the R-i′ absence of any B-side record clause (neither is an existential record clause); the X25 / R41 exclusion re-grounded on the H11 / A7 conflict (non-occurrence from absence; T1 X18, T2 R49), with the neutrality asymmetry (R-h is an H-B reading; no matching H-D loss) and the human alternative (T-B form with the inference disallowed) disclosed (analyst decision, carried from T1 r1.4 / T2 r1.2). CB1 slot labels and CV7 note aligned. **VP6 and §3c:** unobserved-coordinate list unified with T1 OQ5 and T2 R72 (σ, f, d, Cap, k, t(w), con, Ref, α / α_W per Lem 1.12). §6 neutrality row discloses the asymmetry. Status line → r1.2, "reviewed; corrections verified; awaiting human review (F-LOG-0250)"; input refs → T1 r1.4, T2 r1.2. Counts unchanged |
| r1.3 | residual from the r1.2 worker | §1a P-c and P-d: the symmetric-crossing claim is restricted to the existential record clauses under 𝐑₀; R79 (references no record) and R80 (no B-side record clause) make the crossing asymmetric under R-i and R-i′. Applied by the main analyst |
| r1.4 | cross-artifact verification (PASS_WITH_LIMITATIONS) | VP1d: H11 conflict conditional on capture completeness, A7 direct; human alternative reworded (not applied to the observed capture); status line aligned with the title. Applied by the main analyst |
