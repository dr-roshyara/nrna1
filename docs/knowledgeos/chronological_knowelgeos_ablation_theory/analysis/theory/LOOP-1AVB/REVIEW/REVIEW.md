# Review: de-cued coding pilot (P3, P4)

**Files read:** REVIEW-TASK.md, TASK.md, EVENTS-decued.json, SOURCES.md, ID-KEY.json, SCORE.json, COLLISIONS.json, CODING-P1-cued.json, AUDIT-1AR-REVIEW.json, CODING-P3-decued.json, CODING-P4-decued.json. REVIEW-TASK.md does not name the two P3/P4 coding files; I found them under those names. I had no directory listing, so other files may exist that I did not see. **CODING-P2-cued.json is not provided**, so I cannot check the P3~P2 and P4~P2 figures. The definitions of schemas A and B are also not provided. No code was run; every count below was done by hand.

## Summary verdict

- Every headline number reproduces.
- **None of the 3 changed labels is a CORRECTION.**
  - 1 is a COLLISION (E07).
  - 2 are CUE-DRIVEN JUDGEMENTS (E22, E25). In both, the description carried information needed to *locate* the event (a line number; which assignment of R-90). It did not only carry interpretation. So in both cases the cued label is the better-founded one.
- All three changes move *away* from the audit.
- The collision groups show that at least 11/36 events are individuated only by the description at record level.
  - Kind's contrast *is* in the source text, but each kind pair sits inside one sentence. One member of each pair is the negative side of a stated norm, not a second attested occurrence.
- P3~P4 = 1.000 is still degenerate:
  - 13 identical quotes;
  - identical choices on every hard case;
  - the same invented `OTHER:` verbs.
- Span-level anchors are required. That requirement comes before the A/B choice. It is conditional only on A and B both treating one record as one event (their definitions were not provided).

## 1. Recomputation

| Quantity | Claimed | Recomputed | Match |
|---|---|---|---|
| P3~P4 observation_type | 1.000 | 36/36 = 1.000 (ACT 23 · NORM 11 · UNK 2 for both coders) | ✅ |
| P3~P4 source_act operation | 0.833 | 30/36 = 0.833. The 6 disagreements: E06, E07, E22, E25 (`OTHER:REPORT` vs `NONE`), E29, E30 (`ADOPT` vs `OTHER:DISPOSE`) | ✅ |
| dual YES | 27 / 27 / both 27 | 27 / 27; identical on all 36 | ✅ |
| vs audit (per coder) | 0.889 | 32/36. Misses: E07 (audit GENERIC), E08 (audit PERMISSION→NORM), E22 (audit UNCLEAR), E25 (audit PERMISSION→NORM) | ✅ |
| P3~P1 (cued) | 0.917 | 33/36 | ✅ (P2 not checkable) |
| Changed labels vs cued P1 | 3 | E07 GENERIC→ACT · E22 UNKNOWN→ACT · E25 NORM→ACT | ✅ |
| Collision groups | 3 groups / 11 events | (REGISTER, S0804-L576, UNK): E06, E07 · (OPEN-WORK, R-60, WP-7B-R1): E08, E09 · (RAISE, R-36, AST-013): E15–E21. No other exact duplicates. | ✅ |

What the headline numbers do not show:
- **Near-collision.** E25 `(ASSIGN-ID, S0804-L576, UNK)` shares both anchor and target with the REGISTER pair.
- **Deontic disagreement not reported.** E27 is `UNK` (P3) vs `REQUIRED` (P4). SCORE.json does not report deontic agreement at all.
- **Mislabelled coders.** In SCORE.json, `decued_scores` names the de-cued coders "P1/P2".
- **Cued baseline vs audit is higher.** Cued P1 vs the audit is 35/36 = 0.972 (the only miss is E08). De-cueing therefore lowered agreement with the audit by exactly the three changed labels.

## 2. The three changed labels

| Event | Class | Basis |
|---|---|---|
| **E07** REGISTER constitutional decision (rule) | **COLLISION** | E06 and E07 have identical fields, and both coders gave them an identical quote and label: "a ruling was put forward under a number already holding WP-4C-1's authorization; filed as R-90 with the collision recorded." The rule the cued coder quoted, "*the register holds constitutional decisions*" (S0804), cannot be reached without the description. |
| **E22** RAISE L493-B (single occurrence) | **CUE-DRIVEN JUDGEMENT** (locator cue; the cued label is better) | The de-cued anchor is `S0815-L483`, and SOURCES gives "session log 2026-08-15, lines 480-490". The cued id points to L493, which is outside the excerpt; P1 wrote "S0815-L493 (outside the provided excerpt, lines 480-490)". Both de-cued coders quoted "BINDING VOCABULARY RULING — applies to ALL future documentation, not just this artifact". That span raises nothing into "methodology". Both coders also coded source_act as `OTHER:REPORT`/`NONE`, which contradicts their own ACT label. The right label is UNKNOWN, as in the cued pilot and the audit. |
| **E25** ASSIGN the retired number R-90 | **CUE-DRIVEN JUDGEMENT** (latent span collision) | S0804 has two ASSIGN-ID spans. One is an act: "filed as R-90 with the collision recorded". The other is a norm: "The number R-90 is RETIRED, not recycled" (compare R-90: "THE NUMBER R-90 IS RETIRED AND MUST NOT BE REUSED"). With target `UNK`, the record cannot select between them. The de-cued coders coded the *original* assignment, which is not the frozen event (re-use). P4 quoted both spans and still wrote ACT. The cued NORM/FORBIDDEN label is the faithful one. |

**No CORRECTION.** In none of the three cases is the de-cued label more faithful to SOURCES.md for the event the frozen id denotes.

**Label-preserving evidence shifts that the "3 changed" count misses:**
- **E01.** Both coders quote "✅ ADOPTED … BY THE DECISION AUTHORITY … (adoption act: R-86)". That is E02's act, not the Chief's declined adoption ("THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION … The Chief declines…"). This hits the audit's only clean surviving variable *a*: under de-cueing, the E01/E02 contrast is carried by the quote of the wrong event.
- **E19–E21 ("not promoted").** Both coders quote "Promoted into AST-013 … 6 one-line behaviours". The cued coder quoted "Expressly NOT promoted:". The label stays ACT only because a refusal is also an ACT.

## 3. Individuation

**Are the collision groups confirmed?** Yes. For 11/36 events, the record fields (operation, anchor, target) individuate nothing, so only the description separates them.

**REGISTER pair (E06/E07).** Kind's contrast is in the source, in one S0804 sentence:
> "R-90 itself withdrawn before adoption, because the Authority ruled it was operational acceptance rather than a constitutional decision — *the register holds constitutional decisions*."

It is stated more explicitly in the R-90 annotation, which neither event anchors:
> "The register holds constitutional decisions, not operational acceptance."

- E06 is the refused act clause and E07 is the rule clause of the same sentence.
- That is one **dual** record (a refusal plus the norm it applies), not two occurrences.

**OPEN-WORK pair (E08/E09).** The contrast is in the R-60 recording note:
> "the issuing text placed this motion under a heading reading "Recording Notes -- Not Part of Ruling" … **A recording note cannot open a work package** — that is the ruling/note distinction this register enforces — so it is filed as a ruling in line with the operative text."

- There is one opening of one package.
- The "note" member is what the norm says *cannot* happen. The "ruling" member is the act.
- The sub-spans are distinct, so a span anchor can separate them. What it reveals, though, is one act plus one norm, not two acts.

**Consequences for the kind witnesses:**
- *Strictness is vacuous.* In AUDIT-1AR, both kind pairs were "strict" (only k differs). But k is not a recorded field, so at record level the pairs differ in **zero** fields. The strictness came from the description.
- *No attested occurrence on the negative side.* In each pair, kind's contrast is located at one span, and one side of it is a norm.
- *Result unchanged.* The audit already had k = 0 surviving; this pilot shows the pairs were not witnesses to begin with.

**The *e* pair has the same problem.** The *e* cluster pair (R-36 promoted vs not promoted) lies inside the third collision group. Items #2/#3/#9/#10/#5/#14/#17 refer to the matrix in `claude/plans/swirling-jingling-blossom.md`, which was not provided. So *e*'s remaining support is also individuated only by the description, and no quoted span can currently be supplied for the individual items.

## 4. Agreement

**Quote overlap across the 36 events:**
- 13 identical: E01, E06, E07, E12, E15–E21, E22, E33.
- 17 overlapping or nested.
- 6 disjoint spans: E04, E05, E09, E26, E35, E36. All six are easy rows where the whole row says the same thing.

**Identical choices on every hard case:**
- the collision pairs;
- E22 (ACT with REPORT/NONE);
- E25 (ACT on the wrong span);
- E19–E21 (the promoted-clause quote);
- E27 (NORM);
- E35 (FORBIDDEN while quoting "freeze is lifted").

**Identical invented verbs:** `OTHER:DETERMINE` (E03), `OTHER:ADJUDICATE` (E26) and `OTHER:DEFER` (E12). The last two also match cued P1. The only divergences are in vocabulary (`REPORT` vs `NONE`, `ADOPT` vs `DISPOSE`) and in one deontic value (E27).

**Still degenerate.** This looks like shared same-family priors, not independent judgement.

**What de-cueing changes:**
- *The description is removed as a shared cause*, and still 3 labels move, identically in both coders. Cue sensitivity is therefore a property of the input, not of the coder.
- *Agreement on collision groups is guaranteed.* Identical inputs give identical outputs, so 11 of the 36 agreements are guaranteed. At most 28 distinct records are really being agreed on (E24 and E28 are also the same placeholder).
- *The audit cannot referee cue effects.* The audit was itself made with descriptions.

## 5. Schema implication

**Yes: event records need a span-level anchor.** That means a verbatim quoted span which, together with operation and target, is **unique** across records. It should be separate from `type_quote`, the evidence for the label; the coders' type quotes wander across a row (E04, E05, E09, E35, E36).

**Why row-level anchors are not enough:**
- they fail for 11/36 events outright;
- they fail latently for E01, E25 and E22, where the anchor does not even cover the described line.

**The span anchor also works as a test.** If two events resolve to the *same* span, they are one (possibly dual) event. On that test, the REGISTER pair collapses into one dual record.

**Required under both A and B.** Individuation comes before classification. A two-level schema multiplies the labels per record, and minimal v1 rules constrain the labels. Neither can repair a record that denotes more than one event. This is conditional on the A/B definitions, which were not provided. It holds unless one of them itself defines events by a unique span.

**Practical constraint:** for the R-36 items, span anchors need the item-level source (the matrix). Otherwise those events should be merged to the cluster level.

## Disagreements

See REVIEW.json `disagreements` (D1–D14), classified by type.
