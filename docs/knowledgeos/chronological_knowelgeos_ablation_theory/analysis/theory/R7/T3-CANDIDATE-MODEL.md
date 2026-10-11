# R7 · T3: candidate domain model with traceability (draft r1.6)

**Status:** r1.6, post-closure amendment under F-LOG-0266 (VP14 = P-c, F-LOG-0265); nothing adopted; E2b not amended; awaiting independent review. **[r1.6]** Re-derives §1a, the §2 boundary rows, the §3a host column (X1, X2, X4, X10, X19, X25), the §4 VP1d / VP2 / VP3 / VP10 / VP11 / VP12 / VP14 rows and the affected §6 rows under VP14 = P-c, with the participant provisionally inside CG1 (to be re-checked after VP12), per `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 §2.9. VP10 = (iii) re-affirmed (F-LOG-0263; counts for O3, F-LOG-0264); VP12 and VP11 not decided. The CG, CV, CE, CS and MW rows are unchanged. It decides no variation point and authorizes no O3 proposal, E3, E3b, E4 or E5 (§7). **[r1.5 status, kept as history]** R7 task artifact T3, draft r1.5, 2026-10-11. r0 (2026-10-10) was reviewed PASS_WITH_LIMITATIONS; r1 applies every required change, r1.1 the verification correction, and r1.2 the cross-artifact audit fixes (§7). Authorized by F-LOG-0248 under the boundary of `R7-AUTHORIZATION-BRIEF.md` r3 §6; input scope per F-LOG-0249 (direct citation of R4 r3.1 statements and FROZEN R3 items; never QUARANTINED items or B10). Builds **only** on `R7/T1-CONCEPT-CANDIDATES.md` r1.4 (C1–C28, X1–X25, OQ1–OQ11, IA-*) and `R7/T2-RULE-CANDIDATES.md` r1.4 (R1–R83, tiers T-A/T-B/T-C/T-D), with R4 r3.1 used for checking. **Reviewed; corrections verified; R7 closed by F-LOG-0253; amended under F-LOG-0254. Nothing here is adopted.** **[r1.5] Amendment under human ruling, post-closure (F-LOG-0254; R7 closed by F-LOG-0253):** VP1d gains T2 R83 (T2 r1.4: R1–R83; T-B 18 rows) as an inactive slot; T1 r1.6 X25 (§1a, §2, §3a, §4, §6, §7).

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

**[r1.6] Re-derivation under VP14 = P-c (decided F-LOG-0265; re-derivation authorized F-LOG-0266; scope and review criteria: `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 §2.9).** P-c is "one undivided norm-and-work side │ records". Nothing here is adopted and E2b is not amended: until the E2b amendment of the "no boundary layer" basis is made by its own act, the adopted model stays as in F-LOG-0261.
- **Prerequisite (participant):** the re-derivation is performed with the participant (C1) **provisionally inside CG1**, as §1b does. CG1's other concept, C2, lies on the norm-and-work side, so C1 is counted there for the crossing criterion below. **This is to be re-checked after VP12**, and under VP12 (ii) partly re-derived by its own authorization.
- **VP10 = (iii)** (re-affirmed F-LOG-0263; counts for the O3 minimal set, F-LOG-0264): **CB4 is not a decided side.** It stays an explicit open slot, and CV8, CV9 and CS1 stay adopted and ungrouped. **[r1.6 review fix K4]** VP10 = (iii) means "leave open as a slot" (register r1.13, VP10). CB4 is therefore not a third side, and it forms no peer partition with CB1 and CB23. Placing C25, C26 and C28 "in the CB4 open slot" is T3's candidate labelling, carried from r1.5. It is not a grouping in the adopted model (F-LOG-0263: CV8, CV9 and CS1 "stay adopted and ungrouped").
- **VP12 and VP11 are not decided.** The cut is stated by concept placement only and makes no reference to any VP1a–d outcome (N1); every T-B row stays an inactive slot.

**[r1.6] Id mapping (r1.5 → r1.6; no trace is lost).**

| r1.5 id / relation | r1.6 id / relation | note |
|---|---|---|
| CB1 record / capture side | **CB1** records side | reused: same concepts (C13–C18, C23, C24, C27); it is the P-c "records" side |
| CB2 norm side | **CB23** norm-and-work side | merged: CB23 = CB2 ∪ CB3 (the P-c undivided side) |
| CB3 work side | **CB23** norm-and-work side | merged (as above) |
| CB4 evidential-limitation side (open slot) | **CB4** open slot | unchanged; **not a P-c side** (VP10 = (iii)) |
| participant, "shared across CB1–CB3" | participant, provisionally inside CG1 | counted on CB23 for the crossing criterion; stated exception (spans CB1 and CB23 under VP12 (i)) |
| relation CB2 ↔ CB1 | CB23 ↔ CB1 | boundary relation |
| relation CB3 ↔ CB1 | CB23 ↔ CB1 | boundary relation (merged with the row above) |
| relation CB2 ↔ CB3 | internal to CB23 | no longer a boundary relation |
| "CB1–CB3" (VP12, VP14) | "CB1 and CB23" | — |

**[r1.6] Candidate boundaries under P-c ([E] candidate, not adopted).** Crossing rows are listed on both sides and marked "shared".

| id | working label | T1 concepts | T-A carried (candidate model invariants) | T-B optional policy slots (not active; need a human act) | inherited open questions (OQ + T1 open status) | observability |
|---|---|---|---|---|---|---|
| CB1 | **records side** (P-c "records"; r1.5 CB1 reused) | C13, C14, C15, C16, C17, C18, C23, C24, C27 | internal: R7. Shared with CB23 (crossing): R2, R3, R8, R9, R22 (constraint rows); R14, R15, R16, R21 (guard form); R24 (meta, hosted at CV4). R12, R13 and R23 also cross, but are carried at MW1 | all crossing, shared with CB23: R29, R35, R36 (existential record clauses), R83 (R-h universal record clause as optional policy; inactive slot, F-LOG-0254), via VP1d; R39 (mode-fixing convention), via VP2. (R79 and R80 stay VP1d options but are not record-side under the criterion: see CB23) | OQ2, OQ5, OQ11; C16 keeper no source; C17 codability rests on IF; C18 no reliable fill; C24 maps to no work start; C27 every pair ambiguous | **mixed:** record ids, r, c obs (partially, O1); k, Ref, con unobs |
| CB23 | **norm-and-work side** (P-c "one undivided norm-and-work side"; = r1.5 CB2 ∪ CB3) | C2, C3, C4, C5, C6, C7, C8, C9, C10, C11, C12, C19, C20, C21 | internal: R1, R4, R5, R6, R10, R18, R19, R20; R11, R17 (guard form). Shared with CB1 (crossing): R2, R3, R8, R9, R22 (constraint rows); R14, R15, R16, R21 (guard form). R12, R13 and R23 at MW1. R5, R6, R8, R10 and **[r1.6 L1]** R19 read the actor set via C1 (participant-dependent; R25 likewise on the T-B side) | internal: R25, R26, R27, R28, R38 (H-D-side change and status clauses, via VP1a, VP1b); R30, R31, R37 (H-B-side change clauses, via VP1a); R32, R33, R34 (conformance clauses, via VP1c); R79 (R-i: "E ≠ ∅", references no record, via VP1d). No clause: R80 (R-i′, via VP1d). **[r1.6 review fix K3]** R80 is hosted here only as the H-B-side VP1d option, the alternative to R35 and R36. It is not hosted here by K-c: it states no clause and reads no placed item, so its K-c result is "none" (crossing table). The two entries are consistent. Shared with CB1 (crossing): R29, R35, R36, R83 (VP1d); R39 (VP2) | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11; C2, C6, C11, C12, C20 no source; C7, C10 derived from unobserved; C3 multi-target untested; C8/C9 act identifiability; C19 executor unobserved; C21 depends on σ and t(w) | **mixed:** ~~r enters only through R3~~ **[r1.6 review fix K8]** r enters through R3 (d = r under constitution, shared with CB1), whose antecedent con is unobserved, and through the shared guards R14 and R15, which deny an equation or order between r and d, f (t(w)). All CB23 attributes (σ, f, d, Cap, acts, work events, t(w)) are **unobs** |
| CB4 | **evidential-limitation open slot** (**not a P-c side**; VP10 = (iii), re-affirmed F-LOG-0263; may not exist at all) | C25, C26, C28 | none (no T-A row has these as subject; R23 is model-wide, MW1) | none | OQ7; C25 not certifiable (Prop 5.4(c)); C26 no instrument shown; C28 touches X20 | **unobs** (con, k, σ, f) |
| — | **participant** (provisionally inside CG1, §1b; counted on CB23 for the crossing criterion; **stated exception:** under VP12 (i) one shared concept spanning CB23 (norm-maker α, executor α_W) and CB1 (keeper k); re-check after VP12) | C1 | R22 (actor ids), R5, R6, R8 (typing of α, α_W, k); **[r1.6 L1]** typing rows only; the full participant-dependent list is in the §1a sensitivity (R5, R6, R8, R10, R19, R25, R63, R76) | none | OQ10, OQ5; C1 identity unobserved | **unobs** |

**[r1.6] Placement of C1–C28 (review check; derived, then verified).**
- **CB1 (9):** C13, C14, C15, C16, C17, C18, C23, C24, C27 (r1.5 CB1, unchanged).
- **CB23 (14):** C2–C12 (r1.5 CB2: 11) and C19, C20, C21 (r1.5 CB3: 3). Verified: the union of CB2 and CB3 is disjoint, and no C-id is gained or lost. The expected content (C2–C12, C19–C21) holds.
- ~~**CB4 open slot (3):** C25, C26, C28. They are held in the open slot, on **no** P-c side (VP10 = (iii)).~~ **[r1.6 review fix K4]** This is superseded: these three are stated exceptions (below), not a third category.
- **Stated exceptions (5)** **[r1.6 review fix K4; was 2]** :
  - **C25, C26, C28:** held in the CB4 open slot, on no side (VP10 (iii); register r1.13). This is T3's candidate labelling carried from r1.5, not a grouping in the adopted model (F-LOG-0263: CV8, CV9 and CS1 "stay adopted and ungrouped").
  - **C1** (participant): provisionally in CG1, so on CB23 for the crossing criterion; it spans CB1 and CB23 under VP12 (i). Re-check after VP12.
  - **C22** (three governance times kept distinct): **unplaced in the CB layout**. It appears only under CV3, whose times straddle the cut: r (C14) lies on CB1; d (C11) and f (C12) lie on CB23; t(w) (C20) is on CB23, outside the governance times.
- **Total:** 9 + 14 + 5 = 28 **[r1.6 review fix K4; was "9 + 14 + 3 + 2"]** No C-id is on two sides.

**[r1.6] Crossing criterion (K-c; analyst statement [E], applied to every T2 r1.4 row R1–R83).**
1. A T2 row **crosses** the P-c cut iff its statement (T2 r1.4 "statement (working label)" column, including the clauses it names) reads **at least one item placed on CB1 and at least one item placed on CB23**. **[r1.6 review fix K3]** A **T-B row** is judged by its clause text only (see rule 3). A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies. For example, R14 denies an equation between f, d (CB23) and r (CB1), so it crosses.
2. **Items are placed by the concept table above, at the level at which the statement names them.** A named concept counts at its own placement. A spelled-out coordinate counts at the placement of its concept: records, record identity and O → C13 (CB1); r → C14; c → C15; k → C16; Ref → C17; con / mode → C18 (all CB1); Cap → C2; σ, Chg → C6, C7; acts, obj, prep, Adopted → C8–C10; d → C11; f → C12; work events, τ(w), t(w) → C19, C20; conformance / P → C21, C4 (all CB23); actors 𝒜, α, α_W and actor identity → C1, provisionally CB23 via CG1. Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to.
3. **Class rule:** a statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' 𝐑₀ clauses (Def 3.1). These include the record clauses (D-rec, B-recW), so such a row crosses. The rule does **not** apply to a T-B row that states a clause or reading of a hypothesis: T-B rows are assessed by their clause text only.
4. **Row references:** a row that refers to other T2 rows (by id or as a set) is assessed by the items its own statement reads, not through those rows.
5. **CB4:** items in the CB4 open slot (C25, C26, C28) are on no P-c side. A row reading them is marked "touches CB4", and it crosses only if it also reads both CB1 and CB23 items. A row reading no placed item (an absent clause, finite parameters, an item outside R4) is "none".

**[r1.6] Crossing table (all 83 T2 r1.4 rows; verified row by row against T2 r1.4).** "C1*" marks an item placed via the provisional participant placement (participant-dependent).

| id | tier | reads on CB1 | reads on CB23 | result |
|---|---|---|---|---|
| R1 | T-A | — | σ, f, τ(a), v(a) | internal CB23 |
| R2 | T-A | con, Ref | act a | **crosses** |
| R3 | T-A | con, r | d | **crosses** |
| R4 | T-A | — | adoption act, obj, issue act | internal CB23 |
| R5 | T-A | — | E^iss, E^adp, α (C1*), d, τ, v, prep, f | internal CB23 (C1*) |
| R6 | T-A | — | τ(w), α(w) (C1*), t(w) | internal CB23 (C1*) |
| R7 | T-A | records, r, c | — | internal CB1 |
| R8 | T-A | k(ρ) | 𝒜 (C1*) | **crosses** (C1*) |
| R9 | T-A | Ref, con, records | E, W | **crosses** |
| R10 | T-A | — | Cap, 𝒜 (C1*) | internal CB23 (C1*) |
| R11 | T-A guard | — | σ, E | internal CB23 |
| R12 | T-A implication | D-rec (class rule: H-D ∪ H-B) | E | **crosses** (class rule) |
| R13 | T-A implication | B-rec, D-rec, B-recW (Ref) | D-chg, B-chg, B-conf, D-st, acts | **crosses** |
| R14 | T-A guard | r | f, d | **crosses** |
| R15 | T-A guard | r | d, f, t(w) | **crosses** |
| R16 | T-A guard | records referencing (Ref) | acts, work events | **crosses** |
| R17 | T-A guard | — | norm value, acts, work events | internal CB23 |
| R18 | T-A | — | σ, t(w), τ(w), P | internal CB23 |
| R19 | T-A | — | prep, Cap with α (C1*), d, obj | internal CB23 (C1*) **[r1.6 review fix K1]** |
| R20 | T-A | — | σ (Chg) | internal CB23 |
| R21 | T-A guard | c | σ, f | **crosses** |
| R22 | T-A | record ids | actor (C1*), act, work-event ids | **crosses** (also without C1*) |
| R23 | T-A meta | O, Ref, con, k | σ, f, d, Cap, 𝒜, E, W | **crosses** |
| R24 | T-A meta | Ref, O(h), con | acts | **crosses** |
| R25 | T-B | — | Chg, issue act, τ, f, v, σ, Cap with α (C1*) | internal CB23 (C1*) **[r1.6 review fix K1]** |
| R26 | T-B | — | Chg, issue acts, f, value | internal CB23 |
| R27 | T-B | — | f, Adopted | internal CB23 |
| R28 | T-B | — | Adopted, f | internal CB23 |
| R29 | T-B | Ref | a ∈ E | **crosses** |
| R30 | T-B | — | Chg, issue act, f, t(w) | internal CB23 |
| R31 | T-B | — | τ(w), τ (work disjunct) | internal CB23 |
| R32 | T-B | — | σ, t(w), τ(w), P | internal CB23 |
| R33 | T-B | — | work events, conformance | internal CB23 |
| R34 | T-B | — | some w, conformance | internal CB23 |
| R35 | T-B | Ref | w ∈ W | **crosses** |
| R36 | T-B | Ref (D-rec ∧ B-recW; also the R-c / R-f variant) | acts, work events | **crosses** |
| R37 | T-B | — | change, work, act entering into force | internal CB23 |
| R38 | T-B | — | D-chg, Chg | internal CB23 |
| R39 | T-B | records, con ("act and after-state are the same record") | act | **crosses** |
| R79 | T-B | — | E ≠ ∅ (references no record) | internal CB23 |
| R80 | T-B | — | — (no B-side record clause) | none |
| R83 | T-B | Ref, records | every act and work event | **crosses** (universal) |
| R40 | T-C | r | f | **crosses** (excluded) |
| R41 | T-C | Ref, records | every act and work event | **crosses** (excluded) |
| R42 | T-C | r, recording order | d, decision order | **crosses** (excluded) |
| R43 | T-C | r | f, d | **crosses** (excluded) |
| R44 | T-C | record (con) | act | **crosses** (excluded) |
| R45 | T-C | con, "what the record shows" | act | **crosses** (excluded) |
| R46 | T-C | c | σ | **crosses** (excluded) |
| R47 | T-C | — | σ, E | internal CB23 (excluded) |
| R48 | T-C | — | authorization (norm value), execution | internal CB23 (excluded) |
| R49 | T-C | absence in the record | occurrence | **crosses** (excluded) |
| R50 | T-C | — | work, norm change | internal CB23 (excluded) |
| R51 | T-C | (class rule: H-D, H-B) | (class rule) | **crosses** (class rule; excluded) |
| R52 | T-C | c, mode (con) | act | **crosses** (excluded) |
| R53 | T-C | — | — (finite values as parameters) | none (excluded) |
| R54 | T-C | — | norm content | internal CB23 (excluded) |
| R55 | T-C | — | act kinds | internal CB23 (excluded) |
| R56 | T-C | — | Cap | internal CB23 (excluded) |
| R57 | T-C | — | — (drafting time is not an R4 item; Rem 0.1) | none (excluded) |
| R58 | T-C | — | norms, acts (transitions) | internal CB23 (excluded) |
| R59 | T-C | records | — (labels: C28, CB4) | CB1, touches CB4; no P-c crossing (excluded) |
| R60 | T-C | — | Cap (host membership is not an R4 item) | internal CB23 (excluded) |
| R61 | T-C | — | B-chg act disjunct, Adopted, Cap | internal CB23 (excluded) |
| R62 | T-C | corpus record | — (independence, source: C25, C26, CB4) | CB1, touches CB4; no P-c crossing (excluded) |
| R63 | T-C | — | adoption, C1* (roles are not an R4 item) | internal CB23 (C1*; excluded) |
| R81 | T-C | — | work outcomes, norm changes | internal CB23 (excluded) |
| R64 | T-D | — | norm content | internal CB23 |
| R65 | T-D | — | act kinds | internal CB23 |
| R66 | T-D meta | records, keepers, Ref, con | σ, f, Cap, acts, work events (and the classes) | **crosses**; touches CB4 via its host CS1 only |
| R67 | T-D meta | (class rule: H-D, H-B) | work event target and start, value ∉ P | **crosses** (class rule); touches CB4 (Q, C26) |
| R68 | T-D meta | (class rule: H-D) | σ at two times, capable adopted act, Cap | **crosses** (class rule); touches CB4 (Q′, C26) |
| R69 | T-D meta | (class rule: "separate") | σ, work event start | **crosses** (class rule); touches CB4 (Q, C26) |
| R70 | T-D meta | con, k, record | act on τ, norm-maker, executor | **crosses**; touches CB4 (C25) |
| R71 | T-D | — | D-chg vs B-chg (row range R25–R38 not read through: rule 4) | internal CB23 |
| R72 | T-D | k, con, Ref | σ, f, d, Cap, t(w), α / α_W | **crosses** |
| R73 | T-D | — | multi-target change (R31) | internal CB23 |
| R74 | T-D | — | — (C28, CB4) | touches CB4 only; no P-c crossing |
| R75 | T-D | — | — (the T-A set as a whole: rule 4) | none |
| R76 | T-D | — | roles beyond Cap: C1*, C2 | internal CB23 (C1*) |
| R77 | T-D | records | acts | **crosses** |
| R78 | T-D | record, mode (con) | act | **crosses** |
| R82 | T-D meta | O, O_¬σf (reference, mode coding) | σ, f, work events, classes | **crosses** |

**Counts (83 rows):**

| tier (rows) | crosses | internal CB23 | internal CB1 | CB4 touch, no crossing | none |
|---|---|---|---|---|---|
| T-A (24) | 13 | 10 | 1 | 0 | 0 |
| T-B (18) | 5 | 12 | 0 | 0 | 1 |
| T-C (25) | 10 | 11 | 0 | 2 | 2 |
| T-D (16) | 9 | 5 | 0 | 1 | 1 |
| **total (83)** | **37** | **38** | **1** | **3** | **4** |

**Full crossing list (37):**
- **T-A (13), carried as candidate invariants on both sides:** R2, R3, R8, R9, R22 (constraint rows); R14, R15, R16, R21 (guard form); R12, R13 (implications, MW1); R23 (meta, MW1); R24 (meta, CV4).
- **T-B (5), inactive slots only:** R29, R35, R36, R83 (VP1d), R39 (VP2).
- **T-C (10), excluded, recorded only:** R40, R41, R42, R43, R44, R45, R46, R49, R51, R52.
- **T-D (9), open questions / meta only:** R66, R67, R68, R69, R70, R72, R77, R78, R82.

**Sensitivity (disclosed):**
- **Class rule:** R12, R51, R67, R68 and R69 cross only through rule 3. Without that rule the total would be 32.
- **Row references (rule 4):** R71 and R75 are not read through the rows they name. If they were, R71 would cross through R29, R35 and R36.
- **Participant-dependent (C1*):** R5, R6, R10, R63 and R76 **[r1.6 review fix K1]** and R19 and R25 (they name α in "(α(a), adp) ∈ Cap" and "(α(a), iss) ∈ Cap") are internal to CB23, and R8 crosses, because C1 is provisionally in CG1. Under VP12 (ii) (for example a keeper role local to CB1), R8 could become internal to CB1. R22 crosses without C1 (record ids against act and work-event ids). **Re-check after VP12.**
- **[r1.6 review fix K1]** **The 𝐑₀ neutrality result depends on the participant placement.** "The change and conformance clauses of both families are internal" holds because C1 is counted on CB23. Suppose instead that C1 spans CB23 and CB1, as in VP12 (i) ("one shared concept spanning CB23 and CB1"). Then R25 (H-D, D-chg), R5, R6, R10, R19, R63 and R76 would cross. The H-B change and conformance clauses (R30–R34, R37) name no actor, so they would not. The result would then be **H-D-asymmetric**: an H-D change clause would cross while no H-B change clause does. The VP12 re-check must re-test R25 and this result (§4 VP12). Under VP12 (ii), the norm-maker and executor are CB23-local, so R5 and R6 stay internal; their C1* marking is conservative.
- **[r1.6 review fix K2]** **Rule 2's relational clause** ("Ref and con relate records to acts or work events …"): R52 and R45 cross only through it, because they name mode / con but no act directly. Without the clause they would be internal to CB1, and the total would be 35. R2, R9, R24, R39, R44 and R78 name the act or pair explicitly, so they cross either way.
- **[r1.6 review fix K6]** **Borderline readings (one line each; counts as stated unless noted):**
  - R60: if repository access / host membership were read as records (CB1), R60 would cross (+1).
  - R57: if drafting time were read as an act attribute, R57 would be internal to CB23 instead of "none" (no change in crossings).
  - R62: if "for a target" were read as C3 (CB23) rather than as part of C25's label, R62 would cross (+1).
  - R68: its "(not an absence in capture)" is a negation and is not read as a CB1 item. This is the same convention as for R79 ("references no record"). R68 crosses by the class rule anyway.
- **Against r1.5 (P-a):** R17, R18, R30–R34, R37 (and, among the excluded rows, R48, R50) read norm and work together. They crossed CB2↔CB3 and are now internal to CB23.

**[r1.6] H-D / H-B placement (check against the P-c row of the option table below).**
- **Under 𝐑₀**, both families' change and conformance clauses are internal to CB23: R25 and R27 (H-D), and R30 and R32 / R33 (H-B). **[r1.6 review fix K1]** This depends on C1 being counted on CB23. If C1 spans, as under VP12 (i), R25 crosses while the H-B clauses (they name no actor) do not, which is H-D-asymmetric. Re-test after VP12. Each family's record clause crosses: R29 (D-rec, shared) on the H-D side, and R35 (B-recW) and R36 (B-rec) on the H-B side. The crossing is therefore **symmetric under 𝐑₀**.
- **Readings:** R26, R28 and R38 (H-D readings) are internal, as are R31, R34 and R37 (H-B readings). The weaker R-c / R-f variant of R36 (B-rec → D-rec) still crosses.
- **Under R-i and R-i′** (R79: "E ≠ ∅", internal; R80: no clause), the H-B side has no record-crossing clause, while R29 still crosses. The crossing is therefore **asymmetric under R-i and R-i′**.
- **R-h / R83 note:** with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger). R83 is inactive (F-LOG-0254).
- R39 (a convention that encodes neither family) crosses. This is not an H-D / H-B asymmetry.
- This matches the P-c row (r1.3, r1.5 sync) and F-LOG-0265 ("neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), with the R-h / R83 crossing note"). **[r1.6 L3]** Rule 2 is silent on spanning concepts; the K1 scenario assumes a spanning concept counts on both sides [E]. Under VP12 (i), R38 (R-j, naming D-chg by clause only) and R13 are borderline; if they crossed, the asymmetry would point the same way (H-D). **[r1.6 L4]** C1* is shown only where it changes a crossing result; R23, R70 and R72 also read actors but cross anyway. **[r1.6 L2]** It is further **conditional on C1 being counted on CB23**; if C1 spans (VP12 (i)), the result is H-D-asymmetric (sensitivity, K1). F-LOG-0265's recorded premise and the register's VP14 row are therefore conditional on VP12.

**[r1.6] Relations between boundaries (P-c; [E]; neutral).**

| from / to | relation held by the model | not held (and why) |
|---|---|---|
| CB23 ↔ CB1 | reference (R9) and, under constitution only, F2 (R2, R3); keeper typing (R8, participant-dependent); identifiers (R22); recording order of traces (C24) | "record is the act" (X4, R44, T-C); d read off r (X5, R42); work start read off recording order (R15 guard; R69) |
| within CB23 (r1.5 CB2 ↔ CB3) | conformance as a **derived predicate** (R18) only; **now internal**, not a boundary relation | work→norm (X1, R50: no primitive); norm→work (R17 guard; X10, R48). Their conditional forms are **slots only** (VP1a, VP1c), now internal to CB23 |
| CB4 (open slot) ↔ CB1, CB23 | none active; CB4 is an open slot, not a side; CS1 is an unrealized slot | existence of an independent source either way (X21, R62) |

**[r1.6] Partition disclosure: P-c was chosen by F-LOG-0265 as a human design decision, not derived.** T1/T2 still give no criterion for the cut (§6 item 8). The act records that "its neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), with the R-h / R83 crossing note", and that it is decided "without reference to any VP1a–d outcome (N1)" and "encodes neither H-D nor H-B as a rule". **P-a, P-b and P-d are kept as recorded alternatives** in the option table below, annotated. The r1.5 P-a working layout and its H-D-congeniality disclosure are kept below as history; they are superseded as the working layout.

---

**[r1.5] P-a working layout (CB1 │ CB2 │ CB3, with CB4 as an open slot). Superseded by r1.6 under VP14 = P-c (F-LOG-0265); kept as history, not deleted.**


| id | working label | T1 concepts | T-A carried (candidate model invariants) | T-B optional policy slots (not active; need a human act) | inherited open questions (OQ + T1 open status) | observability |
|---|---|---|---|---|---|---|
| CB1 | **record / capture side** | C13, C14, C15, C16, C17, C18, C23, C24, C27 | R7, R8, R9, R2, R3 (shared with CB2), R16, R21, R22 (record ids), R24 | R39 (mode-fixing convention); R29, R35, R36 (existential record clauses), R79 (R-i: act-existence clause "E ≠ ∅"), R80 (R-i′: no B-side record clause), **[r1.5]** R83 (R-h universal record clause as optional policy; inactive slot, F-LOG-0254), all via VP1d | OQ2, OQ5, OQ11; C16 keeper no source; C17 codability rests on IF; C18 no reliable fill; C24 maps to no work start; C27 every pair ambiguous | **mixed:** record ids, r, c obs (partially, O1); k, Ref, con unobs |
| CB2 | **norm side** | C2, C3, C4, C5, C6, C7, C8, C9, C10, C11, C12 | R1, R4, R5, R10, R11, R14, R15, R19, R20; R2, R3 (shared with CB1) | R25, R26, R27, R28, R38 (H-D-side clauses) and R30, R31, R37 (H-B-side change clauses), all via VP1 | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11; C2, C6, C11, C12 no source; C7, C10 derived from unobserved; C3 multi-target untested; C8/C9 act identifiability | **mixed:** r enters only through R3 (d = r under constitution, shared with CB1), whose antecedent con is unobserved; all CB2 attributes (σ, f, d, Cap, acts) **unobs** |
| CB3 | **work side** | C19, C20, C21 | R6, R15, R18 (shared with CB2), R17 (guard, shared) | R32, R33, R34 (conformance clauses, via VP1c); R30, R31, R37 work disjuncts (via VP1a) | OQ1, OQ5; C19 executor unobserved; C20 no source; C21 depends on σ and t(w) | **unobs** |
| CB4 | **evidential-limitation side** (open slot: may not exist at all, VP10) | C25, C26, C28 | none (no T-A row has these as subject; R23 is model-wide, MW1) | none | OQ7; C25 not certifiable (Prop 5.4(c)); C26 no instrument shown; C28 touches X20 | **unobs** (con, k, σ, f) |
| — | **participant (shared across CB1–CB3)** | C1 | R22 (actor ids), R5, R6, R8 (typing of α, α_W, k) | none | OQ10, OQ5; C1 identity unobserved | **unobs** |

**[r1.5; superseded by the r1.6 relations above] Relations between boundaries ([E]; neutral).**

| from / to | relation held by the model | not held (and why) |
|---|---|---|
| CB2 ↔ CB1 | reference (R9) and, under constitution only, F2 (R2, R3) | "record is the act" (X4, R44, T-C); d read off r (X5, R42) |
| CB2 ↔ CB3 | conformance as a **derived predicate** (R18) only | work→norm (X1, R50: no primitive); norm→work (R17 guard; X10, R48). Their conditional forms are **slots only** (VP1a, VP1c) |
| CB3 ↔ CB1 | reference (R9); recording order of traces (C24) | work start read off recording order (R15 guard; R69) |
| CB4 ↔ others | none active; CS1 is an unrealized slot | existence of an independent source either way (X21, R62) |

**[r1.5 text; it describes the superseded P-a working layout. The option table below is retained as the record of alternatives, annotated with the F-LOG-0265 choice]** **[r1] Partition disclosure: the CB1–CB3 cut is H-D-congenial ([E]; not a choice).** Cutting "acts + σ" (CB2) from "work" (CB3) makes the **H-D clauses (R25–R28, R38) internal to CB2**, while the **H-B clauses (R30–R34, R37) cross CB2↔CB3** (they read σ/Chg together with work events). The partition itself is therefore a variation point (**VP14**), not a neutral given. Alternatives, listed as options only:

| option | cut | effect on H-D / H-B clauses |
|---|---|---|
| P-a (r0 cut) **[r1.6] recorded alternative, not chosen; the r1.5 working layout, superseded** | acts + σ │ work │ records | H-D clauses internal; H-B clauses cross (H-D-congenial) |
| P-b **[r1.6] recorded alternative, not chosen** | σ + work │ acts │ records | **[r1.1]** conformance clauses (R32–R34) and the work disjunct of R30/R31 internal. **The change clauses still cross acts↔σ**: R30, R31 and R37 (R-g⁺) have an issue-act disjunct or conjunct reading f(a) and v(a). The H-D clauses (R25–R28, R38) cross acts↔σ. **Only partially H-B-congenial** |
| P-c **[r1.6] CHOSEN (F-LOG-0265; human design decision); re-derived above as CB1 │ CB23, with CB4 an open slot** | one undivided norm-and-work side │ records | **[r1.1]** the change and conformance clauses of both families are internal. **[r1.3]** The existential record clauses (R29 D-rec; R35 B-recW, R36 B-rec) cross to records. **Under 𝐑₀ the crossing is symmetric** (D-rec on the H-D side; B-recW/B-rec on the H-B side). **Reading-dependent:** under R-i (R79, "E ≠ ∅", which references no record) and R-i′ (R80, no B-side record clause), the H-B side has no record-crossing clause, so the crossing is asymmetric there. **[r1.5 sync]** Under R-h with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger); disclosed (F-LOG-0254 sync). Neutral between H-D and H-B under 𝐑₀. **[r1.6 L2]** Conditional on C1 counted on CB23; H-D-asymmetric if C1 spans (VP12 (i)) |
| P-d **[r1.6] recorded alternative, not chosen** | by observability: observed (record ids, r, c) │ unobserved (everything else) | **[r1.1]** the change and conformance clauses are internal to the unobserved side. **[r1.3]** The existential record clauses (R29; R35, R36) cross the observed/unobserved line symmetrically under 𝐑₀. Under R-i and R-i′ (R79, R80) the H-B side has no record clause, so the crossing is asymmetric there (reading-dependent). **[r1.5 sync]** Under R-h with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger); disclosed (F-LOG-0254 sync). **It splits concepts by attribute** (e.g. C13: id, r, c observed; k unobserved). Neutral; follows R23 |

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
| CB1 **[r1.6]** records side (P-c) | C13–C18, C23, C24, C27 | R2, R3, R7, R8, R9, R14, R15, R16, R21, R22, R24 (T-A; all except R7 cross to CB23, shared); R29, R35, R36, R39, R83 (T-B slot; all crossing, shared with CB23). R79 and R80 (VP1d) are not record-side under the §1a criterion (CB23 / no clause); R12, R13, R23 at MW1 | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7, O9; Lem 1.12; Lem 5.2; Cor 5.3; A02, A03, A05–A07, A11, A15, B06, B09 (caveat), B11, F02, F11, F13, F14, F17, G01, G03, G04, H11 (as r1.5); for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23) | [F] def; [C] O partial (A07, F13, G01), mode not codable (F17, B11); [T] satisfiable (bK1) | IA-H, IA-A (A3, A7, A8, A9), IA-T | robust as primitives; R24 robust only for 𝒞 = 𝓗′ (H-D cases fail under R-j, not covered under R-d, [I]); record clauses reading-relative (R-c, R-f, R-i, R-i′); crossing symmetric under 𝐑₀, asymmetric under R-i, R-i′; R-h / R83 universal (§1a) | OQ2, OQ5, OQ11 |
| CB23 **[r1.6]** norm-and-work side (P-c; = r1.5 CB2 ∪ CB3) | C2–C12, C19, C20, C21 | R1, R4, R5, R6, R10, R11, R17, R18, R19, R20 (T-A, internal); R2, R3, R8, R9, R14, R15, R16, R21, R22 (T-A, crossing, shared with CB1); R25–R28, R30–R34, R37, R38, R79 (T-B slot, internal); R29, R35, R36, R39, R83 (T-B slot, crossing, shared with CB1); R80 (T-B, VP1d, no clause) | Def 1.1–1.5, 1.8–1.10; Rem 0.1; Rem 1.9a; Rem 3.1a; Rem 4.10; Obs O2–O5, O8; Lem 3.7; Rem 9.7, 9.8; A01, A03, A05–A08, A10, B06, B07, D04, E01 (recorded bearing), F06–F08, F17, G05, G07, H04, H10, H12, H14, H19 (union of r1.5 CB2 and CB3); for the shared rows R8, R9, R16, R21, R22, as CB1 | [F] def; [C] unobserved (O2–O5, O8), act layer not codable (F17, B11; bK7), work not observed (B06, G05); E01 n = 1, confounded; [T] (bK1) | IA-H, IA-A (A1, A2, A4–A7), IA-SC, IA-X, IA-1T, IA-R3 | primitives robust; status→force and change clauses reading-relative (R-d, R-e, R-g, R-g⁺, R-j); multi-target untested (R-a); B-conf universal vs R-b; B-chg target-tied R-a untested; participant-dependent rows R5, R6, R8, R10, **[r1.6 L1]** R19, R25 (C1 provisional in CG1; re-check after VP12) | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11 |
| CB4 **[r1.6]** open slot (not a P-c side; VP10 = (iii), F-LOG-0263) | C25, C26, C28 | R70, R74, R66–R69, R82 (T-D); R62 (T-C, excluded) | Def 4.4, 4.9; Props 4.5, 4.6, 5.4(c); Rem 4.5b, 4.6a, 4.9a, 5.4a; Lem 4.2, 4.7; Cor 4.3; D04, D16, F01–F03, F06, F17, H14 | [F] sufficient / necessary conditions only; [C] NOT_DETERMINABLE (D04), gaps (F01–F03) | IA-A (A5), IA-H, IA-X | Cor 4.3 reading-scoped (Rem 4.3s); fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′, R-j | OQ7, OQ5, OQ1 |
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

**[r1.6] Superseded r1.5 boundary trace rows (P-a layout; kept as history, not deleted).** r1.5 CB1 → r1.6 CB1; r1.5 CB2 and CB3 → r1.6 CB23; r1.5 CB4 → r1.6 CB4 (open slot). Their T1, T2 and R4 traces are carried into the r1.6 rows above, unchanged in substance.

| element (r1.5) | T1 ids | T2 ids (tier) | R4 / FROZEN R3 ids | evidence class | inherited assumptions | reading sensitivity | open questions |
|---|---|---|---|---|---|---|---|
| CB1 | C13–C18, C23, C24, C27 | R2, R3, R7, R8, R9, R16, R21, R22, R24 (T-A); R29, R35, R36, R39, R79, R80, R83 (T-B slot) | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7, O9; Lem 1.12; Lem 5.2; Cor 5.3; A02, A03, A05–A07, A11, A15, B06, B09 (caveat), B11, F02, F11, F13, F14, F17, G01, G03, G04, H11 | [F] def; [C] O partial (A07, F13, G01), mode not codable (F17, B11); [T] satisfiable (bK1) | IA-H, IA-A (A3, A7, A8, A9), IA-T | robust as primitives; R24 robust only for 𝒞 = 𝓗′ (H-D cases fail under R-j, not covered under R-d, [I]); record clauses reading-relative (R-c, R-f, R-i, R-i′) | OQ2, OQ5, OQ11 |
| CB2 | C2–C12 | R1, R2, R3, R4, R5, R10, R11, R14, R15, R19, R20 (T-A); R25–R28, R30, R31, R37, R38 (T-B slot) | Def 1.1–1.4, 1.8–1.10; Rem 0.1; Rem 1.9a; Obs O2–O5; Lem 3.7; Rem 9.7, 9.8; A01, A05–A08, A10, B07, D04, F06–F08, F17, G05, G07, H04, H12, H14, H19 | [F] def; [C] unobserved (O2–O5), act layer not codable (F17, B11; bK7); [T] (bK1) | IA-H, IA-A (A1, A2, A5, A6), IA-SC, IA-X, IA-1T | primitives robust; status→force and change clauses reading-relative (R-d, R-e, R-g, R-g⁺, R-j); multi-target untested (R-a) | OQ1, OQ4, OQ5, OQ6, OQ10, OQ11 |
| CB3 | C19, C20, C21 | R6, R15, R17, R18 (T-A); R30–R34, R37 (T-B slot) | Def 1.5, 1.9, 1.10; Obs O8; Rem 3.1a; Rem 4.10; A03, B06, E01 (recorded bearing), G05, H10, H12 | [F] def; [C] not observed (B06, G05); E01 n = 1, confounded | IA-H, IA-A (A4, A6, A7), IA-R3 | B-conf universal vs R-b; B-chg target-tied R-a untested | OQ1, OQ5 |
| CB4 | C25, C26, C28 | R70, R74, R66–R69, R82 (T-D); R62 (T-C, excluded) | Def 4.4, 4.9; Props 4.5, 4.6, 5.4(c); Rem 4.5b, 4.6a, 4.9a, 5.4a; Lem 4.2, 4.7; Cor 4.3; D04, D16, F01–F03, F06, F17, H14 | [F] sufficient / necessary conditions only; [C] NOT_DETERMINABLE (D04), gaps (F01–F03) | IA-A (A5), IA-H, IA-X | Cor 4.3 reading-scoped (Rem 4.3s); fails generically under R-c, R-d, R-g, R-g⁺, R-i, R-i′, R-j | OQ7, OQ5, OQ1 |

## 3. Exclusions

### 3a. Deliberately left out (T1 X-rows and T2 T-C rows; R4 Rem 0.1)

| excluded | T2 row (T-C) | element that would have hosted it | why left out |
|---|---|---|---|
| X1 causal work→norm response | R50 | **[r1.6]** within CB23 (P-c; r1.5: CB2↔CB3 link), CE4 Chg-constraint slot | no primitive (Rem 0.1); N1 |
| X2 work outcome / feedback | R81 | **[r1.6]** CB23 (r1.5: CB3) | Rem 0.1; B-fb unformalized; N1 |
| X3 norm content beyond target ↦ value | R54 | CV2 | Rem 0.1; OQ3 (§3b) |
| X4 "record is the act" | R44 | CV4, **[r1.6]** CB1↔CB23 (r1.5: CB1↔CB2) | N2; held only as unknown-capable CV4 |
| X5 decision time from recording time | R42 | CV3 | N3 (Rem 1.9a) |
| X6 f equated with / bounded by r or d | R43 | CV3, CE3 | N3, N1 |
| X7 "change only through capable acts" as invariant | (R25, T-B slot only) | CE4 Chg-constraint slot | N1: H-D as rule; slot VP1a only |
| X8 D-st / D-st⁺ as invariant | (R27, R28, T-B slot only) | CV5 | N1, N4; slot VP1b only |
| X9 "all work conforms" as invariant | (R32, R33, T-B slot only) | CV6 | N1: H-B as rule; slot VP1c only |
| X10 authorization ⇒ execution | R48 | **[r1.6]** within CB23 (r1.5: CB2↔CB3) | H10 MAY_NOT; R17 guard |
| X11 σ computed from acts | R47 | CG2 | N1; R11 guard |
| X12 hold / withdraw / supersede / annotate as act kinds | R55 | CG3 | Rem 0.1; OQ4 (§3b) |
| X13 time-varying capability | R56 | CG1 | Rem 0.1 |
| X14 drafting time | R57 | CV3 | Rem 0.1 |
| X15 state/transition view | R58 | CG2, CG3 lifecycle | Rem 0.1; §2 WITHDRAWN |
| X16 repository access as capability | R60 | CG1 | G07, H19 |
| X17 self-asserted force as σ | R46 | CV-text, CG2 | N2, N3, N5; R21 guard |
| X18 non-occurrence from absence | R49 | CV7 | H11; R16 guard |
| X19 support / frequency / epistemic labels | R59 | CB4 (**[r1.6]** open slot, not a P-c side; VP10 = (iii), F-LOG-0263), CV9 | Rem 0.1; OQ7 |
| X20 classifying the actual system as H-D or H-B | R51 | whole model | N1; path A |
| X21 independent source exists / none exists | R62 | CV8, CS1 | H14 either way |
| X22 self-stating text fixes mode; Conj 5.5 as explanation | R52 | CV4 | N7 |
| X23 mode-fixing convention as observation | R45 | CV4 | N2; labelled-convention form is slot R39 (VP2) |
| X24 finite U / slice / bundle-02 values as parameters | R53 | CV1, any threshold | §2a; N4 |
| X25 universal record completeness (incl. R-h) | R41 | CV7, CB1 (**[r1.6]** the clause crosses CB1↔CB23: every act and work event, CB23, is referenced by a record, CB1) | N1, N4; existential clauses are VP1d slots; **[r1.5]** the policy form of R-h's clause is R83, a VP1d inactive slot (F-LOG-0254); the invariant form stays excluded |
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
| VP1d | **record clauses and their reading variants** | **[r1.6]** CB1 and CB23 under P-c: R29, R35, R36 and R83 cross CB1↔CB23; R79 is internal to CB23; R80 has no clause (§1a crossing table) [r1.5: CB1]; T-B R29 (D-rec: existential record clause, shared by H-D and H-B), R35 (B-recW: existential record clause, H-B), R36 (B-rec: conjunction of existential record clauses, H-B; weaker R-c / R-f variant), **R79** (R-i: the B-side record clause **replaced by an act-existence clause "E ≠ ∅"**, which references no record), **R80** (R-i′: **no B-side record clause at all**, i.e. the option of dropping it), **[r1.5] R83** (R-h: the universal record clause as an optional policy; **inactive slot**, never an invariant, activation needs its own human act; the non-occurrence inference X18 / R49 never applied to the observed capture; F-LOG-0254) | adopt any / none (R83 only by its own activating human act) | no completeness (R16). The universal form (R-h; X25, R41) is excluded, **[r1.2]** on the H11 / A7 ground: as an invariant it would license inferring non-occurrence from absence (T1 X18; T2 R49), not because it is universal. **[r1.5] Precision:** the A7 conflict is direct (R4 A7 admits items that no record references). The H11 conflict holds only **conditionally**: inferring non-occurrence from absence *in the corpus* also requires capture completeness, which O1, F11 and G01 deny. **Disclosed neutrality asymmetry:** R-h is an H-B reading (R4 Table 9.4), so the exclusion removes one H-B option with no matching H-D loss, justified by the H11 / A7 conflict. **[r1.5] Human ruling F-LOG-0254 (VP1d-H = (i)):** the T-B optional-policy form of R-h's universal clause, with the non-occurrence inference never applied to the observed capture (it cannot be disallowed inside the model), is admitted as the inactive slot R83; the invariant form (X25, R41) stays excluded. The asymmetry is thereby resolved for the policy layer, **with a residual disclosed condition**: R83 alone carries the separability assumption [E] (the inference is not applied to the observed capture), which has no H-D counterpart | human act; R29, R35, R36 each encode H-D and/or H-B; R79, R80, R83 are H-B readings (R-i, R-i′, R-h); activating R83 needs its own human act |
| VP2 | **constitution (OQ2)** | CV4, **[r1.6]** CB1↔CB23, which R39 crosses (r1.5: CB1↔CB2); T-B R39; T-D R78 | adopt a labelled mode-fixing convention for a class of records / keep mode unknown | unknown-capable mode (CV4); every pair ambiguous (R24) | human act (convention); fact not decidable (bK7, bK8; V5 not offered) |
| VP3 | **act identifiability (OQ11)** | CG3, CE1, CE2, **[r1.6]** CB23 (r1.5: CB2); T-D R77 | treat acts as identifiable in records / not | acts held as unobserved; rules over acts uncheckable | non-Claude coder or V6 (not authorized); human act |
| VP4 | **roles / offices (OQ10)** | CG1, participant; T-D R76; T-C R63 | add a role concept (extension) / keep Cap only | Cap only, constant, unobserved | human design decision |
| VP5 | **corpus specificity and adequacy (OQ8, OQ9)** | every element; T-D R75 | accept one-corpus primitives as model basis / defer to V6 | primitives satisfiable only (bK1); adequacy [E]/[I] | V6 (not authorized); human act |
| VP6 | population of unobserved coordinates (OQ5): σ, f, d, Cap, k, t(w), con, Ref, actor identities α / α_W (per Lem 1.12) | CG1–CG3, CG5, CV3–CV8; T-D R72 | name a source / leave unpopulated | unpopulated; R2–R6, R8–R13, R18–R20 uncheckable | new evidence (not authorized) |
| VP7 | norm content (OQ3) | CV2; T-D R64 | extend / keep target ↦ value | target ↦ value only | human design decision (§3b) |
| VP8 | act kinds (OQ4) | CG3; T-D R65 | distinct kinds / R4's mapping | R4's mapping | human design decision (§3b) |
| VP9 | multi-target (OQ6) | CG2, CV2; T-D R73; T-B R31 | keep R4's finite 𝒯 (multi-target allowed by Def 1.1, untested under R-a) / restrict to one target (**a restriction of R4**, not a neutral default) | R4's finite 𝒯; only one-target behaviour is tested | V2 (not authorized); a restriction needs a human design decision |
| VP10 | evidential-limitation side (OQ7) **[r1.6] re-affirmed (iii) (F-LOG-0263); (iii) counts for the O3 minimal set (F-LOG-0264)** | CB4, CV8, CV9, CS1; T-D R74 | include CB4 / drop it | CB4 as an open slot. **[r1.6]** Under VP10 = (iii), CB4 stays an explicit open slot. It is not a decided side and forms no peer partition with CB1 and CB23 **[r1.6 review fix K4; wording "held alongside" withdrawn]** (register r1.13, VP10 (iii) "leave open as a slot"), and CV8, CV9 and CS1 stay adopted and ungrouped | human design decision **[r1.6] made: (iii) re-affirmed, F-LOG-0263** |
| VP11 | grouping granularity (aggregate-like boundaries) | CG1–CG6; T-A R1, R2, R3, R5, R6, R8, R9, R18, R19, R22 cross groupings (criterion in §1b) | e.g. merge CG2+CG3, or keep separate with cross-grouping constraints | groupings as listed, explicitly provisional, conceptual only (no enforcement unit: R23). **[r1.6] P-c side note:** CG2, CG3 and CG5 lie in CB23, and CG4 and CG6 in CB1. CG1 lies in CB23 through C2, and C1 spans CB1 and CB23 under VP12 (i), so **CG1 is the only grouping that may straddle the P-c cut** (provisional; re-check after VP12). Among the non-groupings, CV3 (C22) straddles; CV8, CV9 and CS1 sit with the CB4 open slot **[r1.6 review fix K4]** (T3 candidate labelling only; in the adopted model they are ungrouped, F-LOG-0263). R2, R3, R8, R9 and R22 cross both a §1b grouping boundary and the P-c cut. **[r1.6 review fix K7]** The guard rows R14, R15, R16, R17 and R21 (**[r1.6 L5]** R17 added) also cross groupings. R21 (c in CG4 / CV-text against σ in CG2 and f in CG3; **[r1.6 L5]** corrected) is missing from the §1b guard list, a gap since r1.5. §1b is out of scope for this amendment and is not edited; closing the gap needs a separate act | human design decision; R4 has no consistency notion |
| VP12 | participant: one shared concept or boundary-local roles (norm-maker / executor / keeper) | participant, CG1, **[r1.6]** CB1 and CB23 (r1.5: CB1–CB3). **[r1.6]** r1.6 is derived with the participant provisionally in CG1 (CB23 for the crossing criterion). Participant-dependent rows: R5, R6, R8, R10 (T-A), R63 (T-C), R76 (T-D). Re-check after VP12; under (ii), partial re-derivation by its own authorization (package r4.1 §2.9). (ii) is defined against P-a's sides only (package r4.1 §6 item 4). **[r1.6 review fix K1]** The re-check must re-test R25 and the 𝐑₀ neutrality result (§1a sensitivity): if C1 spans CB23 and CB1, R25 (H-D) and R5, R6, R10, R19, R63, R76 cross while R30–R34 and R37 do not, which is H-D-asymmetric. R19 and R25 are added to the participant-dependent rows. Under (ii), R5 and R6 stay internal | one shared participant concept / separate boundary-local concepts | one actor set 𝒜 (Def 1.2) | human design decision; interacts with VP4 |
| VP13 | permitting subset parameter (A6: P ≠ V) | CV1; R18 | P ≠ V / P = V | parameter, unset | human design decision (N5) |
| VP14 [r1] | **boundary partition itself** **[r1.6] DECIDED P-c (F-LOG-0265)** | **[r1.6]** CB1, CB23 (r1.5: CB1–CB3); CB4 an open slot; T-B R25–R34, R37, R38 | P-a / P-b / P-c / P-d (§1a); none preferred. P-a (as listed) is H-D-congenial. **[r1.6]** P-c was chosen by human design decision (F-LOG-0265), without reference to any VP1a–d outcome (N1); P-a, P-b and P-d are kept as recorded alternatives | [r1.5, superseded: P-a shown as a working layout only, disclosed as H-D-congenial] **[r1.6]** The P-c layout (CB1 │ CB23, with CB4 an open slot), as re-derived in §1a. It is neutral under 𝐑₀ only (asymmetric under R-i and R-i′; R-h / R83 note). Its effect on the adopted model needs the E2b amendment of the "no boundary layer" basis, by its own act (not made) | human design decision; must not encode H-D or H-B (N1). **[r1.6] made: F-LOG-0265** |
| VP15 [r1] | **accept R4's modelling choices (N5)** | σ as a primitive (R11; Def 1.3); Cap constancy (R10; Def 1.2); A5 / Rem 1.11a (R21); O as the base observation (R23; Def 1.11); F2 as an Asm (R2, R3; Def 1.8) | accept each / replace each (any replacement is an extension beyond R4) | all held as R4 modelling choices, never as corpus facts | human design decision; adequacy [E]/[I] (bK1) |
| VP16 [r1] | **trace-to-work link** (CG6 ↔ CG5) | C24, C19, C20; R15; Obs O8 | add a link (would need t(w) or a mapping, not read off recording order: N3, H12) / leave none | no link | new evidence or human design decision |

## 5. Standing qualifier

"one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## 6. Self-check

| check | result |
|---|---|
| No element is adopted | yes: every element is "[E] candidate, not adopted"; §4 lists the decisions still needed; nothing resolves a VP |
| No T-B, T-C or X item is active as an invariant | yes: T-B rows appear only in "optional policy slot (not active)" columns and VP1a–d, VP2, VP14. **[r1] corrected:** T-C rows and X-concepts appear in §3a, in the §1a "not held" column (X1, X4, X5, X10, X21; R42, R44, R48, R50, R62) and in §2 (R62 on CB4 and CV8), always labelled as excluded / NOT admissible, never as carried. T-D rows only as open questions / VPs. **[r1.6]** Unchanged under P-c. R62 stays on CB4 (now an explicit open slot, VP10 = (iii)) and on CV8, labelled excluded. The CB1 and CB23 T-B columns list inactive slots only. The §1a crossing table lists the T-C rows (R40–R63, R81) as "excluded", as a record of what their statements read, never as carried. No T-B, T-C or X item is activated |
| Only T-A rows carried as candidate invariants | yes: R1–R11, R14–R22 on elements; R12, R13, R23, R24 as implications / meta (MW1, CV4) |
| Every element traces to T1 and T2 ids | yes **[r1] corrected:** r0 lacked a §2 row for the participant (C1); it is added. Every row of §1 and §2 now names T1 C-ids and T2 R-ids. CB4, CV8, CV9 and CS1 carry no T-A row; they trace to T-D (and T-C exclusion) rows only, stated as such. **[r1.6]** CB23 replaces CB2 and CB3, and CB1 is reused. The id mapping is in §1a, and the r1.5 CB1–CB4 trace rows are kept in §2 as superseded history, so no trace is lost. CB4 (open slot) still carries no T-A row and traces to T-D and T-C exclusion rows only. The T1, T2 and R4 ids of the merged rows are the union of the r1.5 CB2 and CB3 traces, unchanged in substance |
| Every C1–C28 placed | yes: C1 participant; C2 CG1; C3, C5–C7 CG2; C4 CV1; C8–C12 CG3; C13–C18 CG4; C19, C20 CG5; C21 CV6; C22 CV3; C23 CV7; C24 CG6; C25 CV8; C26 CS1; C27 CV4; C28 CV9 |
| **[r1.6]** Every C1–C28 on exactly one P-c side, or a stated exception | yes: CB1 holds 9 (C13–C18, C23, C24, C27) and CB23 holds 14 (C2–C12, C19–C21). **[r1.6 review fix K4]** Stated exceptions (5): C25, C26 and C28, held in the CB4 open slot on no side (VP10 (iii), register r1.13; T3 candidate labelling carried from r1.5, not an adopted-model grouping, F-LOG-0263); C1 (provisionally in CG1, hence CB23 for the crossing criterion; it spans CB1 and CB23 under VP12 (i); re-check after VP12) and C22 (unplaced in the CB layout; under CV3 it straddles r on CB1 and d, f, t(w) on CB23). 9 + 14 + 5 = 28 (§1a) |
| No X-concept used except as exclusion | yes: X1–X25 appear only in §3a / §3b, in the §1a "not held" column and as "excluded" mentions in §2, always labelled as excluded |
| H-D / H-B neutrality preserved | yes, **[r1] with disclosure:** CE4 has an empty Chg-constraint slot, and its H-B options are temporal precedence only, not production; **[r1.5, superseded]** the CB1–CB3 cut (P-a) is disclosed as H-D-congenial and made a variation point (VP14) with H-B-congenial and neutral alternatives; CG3's issue/adoption grouping is noted as meaningful mainly under H-D clauses (VP1b); **[r1.5, superseded; r1.6: within CB23]** CB2↔CB3 holds only the derived conformance predicate; H-D-side and H-B-side slots are listed side by side in VP1a–d with no default; **[r1.2]** one disclosed asymmetry: the universal R-h record clause (an H-B reading) is excluded (X25, R41) on the H11 / A7 ground, with no matching H-D exclusion; a T-B form with the non-occurrence inference **not applied to the observed capture** (inside the model R-h entails that inference, so it cannot be "disallowed"; a policy can only forbid applying it to the incomplete record capture) **[r1.5] was admitted by human ruling F-LOG-0254 as the inactive T-B slot R83 (VP1d), so the R-h asymmetry is resolved for the policy layer, **with a residual disclosed condition**: R83 alone carries the separability assumption [E] (the inference is not applied to the observed capture), which has no H-D counterpart; it stands only for the invariant layer (X25, R41)**; R11 (σ not computed from acts) and R17 (no norm→work dependence) are frame absences, used as guards, not as a choice. **[r1.6] VP14 = P-c (F-LOG-0265; a human design decision, not derived; made without reference to VP1a–d, N1).** The r1.5 H-D-congenial P-a cut is superseded as the working layout. Under P-c, both families' change and conformance clauses (R25–R28, R38; R30–R34, R37) are internal to CB23. **[r1.6 review fix K1]** This holds only with C1 counted on CB23. If C1 spans, as under VP12 (i), R25 (H-D) crosses while R30–R34 and R37 do not, which is H-D-asymmetric; the VP12 re-check must re-test it. The record clauses cross CB1↔CB23 **symmetrically under 𝐑₀** (R29 on the H-D side; R35, R36 on the H-B side), and **asymmetrically under R-i and R-i′** (R79 internal, R80 no clause). With R83 active, the H-B crossing is universal (R-h / R83 note; R83 inactive). CB23 holds the conformance predicate (R18) internally; the norm→work and work→norm exclusions (R17, X1, X10) are now internal to CB23, not boundary relations |
| Observability stated per element | yes: only record ids, r, c observed (partially); everything else unobs (Lem 1.12; R23) |
| N-list | N1–N3, N7 touched only via guards, slots or exclusions; N4 via reading-sensitivity columns and X24; N5 via VP13, VP5; N6: no quarantined item or B10 used; N8: every name a working label |
| No architecture, persistence, API, technology, implementation or canonical-term decision | yes: "boundary", "grouping", "value-like", "occurrence" are structuring hypotheses only; no service, layer, store, interface or term is chosen |
| Waived not presented as satisfied | yes: standing qualifier; IA-CB, IA-WF and Λ1–Λ7 on every element |
| §2a finite-only values not used | yes: excluded (X24, R53); CV1 notes the finite V only as scope |

**Counts (r1.2, unchanged from r1):** **29 candidate elements** (4 boundaries + 1 shared participant; 6 groupings; 10 value-like, CV1–CV9 plus CV-text; 6 occurrences; 1 evidential slot; 1 model-wide set; r0 miscounted this as 33). **19 variation points** (VP1a–d, VP2–VP16). 28 exclusions in §3a (X1–X25 plus R40, R61, R63); **10 extensions beyond R4** in §3b; 3 items reclassified to §3c (within R4 but untested, or evidence needs).

**Counts [r1.6] (P-c):**
- **28 candidate elements:**
  - 2 P-c sides (CB1, CB23) plus the CB4 open slot, and 1 participant: 3 + 1;
  - 6 groupings;
  - 10 value-like (CV1–CV9 plus CV-text);
  - 6 occurrences;
  - 1 evidential slot;
  - 1 model-wide set.
- **Change from r1.5 (29):** −1, because CB2 and CB3 merge into CB23.
- **Unchanged:** 19 variation points (VP1a–d, VP2–VP16; VP14 is now marked decided and VP10 re-affirmed, but both stay listed); 28 exclusions in §3a; 10 extensions in §3b; 3 items in §3c.
- **Crossing table (§1a):** 83 T2 rows. 37 cross; 38 are internal to CB23; 1 (R7) is internal to CB1; 3 touch CB4 without crossing (R59, R62, R74); 4 read no placed item (R53, R57, R75, R80).

**Where T1/T2 were insufficient to decide (disclosed):**
1. **Grouping boundaries** (VP11): ten T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and four guard rows cross the candidate groupings; R4 has no consistency, transaction or lifecycle notion; and by R23 no grouping could be an enforcement unit.
2. **Participant placement** (VP12): R4 has one actor set serving as norm-maker, executor and keeper; T1/T2 do not decide one shared concept vs boundary-local ones.
3. **Execution trace ↔ work event** (CG6): no T2 row links C24 to C19; the link is left empty (O8) and is VP16.
4. **CB4's existence** depends on OQ7 (VP10); CB4, CV8, CV9 and CS1 carry no T-A constraint at all. **[r1.6]** VP10 = (iii) was re-affirmed by F-LOG-0263, and it counts for the O3 minimal set by F-LOG-0264: CB4 stays an explicit open slot, so its existence is still undecided. Under P-c, CB4 is not a side and forms no peer partition with CB1 and CB23 **[r1.6 review fix K4; wording "held alongside" withdrawn]**. Where CB4 would sit if it became a side (package r4.1 §6 item 3) is not decided here.
5. **Occurrences:** R4 has no event notion and imposes no order (R15), so no event sequence and no production relation can be stated.
6. **Issue-act / adoption grouping** (CG3): R4 gives only obj typing (R4); whether adoptions belong with their object is undecided (part of VP11).
7. **MW1 implications** R12, R13 attach to no single element.
8. **[r1] Boundary partition** (VP14): T1/T2 give no criterion for the CB cut; the r0 cut is H-D-congenial, so the partition is left open. **[r1.6]** VP14 = P-c was decided by human design decision (F-LOG-0265), not derived; T1/T2 still give no criterion for the cut. The crossing criterion K-c (§1a) is an analyst statement [E]. Its counts depend on three things: the class rule (R12, R51, R67, R68, R69; 37 crossings with it, 32 without), the row-reference rule (R71, R75), and the provisional participant placement (R5, R6, R8, R10, R63, R76), which is to be re-checked after VP12. **[r1.6 review fix K1, K2, K6]** R19 and R25 are added to the participant-dependent rows, and the 𝐑₀ neutrality result itself depends on that placement. Rule 2's relational clause decides R45 and R52 (35 without it). The borderline readings of R57, R60, R62 and R68 are disclosed in §1a.

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
| r1.5 | **amendment under human ruling, post-closure** (F-LOG-0254; R7 closed by F-LOG-0253) | VP1d-H = (i): T2 R83 (R-h universal record clause as an optional policy, inactive slot) added to VP1d's option set and to the CB1 T-B slot lists (§1a, §2); VP1d's "human alternative (not adopted)" replaced by the ruling; §3a X25 row notes R83; §6 neutrality self-check: the R-h asymmetry is resolved for the policy layer (the invariant exclusion stands). Status line cites T2 r1.4 (T-B 18) and T1 r1.6. Nothing adopted; no other VP decided; no partition selected |
| r1.5 sync | amendment review (PASS_WITH_LIMITATIONS), F-LOG-0254 synchronisation, post-closure | status line: "R7 closed by F-LOG-0253; amended under F-LOG-0254", T2 r1.4 (R1–R83); §1a P-c, P-d: R-h crossing disclosure (with R83 active the H-B crossing is universal, stronger); "resolved" qualified in VP1d and §6 with the residual disclosed condition. No decision content changed |
| r1.6 | **post-closure amendment under F-LOG-0266 (VP14 = P-c, F-LOG-0265)**; scope and review criteria: `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 §2.9 | Status line and title → r1.6. **§1a:** new P-c block added. It contains the participant prerequisite (provisionally in CG1, re-check after VP12); the id mapping (CB1 reused; CB2 + CB3 → CB23; CB4 kept as an open slot under VP10 = (iii), F-LOG-0263/0264); the CB table (CB1, CB23, CB4 slot, participant); the C1–C28 placement with the C1 and C22 exceptions; the crossing criterion K-c and the full 83-row crossing table, with counts and sensitivity; the H-D / H-B placement check; the relations; and the P-c partition disclosure. The r1.5 P-a CB table, relations and disclosure are kept as superseded history. The option table is annotated: P-c chosen, and P-a, P-b, P-d recorded alternatives. **§2:** the CB1, CB23 and CB4 rows replace the r1.5 CB1–CB4 rows, which are kept in a superseded sub-table. **§3a:** the X1, X2, X4, X10, X19 and X25 host cells are updated. **§4:** VP1d, VP2, VP3, VP10 (re-affirmed (iii)), VP11 (side note), VP12 and VP14 (DECIDED P-c) are updated. **§6:** updated are the neutrality row, the "No T-B, T-C or X item" and "Every element traces" rows, a new C1–C28 side-placement row, insufficiency items 4 and 8, and the counts (29 → 28 elements). Unchanged: the CG, CV, CE, CS and MW rows; the T1, T2 and R4 traces in substance; the standing qualifier. Nothing adopted; E2b not amended; no VP decided; no O3 proposal, E3, E3b, E4 or E5 authorized. Awaiting independent review. Produced by a worker subagent |
| r1.6 review fixes | fresh independent review of r1.6: PASS_WITH_LIMITATIONS, nothing blocking (K1–K8); still r1.6 | **K1:** R19 and R25 added to the C1* rows. The 𝐑₀ neutrality result is now stated as depending on C1 being counted on CB23: if C1 spans (VP12 (i)), R25 and R5, R6, R10, R19, R63, R76 cross while R30–R34 and R37 do not (H-D-asymmetric). This is stated in the §1a sensitivity, the H-D / H-B placement, the §4 VP12 row (re-test R25 and the result) and the §6 neutrality row. The C1* marking of R5 and R6 is noted as conservative under VP12 (ii). **K2:** rule 2's relational clause is listed in the sensitivity: R45 and R52 cross only through it (35 without it). **K3:** rule 1 now states that T-B rows are judged by their clause and guard rows by the items whose tie they deny; R80's CB23 hosting (as a VP1d option) is reconciled with its K-c result "none". **K4:** C25, C26 and C28 are relabelled as stated exceptions (5 exceptions; 9 + 14 + 5 = 28), with register r1.13 cited for (iii) and F-LOG-0263 cited for "ungrouped". This is noted as T3 candidate labelling, not an adopted-model grouping. "Held alongside" is reworded in §1a, VP10 and insufficiency 4. **K5:** the r1.5 P-a text in the §6 neutrality cell is tagged "[r1.5, superseded]". **K6:** borderline disclosures added for R60, R57, R62 and R68. **K7:** §1b not edited. The VP11 side note adds that the guard rows R14, R15, R16 and R21 cross groupings, and discloses that R21 is missing from the §1b list (a gap since r1.5; needs a separate act). **K8:** CB23 observability now says r also enters through R14 and R15. Crossing counts are unchanged (37 / 38 / 1 / 3 / 4). The superseded wording is struck through or marked, not deleted. Nothing adopted; E2b not amended; no VP decided |
| r1.6 verification fixes | second fresh review (verification of K1–K8): PASS_WITH_LIMITATIONS, nothing blocking; applied by the main analyst, not re-reviewed; still r1.6 | **L1:** R19 / R25 carried into the CB23 T-A cell, the §2 CB23 sensitivity cell and the participant row (typing rows only, labelled). **L2:** the P-c option row and the "matches the P-c row" line carry the C1-on-CB23 condition; F-LOG-0265's premise and the register's VP14 row are conditional on VP12. **L3:** spanning-concept rule stated as [E]; R38 / R13 borderline. **L4:** C1* shown only where it changes a result. **L5:** R21 placement corrected (σ in CG2, f in CG3); R17 added to the VP11 guard note. **L6:** R74 cell reworded. **L7:** the §6 neutrality cell's CB2↔CB3 clause tagged "[r1.5, superseded; r1.6: within CB23]". Counts unchanged; nothing adopted; no VP decided |
