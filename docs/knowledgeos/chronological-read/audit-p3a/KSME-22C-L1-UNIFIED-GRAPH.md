---
task: KSME-22C Steps 5-6 complete -- resolve prose lineage_claims, structure narrative chains, merge into
  the unified L1 graph
scope: corpus-wide chronological-read pipeline; supersedes KSME-22C-L1-FILE-GRAPH-REAL-STRUCTURE.md's
  P3a-only numbers with the full merge
derived_from: [KSME-22C-L1-FILE-GRAPH-REAL-STRUCTURE.md, lineage_claims resolution (this pass),
  09-ORCHESTRATOR-FLAGS.md both halves structured extraction (2 forks), direct computation]
---

# KSME-22C — L1 Unified Graph (P3a citations + resolved lineage_claims + narrative chains)

## Step 5: prose lineage_claims resolution

Extracted a candidate short-ID token (using a broadened set of known corpus ID patterns: `U-MM-\d+`,
`PM-\d+`, `R-\d+`, `I-\d+`, `G-\d+`, `OQ-...`, `EKS-\d+`, `MD-\d+`, `FR-\d+`, `GN-\d+`, `S\d{4}`,
`KR-...`, `Step \d+`, etc.) from each of the 2,138 lineage_claims' `target` text, then searched the whole
corpus for which other file(s) actually carry that exact token in `dependencies`/`labels`/`anchor`/
`statement`.

| Status | Count | % |
|---|--:|--:|
| NO_MATCH_NO_TOKEN (long prose, no extractable ID — genuinely out of scope for a mechanical pass) | 1,164 | 54.4% |
| RESOLVED_AMBIGUOUS (token found in >1 other file — not usable without judgment) | 624 | 29.2% |
| UNRESOLVED_TOKEN_NOT_FOUND_ELSEWHERE | 224 | 10.5% |
| **RESOLVED_EXACT (token found in exactly 1 other file — usable as an edge)** | **126** | **5.9%** |

126 RESOLVED_EXACT claims yielded edges between 124 distinct files. **Only these were added to the graph**
— RESOLVED_AMBIGUOUS was deliberately excluded (per the Missing-Source discipline: do not force an
ambiguous match into a specific edge).

## Step 6: narrative chain structuring (both ORCHESTRATOR-FLAGS halves)

Two forks gave B0001-B0042 and B0043-B0070 the same rigorous, source_id-level extraction. Combined result:
**16 distinct named multi-S-number chains/pairs** with explicit `Sxxxx` citations (e.g. the 5-step "Zero
Lens" self-correction chain S0481→483→484→485→486; the 4-layer equality-relation chain
S2063→S2070/S2072→S2080/S2081/S2083; multiple exact-duplicate pairs found by the cross-batch near-dup scan
that no single batch could see: S2760/S2830, S0639/S0640/S0700, S0149/S0151), plus 2 large range-episode
claims (Constitution v0.1 batch S0981-S1020; Systematic Synthesis S1069-S1107, connected only at their
endpoints per the log's own disclosure that intermediate members weren't individually itemized — tagged
`NARRATIVE_RANGE_EPISODE_WEAK`, lower confidence than the itemized chains). All 18 source_ids across every
chain resolved cleanly to files — zero unresolved. Produced 56 strong + 2 weak = 58 edges.

**Also disclosed, not incorporated this pass**: 21 additional named chains/threads (first half) plus
several more (second half) reference only step numbers, document IDs, or governance-note numbers (GN-xx),
not literal `Sxxxx` citations — resolving these requires mapping step/GN numbers to specific source_ids,
a real but separate task not done here.

## Final unified L1 graph

| Metric | P3a only (prior report) | + lineage_claims | **+ narrative chains (final)** |
|---|--:|--:|--:|
| Edges | 1,133 | 1,259 | **1,317** |
| Files with ≥1 edge | 1,003 (36.1%) | 1,074 (38.6%) | **1,124 (40.4%)** |
| Isolated files | 1,776 (63.9%) | 1,705 (61.4%) | **1,655 (59.6%)** |
| Components (size ≥2) | 186 | 199 | **200** |
| Largest component | 406 (14.6%) | 482 (17.3%) | **516 (18.6%)** |
| Components size ≥3 | 62 | 69 | **70** |

## Honest assessment

Incorporating both remaining major L1 evidence sources moved coverage from 36.1% to 40.4% — a real,
non-trivial improvement (+4.3 points, +121 files, +110 files in the giant component alone), but a modest
one, not a transformation. **This is itself informative**: it means the corpus's fragmentation at the
rigorous file-to-file level is not primarily an artifact of incomplete L1 assembly — most of the easily
recoverable explicit evidence has now been folded in, and 59.6% of the corpus remains without a specific,
citable file-to-file connection. The giant component (516 files, 18.6%) is real and substantial; everything
else is a long tail of small components (median size 2) and a genuinely large isolated set.

**L1 closure gate assessment, per KSME-22C's own stated order**: L1 is now reasonably closed for the
purposes of authorizing targeted L2 — the two named remaining loose threads (624 RESOLVED_AMBIGUOUS
lineage_claims requiring judgment, and ~30+ named chains referencing only step/GN numbers requiring a
separate resolution pass) are real but are correctly classified as bounded residual work, not blockers,
since incorporating them would very likely add only a similarly modest increment (by the same-order-of-
magnitude pattern just observed), not change the fundamental picture.

**Recommendation**: the 1,655 isolated files are now the honest, L1-informed L2 candidate set (not the
2,222/557 figures from the earlier flawed touch-metric). Per the earlier agreement, KSME-22D's progressive
whole-file-reading algorithm is the right method for this phase — applied here, to this measured set, not
to the whole corpus and not before this L1 closure step.

## Not yet done

Resolving the 624 RESOLVED_AMBIGUOUS lineage claims (requires per-claim judgment, not mechanical); mapping
the ~30+ step/GN-number-only named chains to source_ids; the targeted L2 pilot itself; path/branch/merge
derivation from the graph structure; dual-graph (`G_path` vs `G_reconciliation`) comparison.
