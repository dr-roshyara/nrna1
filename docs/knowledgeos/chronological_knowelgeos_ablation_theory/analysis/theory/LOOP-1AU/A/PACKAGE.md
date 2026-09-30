# Decision package: should the observation schema separate norm statements from act observations (r2)?

| | |
|---|---|
| Status | Draft for a human decision. **Nothing is adopted and nothing is recoded.** |
| Inputs | `SCHEMA-v1.md`, `EVENTS.json`, `START-1AQ-RECONCILIATION.md`, `AUDIT-1AR-RECONCILIATION.md`, `AUDIT-1AR-WORKER.json` (A), `AUDIT-1AR-REVIEW.json` (reviewer). `WITNESSES.md` is not in this directory, so recorded witness pairs are taken from the reconciliation and the reviewer's list. |
| Labels | **SF** = SOURCE FACT · **INF** = INFERENCE · **PROP** = PROPOSAL · **UNK** = UNKNOWN |

## 1. The current schema (SF, quoted from SCHEMA-v1)
- §1: "**One semantic observation = one historical act (never one textual mention)**".
- "`outcome` ∈ {PERFORMED, REFUSED, NOT-IN-FORCE}; `ground` ∈ {RULE, CHOICE, n/a}."
- "Realization fields (never used as theory variables): … `speech_act` ∈ {performed, reported, self-restraint, claim-revision}".
- "A field that the source does not establish is `UNK`. Nothing is filled in silently."
- R3: "A pair of events with opposite RULE-grounded / PERFORMED outcomes that differs in exactly one applicable field x" is a STRICT or POSSIBLE witness. R5: "≥ 2 independent strict witnesses: EMPIRICALLY SUPPORTED".

## 2. The conflation
**INF.** v1 requires every record to be an act, but it has no field that records *whether the act is attested*. It also has no outcome value for "permitted or forbidden, but never attempted". A ruling's statement that X may or may not happen was therefore coded as X PERFORMED or X REFUSED. This happened in both directions:
1. **SF** `START 7C at R-58`: coded `outcome: REFUSED, ground: RULE`. The source says only "Slice 7C remains unauthorized" (A).
2. **SF** `START 7C at R-65`: coded `outcome: PERFORMED`. The source says "Slice 7C AUTHORIZED; engineering may begin RED", and "No record that 7C actually started is provided" (A).
3. **SF** `ASSIGN the retired number R-90`: coded `REFUSED`, `h: retired`. The source says "The number R-90 is RETIRED, not recycled", and "No attempt to assign R-90 again is recorded" (A).

For contrast, **SF** `ADOPT R-91 by the DA (held)` has the same v1 coding (REFUSED/RULE/register-row/performed). It is a real refused act: "HELD 2026-08-04 — NOT ADOPTED".

## 3. The affected records (36)
**SF.** A and the reviewer (Rv) agree on 35 of the 36 classes. Their one class disagreement is R-89: A codes it ACT-REFUSED, Rv codes it ACT-PERFORMED (D2). Both treat it as an act.

**Tiers (INF, derived from the audit files):**
- **T1 certain:** same class from both coders, with no recorded ambiguity.
- **T2 agreed but flagged:** same class, but A flagged the class as ambiguous, an earlier audit disputed it, or the item mapping is UNKNOWN.
- **T3 class disputed.**
- **T4 UNCLEAR:** both coders.

| Operation | n | ACT | NORM (permission statement) | GENERIC | UNCLEAR | Non-T1 items |
|---|---|---|---|---|---|---|
| ADOPT | 3 | 3 | – | – | – | T2: Chief's declined adoption (was "agreed after close check") |
| AUTHORIZE-IMPL | 2 | 2 | – | – | – | T3: R-89 (performed vs refused; v1 codes NOT-IN-FORCE) |
| REGISTER | 2 | 1 | – | 1 | – | – |
| OPEN-WORK | 2 | 1 | 1 | – | – | T2: by recording note (A: "This is ambiguous") |
| START | 11 | 1 (git) | 10 | – | – | T2: R-72 (1aq D1). Other notes: git s-value (D8); R-81 target from later rows (1aq D6) |
| RAISE | 9 | 8 | – | – | 1 (L493-B) | T2: 7 R-36 items, "item-to-category mapping is UNKNOWN" |
| ASSIGN-ID | 2 | – | 1 | – | 1 (never-used) | – |
| SUPERSEDE | 4 | 2 | 1 | – | 1 (D-12) | T2: §12 R-83 (A: "ambiguous") |
| ANNOTATE | 1 | 1 | – | – | – | – |
| **Total** | **36** | **19** | **13** | **1** | **3** | T1 21 · T2 11 · T3 1 · T4 3 |

By tier, the 19 ACT records are 10 T1, 8 T2 and 1 T3. The 13 NORM records are 10 T1 and 3 T2.

## 4. Proposed r2 schema and a challenge to it (PROP)
**Minimal r2.**
- **Add a field.** Each record gets `observation_type` ∈ {ACT_OBSERVATION, NORM_STATEMENT, GENERIC_PRACTICE, UNKNOWN}. The field carries an epistemic basis (SOURCE/DERIVED/UNK) and a quote.
- **Restrict R3.** Only ACT_OBSERVATION records enter R3.
- **NOT_APPLICABLE rule.** For NORM_STATEMENT and GENERIC_PRACTICE records, `outcome` and `ground` are `NOT_APPLICABLE`. That means the question does not arise for this type. It is distinct from R1's per-operation `n/a` and from `UNK` (the field applies but the source does not establish it). NOT_APPLICABLE is never written to mean "not stated".
- **Optional additions.** A `deontic` field ∈ {PERMITTED, FORBIDDEN, UNK} for NORM_STATEMENT records, and a `stated_by: <event_id>` link to the norm-making act that produced the statement.

**Challenge.**
- **(a) Is it too large?** The information needed is one label per record. The `deontic` field and the `stated_by` link are not needed for the act-only count, so they could be deferred.
- **(b) Is a field needed at all?** v1 §1 already forbids non-acts. A stricter coding guide alone would enforce "one historical act". But norm statements would then be *dropped*, not kept, and compliance would stay uncheckable.
- **(c) Would an outcome extension do?** An alternative is to add PERMITTED/FORBIDDEN to `outcome`. It is smaller, but it overloads one field with two meanings (the result of an act, and the content of a norm). It also cannot express GENERIC_PRACTICE or UNKNOWN.
- **Assessment (INF).** Option (a) is the smallest version that is still sufficient: the field plus the NOT_APPLICABLE rule, with `deontic` and `stated_by` deferred.

## 5. Migration rule (PROP)
- **T1 (21 records): assigned automatically** from the agreed audit class. For the 10 NORM records, the v1 `outcome` value is kept as `v1_outcome` and replaced by NOT_APPLICABLE. If `deontic` is adopted, it is DERIVED from that value: REFUSED→FORBIDDEN, PERFORMED→PERMITTED.
- **T2 (11 records): blind re-coding** of the type only. A fresh coder receives the source excerpt, but neither the v1 coding nor the audit class.
- **T3 (R-89): human decision** on the outcome. The type (ACT) is certain. The v1 value stays until the human decides.
- **T4 (3 records): UNKNOWN** until the missing source text is provided.
- **Lineage:** no v1 field is overwritten. Each record keeps `v1_record`, `r2_type_source` (audit-1ar-agreed / blind / human) and the quote. v1 verdicts are archived, not deleted.
- **No new records are created by migration. UNK:** should the ruling acts behind the START statements (R-47, R-56, R-58, R-65, R-72, R-79, R-81, R-86) become AUTHORIZE records? Creating them would be recoding, so it needs a separate authorization.

## 6. Ambiguity rule (PROP)
`observation_type` = UNKNOWN when any of the following holds:
- no source text is anchored (ASSIGN never-used; SUPERSEDE D-12);
- the anchored excerpt does not contain the item (L493-B);
- the text reads equally as an attempted act or as a statement, and no separate record decides it (for example, R-60's "placed … under a heading");
- blind coders still disagree after reconciliation by quote.

**No default applies.** A missing type is a validation error. It is never silently ACT (v1's implicit default) or NORM. UNKNOWN records are counted and reported, but they enter no witness count.

## 7. Expected impact (the two datasets reported separately)
In this table, "v1" means the recorded witness pairs, and "Act-only" means the reconciled 1ar result (SF). The NORM column is INF: a norm contrast is FORBIDDEN vs PERMITTED differing in one field, and it counts as support for "norms are indexed by x", not for "x guards acts".

| Var | v1 | Act-only (ACT dataset, 19) | NORM dataset (13) |
|---|---|---|---|
| a authority | 2 → SUPPORTED | 2 → **SUPPORTED**. Both pairs express one Chief-vs-DA rule, and the R-89 pair uses NOT-IN-FORCE as the opposite outcome | 0 |
| k kind | 2 → SUPPORTED | 0 → NOT DEMONSTRATED | 1 capability statement (R-60 note), no contrast |
| t target | 0 | 0 | 0 single-field contrasts. R-47's 7A/7B differ in both s and t |
| s state | 2 (A: 5) → SUPPORTED | 0 (the only act has s = UNK) | 2 contrasts (7C R-58/R-65; §12 R-81/R-86). **Circular (D9)**: s and the deontic value are coded from the same text, and R-81's §12 link is disputed |
| e evidence | 1 cluster → WEAK (A: 12 item pairs, D3) | 1 → WEAK, half-attested (D7) | 1 statement (R-83, "no evidence was presented"), no contrast |
| c conformance | 0 strict, 1 coarse | 0 strict, 1 coarse (R-86 vs R-91) | 0 |
| h history | 1 pair → WEAK | 0. D10's occupancy contrast lies outside the frozen 36 | 1 statement (R-90 retired), no contrast |
| x exception | 0 (all UNK / none-stated) | 0 | 0 |
| o operation | 36 records / 9 ops | 19 / 8 ops. START has 1 record and ASSIGN-ID has 0 | 13 / 4 ops (START 10, OPEN-WORK 1, ASSIGN-ID 1, SUPERSEDE 1) |
| r route | no witnesses | no witnesses | **UNK**: should a norm record carry the stating ruling's route (RULING) or the governed act's route (EXECUTION)? |

## 8. Failure modes (INF)
- **Coder-dependent boundary.** 1aq needed a D1 reconciliation for R-72, and A flagged 3 classes as ambiguous. If the type depends on who codes it, r2 moves noise into a new field.
- **Rows that are both.** R-58 is an attested act (authorizing) *and* a norm (START 7B permitted). A single-valued type forces a choice. The rule "one record per act, with the norm as a separate linked record" doubles the records and invites double counting.
- **Over-splitting.** Four types across 36 records leaves most operation-by-type cells at 0–2, and every START verdict becomes "unobserved".
- **Loss of usable data.** The act-only count discards 13 records that are the corpus's main content ("predominantly a deontic record", 1aq). If the NORM dataset has no analysis rules, that data is lost in practice.
- **Recursion.** An ACT record can itself be the result of a norm (R-89 "PREPARED" under R-86's standing rule), so disputes on outcome can remain after the type is settled.

## 9. Falsification criteria (PROP; thresholds to be fixed by the human before any re-coding)
- **r2 is unnecessary if either of these holds:**
  - a blind coder, working from v1 §1 alone plus the existing rule text, codes (almost) no norm statements as acts. The conflation would then be one coder's error, and a coding-guide fix would be enough.
  - some rule over v1 fields reproduces the audit classes on all 36 records. §10 lists counterexamples to every candidate tried so far.
- **r2 is wrong if any of these holds:**
  - blind inter-coder agreement on `observation_type` falls below the preset threshold;
  - "both" rows are frequent enough that one type per record misrepresents them;
  - UNKNOWN takes a large share of records on new sources;
  - new execution records (git) show acts that match permissions record by record. In that case norms would be a valid proxy for acts, and the split would change no verdict. The one attested START so far goes the other way: it falls under "WP-4B REMAINS BLOCKED" (R-77, but the order within the day is UNK).

## 10. Necessity
- **INF: The distinction is necessary, because verdicts depend on it.** k (SUPPORTED → 0), s (SUPPORTED → 0) and h (WEAK → 0) change once norm statements are separated from acts.
- **SF + INF: No v1 field already encodes it.** Each candidate field takes the same value on an act and a norm statement:

| v1 field or rule | Act | Norm statement | Shared value |
|---|---|---|---|
| `genre`, `speech_act` | ADOPT R-91 | START 7C R-58 | register-row / performed |
| `speech_act` | REGISTER R-90 | ASSIGN retired R-90 | session-log / reported, same cluster |
| `outcome` + `ground` | ADOPT R-91 | START 7C R-58 | REFUSED + RULE |
| `o` = START | git 6a67da5d7 | R-58 | START |
| `s` | – | – | predicts the START outcome only by construction (D9) |

- **INF: A filter over v1 fields would give wrong verdicts.** "Drop START" drops the only attested START. It keeps OPEN-WORK-by-note, ASSIGN-retired and SUPERSEDE §12, so k stays SUPPORTED and h stays WEAK. Both are wrong on the audited classes.
- **INF: A v1 filter works only with an extra per-record label.** A filter reaches the same result only if it reads a label from outside v1, namely the audit classes. That label is the r2 field stored elsewhere.
- **Conclusion (INF).** Recording the distinction is necessary. Putting it inside the schema, rather than in an overlay, is a governance choice, not a necessity.

## 11. Options for the human
| Option | What it means | Consequence |
|---|---|---|
| **A. Adopt minimal r2** (§4a) | Schema version bump. 21 records typed automatically, 11 blind re-coded, 1 human decision, 3 UNKNOWN | Reported core: a SUPPORTED (with caveats); e WEAK; c coarse only; k, s, h NOT DEMONSTRATED on acts. The NORM dataset is kept and reported separately. Future coding cannot repeat the conflation silently |
| **B. Smaller: v1 + attestation overlay** | v1 unchanged. A separate file maps event_id → audit class, coder and quote; R3 is run on ACT-labelled records only; §1 is enforced in the coding guide | Same verdicts as A with no schema change. But the label lives outside the frozen instrument, so coders who use SCHEMA-v1 alone will reproduce the error. Norm records keep a misleading `outcome` value |
| **C. Do not adopt** | v1 and its verdicts stand | k and s remain "SUPPORTED" on permission statements, against both reconciled audits. The circularity in s (D9) stays. Every result must carry that caveat, and later datasets will not be comparable with the audited counts |
