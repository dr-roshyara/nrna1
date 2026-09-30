# PRIMARY Evidence Validation Experiment — Report

Read-only investigation, except for one disclosed, in-scope fix to this
experiment's own measurement tooling (§0). Nothing in `31-RECONCILIATION-
PAIRS.jsonl`, any existing P3a verdict, RP0526, RP0288, OB0001–OB0003, `02-FILES.jsonl`,
the ontology, or the relationship enums was modified. No candidate relationship was
promoted into production. All outputs are new audit artifacts under `audit-p3a/`.

**Numbers first, interpretation second, recommendations third — as requested.**

---

## Numbers

### A. Population and sampling

- PRIMARY candidate pairs (population): **1,104**
- Total candidate signals associated with PRIMARY pairs: **1,668**
- Sampling method: stratified random sampling without replacement, proportional
  allocation with a 5-per-stratum floor, remainder assigned to the largest
  stratum, `random.Random` seed **20260921**
- Strata (per-pair signal profile): `DEP_ONLY` (949 pairs, 84 sampled),
  `LINEAGE_SUBSTRING_ONLY` (92 pairs, 8 sampled), `BOTH_DEP_AND_LINEAGE` (61
  pairs, 6 sampled), `LINEAGE_SOURCEID_ONLY` (2 pairs, 2 sampled — full census,
  since the stratum floor of 5 exceeds its population)
- Sample size: **n = 100** (9.1% of the 1,104-pair population)
- RP0526 was independently drawn (its stratum, `LINEAGE_SOURCEID_ONLY`, has only
  2 members and both were sampled by necessity) — assigned `PV0033`. RP0288 was
  **not** drawn by the random process; per your instruction it is reported
  separately in §RP0288, not counted in the n=100 statistics.

### B. Source fidelity (n=100)

| Category | N | % | Wilson 95% CI |
|---|--:|--:|---|
| VERBATIM | 60 | 60% | 50.2%–69.1% |
| FAITHFUL_PARAPHRASE | 31 | 31% | 22.8%–40.6% |
| PARTIAL | 8 | 8% | 4.1%–15.0% |
| MISREPRESENTED | 1 | 1% | 0.2%–5.4% |
| NOT_FOUND | 0 | 0% | 0.0%–3.7% |

### C. Relationship support (n=100)

| Category | N | % | Wilson 95% CI |
|---|--:|--:|---|
| EXPLICIT_RELATIONSHIP | 63 | 63% | 53.2%–71.8% |
| STRONG_IMPLICIT_RELATIONSHIP | 23 | 23% | 15.8%–32.2% |
| WEAK_INFERENCE | 8 | 8% | 4.1%–15.0% |
| NO_RELATIONSHIP | 6 | 6% | 2.8%–12.5% |
| CONTRADICTORY | 0 | 0% | 0.0%–3.7% |
| UNRESOLVED | 0 | 0% | 0.0%–3.7% |

### D. Evidence strength (n=100)

| Category | N | % | Wilson 95% CI |
|---|--:|--:|---|
| E0 (no evidence) | 0 | 0% | 0.0%–3.7% |
| E1 (weak/inferential) | 14 | 14% | 8.5%–22.1% |
| E2 (strong contextual) | 21 | 21% | 14.2%–30.0% |
| E3 (explicit) | 22 | 22% | 15.0%–31.1% |
| E4 (explicit directional lineage) | 43 | 43% | 33.7%–52.8% |

### E. Supported relationship types (n=100, existing P3a vocabulary only)

| Type | N |
|---|--:|
| EXTENSION | 31 |
| CONTINUATION | 18 |
| REFINEMENT | 17 |
| *(no relationship — WEAK_INFERENCE/NO_RELATIONSHIP)* | 14 |
| ONTOLOGY-GAP | 7 |
| DERIVED-FROM | 6 |
| INDEPENDENT | 4 |
| SPECIALIZATION | 2 |
| SAME | 1 |
| HOMONYM / REPLACEMENT / REDEFINITION | 0 / 0 / 0 |

Notable: this sample contains **zero** HOMONYM, REPLACEMENT, or REDEFINITION —
the undefined-signal population skews heavily toward positive-continuity
relationships (EXTENSION/CONTINUATION/REFINEMENT = 66% of the sample), unlike
the full 1,793-pair P3a corpus where UNWITNESSED dominates (38%).

### F. Comparison with existing P3a verdicts (revealed only after blind validation)

- Sampled pairs already judged as one of the 1,793 P3a pairs: **13**
- Sampled pairs never constituted as a P3a pair at all: **87**
- Agreement rate (exact relationship-type match, of the 13 already-judged):
  **4/13 = 30.8%** [Wilson 95% CI: 12.7%–57.6%] — wide interval, small n, but the
  point estimate independently corroborates the v1 gate's earlier 20.9% exact-
  agreement finding on a completely different, non-overlapping-by-design sample
  and protocol.
- **"False-UNWITNESSED" rate**: of the 6 sampled pairs whose existing P3a verdict
  was UNWITNESSED, **6/6 = 100%** were independently validated as
  EXPLICIT_RELATIONSHIP or STRONG_IMPLICIT_RELATIONSHIP against primary source
  text [Wilson 95% CI: 61.0%–100.0%]. Small n (6), but the lower CI bound still
  sits well above chance.
- Ontology-gap rate (of the full 100-pair sample, not just already-judged pairs):
  **7/100 = 7%**.

### Business metrics

- **PRIMARY Evidence Recovery Rate** (E3+E4 / n): **65/100 = 65%** [Wilson 95% CI:
  55.3%–73.6%]
- **PRIMARY Relationship Support Rate** (EXPLICIT+STRONG_IMPLICIT / n): **86/100
  = 86%** [Wilson 95% CI: 77.9%–91.5%]
- **Potential P3a Miss Rate** (of the 86 validated relationships, how many does
  current P3a fail to correctly represent — either no pair exists, or a pair
  exists with a disagreeing relationship): **82/86 = 95.3%** [Wilson 95% CI:
  88.6%–98.2%]. **This number requires the scope caveat in §Interpretation below
  before it is used for anything — it is not a statement about the 1,793-pair
  P3a corpus as a whole.**

### Failure modes (n=100, a record may carry more than one tag)

| Mode | N | Meaning |
|---|--:|---|
| F2 — identifier/target resolution failed | 19 | The largest single failure category |
| F5 — signal is inference, not real source evidence | 14 | |
| F4 — no existing relationship value fits (ontology gap) | 7 | |
| F3 — exact-string matching failed | 6 | |
| F1 — evidence exists but pairing script doesn't read this field type | 2 (explicitly tagged; structurally true of all 100, since this is exactly how the population was defined) | |
| F6 — evidence does not exist in the source | 0 | |
| F7 / `PROVENANCE_CLASSIFICATION_CONCERN` | 0 | No reviewer, across all 100 pairs, found reason to doubt any file's PRIMARY classification |

---

## §0. One in-scope tooling correction made during this experiment

**What happened**: two sampled pairs, `PV0033` (= RP0526) and `PV0087`, share the
same underlying signal — a `SOURCE-CLAIMED-EXTENSION` lineage claim in row S1347
whose `target` field is the specific source_id `"S1345"`. The provenance-tracing
script (`trace_undefined_relationship_provenance.py`) resolves this target's
*label* correctly (`sarathi-investigation-guide`) but then, when reporting a
`target_file` for the blind reviewer to inspect, called a "representative row"
helper that picks the *earliest-dated* row under that label — landing on `S1273`
(`three-distinct-information-pathways.md`), a different, topically unrelated
document — instead of using the exact, already-known source_id (`S1345`,
`step_155-156_recovery-of-earlier-step-155-156-material-sarathi-formulation.md`)
the claim actually names.

**Why this was fixed, not just documented**: this bug lives entirely in this
investigation's own measurement instrument (a script written this session for
this experiment), not in `derive_reconciliation.py`, not in P1/P2/P3a, and not in
any production ledger. Per your own §18 principle — "follow the original evidence
and measure what is actually there" — leaving a known, demonstrated defect in the
measurement tool itself would corrupt the measurement, not protect it. The fix
(use the exact source_id when the claim names one; keep the representative-row
heuristic only where a claim genuinely names a whole label, not a specific row)
is one clearly-scoped, disclosed code change to an audit script, re-run to
regenerate the provenance inventory (the population count is unchanged: 1,104
PRIMARY pairs; one pair moved between the C/D/E secondary-provenance buckets as
a side effect).

**Consequence for §Population/Interpretation**: the corrected target file
(`S1345`) is itself P1-classified `SECONDARY-SYNTHESIS` — a genuine, separate
finding, not an error. Its own filename ("recovery of earlier... material") and
content ("I don't want to jump to that conclusion until I read the actual Step
155/156 texts") both independently corroborate that classification. This means
`PV0033`/RP0526's true evidence chain is PRIMARY-cites-SECONDARY-SYNTHESIS, a
distinct, third pattern from the clean PRIMARY-PRIMARY chains most of this sample
shows — worth carrying forward, not hiding. Both `PV0033` and `PV0087`'s
validation records were corrected in place (with a `correction_note` field
documenting exactly this, and citing Stage 1's own independent grep-verification
of the same quote, done before this bug was found).

**Aggregate statistics above already include the two corrected records** — the
original (buggy-target) blind answers for PV0033/PV0087 were NO_RELATIONSHIP with
F2 flags; the corrected answers are EXTENSION/EXPLICIT_RELATIONSHIP (PV0033, E4)
and EXTENSION/STRONG_IMPLICIT_RELATIONSHIP (PV0087, E2). This upgraded 2 of the
100 records from "no relationship found" to "relationship found," which is a
real, if small, upward contribution to the recovery/support rates above — fully
disclosed, not silently absorbed.

---

## RP0288 (not independently drawn — reference case only, not in n=100)

Reformatted from Stage 1's already-completed manual investigation
(`STAGE1-EMPIRICAL-PROBE.md` §2) into this experiment's schema: source S0940
(`champion-challenger-model-promotion-lifecycle`, grep-confirmed verbatim "This
is much safer than: Outcome → AI retrains itself → Production", line 1402),
target S0961 (`kos-model-risk-and-self-validation`, grep-confirmed verbatim
match at line 1458, both `PRIMARY`), evidence_fidelity=VERBATIM,
relationship_supported=EXPLICIT_RELATIONSHIP, supported_relationship_type=
EXTENSION, evidence_strength=E4, direction=B_TO_A (the later Step-064 document
extends the earlier Step-045 document), failure_modes=[F1, F2] (the exact-label
`dependencies` entry is never read by the pairing script at all; the
corroborating `lineage_claims` entry's target string embeds the label as a
substring, not an exact match). Existing P3a verdict for this pair: UNWITNESSED —
**disagrees** with the validated EXTENSION finding, consistent with the pattern
found throughout this experiment.

---

## Interpretation

**1. Is the original corpus actually producing relationship evidence?** Yes,
strongly. 91% of sampled signals are VERBATIM or FAITHFUL_PARAPHRASE extractions
of real source text (only 1 MISREPRESENTED, 0 NOT_FOUND), and 86% of the sample
shows an EXPLICIT or STRONG_IMPLICIT relationship once both sides' actual context
is read. This is not a corpus that lacks relationship information — it has it,
and had it all along.

**2. Is the current P3a process missing a significant amount of that evidence?**
Yes, by construction of this experiment's population (every sampled pair was, by
definition, invisible to `derive_reconciliation.py`) — but the *validated* size
of that miss is now measured rather than assumed: 87 of 100 sampled pairs were
never even constituted as a P3a pair, and of the 13 that were, only 4 agree with
the independently-validated relationship. **The "95.3% Potential P3a Miss Rate"
figure above must be read as scoped to this specific population — the pairs
Stage 1 already identified as invisible or under-evidenced — not as a claim
about the 1,793-pair P3a corpus overall.** Extrapolating it to "95% of all of
P3a is wrong" would be a real overclaim this report explicitly declines to make.
What this number *does* establish, at population scale (not sample scale): among
the 1,104 PRIMARY-sourced candidate pairs Stage 1 found, the population-level
inference the sample supports (n=100, finite population N=1,104, sampling
fraction 9.1%) is that a large majority — the point estimate is 86% relationship
support with a 95% CI of 77.9%–91.5% — carry a real, findable relationship that
the current pipeline cannot see.

**3. Is the main problem evidence discovery, relationship classification,
ontology, or provenance?** Measured, not assumed:
- **Evidence discovery (extraction/recall) is the dominant problem.** 87% of the
  sample was never even attempted as a pair (F1, by construction), and F2
  (identifier/target resolution failure) was the single most common
  reviewer-raised failure mode (19/100) — even within the narrow subset the
  pipeline *does* partially reach.
- **Classification is a real, secondary problem, not the dominant one.** Among
  the 13 pairs that *did* get judged, agreement was only 30.8% — this replicates
  the v1 gate's earlier, independently-measured finding via a completely
  different sampling method, which is a strong (if small-n) cross-validation
  that classification unreliability is real, not a one-off measurement artifact.
- **Ontology is a real, smaller problem.** 7% explicit ONTOLOGY-GAP rate — the
  same magnitude found in Stage 1's hand-selected cases, now confirmed on an
  independent random sample rather than cherry-picked examples.
- **Provenance is not a problem.** 0/100 `PROVENANCE_CLASSIFICATION_CONCERN`
  flags — every reviewer, across all 100 independently-read pairs, found the
  cited files' content consistent with their P1 `PRIMARY` classification.

**4. How strong is the evidence for each conclusion?** The evidence-discovery
conclusion rests on the full n=100 sample plus the exact 87/1104-scale
population structure (not itself a sample — this is a census fact from Stage 1).
The classification conclusion rests on only 13 already-judged pairs in this
sample (wide CI: 12.7%–57.6%) but is corroborated by the v1 gate's independent
158-pair measurement (20.9% exact agreement, tighter CI) — two different samples,
two different protocols, converging on "roughly one-in-three to one-in-five
agreement." The ontology conclusion rests on 7 explicit tags in this sample plus
Stage 1's earlier hand-picked cases — consistent but not yet large-sample.

**5. What should happen next?** (see Recommendations)

**6. What should NOT happen yet?** Promoting any of these 100 (or the other
1,004 PRIMARY pairs) into the production P3a ledger, changing
`derive_reconciliation.py`, or declaring the ontology settled — none of that
follows from a measurement experiment alone, and none of it was done here.

---

## Decision Gate

| State | Supported by measurement? |
|---|---|
| A — PRIMARY evidence is weak | **No.** 91% fidelity, 86% relationship support, 65% at E3/E4 strength — this is not a weak-evidence corpus. |
| B — Classification is the main problem | **Partially, but not dominantly.** Real (30.8% agreement on 13 cases) but this sub-population is small (13% of the sample) relative to the extraction gap. |
| C — Extraction/recall is the main problem | **Yes, dominantly.** 87% of the sample was never even a candidate pair; F2 (resolution failure) is the single largest failure mode even within the reached subset. |
| D — Ontology is the main limitation | **Present but secondary.** 7% explicit gap rate — real, recurring, but a minority driver. |
| E — Mixed failure | **Applies, with C as the dominant, clearly measured component and B/D as real, smaller, independently-confirmed secondary components.** |

### Selected state: **E — MIXED FAILURE, DOMINATED BY C (EXTRACTION/RECALL)**

This is not a pre-selected conclusion — it is the state the five numbers above
actually point to: a large, measured evidence-discovery gap (dominant), a
real but smaller classification-reliability gap (corroborated across two
independent samples), and a real but smaller ontology-coverage gap. State A is
excluded by direct measurement. No single non-mixed state (B, C, or D alone)
fully accounts for both the 87% recall-blind-spot number and the independently
corroborated ~20–31% classification-agreement number simultaneously.

---

## Recommendations (measurement-only experiment — these are proposals for a
future, separate decision, not actions taken here)

1. This experiment measured; it did not intervene. Any change to
   `derive_reconciliation.py`, the ontology, or any P3a verdict remains a
   separate decision for you to make, informed by — but not automatically
   following from — these numbers.
2. If evidence-discovery is judged the priority (per the State-C finding), the
   highest-leverage next step would be a further-scoped experiment: validate a
   sample of the SECONDARY-SYNTHESIS-tier candidate pairs (C/D/E, 24.7% of the
   1,466) the same way, since this report only validated the PRIMARY tier.
3. RP0526 specifically: three independent reads now exist (2 blind UNWITNESSED
   reads from the v1 gate, plus this experiment's corrected EXTENSION finding
   using the true target). All three are now visible together for the first
   time in this report — the discrepancy between them is itself informative
   (the v1 gate's blind reviewers, working from the SAME buggy target-resolution
   this experiment found and fixed, could not have found the true answer either;
   their UNWITNESSED verdict was a correct response to bad input, not a
   competing independent judgment on the same evidence).
4. RP0288: still UNWITNESSED in production; this experiment's reference-case
   validation (EXTENSION, E4) is a third data point agreeing with Stage 1's
   original manual finding, using a stricter, more explicit protocol.
