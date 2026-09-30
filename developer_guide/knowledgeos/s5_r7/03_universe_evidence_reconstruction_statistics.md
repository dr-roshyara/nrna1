# 03: Universe, Evidence, Reconstruction, Statistics

## Universe (`p3b_s5_r7_universe.py`)

**Registry sovereignty (F1/F2).**
- `load_frozen_contract(CR)` parses the `REGISTRY` and `CONSUMERS` tables **from the frozen addendum** and checks its sha256 against `ADDENDUM_SHA256`, so the contract is the single authority.
- `type_of(reg, obj, raw)` returns the one row matching (path, discriminator). A discriminator reads the nearest ancestor holding the key (for example `resolution` for `absences.<dim>.supplied_by.quote`).
- `norm()` writes a `.` inside a key as `·`, so status_basis keys like `births.formal` stay single segments.

**Discovery ≠ validation (PF-3).**

| Function | What it does |
|---|---|
| `discover(obj)` | finds only what is present: S-id-bearing strings and source-key values, including null |
| `validation_violations(obj)` | checks the closed schema Σ (required and non-null sources, element types, closed enums, cardinality, uniqueness). **A missing `source_id` (M1) is caught here, not by discovery** |
| `typing_violations(reg, obj)` | the A-closure: every discovered location typed by exactly one row |
| `meta_violations(reg, obj)` | META rows marked "⊆ verified cited sources" |

**Claims.**
- `claims(reg, obj)` is the verifier's own claim enumeration. Claim ids follow addendum §3.3.
- SUMMARY entries own no claim; they inherit their timeline point.

**Plan and namespace.**
- `plan_derive_v7`: the R6 derivation with R7 run ids.
- `namespace_violations`: every `<batch>-*` directory must be a planned run, the assembly or a frozen legacy directory, with the digest in `legacy_ledger_sha256`.
- `revision_violations`: `contract.revision` must be the integer 7, and `contract.sha256`/`path` must name the frozen addendum.

## Evidence (`p3b_s5_r7_evidence.py`)

- `coverage(reads)` is computed from **witnessed, recomputation-verified** reader reads only.
- `witnessed_intervals(reads, run, sid)` merges only byte-contiguous pages of the same run.
- `anchored(quote, raw, intervals)`:
  - an exact byte match at some offset inside the intervals; otherwise
  - an **N-WS** match (runs of ASCII whitespace collapsed to one space) through an offset map back to bytes;
  - nothing else counts (no case folding, no NFKC).

**The claim path:**

```python
idx = E.facts_index(records_by_run, contents, reads)            # facts anchored on their own run's witnessed pages
E.claim_violations(claims, claim_map, idx, states, cov, reading_runs, label)
```

**Also checked:**
- `quote_violations`: object QUOTE locations must be anchored;
- `reading_rule_violations`: whole-file claims need WHOLE-FILE sources, and a CONTRACT-DEVIATION escalation must name each unread file;
- `census_violations`;
- `readlog_violations`: W6, READ-LOG ↔ witness;
- input reads never enter `reads`, so they never anchor anything (T96).

## Reconstruction (`p3b_s5_r7_reconstruction.py`)

- `precedence(t, s, points, meta)` is ESTABLISHED only when both points have A.10 dated positions and date(t) < date(s), strictly. A dated position requires:
  - EXPLICIT basis;
  - `order_evidence` other than exactly `SOURCE_ID`;
  - no `BULK-` block;
  - `date_applies_to_file: CONFIRMED`.

  Array, list, S-id, UNORDERED-BLOCK or STEP-NUMBER order never counts. The relation is a strict partial order, checked exhaustively in the tests.
- `summary_violations(obj, meta)`:
  - membership;
  - the one-directional constraints (CONTRADICTS ⇒ ∈ contradicted_by with target = predecessor; RETRACTS ⇒ ∈ rejected_by);
  - the FD-1′b lists (RESTATES → later_support; EXTENDS, NARROWS, CHANGES-* → later_refinement);
  - the lateness gate (`later_*` needs ESTABLISHED precedence);
  - the relation's validity;
  - `first_<kind>` = the S-ids of `births.<kind>`.
- `lifecycle_violations`: SUPERSEDED ⇔ a non-empty `superseded_by_sources`; `position` is the A.10 file-level position or `UNDATED`.
- `s3_violations` uses the **canonical** `SID_RANGE`/`SID_BETWEEN` after `_range_norm`, which replaces `RANGE6`. "/" stays an enumeration. Pointers are matched after Unicode and whitespace normalization.
- `s1_violations`: duplicate pair checks FAIL, with no last-wins.

## Statistics (`p3b_s5_r7_stats.py`)

`estimate_v7` is total on its domain:
- **REJECT:**
  - an anchor or frame mismatch;
  - TIER-X or any unknown stratum;
  - no partition;
  - plan-derived membership ≠ frozen membership;
  - a rate outside [0, 1];
  - a frozen n ≠ `round_half_up(rate·N)`;
  - a sample hash ≠ frozen;
  - outcomes ≠ the sampled labels.
- **NOT-ESTIMABLE:** n_h = 0 < N_h, or n_h = 1 < N_h (the variance is undefined; the point estimate is informational).
- **VALID:** otherwise.
- **N_h = 0** is an empty stratum and not a trigger.
- The formulas are unchanged (`ht_stratum`), and their unbiasedness is verified by exact enumeration.

**v2.5-S (G-LOG-0093; DR-19): the exact bound is the primary statement.**
- `exact_upper_bound(d, n, N, alpha)` returns the largest D with P(X ≤ d | N, D, n) ≥ α, computed hypergeometrically in exact rational arithmetic. n = 0 gives N; a census gives d.
- `estimate_v7` sums the per-stratum bounds at α/H′ over the H′ sampled strata (a Bonferroni split) into `upper_bound_total` / `upper_bound_share`.
- `ci95_share` is set only if every sampled stratum has d ≥ 5 and n − d ≥ 5. Otherwise the interval goes to `ci95_informational`. At small discordance the normal CI's real coverage can be as low as 6%.
- `freeze(..., alpha=0.05)` records α; an α that is missing or outside (0, 1) gives REJECT.
- Tests T105–T109 check the definition and the coverage guarantee exhaustively on small (N, n, D).

**Traceability:** addendum §2–§4, §6, §8 · DR-01…DR-04, DR-08, DR-14 · FD-1′a/b/c, FD-2′, FD-3′.

## v2.6-DC3: decided binary files (G-LOG-0099; addendum §6.1, DR-20)

- **Why.** The reader refuses binary content, and a refused call is a W4 stop. The historical G-04 gate demands that every stage-2 hit file be read. Before v2.6, therefore, no label with a required binary file could pass (DC-3).
- **Universe.**
  - `binary_decisions(raw, want_sha)` loads `audit-p3b/20260928_BINARY-DECISIONS.json`, bound by the manifest header `r7_binary_decisions_sha256`.
  - `plan_derive_v7(…, decisions)` adds `binary_decisions` to a label's plan entry, only when the label has a decided file, so every other plan stays byte-identical.
- **Evidence.** `binary_decision_violations(...)` checks each decided file: never read; NOT-CONSUMED record; not cited; every disposition equal to the decision. FALSE-HIT requires method `BINARY-DECIDED` with all dimensions FALSE-HIT. NOT-CONSUMED-ESCALATED requires all dimensions ESCALATED and a CONTRACT-DEVIATION naming the file. `reading_rule_violations` and `census_violations` take the conforming FALSE-HIT set as an exemption.
- **Gate.** `p3b_s5_verify` counts the composer's `binary_decided[label]` as covered in G-04, accepts the method `BINARY-DECIDED` under revision 7, and skips READ-COVERAGE for a conforming BINARY-DECIDED disposition. The decisions file is on the verifier input allowlist, as one exact path.
- **Tests.** `test_p3b_s5_r7_full.DC3DecidedBinary`, T110–T116 plus the W4 case.
