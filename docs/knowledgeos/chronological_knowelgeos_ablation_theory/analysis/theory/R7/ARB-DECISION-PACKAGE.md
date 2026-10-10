# R7 · Human ARB decision package (draft r4.3)

**Status:** decision-support artifact for the Human ARB review of R7 (F-LOG-0250), 2026-10-11. Draft r4 (aligned with the verified final state and the package review; change rows at the end), **not independently reviewed**. **It decides nothing.** It recommends a disposition of R7 and the *timing* of decisions; it chooses no value for any variation point (VP), selects no partition and closes nothing. Every authorization below is **draft wording for the human to approve or reject**. **[r4.2] Amendment under human ruling, post-closure (F-LOG-0254; R7 closed by F-LOG-0253):** §B item 4 note and §C priority 1 marked decided; nothing else changed.

**Inputs:** `R7/T1-CONCEPT-CANDIDATES.md` r1.5 · `R7/T2-RULE-CANDIDATES.md` r1.3 · `R7/T3-CANDIDATE-MODEL.md` r1.4 (commit 1a63f3a14) · `R7/REVIEW-T1.md`, `REVIEW-T2.md`, `REVIEW-T3.md`, `REVIEW-CROSS-ARTIFACT.md` · `R7/VP-DECISION-REGISTER.md` r1.4 · `R7-AUTHORIZATION-BRIEF.md` r3 · `F-GOVERNANCE-LOG.md` (in `docs/knowledgeos/chronological_knowelgeos_ablation_theory/`), F-LOG-0245 to 0251.

**Standing qualifier (applies to every statement here):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## A. Audit verdict and material findings

**Cross-artifact audit of T1 r1.3, T2 r1.1, T3 r1.1 (F-LOG-0250 batch): PASS_WITH_LIMITATIONS.** Fix round 1 (T1 r1.4, T2 r1.2, T3 r1.2 / r1.3) was verified with residuals; fix round 2 (T1 r1.5, T2 r1.3, T3 r1.4) closed them. Fixes are recorded in the change tables of T1 (§6), T2 (§7) and T3 (§7); the audit and both rounds in `R7/REVIEW-CROSS-ARTIFACT.md`.

| # | finding | class | fix | where |
|---|---|---|---|---|
| M1 | **X25 grounding.** X25 / R41 (universal record completeness) was excluded on "quantifier strength"; but T2 tiers other universal clauses (R32, R33) as T-B, so universality was not the real criterion | **material** | **final ground (round 2):** NOT admissible as an invariant, because it would license inferring non-occurrence from absence (T1 X18, T2 R49), which contradicts **A7 directly** and **H11 conditionally on capture completeness**, which O1, F11 and G01 deny; the existential clauses (R29, R35, R36) avoid the inference. **Disclosed R-h asymmetry:** R-h is an H-B reading (Table 9.4), so one H-B option is removed with no matching H-D loss. Human alternative recorded (T-B form of R-h with the inference **not applied to the observed capture**; inside the model R-h entails it, so it cannot be disallowed) → register item **VP1d-H**. Analyst decision, disclosed | T1 X25 r1.5; T2 §0, R29, R35, R36, R41 r1.3; T3 VP1d, §6 r1.4 |
| M2 | **Causal residue.** Wording that framed norm change as produced by acts or work (C7, OQ1, X7; R20, R25) | **material** | restated as "which constraint on change points (Chg) holds"; H-D side (D-chg alone) "change points coincide with the entry into force of capable acts" (round 2: "adopted" removed from X7, since adoption is D-st, X8); H-B side: preceded by earlier work or coinciding with an issue act entering into force (B-chg's disjunction, round 2: issue-act disjunct added to C7, R20); temporal precedence only | T1 C7, OQ1, X7 r1.5; T2 R20, R25 r1.3 |
| m1 | R79 / R80 mislabelled as existential record clauses | minor | relabelled: R79 = R-i act-existence clause "E ≠ ∅" (references no record); R80 = R-i′, no B-side record clause | T3 VP1d r1.2 |
| m2 | P-c / P-d "symmetric crossing" claim too broad | minor | restricted to the existential record clauses under 𝐑₀; asymmetric under R-i, R-i′ | T3 §1a r1.3 |
| m3 | unobserved-coordinate list not uniform | minor | unified (σ, f, d, Cap, k, t(w), con, Ref, α / α_W per Lem 1.12) | T1 OQ5, T2 R72, T3 VP6 / §3c |
| m4 | §0 Λ1–Λ7 blanket statement; F-LOG-0249 confirmation; T2-local row ids | minor | added | T1 §0 r1.4; T2 §0 r1.2 |

**Verification result:** fix round 1 verified PASS_WITH_LIMITATIONS with residuals (X25's H11 ground needs capture completeness; the human alternative could not "disallow" an inference R-h entails; X7 folded in D-st; the H-B gloss omitted B-chg's issue-act disjunct). Fix round 2 (T1 r1.5, T2 r1.3, T3 r1.4) **confirmed PASS_WITH_LIMITATIONS; no material defect remains** (`R7/REVIEW-CROSS-ARTIFACT.md`).

**F-LOG-0250 overstatements, corrected by F-LOG-0251 (recorded, commit dd546caf4):**
1. "T-A 24 are model constraints" → **candidate** model constraints ("holds in R4's model; adequacy [E]/[I]"; T2 §0).
2. "all but record identities not checkable from records" → F-LOG-0251: only R7 (r and c, **checkable in principle, though O1 is incomplete**) and R22 (record identities) involve observed coordinates; every other T-A row is not checkable from records (Lem 1.12). (T2 §6 adds that R7's totality cannot be confirmed from the corpus.)
3. "T-B 17 … encoding H-D or H-B" → "encoding H-D, H-B **or a convention** (R39)" (F-LOG-0251; T2 R39; T1 X23).

## B. R7 acceptance recommendation

**Recommendation: ACCEPT WITH SPECIFIED LIMITATIONS; both conditions satisfied:** (a) the verification found no material defect (fix round 2 confirmed, §A); (b) the F-LOG-0250 correction is recorded as F-LOG-0251. **This is a recommendation, not closure; this package does not close R7.**

**Specified limitations (to be named in the acceptance act):**
1. one corpus; finite universes U and U_P2 only; R-a untested (Λ5, Λ6);
2. within-family evidence only; cross-family **waived, not satisfied** (F-LOG-0245); every reviewer and auditor Claude-family (Λ7);
3. H-D vs H-B non-identifiable on this corpus (path A), resting on H5 with the F-LOG-0190 condition waived (Λ3); neither validated nor falsified (Λ2); D1's strong form reading-relative (Λ4);
4. the X25 / R41 exclusion is an **analyst decision** (A7 direct, H11 conditional on capture completeness) with a disclosed H-B-side asymmetry (R-h), unratified until VP1d-H is decided; **[r4.2] superseded for the policy layer by F-LOG-0254** (VP1d-H = (i): the policy form is the inactive T-B slot T2 R83; the asymmetry is resolved for the policy layer, **with a residual disclosed condition**: R83 alone carries the separability assumption [E] (the inference is not applied to the observed capture), which has no H-D counterpart); the invariant exclusion (X25, R41) still stands;
5. the T-C reading of "open" (T2 §0) is an analyst interpretation [I];
6. adequacy of every T-A candidate constraint to the domain is [E]/[I]; only R7 (r, c) is over observed coordinates alone, its totality not confirmable; nothing is enforceable from records (R23);
7. 10 extensions beyond R4 (T3 §3b) are unsupported; T3 §3c holds 1 item within R4 but untested (multi-target) and 2 evidence needs (unobserved-coordinate source; generality beyond one corpus), all unmet;
8. all 19 VPs (and VP1d-H) remain undecided; T3's P-a layout is not adopted by acceptance.

| criterion | demonstrated evidence | limitation that travels with acceptance |
|---|---|---|
| **Traceability T1 → T2 → T3 → evidence** | T1: every C-, X-, OQ-row has an R4 and/or FROZEN R3 source (T1 §6). T2: every row cites R4 / R3 ids and T1 concepts (T2 §6). T3: every element of §1 has a §2 row with T1 ids, T2 ids (tier), R4 / R3 ids, class, assumptions, reading sensitivity (T3 §6; participant row added in r1). Reviews spot-checked 16 (T1), ~20 (T2) and 12 (T3) rows: all match | spot checks, not exhaustive (the cross-artifact audit resolved 10 chains end to end); every reviewer Claude-family (Λ7) |
| **Bounded-corpus limitations** | standing qualifier on every artifact; Λ1–Λ7 carried per F-LOG-0249 (T1 §0); §2a finite-only values excluded (X24, R53); generality stated as an evidence need, not an extension (T3 §3c; V6 not authorized) | one corpus; U and U_P2 only; R-a untested (Λ5) |
| **H-D / H-B non-identifiability** | no H-D or H-B clause is active: they appear only as inactive T-B slots (VP1a–d) with H-D and H-B options listed side by side; X20 (classifying the system) excluded; partition tilt disclosed (P-a H-D-congenial; P-b partially H-B-congenial; VP14); CG3's dependence on H-D-side clauses disclosed (VP1b); **one disclosed asymmetry** (R-h, M1) | path A rests on H5 with the F-LOG-0190 condition waived (Λ3); the R-h asymmetry is an unratified analyst decision until VP1d-H is decided |
| **No unsupported causal, temporal, aggregate, consistency or universal claims** | causal: X1, X2 excluded; causal residue removed (M2). Temporal: no order among occurrences (T3 §1d; R15); d not read off r (R3 guard, R42 T-C); f not equated (R14, R43). Aggregate / consistency: groupings conceptual only, no enforcement unit (R23; T3 §1b); "consistency clusters" removed (T3 r1). Universal: X25 excluded; universal T-B clauses (R32, R33) are inactive policy only | M2 confirmed in fix round 2; R4 itself has no consistency notion, so VP11 can only be closed by a design decision |
| **Candidate vs adopted separation** | every T3 element "[E] candidate, not adopted"; T-A rows are candidate invariants of the model, never of the world; T-B never invariants; T-C / X only as labelled exclusions; 19 VPs default empty (T3 §4, §6); F-LOG-0248 stop condition: no adoption by implication | F-LOG-0250's wording corrected by F-LOG-0251 (§A) |

**Other limitations to specify in the acceptance act:** cross-family **waived, not satisfied** (F-LOG-0245); the X25 ground and the T-C reading of "open" (T2 §0) are analyst decisions / interpretations [I]; 10 extensions beyond R4 (T3 §3b) are not supported.

## C. Prioritized register summary

Full analysis: `R7/VP-DECISION-REGISTER.md` r1.4, §5a (per-VP timing) and §5b (order). Timing only; no value is recommended.

| priority | item | recommended timing | needed for |
|---|---|---|---|
| 1 | VP1d-H (admit the T-B form of R-h, inference not applied to the observed capture?) **[r4.2] DECIDED (i), F-LOG-0254** | DECIDE NOW, as its own item; **[r4.2] decided (F-LOG-0254)** | not strictly O1; removes an unratified analyst decision |
| 2 | VP15 (accept R4's modelling choices) | DECIDE NOW if going beyond O1 | O2, O3 |
| 3 | VP5 (one-corpus basis vs defer to V6) | DECIDE NOW | O2, O3; **O2 presupposes option (i)** ((ii) and (iii) block O2; disclosed, not recommended) |
| 4 | VP10 (CB4) | DEFER the value; record "left open" before VP14 | **O2: the explicit "left open" record** (edge VP10 → VP14); O3: decision |
| 5 | VP14 (partition, or no boundary layer) | DECIDE NOW | O2, O3 |
| 6 | VP3 (act identifiability) | AUTHORIZE TARGETED RESEARCH (non-Claude coding check), optional, parallel | none |
| 7 | VP4, VP12, VP11 | DEFER to the O3 gate | O3 |
| 8 | VP9, VP8, VP7 | DEFER (VP9 → V2 slice if any gate relies on multi-target norms) | none |
| 9 | VP13, VP1a, VP1b, VP1c, VP2, VP1d | DEFER (path A; VP1b / VP1c symmetric) | none |
| 10 | VP6, VP16 | DEFER until a source exists | none |

**Minimal set for O2 (register §3, r1.2):** VP15 decided; VP5 decided as option (i); VP10 explicitly recorded as left open (or decided); VP14 decided (a partition or "no boundary layer"). Everything else stays open.

### C1. Partition comparison (VP14). No option is selected

**Research findings** (from T3 r1.4 §1a and §6; [F] / [E] as there):

| option | cut | H-D / H-B clause placement (finding) | element-level traceability in T3 |
|---|---|---|---|
| P-a | acts + σ │ work │ records | H-D clauses internal; H-B clauses cross CB2↔CB3 | **full** (the CB1–CB3 tables are P-a's layout) |
| P-b | σ + work │ acts │ records | conformance and work disjuncts internal; change clauses of both families cross acts↔σ | clause-level only; element tables would need re-derivation |
| P-c | norm-and-work │ records | change and conformance clauses of both families internal; existential record clauses cross, symmetric under 𝐑₀, asymmetric under R-i / R-i′ | clause-level only |
| P-d | observed │ unobserved | as P-c for the record clauses; splits concepts by attribute (C13) | clause-level only |
| no boundary layer | none | no crossing by construction | CB1–CB4 rows dropped; CG, CV, CE, CS, MW rows unaffected |

**Design rationale** ([E] assessments by the analyst; **not research findings**; no weighting, no ranking, no total).

**Disclosed tilt:** criteria 1–5 below favour "no boundary layer" largely because it makes no decomposition at all; that advantage is the absence of a structure, not evidence for it. The decomposition-value row states what each option gives up or provides. The table must not be read as recommending any option.

| criterion | P-a | P-b | P-c | P-d | no boundary layer |
|---|---|---|---|---|---|
| traceability | complete today | needs re-derivation | needs re-derivation | needs re-derivation | retained for all non-CB elements |
| minimal unsupported assumptions | a cut not derivable from T1 / T2 (T3 §6 item 8) | same | same, plus one undivided side | the line follows R23 (Lem 1.12, [F]); adds the assumption that concepts may be split by attribute | none added |
| reversibility | medium (re-layout re-derives §1a, VP11, VP12) | medium | medium | medium | high (a layer can be added later; VP11 / VP12 then follow) |
| coupling ([I]) | H-B clauses and R18 cross; R2, R3 shared with records | both families' change clauses cross; F1 (R1) crosses acts↔σ [I] | fewest clause crossings among the cuts; largest single side | record-concept attributes cross the line | none at boundary level; no decomposition either |
| compatibility with future evidence ([I]) | V6 / V2 affect all options alike | as P-a | as P-a | an E-A5 channel (σ / f) or a VP3 change (acts identifiable) **moves its line** | unaffected |
| H-D / H-B congeniality (disclosed, not optimized) | H-D-congenial | partially H-B-congenial | neutral under 𝐑₀ | neutral under 𝐑₀ | neutral |
| decomposition value ([E]) for O3 (register §3: O3 needs boundaries to map onto) | three sides, separating norm-making, work and records; element tables exist | three sides, separating acts from σ + work | two sides; the norm-and-work side is undivided, so less to map onto | two sides aligned with what records can and cannot show (R23); concepts split across them | **none:** O3 would have no boundary to map onto, so VP14 would have to be reopened before O3 |

## D. Recommended next gate

**Gate: Human ARB disposition of R7** (proposal E1). Its two preconditions are met: the verification is confirmed (§A) and the F-LOG-0250 correction exists (F-LOG-0251).

| rationale | source |
|---|---|
| R7 reached its stop condition; every decision needed beyond it is listed and defaulted empty | F-LOG-0248, F-LOG-0250; T3 §4 |
| O1 needs **no** VP decision; deciding VPs first would bundle design choices into acceptance | register §3 |
| VP1d-H is the only item affecting the neutrality of the R7 deliverable itself; it is cheap and reversible, so it should follow as a **separate** act | register §5a |
| Bounded architecture derivation is **not** the next gate: it needs VP15, VP5, VP14 (O2) and VP10, VP12, VP11 (O3), and its own authorization | register §3; F-LOG-0248 |
| The F-LOG-0247 order (V2 / V5 / V6 first) was overridden for R7 only; it is not overridden for later gates | F-LOG-0248 |

## E. Draft authorization wording (separate proposals; approve or reject each)

**Approval of one proposal is not approval of any other, and not approval of a later gate.** Each proposal can be approved, rejected or amended on its own.

**Gate chain:** E1 → E2 → E2b → E3 → E3b → E4 → E5. Each arrow is a prerequisite, never an implied approval.

### E1. R7 closure

| field | content |
|---|---|
| scope | disposition of T1 r1.5, T2 r1.3, T3 r1.4 (commit 1a63f3a14) as the R7 candidate deliverable |
| prerequisites | verification result recorded (§A: met); F-LOG-0250 correction recorded (F-LOG-0251: met) |
| exclusions | no VP decided; no partition selected; nothing adopted; no architecture, R8 or implementation |
| acceptance criteria | §B table holds; no material verification failure |
| unresolved risks | §F; Claude-family review only (Λ7); analyst decisions (X25 ground, T2 §0 [I]) unratified |
| permits | recording R7 as closed with limitations; nothing downstream |

> **Draft:** "I, Human ARB, accept R7 (T1 r1.5, T2 r1.3, T3 r1.4) as a candidate deliverable WITH the limitations specified in `R7/ARB-DECISION-PACKAGE.md` §B, and close R7. This act adopts no model element, decides no variation point, selects no partition, and authorizes no architecture, canonicalization (R8) or implementation." / "I, Human ARB, do not accept R7 and return [items] for revision."

### E2. Variation-point decisions (one item per VP; never bundled)

Each line is a **separate** act. The human fills in the option from the register (§1) or "leave open"; this package proposes none.

| item | scope | prerequisites (register §2) | permits |
|---|---|---|---|
| E2-VP1d-H | admissibility of the T-B form of R-h, with the non-occurrence inference not applied to the observed capture | **E1** (no VP prerequisite in the register graph; it follows E1, as §D sequences it, so it is not bundled with acceptance) | fixes VP1d's option set |
| E2-VP15 | R4's five modelling choices (each may be ruled separately) | E1 | O2 basis (with VP5, VP14) |
| E2-VP5 | corpus basis | E1 | O2 basis, one-corpus scoped |
| E2-VP10 | CB4 | E1 | VP14's boundary set |
| E2-VP14 | partition or no boundary layer; decided without reference to VP1a–d (N1) | **E1; VP15; VP5; VP10** (an explicit "left open" record counts) (register edges VP15, VP5, VP10 → VP14) | O2 / O3 layout |
| E2-VP4, -VP7, -VP8, -VP9, -VP12, -VP11, -VP13, -VP2, -VP3, -VP6, -VP16, -VP1a, -VP1b, -VP1c, -VP1d | as register §1, one each | E1, plus the register §2 edges | as register §1 |

Exclusions for every item: no other VP; no architecture. Acceptance criteria: the chosen option exists in register §1 and its assumptions are recorded as such (N5); **for an evidence-gated VP** (VP3, VP6, VP16, and VP9 where it relies on multi-target norms) the decision record states either (a) the authorized and completed evidence route (VP3: a non-Claude coder; VP6 / VP16: a named new source, for σ / f via E-A5; VP9: a V2 slice) or (b) the label **"assumption, not evidence"**, with the value listed as an added limitation in §B. Unresolved risk: register §5a "research" column.

> **Draft (template, one per item):** "I, Human ARB, decide VP__ as: [option from register §1 / leave open], labelled as [modelling choice / normative policy / convention / design decision]. This decides no other variation point." / "I, Human ARB, do not decide VP__ at this time."

### E2b. O2: adoption of the bounded candidate model

| field | content |
|---|---|
| scope | adopt the T3 r1.5 (as amended under F-LOG-0254) candidate model as a **bounded** model ([E], one corpus, pre-architecture), with the VP outcomes of E2. **Rule for VP1a–d (analyst resolution, disclosed; this rule wins over any other line of E2b):** (1) if the human decided a VP1a–d value under E2, E2b adopts it as an **active optional policy, explicitly labelled "human-decided policy, not derived from research phases R1–R6"**, keeping its H-D / H-B encoding label; VP1a–d values not decided stay inactive slots. (2) an evidence-gated VP value (VP3, VP6, VP16, and VP9 where it relies on multi-target norms) decided under E2 enters E2b **only if** (a) its evidence route was authorized and completed (VP3: a non-Claude coder; VP6 and VP16: a named new source, for σ / f via E-A5; VP9: a V2 slice), or (b) it is labelled **"assumption, not evidence"** and listed explicitly as an added limitation in §B. |
| prerequisites | E1; E2-VP15 decided; E2-VP5 decided **as option (i)** (other options block this gate); E2-VP10 recorded as left open (or decided); E2-VP14 decided (a partition or "no boundary layer"); independent review of the adoption text |
| exclusions | undecided VP1a–d, VP2 and VP13 stay inactive slots (decided VP1a–d values: see the scope rule); VP3, VP6, VP16, VP9 stay evidence needs; VP4, VP7, VP8 unsupported; VP11, VP12 provisional, in each case unless decided under E2; no T-B / T-C / X item as an **invariant** (a human-decided VP1a–d policy is an optional policy, not an invariant); T-A rows adopted only as model constraints with adequacy [E]/[I]; no generality; no architecture, R8 or implementation |
| acceptance criteria | each adopted element traces to T3 §2; the standing qualifier and §B limitations carried; the partition (or its absence) recorded as decided under N1 without reference to VP1a–d |
| unresolved risks | one-corpus basis (VP5); low reversibility of VP15; nothing enforceable from records (R23); every §F condition |
| permits | a bounded adopted model that later gates may cite; nothing downstream |

> **Draft:** "I, Human ARB, adopt the R7 candidate model (T3 r1.5, as amended under F-LOG-0254) as a bounded [E] model, one corpus, pre-architecture, with the variation-point decisions recorded under E2 (any decided VP1a–d value as an active optional policy labelled "human-decided policy, not derived from research phases R1–R6", with its H-D / H-B label) and the limitations of `R7/ARB-DECISION-PACKAGE.md` §B. This authorizes no architecture derivation, canonicalization (R8) or implementation." / "I, Human ARB, do not adopt the bounded model."

### E3. Bounded architecture derivation

| field | content |
|---|---|
| scope | derive an architecture proposal from the adopted bounded model only. **Relation to register O3:** O3 is the *preparation* of the authorization proposal for this gate; E3 is the gate itself (the derivation). Both use the same minimal VP set |
| prerequisites | E1; E2b (O2 adoption); E2 for VP10, VP12, VP11 (beyond E2b's set); a prepared authorization proposal (O3) |
| exclusions | human-decided VP1a–d values as active optional policies, labelled "human-decided policy, not derived from research phases R1–R6" with their H-D / H-B label; undecided VP1a–d, and undecided VP2, VP13, as inactive slots (VP2, VP13 decided under E2: carried with their decision label); never invariants (N1, N2, N5); VP3, VP6, VP16, VP9 as evidence gaps unless they entered E2b under its condition (a) or (b), and then carried with that label; VP4, VP7, VP8 stated as unsupported unless decided under E2 (then carried with their decision label); no canonical terms (R8); no implementation; no generality claim |
| acceptance criteria | every architecture element traces to T3 elements and their §2 rows; standing qualifier carried; independent review |
| unresolved risks | one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records) |
| permits | an architecture **proposal** for human review; nothing built; the proposal is not accepted by this gate (see E3b) |

> **Draft:** "I, Human ARB, authorize a bounded architecture derivation from the adopted R7 model under the scope and exclusions of `R7/ARB-DECISION-PACKAGE.md` §E3. This does not authorize R8 or implementation." / "I, Human ARB, do not authorize architecture derivation."

### E3b. Acceptance of the architecture proposal

| field | content |
|---|---|
| scope | accept or reject the architecture proposal produced under E3 |
| prerequisites | E3's proposal produced; an independent review of it completed |
| exclusions | no R8 canonicalization; no implementation; no change to E2 / E2b decisions by implication |
| acceptance criteria | every architecture element traces to T3 elements and their §2 rows; no T-B / T-C / X item as an invariant; human-decided VP1a–d policies kept labelled; standing qualifier carried; review findings resolved or recorded as limitations |
| unresolved risks | one-corpus basis (VP5); low reversibility of VP15; R23 (nothing enforceable from records); every §F condition |
| permits | an accepted architecture proposal that E4 and E5 may cite; nothing built |

> **Draft:** "I, Human ARB, accept the architecture proposal [id / revision] produced under §E3, with its review findings and the §B limitations. This authorizes no canonicalization (R8) and no implementation." / "I, Human ARB, do not accept the architecture proposal and return [items]."

### E4. R8 canonicalization

| field | content |
|---|---|
| scope | ubiquitous-language decisions over working labels (N8) |
| prerequisites | E1; E2b; E2 for the VPs whose labels are canonicalized; **E3b (the architecture proposal accepted after its mandatory independent review)** |
| exclusions | no R4 primitive name becomes canonical by default (N8); no N1–N7 item as a term; no implementation |
| acceptance criteria | each term has [E] justification and a trace to T1 / T3 |
| unresolved risks | terms may harden one-corpus concepts (Λ6) |
| permits | a canonical glossary; nothing else |

> **Draft:** "I, Human ARB, authorize R8 canonicalization within §E4. This authorizes no implementation." / "I, Human ARB, do not authorize R8."

### E5. Implementation

| field | content |
|---|---|
| scope | building any part of the model or architecture |
| prerequisites | E1, E2 (needed VPs), E2b, E3, **E3b**, E4 for the terms used |
| exclusions | no T-B / T-C / X item as an invariant; no inference of non-occurrence from absence; no reading of d or f off r |
| acceptance criteria | per the accepted architecture; standing qualifier in documentation |
| unresolved risks | everything in §F; enforcement from records is impossible by R23 |
| permits | implementation of the accepted scope only |

> **Draft:** "I, Human ARB, authorize implementation of [scope] under §E5." / "I, Human ARB, do not authorize implementation."

## F. Outstanding risks and reopening conditions

| condition that would require reopening | decisions to reopen |
|---|---|
| a later review finds a material defect in T1 r1.5, T2 r1.3 or T3 r1.4 | E1 (R7 closure); affected register rows; E2b if it cites the defective row |
| a defect is found in an independent review of **this package or the register** | the affected recommendation or draft wording; any act taken on it |
| a defect is found in **sealed R4 r3.1** | every T1–T3 row citing the defective statement; E1; E2b; every VP whose evidence column cites it |
| the **H5 / F-LOG-0190 condition** is met or fails (Λ3) | path A (F-LOG-0247); VP1a–d; E1 limitation 3; if it fails: VP5, VP15 and E2b's basis |
| the **T2 §0 [I] reading of "open"** in the T-C criterion is overturned | T2 tiering of the rows it governs (T-A vs T-C), hence T3 carried rows; E1; E2b |
| the human **rules on VP1d-H** | VP1d (its option set); T1 X25, T2 R41 status if (i) is chosen; E1 limitation 4 |
| a VP is found to have been **decided by implication or bundled** with another act | that VP (to be re-decided on its own); every act that relied on it (e.g. E2b if VP14 was implied by T3's P-a layout) |
| a **non-Claude run** disagrees with the within-family results (slice, bundle-02) or with act / mode coding | K7-dependent items: VP3, VP2; E1 limitations; Λ7 |
| **V6** shows the primitives, K6 or K7 are corpus-specific | VP5, VP15; E2b (the adoption); E3's derivation and proposal; E4's terms that name the affected primitives; E5's built scope |
| **E-A5 / Protocol C** evidence on σ or f (an observable channel in Cor 4.3's sense) | VP1a–d, VP2, VP14 (P-d's line), VP6, VP16; path A |
| **V2** shows D1 or its weak form fails under R-a / multiple targets | VP9; K2-dependent statements; VP1a (R31) |
| a T-A constraint is found **not to hold in the domain** (adequacy failure) | VP15 (the choice it rests on); every T3 element whose T-A column carries that row (T3 §2); E2b; any E3 / E4 / E5 output tracing to those elements |
| the A7 (direct) / H11 (conditional on capture completeness) ground for X25 is overturned, capture completeness is established, or a universal-completeness policy that never applies the inference to the observed capture is shown workable | VP1d-H, VP1d |
| any adopted element is found to encode H-D or H-B, or a partition choice is found to be justified by VP1a–d outcomes | VP14, the adopting act (N1) |
| a quarantined R3 item, B10 or Conj 5.5 is found used as fact | the citing artifact (N6, N7) |

## Standing qualifier

"one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

## Self-check

| check | result |
|---|---|
| no VP value chosen; no partition selected | yes: §C, §C1, §E2 give timing or blanks only |
| R7 not closed | yes: E1 is draft wording |
| no generality claimed; waived never called satisfied | yes: §B, standing qualifier |
| no H-D / H-B validation or falsification claimed | yes: path A cited as non-identifiable |
| design rationale separated from findings | yes: §C1 two tables, the second labelled [E] |
| gates separate | yes: §E preamble and each draft; O2 is its own gate (E2b) |

## Change rows

| rev | change | reason |
|---|---|---|
| r0 | initial package | ARB decision package task |
| r1 | live references → T1 r1.5, T2 r1.3, T3 r1.4, register r1.1, F-LOG-0251; §A verification result filled in (round 1 residuals, round 2 confirmed PASS_WITH_LIMITATIONS, no material defect) and F-LOG-0251 recorded; M1 final X25 ground (A7 direct, H11 conditional on capture completeness) and reworded human alternative ("not applied to the observed capture"); M2 aligned with X7 (D-chg alone) and C7 / R20 (B-chg issue-act disjunct); §B conditions stated as satisfied and limitations listed (still a recommendation, not closure); §C item 1, §D, E1, E2-VP1d-H, §F rows updated | coordinator instruction; verified final state |
| r2 | package review (PASS_WITH_LIMITATIONS): §B limitation 7 corrected (T3 §3c = 1 within-R4-untested + 2 evidence needs); §A item 2 uses F-LOG-0251's wording; §C cites register r1.2; new gate **E2b (O2 adoption)**; E3 scope reconciled with register O3; E2-VP14 prerequisites + E1, VP5; E2-VP1d-H prerequisite = E1 (matches §D); all other E2 items follow E1; E4: E3 authorization and review mandatory, draft excludes implementation; §C1: tilt toward "no boundary layer" disclosed and a decomposition-value row added ([E], unranked); O2 constraints explicit (VP5 = (i); VP10 "left open" record); §F extended (H5 / F-LOG-0190, R4 r3.1 defect, T2 §0 [I], VP1d-H ruling, review of this package / register, implied or bundled VP) and "everything built on them" made precise | package review required changes 1–6 |
| r3 | new gate **E3b** (acceptance of the architecture proposal); E4 and E5 now require E3b; gate chain E1 → E2 → E2b → E3 → E3b → E4 → E5 stated; **E2b conflict resolved (analyst resolution, disclosed; it wins):** decided VP1a–d values become active optional policies labelled "human-decided policy, not derived from R1–R6", keeping their H-D / H-B label, undecided ones stay inactive; E2b exclusions reworded to match; register → r1.3 | coordinator final changes 1, 2 |
| r4 | E3 exclusions aligned with E2b (decided VP1a–d = labelled active optional policies; undecided = inactive slots); evidence-gated condition (route completed, or "assumption, not evidence" plus a §B limitation) added to E2's acceptance criteria and E2b's rule; label → "not derived from research phases R1–R6" in live text (r3 change row left as historical); register → r1.4 | coordinator last fixes 1–3 |
| r4.1 | final verification (PASS_WITH_LIMITATIONS) | E3 exclusions: VP2, VP13, VP4, VP7, VP8 carried with their decision label when decided under E2 (consistent with E2b). Applied by the main analyst |
| r4.2 | **amendment under human ruling, post-closure** (F-LOG-0254; R7 closed by F-LOG-0253): §B item 4 superseded for the policy layer (the invariant exclusion stands); §C priority 1 marked decided. No other VP decided; nothing adopted | F-LOG-0254 |
| r4.2 sync | amendment review (PASS_WITH_LIMITATIONS), F-LOG-0254 synchronisation, post-closure: §B item 4 "resolved" qualified with the residual disclosed condition. No decision content changed | F-LOG-0254 |
| r4.3 | sync | E2b cites T3 r1.5 (amended under F-LOG-0254). No decision content changed |
