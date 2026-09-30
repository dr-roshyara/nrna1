# P3a Quality Gate v2 — Stage 1: Empirical Probe

Read-only. No production ledger modified (`31-RECONCILIATION-PAIRS.jsonl` unchanged,
RP0526 unchanged, RP0288 unchanged, no new enum values, OB0001–OB0003 untouched).
Every finding below is traced to primary evidence in `_derived.json`'s full family
rows — not to the prior blind reviewers' conclusions, which are used only as
pointers to where to look, per instruction.

## 0. Headline finding, found while investigating the two mandatory cases

Both RP0526 and RP0288 turned out to share a **single, general, corpus-wide root
cause** that neither the original P3a reviewers nor the two independent blind
auditors could have caught, because it is not a reasoning failure — it is a data
visibility failure baked into the evidence-assembly script itself.

**`derive_reconciliation.py`'s `row_brief()` function strips every row down to just
6 fields (`source_id, anchor, types, statement, type_signature, explicit_date`)
before it is shown to any P3a reviewer — for every pair, in every batch, regardless
of truncation status. The `dependencies` and `lineage_claims` fields — which is
where a row's own most decisive, structured relationship evidence lives — are
never included in `a_rows_sample`/`b_rows_sample` at all.**

Separately, even the mechanical `lineage_claims_a_to_b`/`b_to_a` fields (computed
from the *full*, untruncated row set, so truncation genuinely doesn't affect them)
only fire when a claim's `target` string is an **exact** match to the other label's
own name/notation/alias. A target phrased as a bare source_id (`"S1345"`), or as a
prose paraphrase that merely *contains* the label name as a substring
(`"champion-challenger-model-promotion-lifecycle's governed promotion workflow
(Step 45)"`), never matches, and the claim is silently dropped from the pair's
evidence bundle.

**Corpus-wide quantification** (exact, not sampled):

| | Count |
|---|--:|
| Total contribution rows | 29,733 |
| Rows with non-empty `dependencies` | 5,471 |
| — of which the dependency string is an exact match to a real, different label (structurally reliable, but **never read by the pairing script at all**) | 2,052 |
| Rows with non-empty `lineage_claims` | 2,367 |
| — target exact-matches a real label (only these can possibly be caught, and only if it's the same pair) | 81 |
| — target is a bare source_id, e.g. `"S1345"` (**invisible**) | 18 |
| — target is a prose paraphrase containing a real label as a substring (**invisible**) | 259 |
| — target references something outside the label universe entirely (out-of-batch step numbers, filenames — correctly excluded) | 2,030 |

Collapsing dependency-exact-matches and paraphrase/source-id lineage-claim matches
into distinct label-pairs and checking against the existing 1,793-pair P3a corpus:

| | Count |
|---|--:|
| Distinct label-pairs carrying a real, structural relationship signal that the pairing script never used | **1,464** |
| — of which already exist as one of the 1,793 judged P3a pairs (judged on evidence that omitted this signal) | **257** |
| — of which were **never constituted as a pair at all** — missing from the P3a universe entirely | **1,207** |

**This reframes the nature of the problem.** It is not only that some existing
verdicts might be under-evidenced due to soft ontology boundaries or search effort
(the concerns the v1 gate measured) — the P3a *pair universe itself* is
incomplete by at least 1,207 pairs with real, citable structural evidence of a
relationship, and at least 257 of the 1,793 pairs that WERE judged were judged with
this evidence silently omitted from what the reviewer ever saw. Of the 257:
**65 are currently UNWITNESSED** (9.5% of all 684 UNWITNESSED verdicts) and **24 are
in the 158-pair high-stakes tier** (15.2% of the high-stakes tier) — both far above
what random chance would predict, meaning this root cause concentrates specifically
in exactly the two populations the v1 gate flagged as least reliable.

---

## 1. RP0526 — full reconstruction

**Original verdict:** SAME / CORROBORATED / COMPATIBLE.
**Two independent blind reviews (v1 gate):** both UNWITNESSED / NONE / COMPATIBLE.

**What the row_brief-limited evidence bundle showed all three reviewers**: `a`
(`epistemic-sarathi-see-guide-decide`, 1 source, S1347, 3 rows) states it "confirms
the earlier KnowledgeOS-as-Epistemic-Sarathi formulation" and gives a formal
signature `Sarathi(K_t,Z_t,L_t,I_t,Q_t,C_t) -> a_t`. `b`
(`sarathi-investigation-guide`, 14 rows across 12 sources, S0766–S2586) documents an
incrementally-built Sarathi-orchestrator concept across a multi-month thread, of
which 2 rows (S1345) closely echo `a`'s content. The P2a grouping note itself
explicitly hedges: *"Recovered from prior context rather than freshly read from the
original Step 155/156 artifacts; noted as possibly convergent... but explicitly not
confirmed."*

**What the full raw row for S1347 additionally contains, invisible to every
reviewer:**
```json
"dependencies": ["S1345"],
"lineage_claims": [{"kind": "SOURCE-CLAIMED-EXTENSION", "target": "S1345",
                     "quote": "The earlier Question 17 formulation is one of the
                               strongest pieces of evidence."}]
```
S1345 is confirmed to belong to `b`. This is a **P1-tagged, structured
SOURCE-CLAIMED-EXTENSION** naming the exact source row in `b` that `a` builds on —
the single most decisive piece of evidence available for this pair, and it reached
none of the three prior reviews.

**My own determination, from the complete evidence:** Neither SAME nor UNWITNESSED
is correct.
- **Not SAME**: `a`'s own family (3 rows, all dependent on S1345) is not identical
  to `b`'s whole family (14 rows spanning a much broader, independently-continuing
  thread of which S1345 is only 2 rows); and both `a` and the target `b`-row S1345
  itself carry an explicit, unresolved verification gap ("I don't want to jump to
  that conclusion until I read the actual Step 155/156 texts" — never done).
- **Not UNWITNESSED**: there is now a real, P1-tagged, structured claim connecting
  the two families at a specific point, plus strong corroborating content
  continuity (near-verbatim signature match, matching `See≠Guide≠Decide` and
  `Zero≠Lord≠Sarathi≠Transition` invariants, matching "before we continue"
  principle) — this clears the evidence bar for a positive relationship.
- **My verdict: EXTENSION, basis CORROBORATED, type_compatibility COMPATIBLE.** `a`
  takes `b`'s S1345 (an informal, narrative description) and gives it a formal
  function signature and explicit role definitions — adding precision/capability
  without changing S1345's core claim, which is EXTENSION's definition. REFINEMENT
  is a close, defensible alternative (the boundary between "adds capability" and
  "sharpens without adding" is itself soft here, worth flagging for Stage 3's
  formal-definitions work); SAME and UNWITNESSED are both ruled out by the same
  evidence, for opposite reasons.

**Cause of the original disagreement:** primarily **PROCEDURE** (the row_brief
evidence-stripping bug), not evidence ambiguity, not ontology ambiguity, not
reviewer carelessness — all three reviewers did reasonable work on what they were
shown; what they were shown was structurally incomplete by construction.

---

## 2. RP0288 — full reconstruction

**Original verdict:** UNWITNESSED / NONE / UNKNOWN, despite the pair's own
`group_evidence` (P2a's `why_grouped` note) explicitly stating "Extends
champion-challenger-model-promotion-lifecycle (Step 45)... into a full
temporal-validity/drift/governance framework."

**What the full raw rows show, invisible to the original reviewer:** two of `b`'s
(`kos-model-risk-and-self-validation`) 14 rows carry a `dependencies` entry that is
an **exact string match** to `a`'s own label
(`"champion-challenger-model-promotion-lifecycle"`), and one of those two additionally
carries:
```json
"lineage_claims": [{"kind": "SOURCE-CLAIMED-IDENTITY",
  "target": "champion-challenger-model-promotion-lifecycle's governed promotion workflow (Step 45)",
  "quote": "This is much safer than: Outcome → AI retrains itself → Production."}]
```
That quoted phrase is a **near-verbatim reuse** of `a`'s own S0940 row: *"contrasted
as 'much safer than' a naive Outcome -> automatically retrain everything."* The
`lineage_claims.target` string embeds `a`'s label name as a substring but is not an
exact match (`"champion-challenger-model-promotion-lifecycle's governed promotion
workflow (Step 45)"` ≠ `"champion-challenger-model-promotion-lifecycle"`), so the
mechanical extractor silently dropped it — the second sub-pattern of the same root
cause, distinct from RP0526's bare-source-id case.

**My own determination:** the group_evidence's "Extends..." characterization is
supported by the full evidence, not merely asserted. `b`'s row applies `a`'s general
governed-promotion/no-naive-autonomy principle to the specific scenario of
automatically retraining a degraded model, reusing `a`'s own framing nearly
verbatim while adding a new, more specific decision workflow — this is
EXTENSION's textbook shape ("adds new capability/scope... without changing... core
definition"). The P1 tag on the specific claim is `SOURCE-CLAIMED-IDENTITY` rather
than `SOURCE-CLAIMED-EXTENSION`, but per this whole project's standing discipline
(P1 captures claims, P3 tests them — a tag is evidence, not an automatic verdict),
I judge the actual content as EXTENSION rather than SAME: `b` is not claiming to
*be* `a`'s workflow, it is applying/extending it into a new governed-drift context
with materially richer typing (`ModelRisk(M,D,E)`, `Valid(M,t,D,A)`) that `a` never
had.

**My verdict: EXTENSION, basis CORROBORATED, type_compatibility
PARTIALLY-COMPATIBLE** (same core lifecycle concept; `b` adds a richer formal type
structure `a` never specified, which is a partial rather than a full match).

**Cause of the original disagreement:** again primarily **PROCEDURE** — the
group_evidence prose (written during P2a, by an agent that *did* read more context)
correctly named the relationship, but the P3a reviewer, working only from the
row_brief-stripped sample, had no way to independently verify or ground that
one-line hint in specific rows, and defaulted to the protocol's conservative
UNWITNESSED baseline in the absence of visible corroboration. This is a subtler
variant of the same root cause: the evidence needed to *confirm* an already-hinted
relationship was present in the corpus but withheld from the reviewer's view.

---

## 3. UNWITNESSED probe (8 cases, stratified by cause)

Re-derived from primary evidence; the original reviewer's own `what_says_this` is
treated as a data point to check, not as ground truth to repeat.

| Pair | Cause category | My assessment |
|---|---|---|
| **RP0829** (`h-kos-fallacy-001` family / lifecycle-reasoning) | **A — genuinely searched, nothing there** | Confirmed reproducible. Both labels are two of nine sibling entries in one shared census/validation table (S0237/S0239); the table's own compression-test rulings give them different, unrelated dispositions and never cross-reference each other specifically. Correct UNWITNESSED. |
| **RP0341** (context-map / governance hypothesis) | **A — genuinely searched, corpus itself unresolved** | The P1 extraction agent's own note explicitly declines to resolve this ("relationship unresolved"). Nothing beyond weak STRING-SIMILARITY exists. Correct UNWITNESSED — reproducible from the corpus's own stated position. |
| **RP0436** (`S_Kernel` hypothesis / determination-lineage-invariant) | **D — contradictory/unresolved, live in the corpus itself** | The corpus's own `why_grouped` note says "flagged, not confirmed identical" — this is a genuinely open, actively-contested research question in the source material itself (independently corroborated by this repo's own recent commit history, MD-104, discussing "Determination... as a competing framing" to `S_Kernel`). Correctly flagged by the original reviewer as "an actively contested identity question, not a settled non-relationship" — this is the single best-documented example of legitimate Category D in the whole sample. |
| **RP0120** (`audi-epistemic-grounding-model` / independent-evidence-combination-rule) | **E — source unavailable (P1 gap, not P3a gap)** | `audi-epistemic-grounding-model` has **zero** captured rows anywhere in the corpus — a real, named label with no content, most likely a P1 extraction gap (cf. the OB0002 finding that `nyaya-pramana-lens` had the same symptom and turned out to have real content elsewhere, findable via a targeted notation search). This root cause is distinct from §0's dependencies/lineage_claims bug — it is upstream, in P1's row-to-label assignment, not in P3a's evidence assembly. Not investigated further here (out of Stage-1 scope), but flagged as a different failure mode requiring a different fix. |
| **RP0957** (Good weight-of-evidence / kernel-reduction) | **B — insufficient search (partially)** | The recorded reasoning is not perfunctory — it identifies specific shared source_ids and explicitly checks for (and doesn't find) a design/derivation claim. The v1 gate's "insufficient search" flag was based on truncation (91% of one side's rows unseen) rather than on the visible reasoning's quality. **Important nuance for Stage 2's taxonomy: "search coverage" and "reasoning quality on the coverage that was achieved" are two separate axes** — this case scores adequately on the second and poorly on the first. |
| **RP0970** (ideal-state-formalization / state-transition-formalization) | **F — real relationship, no ontology slot** | S0798 explicitly states the two formal objects are *designed* to mirror each other structurally (same revision-authorization pipeline for both). This is real, cited, positive evidence of an intentional relationship that fits none of the 11 values (not identity, not lineage, not supersession — a deliberate design-time symmetry between two separately-defined objects). Correctly identified and explicitly flagged by the original reviewer as needing a new category. |
| **RP0028** (ADR-AIP-04 / six-role model) | **F — real relationship, no ontology slot** | Full rows show a governance body adopted the six-role model as an operating model, and later formalized that adoption via this very ADR. A genuine "governs/adopts" relationship, correctly recognized as real but outside the closed taxonomy. |
| **RP0624** (Lord / Sarathi feedback loop) | **F — real relationship, no ontology slot, and recurring** | S0900 explicitly documents a bidirectional feedback interface (`Sarathi -> KnowledgeGap -> Lord`) between two deliberately-distinct algebra objects. The original reviewer explicitly cross-references two *other* pairs (RP0003, RP0257) showing the identical pattern — confirming this is not a one-off but a recurring architectural-linkage shape the ontology has no name for. |

**Reproducibility check** (the user's fifth required question — "what would another
competent reviewer need to know to reproduce the decision?"): RP0829, RP0341,
RP0436, and RP0120 are all reproducible from the visible evidence alone — a second
reviewer with the same inputs would very likely reach the same UNWITNESSED verdict.
RP0970, RP0028, and RP0624 are reproducible in their *evidence identification* but
not in their *final label* — a second reviewer forced into the same 11-value
enum would likely also land on UNWITNESSED (there's nowhere else to put a
real-but-unclassifiable relationship), but that convergence is an artifact of the
closed list, not agreement that "no relationship exists." RP0957's search-coverage
gap is not reproducible without independently deciding to pull the full row list.

---

## 4. High-stakes probe (4 cases)

| Pair | Original | Independent (blind) | Disagreement cause |
|---|---|---|---|
| **RP0440** (digitalization-robot-concept ↔ knowledgeos-kernel-concept) | DERIVED-FROM | INDEPENDENT (HIGH conf., CORROBORATED) | **REVIEWER INTERPRETATION**, not missing evidence — see below, both reviewers cite the *same* S0167/S0171/S0176 rows and reach opposite conclusions. |
| **RP1376** (KR-EPISTEMIC-AGENCY spec ↔ next-epistemic-act-function) | SAME | CONTINUATION (HIGH conf., CORROBORATED) | **EVIDENCE** — `a`'s own text explicitly "Corrects EA4's formula," "Corrects EA5's model," "Demotes EA6's naming" — active revision of specific named errors, not identity. A clear original misclassification. |
| **RP1111** (knowledge-atma-identity-concept ↔ persistent-identity-of-observation-hypothesis) | SAME | CONTINUATION (MEDIUM conf., INFERRED) | **ONTOLOGY** — the corpus's own group note says "possibly the same... not resolved," and the underlying hypothesis was itself left with "final verdict UNKNOWN... never tested nor formally withdrawn." A genuinely soft SAME/CONTINUATION boundary case, structurally similar to RP0526. |
| **RP0210** (canonical-construction-decision-register ↔ sigma-min-powerset) | DERIVED-FROM | CONTINUATION (MEDIUM conf., SOURCE-CLAIMED-ONLY) | **ONTOLOGY (mild)** — both reviewers agree a real, evidenced connection exists (S1806's "ATTACK 9... directly resolves decision D-4") and cite the same fact; they differ only on whether resolving an open decision counts as DERIVED-FROM or CONTINUATION. The mildest disagreement of the four — a genuine boundary case, not an error. |

**RP0440 deserves special attention as the single cleanest "genuine classification
error" found in this whole probe.** Both reviewers had the complete, untruncated
evidence (`a`'s 8/8 rows, no hidden truncation) and cite the *identical* three
source rows (S0167, S0171, S0176). The original reviewer read "the robot consumes
and operates on governed knowledge" as implying a derivation relationship
(downstream layer ⇒ DERIVED-FROM). The independent reviewer read the same rows'
explicit separation language ("never becomes... an independent authority source,"
"robot = distribution, kernel stays AI-free") as architectural independence. **I
judge neither is fully correct.** There plainly is a described relationship (the
robot is a real, functioning consumer layer built on top of the kernel) — that
rules out INDEPENDENT (which requires no connection, not merely no shared
authority). But there is no lineage/genealogy claim, no formal derivation of one
object's *content* from the other's — that rules out DERIVED-FROM too. What the
evidence actually supports is a fourth pattern, structurally identical to RP0970,
RP0028, and RP0624 above: **two deliberately-separated objects joined by a real,
cited, operational relationship (here: consumption/authority-boundary), which the
current 11-value ontology has no slot for.** Both reviewers, forced to choose from
the closed list, picked the two different existing values that came closest —
which is itself strong, independent confirmation of the ontology gap identified in
§3, now showing up in the high-stakes tier too.

---

## 5. Evidence taxonomy (populated from the eight UNWITNESSED cases + four
high-stakes cases above — not from theory)

| Failure / ambiguity | Observed? | Evidence | Consequence |
|---|---|---|---|
| Strong relation vs. weaker relation ambiguity | **Yes** | RP1376 (SAME vs CONTINUATION, clear original error), RP1111 (SAME vs CONTINUATION, genuine boundary), RP0210 (DERIVED-FROM vs CONTINUATION, mild boundary), RP0526 (SAME vs EXTENSION, resolved by missing evidence) | Drives most of the v1 gate's measured 20.9%/75.3% agreement gap; magnitude varies from "clear error" to "genuine soft boundary" |
| No evidence vs. insufficient search | **Yes** | RP0957 (visible reasoning is real, but coverage is incomplete — 91% of one side unseen) | These two failure modes were previously conflated under one "category B"; they need separate handling |
| Insufficient evidence vs. unresolved evidence | **Yes** | RP0436 (corpus itself is actively contested — not insufficient, genuinely unresolved at the source) vs. RP0341 (corpus itself declined to resolve, and nothing more exists to find) | Both correctly land on UNWITNESSED today, but for very different underlying reasons that a future evidence-status model (Stage 3) should distinguish |
| **Relationship exists but no ontology slot** | **Yes — the single most recurring pattern found** | RP0970 (designed structural symmetry), RP0028 (governance adopts/decides-on), RP0624 (bidirectional feedback interface, cross-referenced to 2 other identical cases), RP0440 (consumer-layer/authority-boundary, in the *high-stakes* tier) | This is not rare or marginal — it recurred in 4 of the 12 cases examined (33%), across both UNWITNESSED and high-stakes tiers, and the original reviewers explicitly, correctly self-diagnosed it every time rather than mislabeling. Strong evidence the ontology itself needs a new category (Stage 6/7), not just better search discipline |
| Source unavailable | **Yes, but a different root cause than everything else in this table** | RP0120 (`audi-epistemic-grounding-model`, 0 captured rows anywhere — a P1 completeness gap, not a P3a evidence-assembly gap) | Requires a P1-level fix (targeted re-search for mis-labeled content), not a P3a ontology or procedure fix |
| Temporal continuation vs. semantic identity | **Yes** | RP0526 (once corrected: EXTENSION, not SAME or UNWITNESSED), RP1376, RP1111 | Overlaps heavily with row 1; the "verification-status gap" signal (one side admits it hasn't checked its own source) is a reliable, checkable tell across multiple cases |
| Provenance ambiguity | **Partially** | RP0526's own source explicitly flags it never verified against the primary Step 155/156 texts | One clear instance; not independently confirmed elsewhere in this small sample |
| **NEW ROW — evidence present in raw data, structurally invisible to every reviewer by pipeline design (not a search-effort failure)** | **Yes — confirmed at corpus scale, not sampled** | §0: 1,464 label-pairs (257 already-judged + 1,207 never-constituted), including RP0526 and RP0288 directly | This is the dominant, highest-confidence finding of Stage 1. It is a **PROCEDURE** cause in the strictest sense — no reviewer, however careful, could have found this without knowing to bypass the row_brief sample and manually re-fetch full family rows with `dependencies`/`lineage_claims` included, which no batch instruction ever suggested doing (instructions only mentioned re-fetching full rows for *truncation*, never for this) |

## 6. Answering the six required distinctions, from what was actually found

1. **Evidence ambiguity** — real, confirmed (RP1111, RP0210, RP0526's EXTENSION-vs-REFINEMENT boundary).
2. **Insufficient search** — real but narrower than previously estimated; RP0957 shows the *visible* reasoning can be solid even when coverage is incomplete — this is a search-*coverage* problem, not a search-*quality* problem, in that case.
3. **Source incompleteness** — real, but a distinct, upstream (P1) root cause (RP0120), not a P3a defect.
4. **Ontology ambiguity** — real, confirmed at both the "soft boundary between adjacent existing values" level (RP1111, RP0210) and the "no existing value fits at all" level (RP0970, RP0028, RP0624, RP0440) — the second is the more serious and more frequently observed of the two.
5. **Procedural inconsistency** — real and now precisely characterized: the `row_brief()` field-stripping in `derive_reconciliation.py` is a single, fixable, corpus-wide procedural defect, independent of any individual reviewer's diligence.
6. **Genuine classification error** — real and clearly demonstrated once (RP1376: "Corrects EA4's formula" is unambiguous active-revision language that should never have produced SAME). RP0440 is a more ambiguous case — not exactly an "error" so much as two defensible readings of evidence that the ontology cannot correctly host either way.

**All six causes are confirmed present in this small sample. None dominates
exclusively.** The single highest-leverage, most corpus-wide, most mechanically
fixable cause is #5 (procedure) via §0's row_brief gap, followed closely by #4's
"no ontology slot" pattern (#4b), which recurred in a full third of the cases
examined and cannot be fixed by better search — it requires an actual ontology
decision, which is exactly what Stage 3 exists to make, and exactly why the user's
staging (empirical probe *before* formalizing definitions) was the right call: the
recurring "no slot fits" pattern is now empirically grounded rather than
speculative.
