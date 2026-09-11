# MD-105 §03 — Standing-Method Decision, Statistical/Provenance Status, Genuine Corpus Gaps

## Standing-method decision: per-file cross-check adopted going forward

**Decision**: for every future position read in this reconstruction, after direct reading of the raw
source file (never instead of it), check whether a corresponding `01_source-analysis/per-file/NNNN.
yaml` record exists (via `00_control/reading-manifest.tsv`'s sequence-number mapping) and cross-
reference it as a quality-check aid.

**Binding scope constraints** (per the standing source/synthesis firewall, extended explicitly to this
new discipline):
- The per-file YAML record is NEVER treated as source evidence. It is a synthesis/reconstruction
  artifact from a differently-authorized earlier phase (MD-021's Model-C1 track), exactly the same
  category as this reconstruction's own MD-* documents — navigation/cross-check only.
- Any concept, relationship, or claim surfaced by a per-file record MUST be verified directly against
  the raw `.md` source before being logged as evidence in this reconstruction's own registers — exactly
  the process followed for every item in this MD-105 entry.
- The cross-check is a SAFETY NET against missed derivations, not a shortcut. It does not replace
  reading the raw file directly, and it does not license skipping any file's own direct reading.
- Where the per-file record and this reconstruction's own direct reading disagree, the raw source is
  authoritative; the disagreement itself is recorded as a finding (as done for the duplication-series
  correction above).
- This does NOT apply to lanes never covered by the MD-021 per-file pass (e.g. `kernel/`, the
  Session-1/Session-2 review cluster) — those have no corresponding YAML records to check.

## Statistical/provenance status

- Cross-checked 35 per-file YAML records (seq 0347–0381) against five MD entries (MD-100–104).
- Of 35, 8 items were independently verified against raw source and found to be genuine, material
  additions or corrections; the remainder were consistent with (not contradicting) what MD-100–104
  already logged.
- No claim in this MD-105 entry rests on a per-file YAML record alone — every item above cites a
  direct raw-source verification performed in this phase.
- The duplication-series correction is a bookkeeping/labeling correction, not a content correction —
  every underlying document's substantive analysis in MD-100–104 stands unchanged.

## Genuine corpus gaps (named, not filled)

- "Eight revised Shani invariants" (seq 0348, §32) — located, not yet read in full; a candidate target
  for a future dedicated investigation if the Shani-invariant family becomes relevant.
- Whether `Claim`/`Hypothesis` (seq 0368) are ever given independent formal signatures later in this
  same root population remains unknown.
- Whether the seL4-derived six-item Kernel-candidate cluster (seq 0351) or the "Refusal" file's
  eight-value decision vocabulary (seq 0353) are ever picked up again later in the corpus remains
  unknown.
- Whether any of the six bridge_candidate entries (seq 0370–0372) are ever elevated beyond CANDIDATE
  status by later corpus text remains unknown.
- The per-file cross-check has been applied only to seq 0347–0381 (MD-100–104's own scope) so far;
  applying it retroactively to `kernel/`'s own MD-093–099 scope was NOT performed this phase (no
  corresponding per-file YAML records exist for `kernel/`, per the standing-method decision above, so
  this is not a gap requiring action — noted for completeness only).
- 533 of 566 root-level `phase_measure_theory/` files remain unread by content (unchanged from
  MD-104 — this phase performed no new chronological reading).
