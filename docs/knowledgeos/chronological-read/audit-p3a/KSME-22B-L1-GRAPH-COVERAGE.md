---
task: KSME-22B Phase 0 -- L1 (explicit-evidence) file-graph coverage measurement, per the hybrid graph-first design
scope: corpus-wide chronological-read pipeline; measures existing evidence before authorizing any new whole-file (L2) reading
derived_from: [03-CONTRIBUTIONS.jsonl, 02-FILES.jsonl, 31-RECONCILIATION-PAIRS.jsonl, 09-ORCHESTRATOR-FLAGS.md
  (both halves, second half read this pass via fork), direct computation]
---

# KSME-22B — L1 Graph Coverage (measured before any new whole-file reading is authorized)

## Headline numbers

| Metric | Count | % of 2,779 total files |
|---|--:|--:|
| Files with ≥1 real-labeled contribution (connected to a named object at all) | 2,682 | 96.5% |
| Files touched by ≥1 already-judged P3a pair (any relationship) | 2,423 | 87.2% |
| **Files touched by ≥1 P3a CHAIN-TYPE pair** (EXTENSION/CONTINUATION/REFINEMENT/DERIVED-FROM/SPECIALIZATION) | **2,222** | **80.0%** |

Of the 1,793 already-judged P3a pairs, **963 (53.7%) are chain-type relationships**, not competing/independent
verdicts. Combined with the file-level number above: **80% of the entire corpus's files already sit inside
at least one machine-verdicted chain-type relationship** — this is the concrete answer to "how much of the
file-sequence graph already exists without new reading."

## Narrative (pre-graph) evidence, qualitative but substantial

`09-ORCHESTRATOR-FLAGS.md`'s second half (B0043-B0070, read in full this pass) documents **55-65 distinct
named multi-file/multi-batch chains, continuations, corrections, branches, and closures** discovered
organically during the P1 whole-file read — not inferred from term overlap, but stated by the extraction
agent as an explicit finding about specific named files/steps. This includes several of the corpus's most
consequential episodes: a proven v1.0/v1.1 impossibility theorem (B0057), a double-independent-audit finding
of **zero governance acts** across an entire ratification cascade (B0062), a formally FROZEN negative result
(FR-001, B0059), a full Theory v2.0 restart explicitly declaring the entire v1.x lineage unsalvageable
(B0066-B0067), and the corpus's own terminal self-audit (B0070).

**The single most important finding for this reconstruction's own methodology**: B0070's terminal
self-audit states, as its own final verdict after the full 70-batch extraction, that apparent gaps/
contradictions in the corpus are almost always one of exactly three things — **(a) already resolved
elsewhere by a non-citing parallel lane** (the dominant pattern since B0041), **(b) a declared scope
exclusion**, or **(c) "OPEN BY COMMISSION"** (deliberately left open). This is the corpus's own independent
confirmation of the exact reframing this redirection has been arguing for: many apparent "competing
definitions" are not competing theories but unassembled pieces of one trajectory, or genuinely resolved
material nobody cross-referenced.

## Caveats that qualify the 80%/87.2% numbers — carried forward, not glossed over

- **~15-20% of truncated P3a pairs hid real evidence on full-row re-read** (per the extraction agents' own
  disclosure across multiple batches) — some fraction of the "chain-type" edges counted above may need
  re-verification once full evidence is visible, the same class of concern KSME-20/21 already diagnosed for
  the 414-pair affected set (that set is a SUBSET of general reliability concern, not the only one).
- **Lineage claims phrased as filenames/Step-references were systematically invisible to mechanical
  string-matching** in ≥8 batches, found only by full-row manual reading. This means the earlier "only 6.4%
  of lineage_claims have a clean short-ID target" finding likely **undercounts** real resolvable evidence —
  a real, human/agent-mediated re-scan of the prose-target claims (not a regex pass) would recover more.
- A fabricated citation (S2111, nonexistent) was caught once in ~127 batches total — the corpus is not
  fabrication-free, but this is a rare, already-caught instance, not a systemic pattern.
- False-cognate short codes (D-1, F1-F10, K_t, etc.) repeatedly caused HOMONYM misverdicts, catchable only
  by full-row reading — a specific, nameable failure mode for any future L1-resolution pass to watch for.

## What this changes about the remaining plan

Given 80% file-level chain coverage already exists mechanically, and given the qualitative density of
already-documented named chains covering most of the corpus's major episodes, **the case for a broad L2
whole-file reading pass is now much weaker than the original KSME-22/22A proposal assumed.** The revised,
narrower target for any L2 work is:

1. **The 557 files (20%) touched by zero P3a chain-type pair** — genuinely uncovered by existing evidence,
   the correct place for a first bounded L2 pilot (not an arbitrary "earliest coherent block").
2. **The subset of the 80% whose supporting P3a pair falls in the already-known-unreliable 414-pair set**
   (KSME-21) or was flagged truncated — needs re-verification, not fresh reading, once the evidence-bundle
   fix (v2) is adopted.
3. **The prose-target lineage_claims (93.6% of 2,138) and the ORCHESTRATOR-FLAGS-named chains** — these need
   a resolution/structuring pass (turning prose findings into `path_id`/file-node/edge records per the
   agreed schema), which is real but bounded work, distinct from either "free" assembly or blind rereading.

**No blind whole-corpus whole-file reading pass is warranted by this evidence.** The corpus's own P1 read
already covered every file once; what's missing is *structuring* that evidence (P3a edges + narrative
chains + resolved lineage_claims) into the formal file-sequence-graph schema, plus targeted new reading only
for the 20% genuinely uncovered — not a second blind pass over the other 80%.

## Not yet done (named, not executed)

- Structuring the 55-65+ named ORCHESTRATOR-FLAGS chains into actual `path_id`/file-node/typed-edge records.
- Resolving the prose-target lineage_claims via a real matching pass (not regex) against the object/family
  index.
- The targeted L2 pilot on the 557 chain-uncovered files.
- Cross-validating `G_path` (once assembled) against `G_reconciliation` (P3a) per the dual-graph comparison
  the hybrid design calls for.
