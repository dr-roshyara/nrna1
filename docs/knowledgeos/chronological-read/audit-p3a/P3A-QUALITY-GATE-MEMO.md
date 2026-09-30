# P3a Quality Gate — Decision Memo

Read-only audit. No production ledger file (`31-RECONCILIATION-PAIRS.jsonl`, any
`ledger-p3a/*`, any `ledger-p3b/*`) was modified in the course of this review.
RP0526 is unchanged. No new enum values were introduced. Every number below is
computed directly from files in this repository — none are estimates unless
explicitly labeled as a sample.

## 1. Scope of affected records (exact, from `31-RECONCILIATION-PAIRS.jsonl`)

| Relationship | N |
|---|--:|
| UNWITNESSED | 684 |
| EXTENSION | 450 |
| CONTINUATION | 199 |
| REFINEMENT | 140 |
| DERIVED-FROM | 94 |
| SPECIALIZATION | 80 |
| HOMONYM | 61 |
| SAME | 45 |
| REPLACEMENT | 19 |
| REDEFINITION | 17 |
| INDEPENDENT | 4 |
| **Total** | **1793** |

Records potentially affected by the two audit findings:
- **UNWITNESSED-conflation finding**: up to 684 records (38.2% of the corpus).
- **INDEPENDENT-under-use finding**: mechanically confirmed — **0 of 684** UNWITNESSED
  records carry a `corpus_wide_search_note`; only the 4 INDEPENDENT records do. 100%
  of non-INDEPENDENT verdicts show zero recorded evidence of the corpus-wide search
  that would be needed to promote them to INDEPENDENT.
- **High-stakes tier** (SAME + REPLACEMENT + DERIVED-FROM): **158 records (8.8%)**.

## 2. UNWITNESSED audit (120-pair stratified sample, seed 20260920, of the 649
records not already mechanically resolved)

Mechanical split (exact, all 684):
- 25 have a zero-row side (label E, "content unavailable from this side").
- 10 have non-`NONE` basis (label C, "real evidence exists but the closed 11-value
  relationship enum has no matching value" — e.g. audits/refutations, explicit
  unresolved disputes).
- 649 required judgment; **120 sampled**.

Sample result (n=120):

| Category | N | % |
|---|--:|--:|
| A — genuinely searched, nothing found | 85 | 70.8% |
| B — insufficient search | 20 | 16.7% |
| D — contradictory/unresolved, uncaught by mechanical-C | 2 | 1.7% |
| E — content present but functionally trivial | 1 | 0.8% |
| F — real relationship found, no ontology slot | 12 | 10.0% |

**Finding: UNWITNESSED is mostly (≈71%) a legitimate epistemic outcome, but carries
a material, non-trivial fallback rate (≈17% B) and a distinct taxonomy-coverage gap
(≈10% F, where real positive evidence was found and documented but the 11-value
enum had nothing to hold it, so it was recorded as UNWITNESSED by construction, not
by search failure).** One concrete misclassification was also caught: RP0288's own
cited `group_evidence` states an explicit EXTENSION claim, which the recorded
verdict did not act on. Truncation-handling compliance among the sample's 47
truncated-sample records: only 11/47 (23%) show evidence of the required full-row
lookup; 36/47 (77%) show no such evidence or explicitly admit relying on the sample.

This finding is a 120-of-649 sample, not a census. It is precise about the sample,
not about the full 649.

## 3. INDEPENDENT audit

**How INDEPENDENT is supposed to be established** (protocol §A11, verbatim):
INDEPENDENT requires either a search stated as corpus-wide (not bounded to the
current pair/group) that returns nothing, or an explicit corpus statement of
independence. A pair/batch-bounded null search must default to UNWITNESSED
("NEGATIVE-BOUNDED"), never INDEPENDENT ("NEGATIVE-CENSUS").

- Cases that received INDEPENDENT: **4 of 1793 (0.22%)**, all 4 carrying a genuine
  `corpus_wide_search_note`.
- Cases that were mechanically "eligible" in the sense of having weak/no evidence:
  **684 (all UNWITNESSED records)** — but eligibility for INDEPENDENT requires an
  affirmative search that was never required of agents for UNWITNESSED verdicts,
  only for INDEPENDENT ones.
- Was the corpus-wide search actually required for anything other than the
  INDEPENDENT verdict itself? **No.** Nothing in the dispatched instructions or in
  `verify_reconciliation_pairs_batch.py` requires an agent to attempt that search
  before defaulting to UNWITNESSED.
- **Confirmed methodological limitation**: the process as run permits, and in fact
  universally exhibits, "could not/did not perform the corpus-wide search →
  UNWITNESSED." This is not a hypothesis — it is the observed behavior in 100% of
  the 684 UNWITNESSED records. **This is recorded here as a limitation. No enum,
  protocol, or ledger has been changed as a result.**

## 4. High-stakes independent review (158 pairs: SAME 45 + REPLACEMENT 19 + DERIVED-FROM 94)

Method: 4 fresh agents, each given only the pre-verdict evidence bundle (structurally
incapable of containing a relationship/basis field — verified by construction), none
told the original verdict, none given access to the production ledger or to each
other's batch. One agent further audited RP0526 by name in a fifth, separate
single-pair dispatch after a batch-assignment mismatch; two independent RP0526 reads
now exist (see §6).

| Relationship | N | Independent agreement (exact) | Disagreement | Agreement % |
|---|--:|--:|--:|--:|
| SAME | 45 | 11 | 34 | 24.4% |
| REPLACEMENT | 19 | 4 | 15 | 21.1% |
| DERIVED-FROM | 94 | 18 | 76 | 19.1% |
| **Total** | **158** | **33** | **125** | **20.9%** |

**This number needs a second, coarser cut to be meaningful**, because most
disagreement is about *which* positive relationship applies, not about whether one
exists at all. Grouping {SAME, REFINEMENT, EXTENSION, SPECIALIZATION, DERIVED-FROM,
CONTINUATION, REDEFINITION, REPLACEMENT} as "some positive historical relationship"
against {UNWITNESSED, INDEPENDENT} as "none established" (HOMONYM held out as its
own bucket):

**Coarse agreement (is there a relationship at all): 119/158 = 75.3%.**

Where the 125 exact disagreements landed:

| Independent said instead | N |
|---|--:|
| CONTINUATION | 54 |
| UNWITNESSED | 35 |
| EXTENSION | 15 |
| SPECIALIZATION | 8 |
| REFINEMENT | 5 |
| REDEFINITION | 3 |
| HOMONYM | 2 |
| INDEPENDENT | 2 |
| DERIVED-FROM | 1 |

Confidence of the 125 disagreements: **HIGH 68, MEDIUM 55, LOW 2.** These are not
hedged, uncertain second-guesses — the majority are confidently-held independent
verdicts that differ from the original.

**Per-category directional pattern** (what independent review said, by original
category):
- **SAME (45)**: 11 agree; 21 → CONTINUATION, 4 → EXTENSION, 3 → SPECIALIZATION,
  1 → DERIVED-FROM (40/45 = 88.9% still "some relationship"); only 5/45 (11%) →
  UNWITNESSED. **Reading: SAME is not usually "wrong" in the sense of "unrelated" —
  it is frequently the wrong positive relationship, specifically over-claiming
  identity where CONTINUATION (a picks up/reconstructs b's thread, with a
  verification-status gap) is more defensible. This is exactly RP0526's pattern,
  confirmed here as a real, if not majority, class (~47% of SAME verdicts were
  downgraded to CONTINUATION specifically).**
- **REPLACEMENT (19)**: 4 agree; 5 → CONTINUATION, 3 → REFINEMENT, 3 → REDEFINITION,
  1 → EXTENSION (16/19 = 84.2% still "some relationship"); 3/19 (16%) → UNWITNESSED.
  **Reading: REPLACEMENT (full supersession, license to treat the earlier object as
  dead) is often better described as a milder evolution (REFINEMENT/REDEFINITION) of
  a continuing object.**
- **DERIVED-FROM (94, the largest tier)**: 18 agree; 28 → CONTINUATION, 10 →
  EXTENSION, 5 → SPECIALIZATION, 2 → REFINEMENT (63/94 = 67.0% "some relationship");
  **27 → UNWITNESSED, 2 → INDEPENDENT, 2 → HOMONYM (31/94 = 33.0% "no relationship,
  or a different kind entirely").** **This is the weakest tier by a clear margin —
  a full third of DERIVED-FROM verdicts were independently judged to show no
  relationship at all (or the wrong kind), not merely the wrong positive label.**

## 5. RP0526, specifically

Two independent blind reads, neither told the original verdict, neither told the
other's answer:
- **Read 1** (batch AH0002, MEDIUM confidence): UNWITNESSED / NONE / COMPATIBLE.
  Cites the P2a group's own hedge — "recovered from prior context... noted as
  possibly convergent... but explicitly not confirmed" — plus zero recorded
  `lineage_claims` either direction, plus the fact that only 2 of `b`'s 14 rows even
  touch `a`'s content (the rest belong to an independently-evolving thread).
- **Read 2** (single-pair dispatch, MEDIUM confidence): UNWITNESSED / NONE /
  COMPATIBLE, same reasoning, explicitly naming CONTINUATION as the "real
  contender" a reasonable evaluator could pick instead, but landing on UNWITNESSED
  because the corpus's own grouping note is explicitly unconfirmed and the
  connecting evidence covers only a small fraction of one side's rows.

**Both independent reads converge on UNWITNESSED, not the original SAME, and
neither endorses CONTINUATION (my own preliminary hypothesis, and the audit
subagent's) as the winning answer either — a third possibility neither of us had
weighted correctly.** Both call it a genuinely close SAME/CONTINUATION/UNWITNESSED
boundary case. **Original verdict (SAME) is now confirmed, by two independent
reads, as very likely incorrect. RP0526 remains unchanged in the production ledger
pending your decision — no auto-correction was applied.**

Whether SAME vs. CONTINUATION is "cleanly distinguishable under the current
ontology": **no** — both independent readers and the original evaluator all found
this pair genuinely hard, for the same reason (partial, unconfirmed content overlap
between one narrow slice of a broad, otherwise-unrelated family). This is a real
ontology-boundary softness, not merely an individual lapse.

## 6. Is RP0526 isolated, or representative? (systematic-pattern search, §7 of your
outline)

A full-corpus pass over all 1793 records' evidence/reasoning text for RP0526's exact
pattern (a self-admitted, unverified retrospective reconstruction vs. a fuller,
independently-documented primary thread) found:
- RP0526's own phrasing ("recovered from prior context," "not yet/freshly read")
  is **unique in the corpus** — no other pair contains an artifact that explicitly
  confesses it was never checked against its claimed original.
- 49 broader candidates reviewed; **4 show genuine provenance asymmetry**: RP0338
  (already handled conservatively — DERIVED-FROM, not SAME), RP1470, RP0625, RP0444
  (softer echoes, 2 of the 3 backed by stronger corroborating evidence than RP0526
  had).
- **Conclusion of this narrow pattern-search: RP0526 is close to isolated, not
  representative of a broad class**, when the pattern is defined narrowly as
  "self-admitted unverified reconstruction."

**However, §4's high-stakes independent-review data shows a broader, related
pattern that this narrow keyword search could not see**: independent reviewers
downgraded SAME→CONTINUATION in ~47% of all 45 SAME verdicts corpus-wide, for
varied evidentiary reasons (verification-status asymmetry, partial rather than full
correspondence, audit-of-original relationships), not only RP0526's specific
"self-admitted reconstruction" phrasing. **RP0526 is an isolated instance of one
narrow textual pattern, but a symptom of a much more general and now
independently-measured tendency across the high-stakes tier to prefer identity/
supersession/derivation claims over the more conservative alternative.**

## 7. Mechanical integrity vs. semantic validity — kept explicitly separate

### Mechanical P3a integrity: **PASS, unconditionally**
- 1793/1793 pairs present, 0 duplicates, 0 missing (verified via `close_p3a.py`'s
  own re-run assertions plus this audit's independent re-count).
- 100% closed-list enum compliance (`relationship`/`basis`/`type_compatibility`).
- 100% citation validity for every previously-checked batch; one fabricated
  citation was found and fixed during original production (RP0028/RP0751,
  logged in `09-ORCHESTRATOR-FLAGS.md`) — none found in this audit's own
  independent re-reads.
- All 30 original batches individually schema-verified; batch coverage complete.

### Semantic P3a validity: **NOT PASS — material, measured limitations**
- **Evidence sufficiency**: confirmed weak specifically in the high-stakes tier —
  20.9% exact / 75.3% coarse independent agreement (§4). DERIVED-FROM is the
  weakest sub-tier (33% "no relationship at all," independently).
- **Ontology consistency**: no formal, written ontology document exists separate
  from the protocol's own brief enum definitions; SAME-vs-CONTINUATION and
  DERIVED-FROM-vs-CONTINUATION boundaries are demonstrably soft (§4, §5).
- **Independent agreement**: measured for the first time in this audit — 20.9%
  exact / 75.3% coarse on the 158 highest-stakes pairs. Not measured on the
  remaining 1635 lower-stakes pairs (out of scope for this gate, per your
  instructions, since the concern was specifically the high-stakes tier plus the
  two systemic UNWITNESSED/INDEPENDENT findings).
- **Ambiguity handling**: UNWITNESSED absorbs at least 3 distinct epistemic states
  (§2) without a distinguishing field; ~10% of sampled UNWITNESSED cases are a
  taxonomy-coverage gap, not a search-quality issue.
- **UNWITNESSED validity**: ≈71% legitimate / ≈17% insufficient search / ≈10%
  taxonomy gap / ≈2% unresolved contradiction, on a 120-pair sample.
- **INDEPENDENT search adequacy**: confirmed absent in 100% of non-INDEPENDENT
  verdicts (§3) — not a sample, a census.
- **Systematic error rate**: RP0526's specific textual pattern is nearly isolated
  (§6), but the high-stakes tier as a whole shows a confirmed, directional,
  mostly-high-confidence bias toward over-claiming strong positive relationships
  (SAME, REPLACEMENT, DERIVED-FROM) where a milder one (CONTINUATION, REFINEMENT,
  REDEFINITION) or no relationship (UNWITNESSED) was independently preferred more
  often than the reverse.

**Do not read "P3a is verified" as meaning both of the above are PASS. Mechanical
integrity is proven. Semantic validity, specifically for the 158 high-stakes pairs
and for the UNWITNESSED/INDEPENDENT pair, is measured and found materially
short of a "trustworthy as recorded" bar — not catastrophic, not uniformly wrong,
but confirmed, directional, and non-trivial in size.**

## 8. Does this require stopping P3b entirely?

**No — but it requires more than "continue unconditionally."** Reasoning from the
actual dependency structure, not convenience:

- P3b's own script (`derive_reconciliation_objects.py`) performs a **direct,
  undiluted lookup** of each label's `pairs_touching` P3a verdicts — there is no
  aggregation, smoothing, or independent re-derivation in between. A specific
  mis-verdict in the 158 high-stakes pairs propagates unchanged into exactly the
  labels that pair touches.
- My own P3b roll-up instructions (given to OB0001-0003 before this pause) make
  **CONTESTED** trigger directly on "a pair touching this label resolved to
  REPLACEMENT/REDEFINITION with CORROBORATED basis," and **RECONCILED** partly rest
  on SAME/positive verdicts holding. This means high-stakes-tier errors don't just
  quietly persist — they actively drive specific, visible governance-relevant
  status flags (CONTESTED, RECONCILED) for the labels they touch.
- However, this exposure is **structurally bounded and identifiable, not diffuse**:
  exactly **260 of 2497 labels (10.4%)** touch at least one of the 158 high-stakes
  pairs. The other **2237 labels (89.6%)** have no dependency on the disputed tier
  at all — their P3b roll-up is unaffected by anything found in this audit.
- Per rule R20, the entire pipeline's output is "a recommendation pending
  governance review" — nothing becomes trusted before a human ARB review at the
  final GATE. That downstream safety net exists, but it operates on the whole
  synthesized theory at the end (P7/GATE), not on individual pair verdicts — it is
  real, but too coarse to substitute for catching a confirmed, localized issue now
  when we already know exactly which 260 labels are exposed.

**Recommendation, following the actual dependency graph (not convenience): a
targeted approach — allow P3b to proceed for the 2237 unaffected labels, and hold
(mark PROVISIONAL, do not dispatch) the roll-up for the 260 labels that touch any
of the 158 high-stakes pairs, pending either (a) a re-adjudication of the 158
disputed pairs, or (b) an explicit decision to accept them as-is with the
limitation documented.** This is not a blanket pause and not an unconditional
green light — it is scoped to exactly where the measured risk is.

## 9. Formal gate

| Criterion | Evidence | Result | Blocking? |
|---|---|---|---|
| Pair completeness | §7, `close_p3a.py` + this audit's re-count | PASS (1793/1793, 0 gaps) | No |
| Schema integrity | §7, all 30 batches individually verified | PASS | No |
| Citation integrity | §7, one fabricated citation found+fixed in production, none in audit re-reads | PASS (with 1 historical fix already made) | No |
| Relationship ontology consistency | §4, §5 — SAME/CONTINUATION and DERIVED-FROM/CONTINUATION boundaries demonstrably soft | **FAIL** (no written ontology; boundaries not evaluator-stable) | Partial — affects high-stakes tier only |
| UNWITNESSED semantics | §2 — 71% A / 17% B / 10% F / 2% D / 1% E on a 120-sample | **PARTIAL** (majority legitimate, material fallback + taxonomy-gap rate) | No (documented limitation, not a hard block) |
| INDEPENDENT methodology | §3 — 0% of UNWITNESSED show a search attempt (confirmed, not sampled) | **FAIL** (confirmed structural limitation) | No (affects labeling precision, not raw fact-of-relationship) |
| High-stakes agreement | §4 — 20.9% exact / 75.3% coarse, on 158 pairs (8.8% of corpus) | **FAIL** at exact granularity, **PARTIAL** at coarse granularity | **Yes — for the 260 labels these pairs touch** |
| Systematic-error analysis | §6 — RP0526's specific pattern is nearly isolated; the broader SAME-over-claim tendency is confirmed and general | **PARTIAL** | No new block beyond §"High-stakes agreement" |
| RP0526 assessment | §5 — 2/2 independent reads say UNWITNESSED, not SAME; genuinely close call | **FAIL** (original verdict likely wrong) | No (single pair, held for your decision, not auto-corrected) |
| Reproducibility | §4 — first-ever independent re-derivation attempt in this project, on a sample | **Measured for the first time; low reproducibility found on the high-stakes sample** | Contributes to "High-stakes agreement" block above |

### Gate classification: **PASS WITH EXPLICIT LIMITATIONS**

Not PASS (semantic validity is measurably short of trustworthy in the specific,
identified 158-pair/260-label high-stakes slice, and the INDEPENDENT-methodology
gap is a confirmed structural fact, not a hypothesis). Not FAIL (mechanical
integrity is total and unconditional; 89.6% of labels have zero dependency on the
disputed tier; the corpus-wide UNWITNESSED base rate is majority-legitimate; the
protocol's own downstream P5/P6/GATE review exists as a real, if coarse, backstop).
**This is not silently converted to PASS.**

## 10. Are the limitations local, systematic-but-acceptable, or blocking?

- The **UNWITNESSED-conflation** and **INDEPENDENT-under-use** findings are
  **systematic** (confirmed at or near census level, not a sampling artifact for
  the INDEPENDENT one) but **non-blocking for P3b as a whole**: they affect
  labeling *precision* within already-conservative, mostly-correct verdicts, not
  the presence/absence of relationships at the scale P3b's roll-up cares about.
  Documented, not fixed, per your no-modification instruction.
- The **high-stakes-tier agreement gap** is **material and blocking, but narrowly
  scoped**: it concretely affects 158 pairs and the 260 labels touching them
  (10.4% of the object universe), not the corpus as a whole.
- **RP0526** is **local** (one pair, now well-characterized, decision deferred to
  you).

## 11. What I have NOT done (compliance with your no-modification instructions)

- RP0526: unchanged.
- No UNWITNESSED label changed.
- No new enum value introduced anywhere.
- No relationship-ontology redefinition.
- No production ledger file rewritten. All audit output lives under
  `docs/knowledgeos/chronological-read/audit-p3a/` (a new, separate directory) and
  this memo.
- OB0001–OB0003 (P3b) left exactly as their agents produced them — not verified,
  not marked DONE, not rolled back.
- OB0004 onward: not dispatched.

## Final answer to your central question

The current P3a limitations are **not merely local** (RP0526 alone) and **not
severe enough to require a full corpus-wide halt** — they are **systematic, now
measured, and precisely bounded to a specific, identifiable 158-pair / 260-label
slice** (the high-stakes relationship tier) plus two general, lower-severity,
already-documented labeling-precision gaps (UNWITNESSED conflation, INDEPENDENT
under-use) that apply across the full corpus but do not change the basic
presence/absence-of-relationship conclusion in ~75% of the cases they touch.

**Recommendation:** resume P3b for the 2237 labels with no dependency on the 158
disputed high-stakes pairs; hold the remaining 260 labels' roll-up as PROVISIONAL
pending your decision on whether to re-adjudicate the 158 pairs (a bounded,
independently-scoped follow-on, not a redo of P3a) or accept them with the
limitation carried forward into governance review. RP0526 itself: both independent
reads say UNWITNESSED; your call on whether to correct it now or carry it forward
as a flagged, unresolved item.
