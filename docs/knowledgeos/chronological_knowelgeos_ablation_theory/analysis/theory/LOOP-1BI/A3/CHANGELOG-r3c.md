# CHANGELOG-r3c: consolidation of SCHEMA-r3 (r2 → r3c)

Inputs used: r2 `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`, `CHANGELOG-r2.md`, `KNOWN-AMBIGUITIES.md`; `REVIEW-r2.md` / `.json` (verdict PASS_WITH_LIMITATIONS, codability risk HIGH); `SCHEMA-r2.md`, `RECORDS.json`, `SOURCES.md`. No commands, no web.

Scope: only the eight drafting items in TASK.md item 1, the KNOWN-AMBIGUITIES corrections (item 2), and the two new documents (items 3–4). Every schema change is tagged `[r3c-fix-<id>]` in `SCHEMA-r3.md`. The r2 wording that r3c replaces is kept there, struck through, and is also quoted below.

**No substantive change.** Each fix makes the text say one thing where r2 said two, or adds a tag. No `act.state`, `act.operation`, `act.basis`, `norm.deontic` or `norm.about_operation` value reported in CHANGELOG-r2 or REVIEW-r2 changes on any record in RECORDS.json. One reading of r2 is removed: the literal step-1 reading under which the *rule* act on R06/R07 had no record. That reading was never the reported answer (CHANGELOG-r2 §3, REVIEW-r2 §4 G6). No rule was chosen to reproduce a coding, and no coder output was consulted.

---

## 1. Changes by TASK item

| TASK item (REVIEW-r2 nonblocking #) | Id | Where in SCHEMA-r3 | Change | Replaced r2 text (verbatim) |
|---|---|---|---|---|
| R-A5 step 1 vs §1 / D10 for shared-span ground acts (R06/R07) (#1) | **SS** | §1 new sub-bullet; R-A5 step 1 first bullet and new "Shared-span exception" bullet; R-S1 ACT bullet | A ground-marked act is excluded from the record's candidates **unless** records share the span. Then, being DISTINCT from the act it grounds (D10), it is the act of its own sharing record (§1, R-S1) and is excluded from the others. If no sharing record is left for it, it is a candidate for none and is entered in `act.ground_candidates` on each. On an unshared span it is never a candidate. §1, D10, R-S1 and step 1 now give the same answer. | Step 1: "An act that stands in a **ground-marked segment** (D15) is not a candidate for this record. It may be its own record." |
| PROVENANCE in D15 (ii) vs PV (#2) | **PR** | D15 (ii) list and new sub-bullet; R-A5 step 1 PV bullet (last sentence) | *PROVENANCE* removed from the ground-label head words. A label whose first word is PROVENANCE ("PROVENANCE:", "PROVENANCE ANNOTATION …:") is not a ground label, so the head-word question for "PROVENANCE ANNOTATION" no longer decides anything. Acts after such a label are provenance mentions and are candidates (PV). Such a label still ends a preceding GROUNDS-type segment. The review chose this direction (remove) and noted that PROVENANCE was the one D15 word the sources do not use for grounds (REVIEW-r2 §6). | D15 (ii): "*GROUNDS*, *EVIDENCE*, *REASON*, *RATIONALE*, *BASIS* or *PROVENANCE*" |
| MECHANISM RESPONSE needs a stated non-effect; intended test failures (#3) | **MR** | D6 MECHANISM RESPONSE definition and two new "not mechanism responses" bullets | A mechanism response must state that the attempt it answers **did not take effect**. "returns nothing" is removed from the examples. An empty result, or a test or check report with no refused, rejected or errored attempt, is a result of an act that took effect and not a mechanism response. **Intended test failures** (marked RED, "expected to fail", "fails as intended" in span or section) are never a non-effect marker: the test run is coded like any other act (rank 3 if it occurred), and the intended failure does not by itself make `act.mechanism_refusal` required. The S0815 CLOSE probe ("→ refused: …, exit 65, record unchanged") is unaffected. | D6: "A refusal, rejection or error expression that the span presents as the **output of a program, tool, machine, test or automated check** (a quoted output, an exit code, "→ refused: …", "returns nothing"), not as the act of a named actor or body." |
| Make R-A5 step 2 conditional (#4) | **S2** | R-A5 step 2 | "The record is coded NONE / OUTSIDE / X" now applies only **if no ungated candidate is coded at step 4**. X is the gated candidate that step 4 chooses among gated candidates. If an ungated candidate wins (e.g. a REPORTED provenance act), the record takes its state, basis and operation, and the gated candidates are kept only in `act.gated_candidates` (`coded = false`) and `SPLIT_REQUIRED`. This states what step 4 and CHANGELOG-r2 §3 (R18, R20) already did. | Step 2: "The record is coded `act.state = NONE`, `act.basis = SELF_PERFORMED_OUTSIDE_RA3`, `act.operation = X`. `act.state_if_extended` is the result step 3 would give it." |
| Align the TC-1 1c table and recipe (#5) | **TC1c** | THEORY-CHOICES TC-1 (erratum in §2 below; no schema text changes) | 1c is **additive per (operation, target)**, as the table says. The recipe no longer re-applies step 4. | See §2 |
| Add `act.ground_candidates` (#7) | **GC** | §2 new field; R-A5 step 1 first bullet | Required on every record: one entry (`operation`, `actor`, `target`, `quote`, `marker`) per act that step 1 excluded as ground-marked. Not entered when the act is coded on another sharing record. No state is coded and no other field changes. | — (new) |
| Define the "normative word" for class-label clauses (#9, first half) | **NW** | §2 `norm.performative_token` new sub-bullet; D8 (i) last class-label bullet | For a class label, the normative word is the label noun phrase, not the copula, preposition or colon. It is `performative_token = true` only if that noun phrase is itself an act expression in the span. On every current class-label clause (R06/R07 "operational acceptance …", R09 "a ruling", R23 "a permanent documentation standard", R31 "a SEPARATE act", R21 "temporary carrier, not a pattern") both the copula reading and the label reading give `false`, so no tag changes. | — (new) |
| Fill `mechanism_refusal` on R03 (#9, second half) | **R03** | Record value only (§3 below) | `act.mechanism_refusal = false`. | — |

REVIEW-r2 nonblocking #6 (KNOWN-AMBIGUITIES) is TASK item 2; see §4. #8 (`label_kind`) is **not** in the TASK's list and is not done; B4 in KNOWN-AMBIGUITIES still discloses it. #10 is TASK items 3–4; see §5.

## 2. Erratum to THEORY-CHOICES.md, TC-1 alternative 1c (binding)

TASK.md does not list THEORY-CHOICES.md among the files to write, so the file itself is not rewritten. This erratum replaces the two passages below. An analysis that computes 1c must use the text in this section.

**Table cell, "What counts as an act", row 1c.** Replace "1a + ~~…~~ every entry of `act.gated_candidates`, at its `state_if_extended`" with:

> 1a + every entry of `act.gated_candidates`, each counted as a further act observation of its own (operation, target), at its `state_if_extended`.

**Recipe for 1c.** Replace the whole paragraph "For each record, restore every entry of `act.gated_candidates` … with its gated *classify* / *recommend* candidates." with:

> **Recipe for 1c (additive).** For each record: (1) count the act that 1a counts, i.e. the coded act if `act.state` is not NONE; (2) add every entry of `act.gated_candidates` as a further act observation of its (operation, target), with state `state_if_extended` and, where given, `attempt_kind`. SCHEMA R-A5 step 4 is **not** re-applied: no entry displaces the coded act, and no entry displaces another entry. The entry with `coded = true` is the record's own gated act; 1a counts nothing for it (its `act.state` is NONE), and 1c adds it. 1c is therefore a superset of 1a on every record. Ungated candidates that lost at step 4 (`SPLIT_REQUIRED` notes outside `gated_candidates`) are counted under neither 1a nor 1c. Under this recipe, R18 keeps its REPORTED *adopt* and R20 its REPORTED *document*, and both gain *classify* and *recommend*. R15–R17 gain both *classify* and *recommend*. R01 gains *resolve* at UNKNOWN.

Why additive: the table and the consequence column ("act counts rise") already described an additive count, and REVIEW-r2 §5 recommended it. The competitive recipe made 1c lose R18's act, which contradicts the table. The additive form needs no tag beyond those r2 already records.

## 3. Records whose recorded values r3c touches

| Record | r3c value | Change vs r2 | Id |
|---|---|---|---|
| R03 | `act.mechanism_refusal = false`. The span contains the D5 (i) refusal expression "NOT ADOPTED". It is the Decision Authority's ("The Decision Authority held it"), a named body, not program or check output. False under every reading in KNOWN-AMBIGUITIES A4. | tag filled | R03 |
| R03 | `act.ground_candidates`: the acts in the D15 (i) segment "on the ground that Event D SEPARATES evidence submission from constitutional review, and this ruling collapsed the two". Marker (i) "on the ground that". | tag new | GC |
| R06 / R07 | The record for *rule* has ACT_OCCURRED *rule*, REPORTED, from the "because" segment, by the shared-span exception. This is CHANGELOG-r2's answer, now given by the text. The other record has ACT_WITHDRAWN *withdraw* R-90, REPORTED. `act.ground_candidates` is empty on both, because the ground act is coded on one of the sharing records. Which `record_id` carries which act is still KNOWN-AMBIGUITIES A5. Norm unchanged. | literal reading removed | SS, GC |
| R15–R21 | `act.ground_candidates`: the acts stated in the Evidence-column cell, marker (iii) with the column header. Examples from REVIEW-r2: R18 "ARB-adopted" (*adopt*, actor ARB, target the Completion-Review discipline); R20 "PB-005 deliberately did NOT reuse it" (*reuse*, actor PB-005, target Strangler reconstitution); R21 "explicitly ruled" (*rule*). Which evidence-cell expressions count as acts follows the existing candidate rules (see KNOWN-AMBIGUITIES B16). | tag new | GC |
| R18, R20 | No value change. Step 2 now says in words that the REPORTED act wins and the gated *classify* / *recommend* stay in `act.gated_candidates` with `coded = false`. | wording only | S2 |
| all other records | `act.ground_candidates` empty (no D15 segment in the span). No value changes. MR and PR affect no current span: no RECORDS span contains a program or test output, and none contains a PROVENANCE label. | tags only | GC |

Rule tests outside RECORDS: the S0815 CLOSE probe stays ACT_ATTEMPTED / FAILED, *append*, `mechanism_refusal = true`. The R-83 "grep … returns nothing" probe is now ACT_OCCURRED of the probe if it is reported, with no FAILED marker. A RED commit's "4 failed" (git-6a67da5d7 type) is no longer a FAILED marker when the RED marking is in the span or section.

## 4. KNOWN-AMBIGUITIES.md corrections

- **Header.** R15, R16 and R17 are removed from the "determinate in all five fields" list. They are now listed as determinate in `act.state`, `norm.deontic` and `norm.about_operation` only, with `act.basis` / `act.operation` open under B1. R19 and R21 are given the same qualification. R18 / R20 are qualified for `gated_candidates` (B1).
- **New Part D** lists every item REVIEW-r2 §4 B.1–B.6 found missing, with its status after r3c: B.1 (R06/R07 *rule* record), B.2 (PROVENANCE), B.4 (step 2) and B.6 (header) are resolved; B.3 (MECHANISM RESPONSE) and B.5 (TC-1 1c) are resolved, with a residual.
- **New Part B rows** for what is still ambiguous: B5 extended (mechanism-response residuals after MR), B12 (D15 (ii) head word when a listed word is not first), B13 (THEORY-CHOICES file not yet carrying the erratum), B14 (shared spans with more DISTINCT acts than records), B15 (normative word of a nominalized label), B16 (`ground_candidates` membership).
- **A4 (R03)** notes that `mechanism_refusal = false` in every reading.

## 5. New documents

- **`SCHEMA-r3-CODER.md`.** The same rules as `SCHEMA-r3.md`, in the order the TASK sets: unit, fields, definitions, ACT procedure, NORM procedure, worked examples, output JSON. It carries no revision tags, rationale, history, theory-choice references or references to earlier coding. Examples taken from source records in SCHEMA-r3 are replaced by invented ones; the worked examples use invented spans only. The JSON layout is a presentation of SCHEMA-r3 §2–§3 (each coded field with its status and premise), not a new rule.
- **`EQUIVALENCE.md`.** It maps every rule of `SCHEMA-r3.md` to its place in the coder text, and records every rewording that is not literal, with the reason it is equivalent.
