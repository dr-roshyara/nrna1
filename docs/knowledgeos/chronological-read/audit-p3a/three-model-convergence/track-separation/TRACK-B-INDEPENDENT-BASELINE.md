---
source_track: TRACK-B-GAP-DISCOVERY
input_artifacts: [verification/gap-discovery/exec/kos_kernel.py, verification/gap-discovery/exec/exp_congruence.py, verification/gap-discovery/exec/exp_identity.py, verification/gap-discovery/second-order/exec/so_model.py, verification/gap-discovery/second-order/exec/so_exp01_congruence_matrix.py]
derived_from: [gap-discovery's own citations to phase_measure_theory Steps 256/257/259/262/263/265/267/269 -- citation only, no code/state-shape import from Track A]
cross_track_dependency: none
---

# Track B — Independent Baseline (Gap-Discovery session)

**Reclassification note**: every number in this document was originally
computed and reported under `BC-02.21`/`BC-02.22` (KSME-04/05), presented at
the time as a continuation of the Track-A/BC-02.x reconstruction. It is not.
It is Track B's own executable construction, extended. The computation is
unchanged and was independently re-verified (re-executed, byte-identical) at
each step — only the epistemic label changes here.

## B1. State space

`so_model.py`'s `Obj`/`State`: `Obj(id, content, origin, status)`,
`State(objs: FrozenSet[Obj], sup, merged)`. Field shapes cite `phase_measure_
theory` sections 256/257/259/262/263/265/267/269 (existence citation only —
the concrete Python dataclass is this track's own construction, self-
disclosed as such). A parallel, **not-yet-reconciled** second carrier exists
within Track B itself: `kos_kernel.py`'s `Assertion=(id,P,e,c,t,Π)` over
`K=(𝒜,ℛ)`. These two Track-B carriers have not been checked against each
other for structural correspondence — an open item *within* Track B, prior
to any cross-track question.

## B2. Observation

Six candidate abstractions (`so_model.py`'s `ABSTRACTIONS`): `F1`
content-only, `F2` content+status, `F3` id+content+status (no provenance),
`F4` `K=(𝒜,ℛ)` [self-named "TERMINAL"], `F5` `F4`+merge-source, `F6` full
state. **Note on `F4`'s name**: this is Track B's own internal label,
independently chosen — its resemblance to Track A's `KO-007`/`KO-009`
notation is a naming coincidence pending verification (see Track A baseline
§A1), not treated as established correspondence anywhere in this document.

## B3. Operations

8 named operations, each with 2 disclosed variants (`"blind"`/`"sensitive"`):
`Add, Remove, Revise, Transform, Supersede, Merge, Withdraw, Reject`.
Empirically verified this pass (not merely read from source) that only 4 are
actually variant-sensitive (`Revise, Transform, Supersede, Merge`) — `Add,
Remove, Withdraw, Reject` accept but never read the variant parameter (0/N
mismatches across all 208 seed states and arguments, vs. >0 for the other
four). **The nominal `2⁸=256` model space is exactly `2⁴=16` behaviorally
distinct models**, proven not assumed.

## B4. Transition semantics

**`EXECUTED`, self-disclosed non-authoritative** (`kos_kernel.py`'s own
docstring: *"Nothing here is architecture. Nothing here is authoritative. It
is a witness."*). Real, total (within domain), deterministic `T:State×Op×
variant→State` for the 8 (`so_model.py`) or 4 (`kos_kernel.py`: `assert,
relate, withdraw, restatus`) named operations. This is Track B's central
contribution and the reason the whole KSME-04/05 computation was possible —
but it is **not** a claim that these are the real KnowledgeOS operation
semantics; only that Track B constructed one disclosed, executable, cited
candidate family.

## B5. Equivalence — exact results

Reachable closure `|E|=41,820` (exact BFS, not sampled) from the 208-state
seed domain `so_exp01_congruence_matrix.py` already used.

| Observation | `K_O` classes | `K_R` classes (exact, all 16 models) |
|---|---|---|
| F2 content+status | 1,486 | **17,129** |
| F3 id+content+status | 2,836 | **17,129** (identical to F2 — observation-choice invariance, confirmed) |
| F4 `K=(𝒜,ℛ)` [Track B's own label] | 27,398 | **27,398** (already fully robust under all 16 models) |

**Congruence** (single-step, `so_exp01_congruence_matrix.py`, independently
re-executed, byte-identical): of the 6 abstractions, only `F4`/`F5`/`F6`
pass on every operation and variant; `F1`/`F2` fail broadly; `F3` fails
exactly when a provenance-sensitive operation is admitted (a real,
executed operation-universe-dependence demonstration).

**`F4` vs. `K_R(F2)`, checked directly (not inferred from cardinality)**:
`F4` **`REFINES`** `K_R(F2)` cleanly — 0 violations over all 41,820 states.
`F4` is sufficient (draws every robustly-necessary distinction) but
demonstrably **not behaviorally minimal** — it carries `27,398−17,129=
10,269` distinctions beyond what any of Track B's 16 admissible models ever
requires.

## B6. Interaction / relevance findings (exact, computed)

- `Merge`'s variant choice **never** changes the class count, despite being
  locally variant-sensitive (192/192 single-step mismatches) — a real
  "locally sensitive, globally irrelevant" result.
- `Transform`'s marginal relevance is **conditional on `Revise`**: relevant
  when `Revise=blind` (+886 classes), irrelevant once `Revise=sensitive`
  (which subsumes it) — a genuine, computed interaction effect.
- `Revise` and `Supersede` are each robustly relevant on their own.

## B7. Minimality

Not cardinality-minimal by assumption. `K_R(F2)=K_R(F3)=17,129` is the exact
fixed point of Track B's own 16-model family — a different, independently
constructed `𝔐_T` could in principle produce a different number. `F4` is
confirmed sufficient, confirmed not minimal (§B5).

## B8. Computability

Fully computable, exactly, throughout — `|E|=41,820` states, 16 real models,
no sampling, no ML, no statistics (all judged unnecessary at this scale).

## Two bugs found and fixed in the open during this computation (unchanged, restated for the record)

1. `so_exp01`'s 208-state seed domain is not closed under `Add`/`Merge`
   (introduces a new id `"z"`) — required computing the true BFS reachable
   closure before any multi-step (`T*`) refinement could be valid.
2. `args_for()` iterates a `frozenset` whose hash includes `origin`, so two
   states differing only in `origin` could iterate their objects in different
   orders, misaligning signature tuples positionally and producing spurious
   splits — fixed by sorting signature entries into canonical order before
   comparison. Confirmed via a minimal 921-state reproduction before and
   after the fix.

## Open items (Track-B-internal, not cross-track)

Raw-state-field-level (not just operation-level) relevance lattice not
computed. Whether a differently-constructed `𝔐_T` changes `K_R^B`: untested.
10 named operations (`Validate, Promote, Reintroduce, Replay, Assess,
Authorize, Determine, Derive, Qualify, Split`) have no Track-B implementation
at all. The two internal Track-B carriers (`so_model.py`'s `State` vs.
`kos_kernel.py`'s `K=(𝒜,ℛ)`) have not been reconciled with each other.
