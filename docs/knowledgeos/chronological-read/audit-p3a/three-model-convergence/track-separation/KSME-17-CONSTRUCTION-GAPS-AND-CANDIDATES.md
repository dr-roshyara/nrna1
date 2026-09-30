---
source_track: TRACK-A-CONSTRUCTION (explicit — never SOURCE_GROUNDED)
input_artifacts: [KSME-17-HISTORICAL-FRAMEWORK]
derived_from: [.claude/scripts/knowledgeos-ksme/ess.py]
cross_track_dependency: none
---

# KSME-17 — Construction Gaps and Semantic Candidates

## Derivation-first compliance check (applied retroactively per the user's standing rule)

Before treating any gap below as a construction target, the user's Derivation-first protocol requires:
source search → derivation reconstruction → derivation validation → classification (SOURCE-DERIVED /
CORPUS-DERIVABLE / DERIVED-BY-RECONSTRUCTION / UNDERIVED) → only then introduce something new. KSME-16's
own exhaustive, 564-file, direct-and-indirect-witness search was already precisely this search, performed
*before* any construction was attempted, for exactly these three gaps (it is the specific reason KSME-17
exists at all). Re-applying the classification explicitly:

| Gap | Source search performed | Classification |
|---|---|---|
| G1 (`EvidenceAdded`/`ConflictResolved` "may") | KSME-16's full corpus sweep, both keyword-direct and semantic-witness-indirect, found no completion of the "may" clause anywhere in the 564-file primary corpus | **UNDERIVED** — premises (the event types, the general δ shape) are present, but no corpus passage supplies the missing update rule, distributed or otherwise |
| G2 (Assertion/retire field construction) | Same sweep; no file gives `Assertion` a closed field list beyond the five source-named fields | **UNDERIVED** |
| G3 (Rollback provenance representation) | Same sweep; Step 016's bitemporal refinement adds `T_v/T_o/T_k` but never a provenance-distinguishing field for rollback specifically | **UNDERIVED** |

None reach `CORPUS-DERIVABLE` or `DERIVED-BY-RECONSTRUCTION` — the search found no distributed premises
anywhere else in the 564-file corpus that could be assembled into a derivation without adding a new
substantive premise. Construction is therefore the correct next step per the protocol's own step 5, not a
shortcut around it.

## G1 — "MAY be updated" (two instances)

The historical framework leaves `EvidenceAdded` and `ConflictResolved`'s epistemic-status effects as
prose ("may"), not a computable rule. This is not merely an implementation omission — mathematically, it
means the framework as stated defines something closer to a **relation** (`δ(K,e)∈{K_1,K_2,...}`) than a
function, exactly as the user's own analysis identified.

**Candidates constructed** (`ess.py`), each tagged CONSTRUCTION, none claimed as source fact:
- `evidence_added_C1`: add evidence only, Sigma untouched (the minimal, "may" reads as "never" here).
- `evidence_added_C2`: add evidence + a disclosed deterministic update rule (`|E|≥2 ⟹ Sigma="Strong"`) —
  one plausible completion among many, not the only one.
- `conflict_resolved_C1`: record the resolution relation only, assertion states untouched.
- `conflict_resolved_C2`: for the source's own named "evidence-based" strategy specifically, a disclosed
  rule retracts the assertion with fewer evidence items.

## G2 — Implicit object construction

`Assertion` is never given a closed field list. `ess.py`'s `Assertion` carrier keeps only source-named
fields (`P, Σ, E, τ, Π`) plus two CONSTRUCTION additions required to make set membership and "retirement"
well-defined at all: an `id` (identity) and a `retired: bool` flag. Neither is invented beyond what's
strictly needed to execute the source's own stated effects ("retires old assertion").

## G3 — Rollback provenance

**Candidates constructed**:
- `rollback_C1_no_marker`: returns a state structurally identical to the historical target — tests
  whether the source's "different provenance" claim is representable at all without new construction.
- `rollback_C2_with_marker`: adds an explicit `rollback_marker` field (CONSTRUCTION, not in any
  source-defined tuple) recording the rollback event, making the distinction structurally real.

## Candidates NOT constructed (explicitly out of scope for this pass)

Nondeterministic/nondeterministic-choice semantics for G1 (`δ(K,e)∈{K_1,K_2}` as a genuine relation,
rather than picking a deterministic completion) — BSE v1 is deterministic-only by design
(`KSME-15-ADDENDUM-HARDENING.md`); testing this would require the deferred v2 extension. Named, not built.

See `KSME-17-EXECUTABLE-SEED-AND-MATERIALITY.md` for the behavioral-materiality verdict on each candidate
pair above.
