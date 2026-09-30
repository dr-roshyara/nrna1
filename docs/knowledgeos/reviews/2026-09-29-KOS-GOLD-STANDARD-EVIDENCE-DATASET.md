# Gold-Standard Evidence Dataset — Blind Double Review of Real Governance Cases

**Date:** 2026-09-29. Machine-readable dataset: `2026-09-29-KOS-GOLD-STANDARD.jsonl` (5 records, full
provenance per record). Corpus is evidence, not authority. Fuse and the deterministic ablation
models (`M1`–`M4`) are hypotheses under test here, not ground truth. This document does not resolve
`OQ-11`, does not evaluate `K5=(S,A,R)`, and does not start ML training.

## 1 · Research question

Can a small, blind, independently-adjudicated gold-standard set of real repository cases be built,
and does it validate, falsify, or refine the deterministic evidence models (`M1`–`M4`) already
developed this session? This directly addresses the largest disclosed limitation of prior work
(`EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md` §10): exactly one independently-verified label
existed before this experiment.

## 2 · Candidate population

Reused, not re-gathered: 379 `KOS`-citing commits (per-commit `M1`–`M4` labels recomputed this pass
from `ablation2.py`'s existing, tested classification logic — see §4 for a labeling correction found
while doing so), the 6-commit Cohesion population, and the 22 work-item ledgers.

## 3 · Sampling strategy — stratified, not arbitrary

Cases were **not** chosen chronologically or randomly. They were selected to cover materially
different evidence/disagreement patterns found in the real `M1`–`M4` distribution over the
379-commit population:

| Case | Commit | Selected stratum |
|---|---|---|
| GOLD-001 | `9f83a369c` | The one previously-known, independently-flagged real anomaly — outside the 379-population entirely (cites no `KOS-`-shaped token), the known `Fuse`/citation-gate blind spot |
| GOLD-002 | `6b983bc12` | The governance-verification document that itself discovered GOLD-001's anomaly (`M4 = TEMPORAL_MISMATCH`, cites a grant but fails the date-window check) |
| GOLD-003 | `0727c9342` | A commit whose message contains no `KOS-`-shaped work-item token (`M4 = UNCITED`), but whose surrounding documentation trail claims explicit human authorization |
| GOLD-004 | `2bdd68b2b` | `M4 = UNDERDETERMINED` stratum (work item has transitions but no temporal date range to check against) |
| GOLD-005 | `1bc550019` | `CITED_NO_GRANT` stratum — cites the work item, not a specific grant ID, yet reaches `M4 = CONFIRMED` |

## 4 · Disagreement strata — and a labeling correction found while building them

Re-deriving `M1`–`M4` per-commit for this sampling step surfaced a real labeling error in the
just-published `EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md` §7: its S4 table calls the 35-case
bucket `UNDERDETERMINED` and the 6-case bucket `CONTRADICTED`. **The actual computed labels are
`TEMPORAL_MISMATCH` (35) and `UNDERDETERMINED` (6); zero commits in the full 379-population reach
`CONTRADICTED` under this classifier** — consistent with, and a further confirmation of, the
already-disclosed `Fuse` blind spot (the one real anomaly, GOLD-001, isn't reachable by the
citation-gated model at all, so it can never produce a `CONTRADICTED` verdict there either).
Recorded here as an explicit correction, not silently fixed in the prior document — the prior
document's S1/S4 entropy figures themselves (98.1%/72.4%) are unaffected, since they are computed
from the same four raw counts regardless of what the buckets are named.

## 5 · Blind review protocol

For each case, two independently-dispatched, context-free agents (no memory of this session, no
exposure to any prior conclusion, `Fuse` classification, or `M1`–`M4` label) were given only: the
repository path, the commit hash, and a mandate to investigate primary sources themselves (`git
show`, `git log`, the ledgers, related docs) and answer four fixed questions, ending in exactly one
label: `AUTHORIZED` / `UNAUTHORIZED` / `NOT_APPLICABLE` / `UNDERDETERMINED`. Reviewer A started from
the commit; Reviewer B started from the ledger side — a deliberate order variation to reduce
identical-path anchoring. **Disclosed limitation, stated plainly and not worked around: both
reviewers are instances of the same underlying model (Claude Sonnet 5) as this session. This is
not the same as independence between two different human domain experts** — it controls for
*session-context contamination* (neither reviewer could see this session's or the other reviewer's
conclusions), but not for shared model-level priors or blind spots. Framed as a real, disclosed
limitation of this gold-standard set, not as human-equivalent inter-rater independence.

## 6 · Reviewer A results

| Case | Label | Key cited evidence |
|---|---|---|
| GOLD-001 | `UNAUTHORIZED` | `G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD3` standing prohibition, no `AMD4`/2026-09-28 ledger entry found |
| GOLD-002 | `AUTHORIZED` | quoted PO/ARB commission, unchanged 45-transition/40-grant counts |
| GOLD-003 | `AUTHORIZED` | plan → readiness-check → self-declared authorization, matches EP-01 convention |
| GOLD-004 | `AUTHORIZED` | full `G-KOS-TOPO-ARCH` chain, verbatim closure declaration |
| GOLD-005 | `AUTHORIZED` | quoted PO/ARB proviso, verified verbatim against ledger, own output cited as trigger for a later grant |

## 7 · Reviewer B results

| Case | Label | Key cited evidence |
|---|---|---|
| GOLD-001 | `UNDERDETERMINED` | self-consistent across 3 sources, but no independent ledger corroboration |
| GOLD-002 | `AUTHORIZED` | same ledger chain, independently re-verified |
| GOLD-003 | `UNDERDETERMINED` | same facts as Reviewer A, but no ledger transition/grant ID corroborates the claimed authorization |
| GOLD-004 | `AUTHORIZED` | same grant chain, transitions seq 1–4 independently checked |
| GOLD-005 | `AUTHORIZED` | commissioned act, self-restricts to non-actions, corroborated by the following grant-creation commit |

## 8 · Adjudication

**3/5 unanimous** (GOLD-002, -004, -005, all `AUTHORIZED`) — no adjudication needed.

**GOLD-001 (`9f83a369c`)**, disagreement `UNAUTHORIZED` vs. `UNDERDETERMINED`: adjudicated
**`UNAUTHORIZED_BY_RECORD / SUBSTANCE_UNDERDETERMINED`**. A standing, `AUTHORIZED` grant
(`G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD3`) explicitly forbids modifying `expected.json` pending a
formal gate transition; none exists for this act. Per this corpus's own stated rule ("the grant is
the authority; a standing bar is lifted by an amendment *before* the act"), the procedural verdict
is `UNAUTHORIZED`. Whether the underlying human decision was itself legitimate is left
`UNDERDETERMINED` rather than adjudicated — this evidence cannot resolve it. This split independently
mirrors a real, pre-existing governance finding already on record in this same corpus (`6b983bc12`'s
own CV-1 analysis: *"GOVERNANCE — RECORDING GAP, NOT A BREACH OF SUBSTANCE... the record as it
stands cannot show what authorized the change"*), reached before this gold-standard experiment began
— treated as a third, independent corroborating source, not as the basis of this adjudication.

**GOLD-003 (`0727c9342`)**, disagreement `AUTHORIZED` vs. `UNDERDETERMINED`: adjudicated
**`UNDERDETERMINED`**. Per `INV-ATTR-1`/`INV-ATTR-2` (self-declared process/session identity and
authority claims are evidential, never attestable — load-bearing throughout this session's prior
work), the self-declared "human-authorized implementation" statement cannot alone be dispositive,
and no ledger transition corroborates it. **Distinguished explicitly from GOLD-001**: no standing
prohibition is contradicted here — this is an unattested-but-not-contradicting case, a different
kind of evidentiary gap than GOLD-001's unattested-and-contradicting one. Collapsing the two into one
"UNDERDETERMINED" category would erase a real distinction the data supports.

## 9 · Gold labels (final)

| Case | Gold label |
|---|---|
| GOLD-001 (`9f83a369c`) | `UNAUTHORIZED_BY_RECORD / SUBSTANCE_UNDERDETERMINED` |
| GOLD-002 (`6b983bc12`) | `AUTHORIZED` |
| GOLD-003 (`0727c9342`) | `UNDERDETERMINED` |
| GOLD-004 (`2bdd68b2b`) | `AUTHORIZED` |
| GOLD-005 (`1bc550019`) | `AUTHORIZED` |

## 10 · Inter-rater agreement

Raw agreement: 3/5 (60%). **Sample too small for reliable κ — not computed.** With n=5 and a
4-category label space, any chance-corrected agreement estimate would have a confidence interval
wide enough to be uninformative; reporting a point value would manufacture precision the sample
cannot support. What can be honestly reported: exact counts (3 unanimous, 2 disagreements) and a
qualitative pattern — **both disagreements involve `UNDERDETERMINED` as one of the two labels**,
never a clean `AUTHORIZED`-vs-`UNAUTHORIZED` split. This suggests the reviewers' shared model-level
uncertainty threshold, not factual disagreement about the underlying evidence, is doing most of the
work in both disagreement cases (both reviewer pairs cited the *same* underlying facts in GOLD-001
and GOLD-003; they differed only in how much evidentiary weight self-disclosure without ledger
corroboration deserves).

## 11 · Model-vs-gold comparison

| Case | `M4` label | Gold label | Match? |
|---|---|---|---|
| GOLD-001 | *not reachable* (cites no `KOS-`-shaped token; outside the 379-population) | `UNAUTHORIZED_BY_RECORD` | **abstention on a real negative case — the disclosed `Fuse` blind spot, now gold-confirmed, not just asserted** |
| GOLD-002 | `TEMPORAL_MISMATCH` | `AUTHORIZED` | **disagreement** — model flags a temporal concern gold does not support |
| GOLD-003 | `UNCITED` | `UNDERDETERMINED` | roughly consistent in direction (both signal insufficient formal evidence), though for a shallower reason (simple absence of a citation string) than the gold adjudication's `INV-ATTR` reasoning |
| GOLD-004 | `UNDERDETERMINED` | `AUTHORIZED` | **disagreement** — model abstains where gold found a complete, unambiguous grant chain the model's own logic never follows (it doesn't do content search over prose for a named grant chain) |
| GOLD-005 | `CITED_NO_GRANT`→`CONFIRMED` | `AUTHORIZED` | match |

**Do NOT extrapolate a repository-wide accuracy figure from this** — n=5, no denominator large
enough to support one. What is legitimate to say: of 5 gold-labeled cases, the deterministic model
was directionally right in 2 (GOLD-003, GOLD-005), wrong in a specific, characterizable way in 2
(GOLD-002's false-positive-shaped temporal flag; GOLD-004's false-abstention from not following a
grant chain by content), and entirely unable to reach a verdict in 1 (GOLD-001, the known blind
spot). Two *different* real failure modes are now evidenced, not one: **the model can wrongly flag a
fine case (GOLD-002), wrongly abstain on a clear case (GOLD-004), and cannot even reach a real
violation case (GOLD-001) — three distinct, now-gold-confirmed failure shapes**, exactly the kind of
finding this experiment was designed to surface.

## 12 · Statistical limitations

n=5. No confusion matrix, sensitivity, specificity, or predictive-value figures are computed — every
cell would have a denominator of 1–2, and any percentage derived from it would misrepresent precision
that does not exist. Exact counts are reported in §11 instead, per the explicit instruction not to
report percentages without denominators large enough to be meaningful.

## 13 · Information-theoretic analysis

**`INFORMATION-THEORETIC ESTIMATION NOT YET JUSTIFIED.`** This is reported as a valid result, not a
gap. n=5 is insufficient to estimate `H(Y)`, `H(Y|E)`, or `I(E;Y)` for even a 2-category collapse of
the label space, let alone the full 4-category one (GOLD-001's compound label alone shows the space
may need more than 4 categories). No such figure is computed or approximated here. The prior
report's `H(classifier output)` figures (98.1%/72.4%) remain what they always were — entropy of
`Fuse`'s own output distribution, never a proxy for this section's unanswered question.

## 14 · ML readiness

Unchanged conclusion: **ML NOT JUSTIFIED YET.** n=5 gold labels (up from n=1) is real progress but
still far short of what any classifier, however simple, could be trained or even meaningfully
validated against — with 2 of 5 labels being genuinely compound/contested rather than clean
categories, the labeling scheme itself is not yet stable enough to train against. The §15 next-step
recommendation below is about growing this gold set further, not about starting ML.

## 15 · Kernel implications

No kernel candidate is re-tagged. One observation worth flagging, per the explicit instruction to
attend to Uncertainty/indeterminacy: **both real disagreement cases in this tiny gold set
independently produced an `UNDERDETERMINED`-shaped gold label, and one of the two produced a
genuinely compound label that a clean 4-category scheme could not hold without loss.** This is
weak, n=2, `HYPOTHESIS`-only support (not promotable given the sample), but it does not weaken the
existing `Uncertainty/indeterminacy` candidate — it is directionally consistent with it, and is
recorded here for traceability if a future, larger gold set confirms the pattern.

## 16 · Falsification results

- **Falsified**: any implicit assumption that GOLD-001-style cases are cleanly separable into
  `AUTHORIZED`/`UNAUTHORIZED` — the real, careful adjudication needed a compound label. A simple
  4-category scheme was insufficient for 1 of 5 real cases.
- **Confirmed, now gold-backed rather than asserted**: the `Fuse`/`M4` citation-gate blind spot
  (GOLD-001) — previously supported by one independently-verified case found via a different
  mechanism (the file sweep); now additionally confirmed by two independent blind reviewers reaching
  a real verdict on a case the deterministic model cannot reach at all.
- **New, not previously known**: `M4` has at least two further, distinct failure modes beyond the
  citation-gate blind spot — a false-positive-shaped temporal flag (GOLD-002) and a false abstention
  from not following a grant chain by content search (GOLD-004). Neither was previously demonstrated
  with an independent gold label.
- **Confirmed**: the prior session's `EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md` §7 table labeling
  error (§4 above) — a real correction, not a reframing.

## 17 · Next smallest decisive experiment

Grow the gold set specifically toward the two now-demonstrated `M4` failure modes (GOLD-002-shaped
temporal false positives, GOLD-004-shaped grant-chain false abstentions) rather than toward more
`AUTHORIZED`-unanimous cases — the marginal information from another unanimous-agreement case is low
(3 of the first 5 already landed there), while each of the two divergence types has exactly one gold
example so far. A stratified pull of 2–3 more cases from each of the `TEMPORAL_MISMATCH` and
`UNDERDETERMINED` M4-strata, blind-reviewed the same way, would be the fastest path to either
confirming these as systematic model weaknesses (justifying a scoped fix to `M3`'s temporal logic
and to `M2`'s grant-matching, still deterministic, still no ML) or revealing them as one-off
artifacts of this particular small sample.

---

**Traceability:** `2026-09-29-KOS-GOLD-STANDARD.jsonl` (machine-readable, full provenance per
record) · `2026-09-29-KOS-EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md` (§4 labeling correction
applies to its §7 table) · `2026-09-29-KOS-evidence-fusion-model.md` §11 (source of the 379-commit
population and `ablation2.py` logic reused here) · `2026-09-29-KOS-CONTRACT-NEUTRALITY-001-PASS-1-containment-verification.md`
(commit `6b983bc12`, the independent prior CV-1 finding corroborating GOLD-001's adjudication) ·
INV-ATTR-1/INV-ATTR-2 (load-bearing in GOLD-003's adjudication).
