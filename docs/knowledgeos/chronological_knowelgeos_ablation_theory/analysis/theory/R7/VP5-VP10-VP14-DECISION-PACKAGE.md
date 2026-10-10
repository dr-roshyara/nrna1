# R7 · VP5, VP10, VP14: Human ARB decision package (draft r1)

**Status:** decision-support artifact, 2026-10-11, post-closure (R7 closed by F-LOG-0253). r0 reviewed PASS_WITH_LIMITATIONS; r1 applies the review changes (§5). **It decides nothing.** It sets out three separate decisions (VP5, VP10, VP14) and, at the human's explicit request, gives a recommendation for each, under the rules stated in each section. **Each VP is a separate act. No two are to be decided as one act.** Nothing here adopts any model element or authorizes E2b, E3, E3b, E4 or E5.

**Inputs** (T = `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/`):
- `T/R7/VP-DECISION-REGISTER.md` r1.7 (authoritative for every quote below);
- `T/R7/T3-CANDIDATE-MODEL.md` r1.5 (§1a partitions P-a to P-d, CB4, §1e CS1, §4 VP10 and VP14 rows);
- `T/R7/ARB-DECISION-PACKAGE.md` r4.3 (§C1 partition comparison; §E2, §E2b, §E3 to §E5; §F);
- `T/R7/T2-RULE-CANDIDATES.md` r1.4 and `T/R7/T1-CONCEPT-CANDIDATES.md` r1.6, for ids;
- `F-GOVERNANCE-LOG.md` F-LOG-0253 (R7 closed), F-LOG-0254 (VP1d-H = (i)), F-LOG-0255 and F-LOG-0256 (VP15 = (i), "the working basis for the bounded … model **only**").

**Current state.** Decided: VP1d-H = (i) (F-LOG-0254); VP15 = (i) (F-LOG-0255, 0256). Undecided: VP5, VP10, VP14 and every other VP. Nothing is adopted.

**Standing qualifier (applies to every row):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 0. Dependency note and decision order

| step | item | why here | graph edges (register §2) |
|---|---|---|---|
| done | VP15 = (i) | foundational (tier 0) | VP15 → VP14 is satisfied |
| 1 | **VP5** | foundational (tier 0); fixes the corpus scope on which any adoption rests | VP5 → VP14; VP5 → VP3; VP5 → VP1a |
| 2 | **VP10** (a decision, or an explicit "left open" record) | precedes VP14; fixes the boundary set | VP10 → VP14; VP10 → VP11 |
| 3 | **VP14** | the last O2 item; must not reference VP1a–d outcomes (N1) | VP14 → VP12; VP14 → VP11 |

- VP5 and VP10 have **no edge between them**. "VP5 before VP10" is the register §5b priority order (timing), not a dependency.
- **After all three:** if VP5 = (i), VP10 is recorded and VP14 is decided, every VP prerequisite of **E2b** (package §E2b) is met. E2b then becomes **presentable only if VP14 = P-a or "no boundary layer"**, the two options whose element-level traceability exists today (§3.4 row 1). **For VP14 = P-b, P-c or P-d**, E2b's acceptance criterion "each adopted element traces to T3 §2" cannot be met until the re-derivation of T3's element tables under the chosen cut has been **separately authorized and completed** (§3.5). In every case E2b is **NOT authorized** by any of these acts; it still needs its own human act and its listed "independent review of the adoption text".
- If VP5 ≠ (i), or VP14 is left open, E2b is **not presentable** (register §3; package §E2b prerequisites).

---

## 1. VP5: corpus specificity and adequacy

### 1.1 Question and options (verbatim, register §1b)

| field | register text |
|---|---|
| question | "Corpus specificity and adequacy (OQ8, OQ9; R75)" |
| options | "(i) accept the one-corpus primitives as the bounded model basis; (ii) defer to V6; (iii) leave open" |
| categories | "A, D" |
| depends on / constrains | "**foundational** (parallel to VP15). **Constrains:** every element" |
| reversibility | "medium: an adoption can be withdrawn, but downstream work inherits its scope" |
| required authority | "**human act**; evidence route **V6, not authorized**" |
| needed for | "**O2, O3**" |

### 1.2 Evidence

| established (ids) | not established |
|---|---|
| K1: R4's primitives are jointly satisfiable in U / U_P2 ([F]+[T]) | whether the primitives, K6 and K7 are corpus-specific (register §5a) |
| Λ3, Λ6: one corpus; finite U / U_P2 | adequacy of the primitives to the domain: [E]/[I] (bK1) |
| T3 §3c: generality is an evidence need, not a result | any generality beyond the one corpus |
| F-LOG-0256: VP15's choices accepted "for the current, bounded model only" | that VP15 = (i) implies anything about VP5 (it does not; VP5 is a separate item, parallel to VP15) |

### 1.3 Consequences per option

| option | consequence (register) | assumption introduced | effect on O2 / O3 |
|---|---|---|---|
| (i) | "adopts with an explicit one-corpus scope (Λ3, Λ6); no generality may be claimed" | "satisfiability treated as a sufficient basis for a *bounded* model; adequacy stays [E]/[I] (bK1)" | **required** for O2 (disclosed constraint, below); also needed for O3 |
| (ii) | "no adoption until V6" | none | **blocks O2.** V6 is not authorized, so O2 stays blocked until a separate act authorizes and completes V6 |
| (iii) | "the model stays a candidate only" | none | **blocks O2** and O3 |

### 1.4 Dependencies, reversibility, cost of later change

- **Downstream:** VP5 → VP14, VP5 → VP3, VP5 → VP1a. VP5 is a prerequisite of E2-VP14 (package §E2) and of E2b.
- **Reversibility:** medium. (i) can be withdrawn, but anything later built on it (E2b, E3 to E5 outputs) inherits the one-corpus scope and would have to be reopened (package §F: "**V6** shows the primitives, K6 or K7 are corpus-specific" reopens VP5, VP15, E2b, E3, E4, E5). (ii) and (iii) cost nothing to revisit.
- **Other reopen conditions** (package §F): the H5 / F-LOG-0190 condition fails; a defect in sealed R4 r3.1.
- **Research:** "**V6: yes, directly.** It may be uninformative if K7's coding failure recurs (brief §5)" (register §5a). V6 is not authorized.

### 1.5 Disclosed constraint

**Bounded-model adoption (O2, gate E2b) under the existing gate conditions requires VP5 = option (i).** Register §3: "O2 requires **VP5 = option (i)**; option (ii) (defer to V6) and (iii) (leave open) block O2 by definition." Package §E2b prerequisites: "E2-VP5 decided **as option (i)** (other options block this gate)". This states what O2 presupposes; it is not itself a reason to choose (i).

### 1.6 Recommendation

| if the human … | recommended | rationale |
|---|---|---|
| intends to proceed to bounded-model adoption (O2) on the present evidence | **(i)**, conditionally | it is the only option under which E2b can be presented; it carries the one-corpus scope explicitly, consistent with the VP15 ruling ("bounded … model **only**", F-LOG-0256); reversibility is medium, and the reopen trigger (V6) is named |
| wants a second-corpus adequacy check before any adoption | **(ii)** | it records a deliberate timing choice; it presupposes that V6 is later authorized, otherwise O2 stays blocked |
| does not yet intend O2 | **(iii)** | it adds nothing and keeps every option; the model stays a candidate |

This recommendation does not recommend O2 itself.

### 1.7 Draft Human ARB acts (one per option; choose one or none)

> **VP5 = (i):** "I, Human ARB, decide VP5 = (i): the one-corpus primitives are accepted as the basis of the **bounded** model, with the explicit one-corpus scope (Λ3, Λ6). Satisfiability (K1) is treated as a sufficient basis for a bounded model only; adequacy to the domain stays [E]/[I] (bK1), and no generality is claimed. This decides no other variation point. It adopts no model element. It does not authorize E2b (adoption), E3 or E3b (architecture), E4 (R8) or E5 (implementation), nor V6 or any other evidence collection."

> **VP5 = (ii):** "I, Human ARB, decide VP5 = (ii): the corpus basis is deferred to V6. No adoption takes place until V6 has been separately authorized and completed. This decision does not authorize V6. It decides no other variation point and does not authorize E2b, E3, E3b, E4 or E5."

> **VP5 = (iii):** "I, Human ARB, decide VP5 = (iii): VP5 is left open, and the R7 model stays a candidate only. This decides no other variation point and does not authorize E2b, E3, E3b, E4, E5 or any evidence collection."

---

## 2. VP10: evidential-limitation side

### 2.1 Question and options (verbatim, register §1b)

| field | register text |
|---|---|
| question | "Evidential-limitation side (OQ7; R74)" |
| options | "(i) include CB4 (CV8, CV9, CS1); (ii) drop it; (iii) leave open as a slot" |
| categories | "A, C, E" |
| depends on / constrains | "independent. **Constrains:** VP14 (the boundary set), VP11" |
| reversibility | "high" |
| required authority | "**human design decision**" |
| needed for | "O3 (whether the proposal includes CB4)" |

**Registered meaning (do not widen).** OQ7 / T2 R74 (T-D): "Should a domain model carry evidential-limitation or epistemic-status information (C28) at all?" CB4 = T3's "**evidential-limitation side** (open slot: may not exist at all, VP10)", with C25, C26, C28; it carries "none (no T-A row has these as subject; R23 is model-wide, MW1)". Its elements are CV8 (record independence for a target, a predicate), CV9 (limitation kind: structural non-identifiability vs instrument unreliability; "meta-level, not a record attribute") and CS1 (norm-state attestation channel, an "**unrealized slot**"). VP10 asks whether the **model represents** this information. It is **not** a question about the domain, and no option turns OQ7 into a domain rule: no option asserts that an independent source exists or does not exist (that is T-C R62, excluded), and no option asserts any limitation as a property of the world.

### 2.2 Evidence

| established (ids) | not established |
|---|---|
| CB4 carries no T-A row (T3 §1a) | "whether the model should carry epistemic information (X19 is a Rem 0.1 exclusion; extension, §3b)" (register §5a) |
| K5: two independent limitations (D16, F17) | whether any independent norm-state source exists (H14: MAY_NOT either way; T-C R62 excluded) |
| R74 is T-D: "design decision outside R4" | any domain criterion for including or dropping CB4 |
| no evidence route bears on it: "no: a design question; no route bears on it" (register §5a) | |

### 2.3 Consequences per option

| option | consequence (register) | assumption introduced | effect on VP14 / O2 / O3 |
|---|---|---|---|
| (i) include CB4 | "carries epistemic information in the model, which is a step beyond R4 (X19, §3b); CB4 has no T-A row" | "an epistemic-status representation" | CB4 enters VP14's boundary set as a slot with no constraint; an extension beyond R4 that VP15 (R4's choices) does not cover |
| (ii) drop it | "removes the slot; its limitations stay in the qualifier only" | none | **Analyst reading [E]:** CV8, CV9 and CS1 are removed from the model, not merely ungrouped (the register says only "(ii) drop it" / "removes the slot"). Basis: option (i) defines CB4 as "(CV8, CV9, CS1)", T3 §4 lists CB4, CV8, CV9 and CS1 as the slots VP10 affects, and (ii) leaves the limitations "in the qualifier only". VP14's boundary set is CB1–CB3. The limitations remain in the standing qualifier and package §B. T1 C25, C26, C28 and T2 R74 / R62 stay as recorded R7 rows (R7 is closed); only what the model carries changes |
| (iii) leave open as a slot | "open slot, as now" | none | satisfies O2's minimal set **only if recorded explicitly** (register §3); VP14 then treats CB4 as an explicit open slot (register §5a); **O3 needs a decision** (register §3), so (iii) must be revisited at the O3 gate |

### 2.4 Dependencies, reversibility, cost of later change

- **Downstream:** VP10 → VP14 (boundary set); VP10 → VP11 (groupings).
- **O2 need (register §3, r1.2):** "an explicit 'left open' (or decided) record for **VP10** (edge VP10 → VP14)". Silence does not count.
- **O3 need:** VP10 must be decided (register §3, O3 row).
- **Reversibility:** high, "additive either way" (register §5a). Changing (iii) to (i) or (ii) later re-touches VP14's boundary set and VP11 only.
- **Interaction with VP14 = "no boundary layer":** package §C1 states "CB1–CB4 rows dropped; CG, CV, CE, CS, MW rows unaffected". Under VP10 (i) or (iii), CV8, CV9 and CS1 therefore stay in the model, ungrouped, without a boundary layer. Under VP10 (ii) they are removed (§2.3; analyst reading [E]). This is a disclosure; it does not decide either VP.

### 2.5 Recommendation

**Recommended: (iii), recorded explicitly as "left open as a slot".**

| rationale | source |
|---|---|
| it satisfies the O2 minimal-set requirement (an explicit record) without committing to an extension beyond R4 | register §3; §5a ("**DEFER** to the O3 gate; record 'left open' before VP14 is decided") |
| no evidence and no route bears on it; deciding the value now would be a design choice with no new information | register §5a |
| "CB4 carries no T-A row, so an open slot constrains nothing" | register §4 |
| reversibility is high, and O3 (which needs the value) is not authorized | register §1b; F-LOG-0248 |

**Costs disclosed:** (iii) must be revisited before any O3 proposal. If the human already holds a design view on OQ7, (i) or (ii) is equally admissible and also satisfies O2.

### 2.6 Draft Human ARB acts (one per option; choose one)

> **VP10 = (i):** "I, Human ARB, decide VP10 = (i): CB4 (CV8, CV9, CS1) is included as the evidential-limitation side of the model, labelled a design decision and an extension beyond R4 (X19, §3b), carrying no T-A row. This represents evidential limitations in the model; it asserts nothing about the domain (in particular, not that an independent norm-state source exists or does not exist; T-C R62 stays excluded). This decides no other variation point, including VP14. It does not authorize E2b, E3, E3b, E4 or E5."

> **VP10 = (ii):** "I, Human ARB, decide VP10 = (ii): CB4 is dropped from the model, and with it CV8, CV9 and CS1, which are removed from the model, not merely ungrouped; the evidential limitations remain stated in the standing qualifier and the package §B only. The R7 rows C25, C26, C28, R74 and R62 stay as recorded. This asserts nothing about the domain. This decides no other variation point, including VP14. It does not authorize E2b, E3, E3b, E4 or E5."

> **VP10 = (iii):** "I, Human ARB, record VP10 = (iii): the evidential-limitation side is left open as a slot (CB4 as an explicit open slot), so that VP14 may be decided. This value must be revisited before any O3 proposal. This decides no other variation point, including VP14. It does not authorize E2b, E3, E3b, E4 or E5."

---

## 3. VP14: boundary partition

### 3.1 Question and options (verbatim, register §1c)

| field | register text |
|---|---|
| question | "Boundary partition (§1a)" |
| options | "P-a / P-b / P-c / P-d; none preferred; or leave open (**[r1.7]** this is not a decision: it blocks O2/E2b, and the candidate merely carries no boundary layer *by default*. 'No boundary layer' as an **explicit decision** is a separate option, counted as deciding VP14 (§3, package §E2b))" |
| categories | "B, C" |
| depends on / constrains | "**after:** VP15, VP10. **Must be decided without reference to** VP1a–d outcomes (N1). **Constrains:** VP12, VP11" |
| reversibility | "medium: re-layout re-derives the §1a crossings and VP11/VP12" |
| required authority | "**human design decision**; must not encode H-D or H-B (N1)" |
| needed for | "**O2** (decide, or explicitly adopt without a boundary layer), **O3**" |

### 3.2 The partitions (T3 r1.5 §1a)

| option | cut |
|---|---|
| P-a (r0 cut; T3's working layout) | acts + σ │ work │ records |
| P-b | σ + work │ acts │ records |
| P-c | one undivided norm-and-work side │ records |
| P-d | by observability: observed (record ids, r, c) │ unobserved (everything else) |
| no boundary layer | none (CB1–CB4 rows dropped; CG, CV, CE, CS, MW rows unaffected; package §C1) |

### 3.3 Evidence

| established | not established |
|---|---|
| T3 §6 item 8: "T1/T2 give no criterion for the CB cut" | which cut is adequate to the domain |
| clause placement per partition (T3 §1a; [F]-level clause reading) | any evidence route: "no route decides it" (register §5a) |
| P-a is H-D-congenial; P-b only partially H-B-congenial; P-c, P-d neutral under 𝐑₀, asymmetric under R-i / R-i′ | that any boundary layer is needed, or that none is needed |
| P-d's line follows R23 (Lem 1.12, [F]) | that R23's observability line is a domain boundary (it is a checkability boundary) |
| the theory does not establish aggregate or consistency boundaries as empirical facts (F-LOG-0256, preserved limitation) | |

### 3.4 Neutral comparison (no ranking, no weighting, no total)

Rows 1 to 6 are H-D / H-B-neutral criteria. Row 7 is the congeniality disclosure; it is **disclosed, not a criterion to optimize**. Cells marked [E] are analyst assessments (package §C1), not research findings.

| # | criterion | P-a | P-b | P-c | P-d | no boundary layer |
|---|---|---|---|---|---|---|
| 1 | traceability (element level, T3 §2) | **full today** (the CB1–CB3 tables are P-a's layout) | clause-level only; element tables need re-derivation | clause-level only; re-derivation | clause-level only; re-derivation | retained for all non-CB elements |
| 2 | unsupported assumptions | a cut not derivable from T1 / T2 (T3 §6 item 8) | same | same, plus one undivided side | the line follows R23 ([F]); adds the assumption that concepts may be split by attribute (C13) | none added |
| 3 | reversibility | medium | medium | medium | medium | high [E] (a layer can be added later; VP11 / VP12 then follow) |
| 4 | coupling [I] | H-B clauses and R18 cross; R2, R3 shared with records | both families' change clauses cross; F1 (R1) crosses acts↔σ | fewest clause crossings among the cuts; largest single side | record-concept attributes cross the line | none at boundary level; no decomposition either |
| 5 | compatibility with future evidence [I] | V6 / V2 affect all options alike | as P-a | as P-a | an E-A5 channel (σ / f) or a VP3 change **moves its line** (package §F reopens VP14 for P-d's line) | unaffected |
| 6 | decomposition value for O3 [E] | three sides; element tables exist | three sides, acts separated from σ + work | two sides; the norm-and-work side is undivided | two sides aligned with what records can and cannot show (R23); concepts split | **none**; VP14 must be reopened before O3 |
| 7 | H-D / H-B congeniality (disclosed) | **H-D-congenial** (H-D clauses R25–R28, R38 internal; H-B clauses R30–R34, R37 cross) | **only partially H-B-congenial** (conformance and work disjuncts internal; change clauses of both families cross acts↔σ) | neutral **under 𝐑₀**; asymmetric under R-i / R-i′ (R79, R80) | neutral **under 𝐑₀**; asymmetric under R-i / R-i′ | neutral (no crossings by construction) |

**R-h / R83 crossing note (disclosure, register §1c "[r1.5 sync]", for P-c and P-d):** "Under R-h with R83 active, both sides' record clauses cross, but the H-B crossing is universal (stronger); disclosed (F-LOG-0254 sync)." R83 is an **inactive** slot (F-LOG-0254); activation needs its own human act. The register states this note for P-c and P-d only; it is a VP1d-reading disclosure and, under N1, must not be used to choose VP14.

**Disclosed tilt of the table.** Package §C1: "criteria 1–5 below favour 'no boundary layer' largely because it makes no decomposition at all; that advantage is the absence of a structure, not evidence for it." **Disclosed deviation:** this package reads criteria 2 to 5 as favouring "no boundary layer" (assumptions, reversibility, coupling, future evidence). On criterion 1 it reads P-a as ahead ("full today", against "retained for all non-CB elements"), with "no boundary layer" second. Criteria 1 and 6 favour P-a, which is the H-D-congenial cut.

### 3.5 Consequences per option

| option | what an adoption would carry | O2 | O3 |
|---|---|---|---|
| P-a | an H-D-congenial cut, adopted with its tilt disclosed; package §F reopens VP14 if "any adopted element is found to encode H-D or H-B" | satisfied; E2b presentable (traceability exists) | boundaries exist |
| P-b | a cut partially H-B-congenial; element tables re-derived first | VP14 prerequisite met; E2b presentable only after the re-derivation is separately authorized and completed | boundaries exist |
| P-c | a neutral-under-𝐑₀ cut with an undivided norm-and-work side; element tables re-derived first | as P-b | fewer boundaries to map onto |
| P-d | a neutral-under-𝐑₀ cut along the checkability line; concepts split by attribute; element tables re-derived first; the line moves if E-A5 or VP3 evidence arrives | as P-b | boundaries exist; at risk of moving |
| no boundary layer | no boundary structure; CB rows dropped; all other elements and their traces kept. **Architectural implication:** an architecture proposal (O3, E3) would have **no boundary to decompose into**, so VP14 must be reopened before any O3 proposal. **This does not mean the theory showed boundaries to be unnecessary**: T1 / T2 give no criterion for a cut either way, and the theory does not establish boundaries as empirical facts | satisfied (as an explicit decision); E2b presentable | **not satisfied**; reopen VP14 |
| leave open | T3's P-a layout must not be carried into an adopted model (register §1c) | **blocked** | blocked |

**Re-derivation cost (P-b, P-c, P-d).** Package §C1 records their traceability as "clause-level only". E2b's acceptance criterion is "each adopted element traces to T3 §2". Choosing P-b, P-c or P-d therefore implies re-laying out T3 §1a / §2 under the chosen cut before E2b can meet that criterion. That work is not authorized by a VP14 act; until it is separately authorized and completed, E2b is not presentable for these three options.

### 3.6 Dependencies, reversibility, cost of later change

- **Incoming:** VP15 (decided), VP5, VP10. **No incoming edge from VP1a–d** (N1; register §2 edge rules).
- **Outgoing:** VP14 → VP12, VP14 → VP11 (both O3-gate items).
- **Reversibility:** medium for P-a to P-d (a re-layout re-derives the §1a crossings and VP11 / VP12); high for "no boundary layer" [E]. The cost grows once E2b, and later E3, rest on a cut.
- **Reopen conditions** (package §F): "**E-A5 / Protocol C** evidence on σ or f" (P-d's line); "any adopted element is found to encode H-D or H-B, or a partition choice is found to be justified by VP1a–d outcomes"; "a VP is found to have been **decided by implication or bundled** with another act" (e.g. "E2b if VP14 was implied by T3's P-a layout").

### 3.7 Recommendation (H-D / H-B-neutral criteria only)

**Basis, stated explicitly:** this recommendation uses only criteria 1 to 6 and one **analyst threshold** (step 1). It does not use, and is not informed by, any VP1a–d outcome or any view on H-D vs H-B (non-identifiable on this corpus).

| step | reasoning |
|---|---|
| 1. screen (analyst threshold, not N1) | **Threshold: no recommendation for an option with any disclosed tilt under 𝐑₀.** P-a is H-D-congenial; P-b is **only partially** H-B-congenial. Both are set aside from the *recommendation* by this threshold, applied to both alike, not by any preference between families. **This is not N1:** N1 forbids *encoding* H-D or H-B, and the register lets the human choose a tilted partition with its tilt disclosed ("P-a / P-b: a tilt toward one family is disclosed"). **P-a and P-b remain choosable with disclosure** (§3.8). P-a's advantages on criteria 1 and 6 are real |
| 2. if no O3 is intended in the near term | **"no boundary layer"** adds no assumption (2), is the most reversible (3), is unaffected by future evidence (5) and keeps every non-CB trace (1). **Cost:** zero decomposition value (6); VP14 must be reopened before O3; and its advantage on 2, 3 and 5 is the absence of structure, not evidence that no structure is needed |
| 3. if an O3 proposal is intended | **P-c**, on criteria 4 and 5: the fewest clause crossings among the cuts ([I]), and a line that does not move with E-A5 or VP3 evidence, with no splitting of concepts by attribute. **Costs:** element tables must be re-derived (1); one undivided side adds an assumption and leaves less to map onto (2; decomposition value [E], 6); neutrality holds under 𝐑₀ only (asymmetric under R-i / R-i′; the R-h / R83 note applies). **P-d** is the equal neutral alternative: its line is better founded ([F], R23), but it splits concepts by attribute and its line moves with future evidence |

**Limit on this recommendation.** P-c's and P-d's neutrality holds **under 𝐑₀ only**, and so depends on VP1d's reading, which is undecided: both are asymmetric under R-i / R-i′ (R79, R80; the H-B side has no record-crossing clause) and under R-h with R83 active (the H-B crossing is universal, stronger). The threshold of step 1 is therefore met by P-c and P-d only under 𝐑₀. If VP1d is later decided for R-i, R-i′ or an active R83, the recommendation's premise no longer holds and should be revisited (VP14 is still not decided by reference to VP1d: N1).

**The human may choose any option, including P-a or P-b.** If so, the act carries the disclosed tilt (row 7) in its text, as in §3.8.

### 3.8 Draft Human ARB acts (one per option; choose one)

Common closing sentence for every VP14 act (**C14**): "This partition is decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule. It decides no other variation point (VP11 and VP12 stay open). It adopts no model element and does not authorize E2b (adoption), E3 or E3b (architecture), E4 (R8) or E5 (implementation), nor the re-derivation of T3's element tables, which needs its own authorization."

> **VP14 = P-a:** "I, Human ARB, decide VP14 = P-a (acts + σ │ work │ records), as a human design decision. I record its disclosed tilt: under P-a the H-D clauses are internal and the H-B clauses cross CB2↔CB3 (H-D-congenial). [C14]"

> **VP14 = P-b:** "I, Human ARB, decide VP14 = P-b (σ + work │ acts │ records), as a human design decision. I record its disclosed tilt: only partially H-B-congenial; the change clauses of both families cross acts↔σ. [C14]"

> **VP14 = P-c:** "I, Human ARB, decide VP14 = P-c (one undivided norm-and-work side │ records), as a human design decision. I record that its neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), together with the disclosed R-h / R83 crossing note. [C14]"

> **VP14 = P-d:** "I, Human ARB, decide VP14 = P-d (observed │ unobserved, following R23), as a human design decision. I record that it splits concepts by attribute (C13), that its neutrality holds under 𝐑₀ only (asymmetric under R-i and R-i′), the disclosed R-h / R83 crossing note, and that an E-A5 channel or a VP3 change would move its line. [C14]"

> **VP14 = no boundary layer:** "I, Human ARB, decide VP14 = no boundary layer: the bounded model is carried without CB1–CB4; the CG, CV, CE, CS and MW elements and their traces are unaffected, and T3's P-a layout is not adopted. This does not find that boundaries are unnecessary in the domain; T1 and T2 give no criterion either way. VP14 must be reopened before any O3 proposal. [C14]"

> **VP14 = leave open:** "I, Human ARB, leave VP14 open. T3's P-a layout is not adopted by implication. O2 (E2b) stays blocked until VP14 is decided. This decides no other variation point and authorizes nothing."

---

## 4. Self-check

| check | result |
|---|---|
| No VP decided | yes. Every act is a draft for the human. VP5, VP10 and VP14 are presented as undecided. VP1d-H and VP15 are cited as already decided (F-LOG-0254, 0255) |
| No bundling | yes. Each VP has its own section and its own act; each act states "decides no other variation point" |
| VP5 recommendation conditional | yes. (i) is recommended only if the human intends O2; the O2 constraint is disclosed as a constraint; (ii) and (iii) are recommended otherwise. O2 itself is not recommended |
| VP10 kept to its registered meaning | yes. CB4 / OQ7 / R74: whether the model represents evidential limitations. No act asserts a domain fact; T-C R62 stays excluded. The O2 "explicit record" need is stated. The (ii) act states that CV8, CV9 and CS1 are removed from the model, not merely ungrouped. "Constrains nothing" is quoted, not widened |
| VP14 neutral | yes. P-a and P-b are set aside from the recommendation by a stated analyst threshold (any tilt under 𝐑₀), applied to both alike, not by N1; both remain choosable with disclosure. The recommendation names its criteria (1 to 6) and states that no VP1a–d outcome was used. Its limit is stated: P-c / P-d neutrality holds under 𝐑₀ only (asymmetric under R-i / R-i′ and R-h / R83). Costs of P-c, P-d and "no boundary layer" are disclosed. [I] and [E] labels kept |
| "No boundary layer" not over-read | yes. §3.5 and its act state that the theory did not show boundaries to be unnecessary, and that VP14 must be reopened for O3 |
| Every quote matches the register | checked mechanically against `R7/VP-DECISION-REGISTER.md` r1.7 rows VP5, VP10, VP14, §3, §4, §5a (and T3 r1.5, T2 r1.4, package r4.3, F-LOG-0254 to 0256 where cited); nested double quotes inside a quotation are rendered as single quotes. VP14's options re-quoted from r1.7 |
| Every act states what it does not authorize | yes: E2b, E3, E3b, E4, E5 (and V6 / evidence for VP5) |
| E2b status | presentable after VP5 = (i), VP10 recorded and VP14 = P-a or "no boundary layer"; for P-b, P-c or P-d only after the T3 element re-derivation is separately authorized and completed (§0, §3.5 consistent); **not authorized** by any act here |
| Standing qualifier | carried verbatim (header) |


## 5. Change rows

| rev | change | reason |
|---|---|---|
| r0 | first draft | coordinator commission |
| r1 | inputs → register r1.7, package r4.3; VP14 options re-quoted (r1.7 "leave open" gloss); obsolete §3.1 wording observation and resolved §4 observations removed; §3.7 screen restated as an analyst threshold (any tilt under 𝐑₀), not N1, P-b's tilt noted as partial, P-a / P-b stated as choosable with disclosure, and a limit added (P-c / P-d neutral under 𝐑₀ only; asymmetric under R-i / R-i′ and R-h / R83; depends on undecided VP1d); [I] / [E] labels in §3.7; §3.4 deviation from §C1's "criteria 1–5" disclosed; §0, §3.5, §4: E2b presentable only for P-a or "no boundary layer", otherwise only after the separately authorized and completed T3 re-derivation; VP10 (ii): CV8, CV9, CS1 removed from the model (§2.3, §2.4, act); §2.5: "constrains nothing" quoted, not widened | package review PASS_WITH_LIMITATIONS, changes 1–7 |

## Standing qualifier

"one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"
| r1.1 | verification (PASS_WITH_LIMITATIONS) | stale §4 residual observation deleted (the ARB package is now r4.3, E2b cites T3 r1.5); VP10 (ii) "removed" labelled as an analyst reading [E] in §2.3 and §2.4. Applied by the main analyst |
