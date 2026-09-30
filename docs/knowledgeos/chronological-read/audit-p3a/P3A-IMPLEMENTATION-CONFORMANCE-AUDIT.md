# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Master Protocol v3.5 — Implementation-Conformance Audit

Read-only, except one previously-disclosed, in-scope fix to this investigation's
own audit tooling (unrelated to this report). Nothing in `31-RECONCILIATION-
PAIRS.jsonl`, any P3a verdict, RP0526, RP0288, OB0001–OB0003, P4/P5/P6/P7,
historical source files, provenance classifications, or `derive_reconciliation.py`
was modified in producing this report. Authoritative specification used:
`docs/knowledgeos/chronological-read/prompts/20260911_0221_prompt3-optimized.md`
— confirmed, by direct grep, to be the actual, only document in this repository
titled "KNOWLEDGEOS THEORY RECONSTRUCTION — MASTER PROTOCOL v3.5".

## §3 Corpus boundary — verified clean

`docs/knowledgeos/brainstorming/knowledgeos_theory_research/` appears **zero
times** in `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, the roadmap file itself,
`_derived.json`, `31-RECONCILIATION-PAIRS.jsonl`, or anywhere under `audit-p3a/`
(checked by direct grep against every one of these files before any other work in
this audit began). **No corpus-boundary violation found or possible to introduce**
— the reconstruction pipeline has no mechanism that could pull files from outside
the roadmap in the first place (P0's `validate_roadmap.py` reads only
`20260909-185001_files-to-read-one-by-one.log.md`, line by line; nothing else
feeds it).

---

## §5–6. Component inventory and execution graph

| Phase | Component | File | Function/mechanism | Input | Output |
|---|---|---|---|---|---|
| P0 | Roadmap validation | `scripts/validate_roadmap.py` | mechanical: sha256, resolve/repair/firewall, dup detection | roadmap `.md` | `00-CORPUS-SNAPSHOT.txt`, `00-ROADMAP-VALIDATED.jsonl` |
| P0.5 | Batch construction | `scripts/plan_batches.py` | partitions `RESOLVED`/`REPAIRED` ∧ `dup=UNIQUE` records into 40-file batches, source_id order | `00-ROADMAP-VALIDATED.jsonl` | `01-BATCH-MANIFEST.jsonl` |
| P1 | Evidence capture | Agent dispatch (no deterministic script — each batch is an LLM extraction pass per the agent-extraction-contract prompt) | one agent per batch reads every assigned file whole once (R2) | batch file list | per-batch `files.jsonl`/`contributions.jsonl` fragments |
| P1 | Batch verify/merge | `scripts/verify_batch.py`, `scripts/merge_batch.py`, `scripts/set_batch_status.py`, `scripts/print_batch.py` | mechanical schema/citation checks; fold into shared ledger on pass | batch fragment | manifest status update, shared ledger append |
| P1c | Close Phase 1 | `scripts/close_phase1.py` | concat all batches, assert 1:1 file coverage, attach date evidence | all batch fragments | `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl` |
| P1c | Overlap/dup register | `scripts/near_dup_scan.py` | MinHash+LSH over filesystem content | `02-FILES.jsonl` | `08-OVERLAP-REGISTER.jsonl` |
| P1c | Source register | `scripts/build_source_register.py` | join files+overlap+status | `02-FILES.jsonl`, overlap register | `12-SOURCE-REGISTER.md` |
| P2a | Label normalization | `scripts/normalize_labels.py` | 7 signal-type clustering (EXACT-STRING-REUSE, POSSIBLY-RELATION, UNKNOWN-CANDIDATE-GROUP, SHARED-NOTATION, SHARED-ALIAS, STRING-SIMILARITY/Jaccard, CO-OCCURRENCE) | `11-OBJECT-INDEX.jsonl`, `11-UNRESOLVED-CANDIDATES.jsonl` | `_derived.json["groups","nodes"]` |
| P2b | Object families | `scripts/derive_families.py` | per-label rows/candidate_births/lifecycle_candidate/completeness(12 dims)/rationale/assumptions roll-up | `03-CONTRIBUTIONS.jsonl`, P2a nodes | `_derived.json["families"]` |
| P2b | Scope collections, unresolved | `scripts/build_scope_collections.py`, `scripts/build_unresolved_objects.py` | mechanical coverage-closure artifacts | families | `_methodological.md`, `_theory-level.md`, `_UNLABELED-OBJECT-SCOPE.md`, `_UNRESOLVED-OBJECTS.md` |
| P3a | Pair generation | `scripts/derive_reconciliation.py` | **exactly two mechanisms** — see §12 | `_derived.json["families","groups","nodes"]` | `_derived.json["reconciliation_pairs"]` (1,793) |
| P3a | Batch plan/verify/close | `plan_reconciliation_pairs_batches.py`, `verify_reconciliation_pairs_batch.py`, `close_p3a.py` | LPT bin-packing; schema+citation checks; concat+assert | pairs | 30 batches, `31-RECONCILIATION-PAIRS.jsonl` |
| P3b | Object roll-up (spec'd, 3/30 executed) | `derive_reconciliation_objects.py`, `plan/verify_reconciliation_objects_batches.py` | per-label pairs_touching lookup + targeted absence search | `31-RECONCILIATION-PAIRS.jsonl`, families | `ledger-p3b/OB0001-3` only; frozen per your instruction |
| P4–P7 | v1.2 / validation / governance / synthesis | **not implemented** | — | — | — |

**Execution graph** (boxes replaced with actual files):

```
20260909-185001_files-to-read-one-by-one.log.md
   ↓ validate_roadmap.py
00-ROADMAP-VALIDATED.jsonl ──plan_batches.py──▶ 01-BATCH-MANIFEST.jsonl
   ↓ (agent dispatch × 70, verify_batch.py, merge_batch.py)
02-FILES.jsonl / 03-CONTRIBUTIONS.jsonl ──close_phase1.py──▶ (via near_dup_scan.py, build_source_register.py)
   ↓ normalize_labels.py (P2a)
_derived.json[groups,nodes]
   ↓ derive_families.py (P2b)
_derived.json[families]  (+ build_scope_collections.py, build_unresolved_objects.py)
   ↓ derive_reconciliation.py (P3a candidate generation — §12)
_derived.json[reconciliation_pairs]  (1,793 pairs; row_brief() STRIPS dependencies[]/
   lineage_claims[] from every a_rows_sample/b_rows_sample — see §8)
   ↓ 30× agent dispatch + verify_reconciliation_pairs_batch.py + close_p3a.py
31-RECONCILIATION-PAIRS.jsonl  (P3a relationship/basis/type_compatibility)
   ↓ derive_reconciliation_objects.py (P3b, per-label lookup only)
ledger-p3b/OB0001-3/objects.jsonl  (3 of 30 batches; frozen)
   ↓ [P4/P5/P6/P7 NOT-IMPLEMENTED]
```

**Where information can be lost, per edge**: the single largest loss point in the
entire graph is the `_derived.json[families] → derive_reconciliation.py →
_derived.json[reconciliation_pairs]` edge — this is where `row_brief()` discards
`dependencies[]`, `lineage_claims[]`, `invariants[]`, `assumptions[]`,
`completeness`, `missing[]`, and file-level `provenance` from every row a P3a
reviewer ever sees, and where candidate-pair generation itself only implements 2
of the mechanisms A11 permits (§12). This single edge accounts for the entire
1,466-candidate-pair gap found by Stage 1 and the 82/86 = 95.3% "miss rate" found
by the PRIMARY Evidence Validation experiment.

---

## §7. Protocol-to-implementation traceability matrix

| Rule | Requirement (verbatim/paraphrased) | Actual implementation | Evidence | Status | Consequence |
|---|---|---|---|---|---|
| R0 | Phase 1 maximizes information preservation; nothing dropped for being partial/informal | P1 contribution contract captures 20 fields per row incl. `dependencies`, `lineage_claims`, `invariants`, `assumptions` | `03-CONTRIBUTIONS.jsonl` schema (§B5 of protocol); 29,733 rows, all fields present when applicable | **CONFORMING** (at P1) | None at P1 — the loss happens later, at P3a (see R13 below) |
| R2 | Each file read whole, once, by exactly one extraction agent | 70 batches, `plan_batches.py` partitions by unique+resolved files only, no file assigned twice | `01-BATCH-MANIFEST.jsonl` batch membership is a partition (verified at P1c close: `close_phase1.py` asserts exact 1:1 file coverage) | **CONFORMING** | None |
| R5 | P1 never resolves identity, never merges labels | P1 batches never cross-reference other batches' labels (R12); labels are handles | Batch dispatch prompts (agent-extraction-contract.md) never instruct cross-batch identity work | **CONFORMING** | None |
| R6 | Source claim ≠ our assessment ≠ agent observation, kept apart in every record | Contribution schema has separate `statement` (transcription), `lineage_claims[].quote` (source's own words), and no generic "assessment" field mixed in | `03-CONTRIBUTIONS.jsonl` schema | **CONFORMING** | None |
| R7 | A source lineage statement is SOURCE-CLAIMED-*, never automatically the fact | P3a's Q1 protocol excerpt requires `basis` (CORROBORATED/SOURCE-CLAIMED-ONLY/INFERRED/NONE) for every relationship verdict | `31-RECONCILIATION-PAIRS.jsonl`: every record has a non-null `basis` (verified: 0 nulls) | **CONFORMING** at the verdict-schema level; **PARTIALLY-CONFORMING** at the evidence-supply level — a SOURCE-CLAIMED-* claim can only inform a verdict if it ever reaches the reviewer, and §12/§13 case 2/3 show many never do | See R13 |
| R8 | Own artifacts are DERIVED, never primary evidence | Roadmap never lists any `chronological-read/` path | grep: 0 occurrences (§3) | **CONFORMING** | None |
| R9 | Duplicate ≠ independent evidence | `validate_roadmap.py` assigns `dup=EXACT-DUPLICATE`; `plan_batches.py` filters to `dup=UNIQUE` only before batching | Fixture case 7; `00-ROADMAP-VALIDATED.jsonl`/`01-BATCH-MANIFEST.jsonl` field definitions | **CONFORMING** | None |
| R10 | Later evidence clarifies, never rewrites, the earlier record | No script anywhere overwrites an existing `02-FILES.jsonl`/`03-CONTRIBUTIONS.jsonl` row once written; corrections are logged as new findings in `09-ORCHESTRATOR-FLAGS.md`, not silent edits | Session history: every correction found this session (RP0028 fabricated citation, RP0526/RP0288, the row_brief bug) was logged and, where a production ledger was touched at all, done as an explicit, disclosed append/fix — never a silent rewrite | **CONFORMING** | None |
| R13 | Scripts derive what can be derived; agents interpret | `derive_reconciliation.py`'s own docstring states this explicitly and its logic contains zero identity/relationship decisions | Docstring + code inspection | **CONFORMING** as a division-of-labor principle. **But R13 does not itself require exhaustive derivation** — it says scripts derive "what can be derived," and the finding of this whole audit chain is that the script derives *less* than what R0's information actually makes derivable (§12) | This is the crux: R13 is not violated by *omission* the way R0 arguably is — R13 permits a script to derive a narrow slice; the actual defect is that the *narrow slice chosen* (exact-label-string CROSS-GROUP matching only) is far narrower than what R0's preserved fields would support |
| R16 | Different type ≠ different object; type and relationship decided separately | Q1 (relationship) and Q2 (type_compatibility) are separate, independently-required fields in every P3a verdict | `31-RECONCILIATION-PAIRS.jsonl`: checked programmatically — 0 HOMONYM verdicts with `basis=NONE` (would indicate type-alone reasoning); only 1 SAME verdict with non-COMPATIBLE type (a single instance worth a closer look, not a pattern) | **CONFORMING** | None material found |
| R17 | Absence is NOT-EVIDENCED-IN-CAPTURE until census+targeted search fails, only then GENUINELY-UNDEFINED-AFTER-CENSUS | `derive_families.py` (P2b) correctly produces only `NOT-EVIDENCED-IN-CAPTURE` (mechanical, pre-search) per its own docstring; the targeted-search step is specified for P3b's per-object roll-up (dispatch prompts require `FOUND[S-id]` or `GENUINELY-UNDEFINED-AFTER-CENSUS`, enforced by `verify_reconciliation_objects_batch.py`) | Code inspection of both scripts; OB0001-3's own completed work shows the search step DID execute correctly where run (e.g. `nyaya-pramana-lens` resolved via a real Sanskrit-notation search) | **PARTIALLY-CONFORMING** — correctly specified and correctly executed in the 3/30 batches that ran; **NOT YET EXECUTED** for the remaining 27/30 batches (2,417 of 2,497 labels), currently frozen at the pre-search `NOT-EVIDENCED-IN-CAPTURE` stage pending your gate decision | No violation of the rule itself — a stalled pipeline, not a broken one |
| R17 (NEGATIVE-BOUNDED/NEGATIVE-CENSUS sub-rule) | UNWITNESSED from a bounded search must carry `NEGATIVE-BOUNDED`; only a corpus-wide null search may carry `NEGATIVE-CENSUS` (and only then may relationship=INDEPENDENT) | `verify_reconciliation_pairs_batch.py`'s `NEGATIVE_LABEL_ENUM` **includes** `NEGATIVE-BOUNDED` as a valid value, but the verify script **only checks/requires** `negative_verdict_label` when `relationship==INDEPENDENT` — nothing requires or even prompts it for UNWITNESSED | Direct code inspection, lines 27/87-92; confirmed empirically: **0 of 684** UNWITNESSED records in production carry any `negative_verdict_label` at all | **NON-CONFORMING** — the schema anticipated this half of R17's dual-labeling design (the enum value exists) but the enforcement and the dispatch prompts never asked for it, so it is universally absent in practice, not merely rare | This is the precise mechanism behind Stage 1's "UNWITNESSED conflates multiple epistemic states" finding — the protocol has the vocabulary to distinguish them, but nothing in the implementation uses it |
| R19 | Never run Phase N+1 as substitute for unfinished Phase N | P3b was explicitly paused (OB0001-3 only) rather than continued while P3a's semantic gate was open | Session history: your explicit instruction, honored | **CONFORMING** (by your intervention, not by an automated guard — there is no script-level check preventing P3b dispatch while P3a is under audit; this session's discipline was manual) | None currently, but no structural safeguard exists |
| R20 | Pipeline output is a recommendation pending governance review | No script or document in this pipeline claims final/ratified status for any P3a/P3b output | `09-ORCHESTRATOR-FLAGS.md`, session logs consistently frame every phase as provisional | **CONFORMING** | None |
| A11 "candidate groups AND cross-group SOURCE-CLAIMED links" | P3 must examine both within-group and cross-group pairs | Both mechanisms exist in `derive_reconciliation.py` | Code inspection, §12 | **CONFORMING** in mechanism-count; **NON-CONFORMING** in mechanism-precision (exact-string-only cross-group matching misses 259+18 = 277 real cross-group lineage claims corpus-wide, per the provenance-trace report) | See §12 |
| A11 "relationship requires stated basis" | Every relationship verdict must carry a basis | `31-RECONCILIATION-PAIRS.jsonl`: 0 nulls | **CONFORMING** | None |
| A11 "INDEPENDENT requires corpus-wide search" | INDEPENDENT may only be concluded from a stated corpus-wide search or explicit corpus statement | `verify_reconciliation_pairs_batch.py` enforces `negative_verdict_label==NEGATIVE-CENSUS` + `corpus_wide_search_note` for every INDEPENDENT verdict | All 4 production INDEPENDENT verdicts carry both fields (verified in Stage 1) | **CONFORMING** | None |
| A11 dependency edges "require source support" | P3b dependency edges must cite a specific row, never manufactured from co-occurrence | `verify_reconciliation_objects_batch.py` requires `source_id` on every edge and validates `target_label` is a real label | OB0001-3's own output: most labels emit zero edges rather than manufacture weak ones (explicitly reported by the dispatched agents) | **CONFORMING** | None |

---

## §8. Field-flow matrix — P1 fields through the pipeline

| Field | Captured P1 | Preserved (P1c ledger) | Indexed (P2) | Used by P2 | Used by P3 discovery | Used by P3 adjudication | Loss point |
|---|---|---|---|---|---|---|---|
| `dependencies[]` | YES | YES (`03-CONTRIBUTIONS.jsonl`) | NO (not read by `normalize_labels.py`) | NO | **NO — never read anywhere in `derive_reconciliation.py`** | **NO — stripped by `row_brief()`, never in `a_rows_sample`/`b_rows_sample`** | **P3 candidate generation** (never consumed at all) |
| `lineage_claims[].target` (exact label match) | YES | YES | NO | NO | YES, if target string exactly equals a label/notation/alias | YES, surfaced as `lineage_claims_a_to_b`/`b_to_a` (structured fields, separate from `row_brief`) | None for this sub-case |
| `lineage_claims[].target` (source_id) | YES | YES | NO | NO | **NO — `string_to_label` only indexes label/notation/alias strings, never source_ids** | N/A (never reaches a pair) | **P3 candidate generation** |
| `lineage_claims[].target` (paraphrase/substring) | YES | YES | NO | NO | **NO — exact-equality check only, no substring/fuzzy matching** | N/A | **P3 candidate generation** |
| `lineage_claims[].quote` | YES | YES | NO | NO | Only for exact-match cases (structured field) | Only for exact-match cases | Same loss point as its parent claim |
| `anchor` | YES | YES | NO | NO | N/A | YES — in `row_brief()` | None |
| `labels[]` | YES | YES | YES (P2a clustering input) | YES | YES (WITHIN-GROUP membership) | N/A directly | None |
| `types[]` | YES | YES | NO | NO (used only for `CONTRADICTION` flag) | NO | YES — in `row_brief()` | None |
| `assumptions[]` | YES | YES | NO | YES (P2b's `assumption_register`) | NO | **NO — stripped by `row_brief()`** | P3 candidate/evidence assembly |
| `invariants[]` | YES | YES | NO | NO | NO | **NO — stripped by `row_brief()`** | P3 candidate/evidence assembly |
| `version_ref` | YES | YES | NO | NO | NO | NO | Never consumed past P1 (not necessarily a defect — no protocol rule requires it downstream) |
| `experiment{}` | YES | YES | NO | NO | NO | NO | Same |
| `review_flag` | YES | YES | NO | NO | NO | NO | Same |
| file-level `provenance` (PRIMARY/SECONDARY-SYNTHESIS) | YES (`02-FILES.jsonl`) | YES | NO | NO | NO | **NO — `row_brief()` has no provenance field at all; confirmed by fixture case 8** | **P3 evidence serialization** — the protocol's own †-marking discipline (§B4) is completely invisible to a P3a reviewer |

**Can each field be lost / misresolved / incorrectly transformed?**
- `dependencies[]`: lost (never read). Not misresolved — simply absent from the pipeline entirely downstream of P1c.
- `lineage_claims[].target`: can be lost (non-exact-match cases) or, as found and fixed in this session's own audit tooling (not production), misresolved to the wrong file if a downstream tool substitutes a "representative" row for a label instead of the exact cited source_id.
- Everything else in the "stripped by row_brief()" rows: lost, not misresolved — a clean omission, not a corruption.

---

## §9. Failure-mode layer attribution

| Mode | Meaning | Layer | Justification |
|---|---|---|---|
| F1 | Evidence exists, pairing logic doesn't read the field type | **B — Implementation** | `derive_reconciliation.py` simply never calls `dependencies` anywhere; this is a code-completeness gap, not a protocol gap — A11 does not forbid using `dependencies` as evidence |
| F2 | Identifier/target resolution failure | **B — Implementation** (mostly) / **I — Audit-tool defect** (in the two specific cases this session found and fixed, PV0033/PV0087) | The production pipeline has no target-resolution step at all for non-exact matches (a gap); this session's own audit script added one and it had a bug, now fixed and disclosed |
| F3 | Exact-string matching failure | **B — Implementation** | The matching rule (`lc.get("target") in b_strings`, Python set membership) is a deliberate, narrow implementation choice — A11's text ("target string matches") is genuinely ambiguous about whether substring/paraphrase matching was intended, but the chosen interpretation is the narrowest possible reading |
| F4 | No existing relationship value fits | **A — Methodology defect** | This is not an implementation bug — the 11-value enum is exactly what the Master Protocol specifies (§B4); the recurring "architecturally-linked-but-distinct" pattern (Stage 1 found it in 4/12 hand-picked cases, this session's validation found it in 7/100 random-sampled cases) is a genuine gap in the *specification*, not a coding error |
| F5 | Signal is inference, not real source evidence | **D — Agent extraction** (P1) or **G — P3 adjudication**, case by case | Where a P1 row's own `dependencies`/`lineage_claims` field appears to encode the extracting agent's inference rather than a source statement, that is a P1 extraction-discipline question (R6/R7); where a P3a *reviewer* over-read weak evidence into a stronger verdict, that is a P3 adjudication question — this audit did not have scope to separately re-derive which of the 14 F5-tagged cases in the validation experiment are which, and reports this as an open item |
| F6 | Evidence absent | **C — Source corpus** (when genuine) | 0 occurrences in the 100-pair validation sample — not currently a measured problem |
| F7 | Provenance concern | **N/A** | 0 occurrences raised across 100 independently-reviewed pairs — no evidence this failure mode exists in the sampled population |

---

## §10. RP0288 — first point of information loss

```
S0940 (champion-challenger-model-promotion-lifecycle) written, PRIMARY
S0961 (kos-model-risk-and-self-validation) written ~11 min later, PRIMARY,
    contains dependencies:["champion-challenger-model-promotion-lifecycle"]
    (EXACT label-string match) AND a SOURCE-CLAIMED-IDENTITY lineage_claims
    entry whose target is "champion-challenger-model-promotion-lifecycle's
    governed promotion workflow (Step 45)" (a paraphrase, not exact)
        ↓  P1 (faithful capture — both fields correctly recorded, R0/R6/R7 conformant)
03-CONTRIBUTIONS.jsonl — both fields present and correct
        ↓  P1c close, P2a/P2b — both fields preserved unchanged in _derived.json
        ↓  ══════ FIRST LOSS POINT ══════
derive_reconciliation.py candidate generation:
    - the `dependencies` entry is an EXACT match to a real label, but the field
      is never read by any part of this script (F1) — information preserved
      through P1/P2 is discarded HERE, not before.
    - the `lineage_claims` entry's target is a close paraphrase, not an exact
      string match, so it also fails the CROSS-GROUP matcher (F3) — a second,
      independent reason the same underlying relationship never becomes a pair.
    - The pair DID get generated anyway, but only via a THIRD, weaker mechanism:
      P2a's own POSSIBLY-RELATION clustering (an agent, during P2a, manually
      noted "kos-model-risk-and-self-validation POSSIBLY relates to
      champion-challenger-model-promotion-lifecycle" — this is a human/agent
      judgment call surfaced during label normalization, not a mechanical
      recovery of the dependencies/lineage_claims fields).
        ↓
_derived.json["reconciliation_pairs"]: RP0288's `group_evidence` field correctly
    carries the POSSIBLY-RELATION note's "Extends... (Step 45)" text — but
    `a_rows_sample`/`b_rows_sample` (row_brief() output) do NOT carry either the
    dependencies entry or the lineage_claims entry, because row_brief() never
    includes those fields at all — SECOND, independent loss, downstream of the
    first.
        ↓
P3a reviewer (batch RP0012) sees: a one-line P2a hint ("Extends...") with NO
    corroborating structured evidence visible anywhere in the sample it was
    given, and — per protocol's own conservative default — correctly declines
    to promote an unconfirmed hint to a positive relationship, landing on
    UNWITNESSED/NONE/UNKNOWN.
```

**The first point at which the information necessary to recover this
relationship became inaccessible is `derive_reconciliation.py`'s candidate
generation step** (both the unread `dependencies` field and the non-exact
`lineage_claims` match). The P3a reviewer's UNWITNESSED verdict was the
*correct*, protocol-conformant response to what it was actually shown — R7's
"a claim never becomes a relationship without the basis being stated" was
honored; the basis simply was never made visible to state.

---

## §11. RP0526 — first point of information loss, and audit-tool-defect scope

```
S1345 (sarathi-investigation-guide) — SECONDARY-SYNTHESIS
S1347 (epistemic-sarathi-see-guide-decide / zero-lens-bayesian-gaps) — PRIMARY,
    contains dependencies:["S1345"] and lineage_claims:[{kind:
    SOURCE-CLAIMED-EXTENSION, target:"S1345", quote:"..."}]
        ↓  P1 — faithful capture (verified by direct grep against both raw files,
           Stage 1 and this experiment)
03-CONTRIBUTIONS.jsonl — both fields correctly recorded
        ↓  ══════ FIRST LOSS POINT (production pipeline) ══════
derive_reconciliation.py: target="S1345" is a bare source_id, not a label/
    notation/alias string — fails `string_to_label` lookup entirely (F2/F3
    combined: the string_to_label index structurally cannot resolve a source_id,
    by design, not by omission — this specific failure mode is a genuine gap in
    what target-string forms the matcher recognizes)
        ↓
No CROSS-GROUP pair generated from this signal at all. RP0526 exists as a pair
    ONLY because these two labels also happen to share a P2a POSSIBLY-RELATION
    WITHIN-... actually a separate group (G0324) flagged uncertainty between them
    directly — a second, independent P2a-level signal, structurally identical to
    RP0288's situation.
        ↓
_derived.json["reconciliation_pairs"]: `lineage_claims_a_to_b`/`b_to_a` are both
    EMPTY (confirmed, Stage 1) — the structured SOURCE-CLAIMED-EXTENSION claim
    is completely absent from the pair's evidence bundle, not merely hard to see.
        ↓
P3a reviewer (batch RP0020) sees only the P2a group's own hedge ("recovered from
    prior context... not confirmed") plus row_brief-limited samples of both
    families — reasoned from close textual/structural overlap (matching function
    signatures, matching invariants) to SAME/CORROBORATED. This is where R7's
    discipline was NOT fully honored: the reviewer inferred CORROBORATED basis
    from pattern-matching two independently-worded rows, without the actual
    structured claim that would have licensed that basis-level directly.
```

**Audit-tool-defect scope, precisely bounded**: a SEPARATE defect, entirely
within this session's own audit tooling (`trace_undefined_relationship_
provenance.py`, written for the provenance-tracing investigation, not part of
production), additionally substituted the wrong *target file* (S1273 instead of
S1345) when reporting this signal to the blind validation reviewers. This defect:
- **Did NOT affect** the file-level provenance classification of S1345 itself
  (`02-FILES.jsonl`'s `provenance=SECONDARY-SYNTHESIS` for S1345 was correct and
  unaffected — it is a pre-existing P1 classification, not something the buggy
  script computed).
- **Did affect** evidence retrieval (the wrong file was shown to 2 of the 100
  PRIMARY Evidence Validation reviewers) and, as a direct consequence,
  relationship interpretation for those 2 specific records (PV0033, PV0087),
  both corrected in that report with full disclosure.
- **Did NOT affect** any P3a production verdict — RP0526's production record
  (SAME/CORROBORATED) was never touched, was reasoned by the original P3a
  reviewer independently of this later audit-tool bug, and remains exactly as
  written pending your decision.
- **Did NOT affect** the population-level statistics in the provenance report
  (§0 of the PRIMARY Evidence Validation report) beyond the single pair that
  moved between secondary-provenance sub-buckets after the fix — the headline
  75.3% PRIMARY finding is unaffected.

---

## §12. Candidate-pair generation — the complete, verified mechanism list

**Exactly two mechanisms exist in the implemented pipeline. No others.**

1. **WITHIN-GROUP**: every pair of members inside a P2a candidate group with ≥2
   members (any of the 7 P2a signal types: EXACT-STRING-REUSE, POSSIBLY-RELATION,
   UNKNOWN-CANDIDATE-GROUP, SHARED-NOTATION, SHARED-ALIAS, STRING-SIMILARITY,
   CO-OCCURRENCE).
2. **CROSS-GROUP**: a `lineage_claims[].target` string that is an *exact* match
   (Python set membership, not substring/fuzzy) to another label's own
   working_label, a notation, or an alias, where the two labels do not already
   share a P2a group.

**Mechanisms verified NOT to exist** (checked by direct code inspection of
`derive_reconciliation.py`, confirmed negatively by fixture cases 1/2/3/5):
`dependencies`-based pairing, source-ID-based lineage resolution, substring/
paraphrase lineage resolution, semantic-continuity pairing, shared-reference
pairing beyond what P2a's own clustering happens to catch, and chronology-based
pairing. **Every one of these missing mechanisms is exactly what A11's own text
("a lineage_claim links them... an object's own history") implies should be
possible in principle, but none beyond exact-string CROSS-GROUP matching was
actually built.**

---

## §13. Synthetic conformance fixtures — results

Full script and output: `scripts/audit_synthetic_conformance_fixtures.py`
(EXPERIMENTAL, in-memory only, zero file writes, faithful line-for-line copies
of the real matching functions, diff-verified identical to production before
use). All 8 cases ran and matched their predicted outcome:

| Case | Pair generated? | Evidence visible to reviewer? |
|---|---|---|
| 1. Dependency only | NO | NO |
| 2. Lineage claim, bare source_id target | NO | NO — reproduces RP0526 exactly |
| 3. Lineage claim, paraphrase/substring target | NO | NO — reproduces RP0288 exactly |
| 4. Lineage claim, exact label match | **YES** | **YES** — the one mechanism that works |
| 5. Prose "extends", no structured claim | NO | NO |
| 6. Prose only, but P2a similarity clustering incidentally catches it | YES (incidental) | YES (incidental) |
| 7. Duplicate source (R9) | N/A — enforced upstream at P0, not P3 | N/A |
| 8. SECONDARY-SYNTHESIS cites PRIMARY | YES (pair generated) | Pair yes, **but the †/provenance marking is invisible** |

This independently, mechanically reproduces both of this session's two mandatory
production case studies (RP0526 as case 2, RP0288 as case 3) from first
principles, confirming they are instances of a general, structural pattern —
not two isolated coincidences.

---

## §14. R17 absence-handling — conformance test result

See the R17 rows in §7. Summary: **the two-tier NOT-EVIDENCED-IN-CAPTURE →
GENUINELY-UNDEFINED-AFTER-CENSUS chain is correctly specified and, where
executed (3/30 P3b batches), correctly implemented.** The parallel
UNWITNESSED/NEGATIVE-BOUNDED → INDEPENDENT/NEGATIVE-CENSUS chain is **only
half-implemented**: the stricter INDEPENDENT/NEGATIVE-CENSUS half is enforced by
`verify_reconciliation_pairs_batch.py`; the routine UNWITNESSED/NEGATIVE-BOUNDED
half has a schema slot (`NEGATIVE-BOUNDED` is a valid enum value) but no
enforcement, no prompt instruction, and — empirically — zero real usage across
684 records. **This is the single most precise, falsifiable finding of this
entire audit**: it is not a design flaw in the protocol (R17's text supports
exactly this two-tier structure) — it is an implementation gap in what the
dispatch prompts asked agents to record and what the verify script checked.

---

## §15. Relationship/type independence (R16) — conformance test result

See the R16 row in §7. **CONFORMING** — no measurable pattern of type
compatibility forcing a relationship verdict was found in 1,793 production
records (0 HOMONYM-by-type-alone; 1 isolated SAME/non-COMPATIBLE case, not a
pattern). Q1 and Q2 are structurally independent fields throughout the schema
and the verify scripts never cross-validate one against the other.

---

## §16. Provenance firewall (R8, PRIMARY/SECONDARY-SYNTHESIS separation) —
conformance test result

- Own artifacts (this pipeline's own outputs) never appear in the roadmap: **CONFORMING** (§3).
- Duplicates never become independent evidence: **CONFORMING** (R9 row, §7).
- SECONDARY-SYNTHESIS never silently becomes PRIMARY: checked directly —
  `02-FILES.jsonl`'s `provenance` field is set once, at P1, and no downstream
  script (`derive_families.py`, `derive_reconciliation.py`, P3a/P3b) ever writes
  to or overrides it. **CONFORMING** at the classification-storage level.
- **But the classification, once made, does not travel with the evidence** —
  fixture case 8 and the R17/row_brief findings show that by the time evidence
  reaches a P3a reviewer, there is no way to tell from `a_rows_sample`/
  `b_rows_sample` alone whether a given row's source was PRIMARY or
  SECONDARY-SYNTHESIS. This is **NON-CONFORMING** with the protocol's own
  explicit citation discipline ("EVERY statement carries [S-id §anchor] † if
  SECONDARY-SYNTHESIS," prompt §B4) — the marking exists at the file level and
  is lost before it reaches the point where the protocol says it must appear.
- Later research does not rewrite historical records: **CONFORMING** — no
  evidence found of any script modifying an already-written P1/P2/P3a record
  in place (all corrections found this session were additive/disclosed, per R10).

---

## §18. Root-cause classification

| Category | Definition | Findings assigned here |
|---|---|---|
| **A — Methodology defect** | The Master Protocol itself does not provide sufficient rules | F4 (no relationship value fits, ~7% recurrence, confirmed on 2 independent samples); the A11 candidate-generation text is genuinely ambiguous about whether "target string matches" was meant to include paraphrase/substring forms |
| **B — Implementation defect** | The implementation does not faithfully implement the Master Protocol | The dominant category: `dependencies[]` never read (F1); exact-string-only matching where the protocol's intent is at minimum ambiguous (F3); `row_brief()`'s field-stripping (loses `dependencies`/`lineage_claims`/`invariants`/`assumptions`/provenance — none of which any protocol rule says to discard); the R17 NEGATIVE-BOUNDED half-implementation; R19's lack of a structural (vs. manual) phase-gate guard |
| **C — Source corpus defect** | The historical corpus itself lacks the needed evidence | **Minimal measured presence.** 0/100 NOT_FOUND in the validation experiment; 0/100 provenance concerns; the corpus was found, repeatedly, to contain more relationship evidence than the pipeline surfaces, not less |
| **D — Agent execution defect** | An extraction or adjudication agent failed to correctly apply an otherwise-adequate protocol | Present but secondary: the RP1376-type cases (an original P3a verdict of SAME where the source text explicitly says "Corrects EA4's formula") and roughly 14% of F5 (inference-presented-as-evidence) tags in the validation sample — real, but far smaller in volume than category B |

**Overall**: this audit finds the KnowledgeOS reconstruction's failure pattern is
**predominantly Category B (implementation defect)**, with a real but
secondary Category A (ontology coverage) component, a near-absent Category C,
and a present-but-minority Category D. The Master Protocol v3.5, as written,
already anticipates most of what would be needed to fix Category B (R0's
information-preservation mandate already requires exactly the fields that are
being captured-then-discarded; R17's two-tier absence model already exists in
text; the NEGATIVE-BOUNDED enum value already exists in the verify script) — the
gap is between what the protocol specifies and what the two P3-phase scripts
(`derive_reconciliation.py`'s candidate generation, `row_brief()`'s evidence
serialization) actually do with information that P1/P2 already preserved
correctly. **No change to the Master Protocol document itself is indicated by
this audit's findings; changes, if authorized separately, would be
implementation-only.**
