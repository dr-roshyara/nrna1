---
source_track: TRACK-A-CONSTRUCTION
input_artifacts: [KSME-17-HISTORICAL-FRAMEWORK, KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES, KSME-17-EXECUTABLE-SEED-AND-MATERIALITY, KSME-17-STEP32-COMPATIBILITY, KSME-17-MINIMALITY]
derived_from: [ess.py, ksme17_materiality.py, ksme17_minimality.py, bse.py]
cross_track_dependency: none
---

# KSME-17 — Minimal Executable Semantic Seed: Final Report

## Permanent architectural principle (restated, honored throughout)

$$
\text{Recovery} \neq \text{Construction} \neq \text{Validation} \neq \text{Canonicalization}
$$

KSME-16 completed recovery. This pass performs construction, explicitly labeled throughout (every field
and rule in `ess.py` tagged SOURCE or CONSTRUCTION inline). BSE performs validation. No canonicalization
is attempted or implied anywhere in this report.

## Derivation-first compliance

Per the user's mid-pass standing rule: every gap constructed on was first checked against KSME-16's own
exhaustive 564-file search (both direct-keyword and indirect-semantic-witness) and classified `UNDERIVED`
— not `CORPUS-DERIVABLE` or `DERIVED-BY-RECONSTRUCTION` — before any construction began. See
`KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES.md`'s compliance table.

## Answers to the 17 required questions

1. **What exactly is historically established?** `δ:𝒦×ℰ⇀𝒦`, `Pre`/`Post` invariants, event/command
   separation, append-only history, `Replay` — see `KSME-17-HISTORICAL-FRAMEWORK.md`. All SOURCE-tagged.
2. **What exactly was constructed?** A minimal `K=(A,R,rollback_marker)` carrier, assertion identity/
   retirement fields, and disclosed completion rules for `EvidenceAdded`/`ConflictResolved`/`Rollback` —
   all CONSTRUCTION-tagged in `ess.py`.
3. **Which construction decisions were unavoidable?** Assertion identity (`id`) and the `retired` flag —
   without them, "retires old assertion" and set membership are not even well-defined.
4. **Which alternatives existed?** At least 2 per ambiguous operation (documented in
   `KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES.md`); a third (genuine nondeterministic/relational semantics
   for G1) was named but not built, since BSE v1 is deterministic-only by design.
5. **Which alternatives are behaviorally equivalent?** `Rollback` C1 vs C2, under the disclosed base
   observation set (no provenance inspection) — immaterial, exact result, horizon 3.
6. **Which alternatives are behaviorally distinguishable?** `EvidenceAdded` C1 vs C2 (material);
   `ConflictResolved` C1 vs C2 (material); `Rollback` C1 vs C2 under an augmented, provenance-aware
   observation set (material).
7. **Which ambiguities are Kernel-material?** `EvidenceAdded`/`ConflictResolved`'s "may"-clauses are
   material under any observation regime that inspects epistemic status — a real, disclosed finding, not
   cosmetic. `Rollback`'s provenance claim is material *only if* a provenance-observation capability is
   separately constructed — the corpus's own claim requires an observation the corpus never supplies.
8. **What is the smallest executable semantic seed?** `K=(A,R,rollback_marker)`, 5 operations (7 counting
   both candidates for the 2 ambiguous ones) — see `ess.py`.
9. **What is its behavioral partition?** **Now completed** (`KSME-17-MINIMALITY.md`) — the earlier closure
   bug (unbounded `tau` growth feeding a non-deterministic global-counter identity) was found and fixed:
   `fresh_id()` is now content-addressed, and the illustrative regime's `revise` operation now uses a fixed
   `tau`, closing the reachable space at exactly 12 states. Behavioral partition: **4 classes, sizes
   `[1,1,2,8]`**, exact and reproducible.
10. **Is the abstraction congruent?** Not separately tested this pass — no abstraction `F` was proposed to
    test congruence against; the materiality experiments tested raw-state behavioral equivalence directly.
11. **What is component minimality?** **Computed**: exhaustive subset search over `{P,Σ,E,τ,Π}` found the
    minimal sufficient set is **`{P,E}`** — `Σ`, `τ`, and `Π` are all droppable in this bounded, `C1`-only
    regime. `Σ`'s droppability is a real, disclosed artifact of the `C1` candidate choice (which never
    modifies `Σ`), not a universal claim about epistemic status.
12. **What is partition minimality?** See Q9: 4 classes, `[1,1,2,8]`.
13. **What is representation minimality?** **Computed**: among 4 disclosed candidates, `F_no_tau_pi`
    (`P,Σ,E`, cost 3) is cheapest-sufficient; `F_P_Sigma_only`/`F_P_only` are insufficient (missing `E`).
    Notably narrower than component minimality's exhaustive `{P,E}` finding — a real, computed
    illustration of why the two notions are kept distinct (representation minimality is bounded by which
    candidates are disclosed; component minimality searches exhaustively).
14. **Which results are source facts?** Everything in `KSME-17-HISTORICAL-FRAMEWORK.md`.
15. **Which results are constructions?** Everything in `ess.py`'s `Assertion`/`K` carrier and all 7
    operation-candidate implementations; every field/rule tagged inline.
16. **What remains unresolved?** The Step-32 compatibility question (H1/H2/H3,
    `KSME-17-STEP32-COMPATIBILITY.md`, best-supported but unproven H3); the nondeterministic/relational
    alternative for G1, named not built; extending the minimality computation to the `C2` candidates and
    to `ConflictResolved`/`Rollback` (currently only `add_evidence`/`revise`/`rollback_to_seed` under `C1`
    were closed and tested).
17. **Does this seed justify a larger KnowledgeOS semantic regime?** Still not yet, but for a sharper
    reason now that minimality is actually computed rather than deferred: the bounded `C1`-only regime
    tested is small and its own scope-limits are now precisely named (one candidate per ambiguous
    operation, a 1-item evidence/revision alphabet, 3 of 5 operations). Extending this to the full
    operation set and to the `C2` candidates — now that the closure/identity machinery is fixed and known
    to work — is the well-defined next step, not a vague "more research needed."

## Success condition assessment

Per the commission's own §24: KSME-17 is successful only if historical framework + explicit construction
+ exact behavioral analysis together produce a small, reproducible, falsifiable regime. **Achieved**: the
construction, behavioral-materiality analysis, and now the full minimality computation (component/
partition/representation, all three kept explicitly distinct per the commission's own requirement) are
real, exact, and reproducible — every result in `KSME-17-EXECUTABLE-SEED-AND-MATERIALITY.md` and
`KSME-17-MINIMALITY.md` was independently computed, not asserted, with two real bugs (non-deterministic
identity, unbounded closure) found and fixed rather than papered over.

## What this report does not establish

No KnowledgeOS Kernel named, selected, or ranked. `ESS≠KnowledgeOS Kernel`, stated explicitly. No
construction promoted to SOURCE tier anywhere. Step 32's relationship to this seed remains unresolved and
does not gate this construction, per explicit instruction.
