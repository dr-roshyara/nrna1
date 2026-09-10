# MD-101 — Resolving the MD-100 Duplication Finding: A Full Exact-Duplicate Map for
`phase_measure_theory/`'s Root Population, plus a Harder Content-Recombination Finding, plus
Continued Sequential Reading (files 9–17)

## 0. Authorization and method

Per the user's explicit instruction this phase: resolve the MD-100 duplication finding *before*
treating further apparently-repeated material as new evidence — identify originals, identify copies,
compare content, distinguish timestamp from semantic origin, record the relationship, and do not
discard a copy merely because it is a copy (the fact of copying may itself be historically
meaningful). This phase does exactly that, mechanically and completely for exact duplicates, and
names — rather than silently absorbing — a harder, only-partially-tractable duplication pattern found
along the way.

## 1. A gap first repaired

Before continuing forward, this phase discovered and repaired a one-file gap in MD-100's own reading
order: `20260825-215434_knowledge-is-probably-not-the-kernel-object-duplicate.md` (chronological
position 6 of the root population) was skipped in MD-100's own sequential read. Verified via
`md5sum`/empty `diff` against `20260825-214833_knowledge-is-probably-not-the-kernel-object.md`
(position 5): **byte-identical**, a pure reproduction, no independent evidentiary weight. Recorded in
the duplicate register below; no content lost.

## 2. The full exact-duplicate map, mechanically produced

Ran `md5sum` across all 566 root-level files and grouped by hash. Result: **33 exact-duplicate
groups (31 pairs + 2 triples = 35 "extra" copies beyond one original each)** — a complete,
mechanically-verified register, not a sample. Full listing in `02_registries.md`. This resolves the
MD-100 duplication finding for **exact** byte-for-byte copies across the entire root population, not
merely the 8-file segment MD-100 itself covered.

**Pattern observed in the filenames**: the overwhelming majority of exact duplicates carry an explicit
`-duplicate`/`-duplicate-2` suffix — i.e., the corpus's own save process is largely (though not
entirely — 3 pairs, noted in `02_registries.md`, carry unrelated-looking filenames on both sides) self-
disclosing about exact reproductions. This is a materially better-behaved corpus, in this specific
regard, than the un-suffixed near-duplicate pattern found next.

## 3. A harder finding: content recombination across files without exact duplication

While verifying the exact-duplicate map, direct content comparison (not filename-based assumption)
revealed that three files read in this phase's own continued sequential pass —
`20260825-221301_next-research-direction-after-measure-theory.md`,
`20260825-222327_doignon-falmagne-knowledge-spaces-source-identified.md`, and
`20260825-222915_session-1-not-executing-continuously-process-observation.md` — do **not** contain
the content their filenames suggest, and are **not** exact duplicates of each other or of anything
already read, but **do** share substantial partial-content overlap with material already read inside
`20260825-220941_the-tuple-is-reasonable-but-not-yet-proven.md` (MD-100's own file 8). Specifically:

- `222327`'s actual content opens with the "adversarial research library" reading-list recommendation
  (eight-book programme, Phases A–F) — **not** anything about "Doignon-Falmagne... source identified"
  as its filename implies.
- `221301`'s actual content opens with "As an independent researcher, I would not go next into more
  formalization..." — the same opening sentence already read, in full, embedded inside `220941`'s own
  concatenated content in MD-100.
- `222915`'s actual content is "Session 1 is not actually executing continuously" (a process-management
  note about a *different* research programme, Session 1/`kernel/` extraction — genuinely new,
  off-topic content unrelated to this file's neighbors) **followed by** a full Pritchard-book
  ("What Is This Thing Called Knowledge?") research extraction — also genuinely new content.

**Conclusion, stated precisely**: this is not simple exact duplication (which the md5 map fully
resolves) and not simple monotonic cumulative growth (which MD-100's own file-3 example showed). It is
**content-block recombination**: passages appear to be composed, saved, and re-saved across files in
an order that does not track either the filenames or a simple linear growth pattern. **This is
recorded as a first-class, only-partially-tractable corpus-hygiene finding for this lane** — full
resolution would require pairwise content-diffing across the whole 566-file population, which this
phase does not attempt in full; going forward, every file's actual opening content is checked directly
(not assumed from its filename) before being logged as evidence.

## 4. Content actually read and logged as evidence this phase (net-new, verified)

Continuing MD-100's own sequential order from its named next file, the following genuinely new content
was read and is logged in `01_objects-and-theorystates.md`: the KST (Doignon–Falmagne) deep-dive
(Knowledge State ≠ Knowledge, the fifteen-plus FACT-KST items, three research hypotheses H-KST-1–3);
Pritchard's *What Is This Thing Called Knowledge?* research extraction (the intersection-method Kernel
hypothesis, the regime reclassification table); the "Fact ≠ Knowledge ≠ Observation ≠ Evidence ≠
Probability" chain; "Knowledge Trajectory" replacing "Knowledge Lifecycle"; the minimality/removal-test
formalization (`Remove(X) ⟹ can the characteristic still be reconstructed?`); and a running synthesis
document consolidating twelve numbered findings.

## 5. Artifacts in this closure

- `01_objects-and-theorystates.md` — Document→Object Impact Map (net-new content only); TheoryState
  updates; new objects.
- `02_registries.md` — the full 33-group exact-duplicate register; Self-Correction Ledger;
  Content-Recombination Register (the harder finding); Cross-Lane Transfer Register.
- `03_status-and-gaps.md` — mathematical/DDD/statistical status; negative boundaries; genuine gaps.
- `04_checkpoint.md` — exact files read; next frontier, with the recombination caveat carried forward.

## 6. Governance note

No frozen artifact (MD-024–100) modified. No object merged. No bridge invented. K-1/K2 untouched.
`theory-extraction/` and `verification/zero-algebra/` never accessed. This phase substantially advances
methodological rigor for this lane (a complete exact-duplicate map, a named recombination problem) even
though the content-reading frontier itself advances only modestly in file count — consistent with the
master mission's own instruction to resolve the duplication finding properly before treating further
apparent recurrence as evidence.
