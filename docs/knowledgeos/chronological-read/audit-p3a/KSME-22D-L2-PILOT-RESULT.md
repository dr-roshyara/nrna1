---
task: KSME-22D L2 pilot (15 files, per user commission -- whole-file reading applied to the highest-value
  subset of L1-unconnected files, not the whole 1,655/1,639 population)
scope: corpus-wide chronological-read pipeline; validates the whole-file-reading method before any larger L2 pass
derived_from: [KSME-22C-L1-UNIFIED-GRAPH.md, 2 L2 pilot forks (whole-file reads), direct computation]
---

# KSME-22D — L2 Pilot Result

## Selection

From the 1,655 files the L1 graph left "L1-unconnected under current explicit-evidence extraction" (never
"isolated" in the strong sense, per the user's correction), selected the 233 that carry a `lineage_claims`
entry whose target is an AMBIGUOUS short code pointing at ≥1 file already in the connected graph (a
known corpus problem: short codes like "D-1"/"I-4" get reused across unrelated documents, so the mechanical
pass correctly refused to force these into edges). Piloted 15 of the 233 via two parallel forks, each doing
full whole-file reads (source + every candidate), never keyword matching alone, following the exact 5-step
method specified: read complete file → build understanding → identify candidates → read candidates
completely → record relationship with full evidence.

## Result: 15/15 found a real connection; 0/15 found nothing

| Outcome | Count |
|---|--:|
| Real connection found (to a given candidate, a resolved S-number target, or a file discovered while reading) | **15 / 15** |
| Of these, the cited short-code/S-number genuinely denoted the same referent in both files | 8 / 15 |
| Of these, the cited token was a **false cognate or spurious** — real relationship found only by full reading, via a different signal (shared commission ID, explicit filename citation, procedural continuation, narrative timing) | 6 / 15 |
| Found nothing at all after full reading | **0 / 15** |

**This directly validates the whole-file-reading method**: in 6 of 15 cases, the mechanical clue that
selected the file for this pilot was actually wrong, yet whole-file reading still recovered the true
relationship — confirming the corpus's own previously-documented "false-cognate short-code" failure mode
(D-1 independently means "Engineering Governance" in one lineage and "Dispose E-1"/a Phase-B.5 decision
item in another; D-001 wasn't even literally present in two "source" files where it had been extracted as
a clue) while showing the method is robust to that failure mode, not defeated by it.

**Necessary caveat, not to be glossed over**: this pilot was drawn from the 233 files that already had SOME
ambiguous clue pointing at the connected graph — a biased, favorable sample, not a random draw from the
full 1,655/1,639 population. The 100% hit rate should NOT be extrapolated linearly to the remainder, much
of which has no such clue at all and may include genuinely unrelated material (later research, standalone
notes, or content the corpus's own final self-audit already classified as "OPEN BY COMMISSION" — see
`09-ORCHESTRATOR-FLAGS.md` B0070).

## Notable finding: within-batch closure

Two of the pilot's "false cognate" cases (`KnowledgeOS_Ontology_Architecture_Classification.md` and
`KnowledgeOS_Engineering_Knowledge_Domain_Model.md`) turned out to connect **to each other**, both within
this same 7-file pilot batch — and both also pointed to `KnowledgeOS_Strategic_Architecture_Discovery.md`,
which was independently being read by the *other* parallel fork as a *different* pilot item. All three
edges were confirmed consistently by both forks working independently. Separately, three of the S-number
items (all dated 2026-08-23) revealed a tight same-day narrative chain among themselves
(`working_state.md` ↔ `perplexity-smallest-consistency-boundary-investigation.md` ↔
`adr-kos-kernel-001-kernel-scope-and-boundary-proposal.md`) that the mechanical L1 pass had not captured at
all — a real, whole-file-only discovery.

## Merged graph after the pilot

| Metric | Before pilot (L1 unified) | After 15-file L2 pilot |
|---|--:|--:|
| Files connected | 1,124 (40.4%) | **1,140 (41.0%)** |
| Files L1/L2-unconnected | 1,655 (59.6%) | **1,639 (59.0%)** |
| Largest component | 516 (18.6%) | **528 (19.0%)** |
| Components size ≥3 | 70 | 70 |

15 files read → 16 net new connected files (roughly 1:1, expected since most targets were already in the
connected graph — that was the selection criterion). Giant component absorbed most of the growth (+12).

## What this tells us about scaling L2

At this pilot's yield rate (≈1 new connection per file read, when selecting from the highest-probability
subset), fully processing the remaining ~218 files in the same ambiguous-clue pool would plausibly connect
a comparable additional slice. The much larger remaining population (~1,400+ files with NO existing
ambiguous clue at all) has not been tested and should NOT be assumed to have the same hit rate — a second,
smaller pilot on a random (not clue-selected) sample of that population would be the honest way to estimate
its true yield before committing to reading it at scale.

## Not yet done

Processing the remaining ~218 files in the ambiguous-clue pool; a calibration pilot on a random sample of
the clue-free remainder (~1,400+ files) to estimate real yield there; path/branch/merge derivation from the
graph; dual-graph (`G_path` vs `G_reconciliation`) comparison.
