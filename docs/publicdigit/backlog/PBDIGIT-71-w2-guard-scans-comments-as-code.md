# PBDIGIT-71 — W-2 structural guard scans comments as if they were code

**Type:** Verification hardening (test infrastructure) · **Raised:** 2026-08-17, during `EM-IMPL-002` GREEN-2 · **Status:** OPEN · **Priority:** low (correctness of the guard's *reach*, not of the architecture it protects)

## The observation

`tests/Unit/Contexts/Election/OperatingCoreApplication/StructuralApplicationGuardsRedTest.php` enforces the forbidden shapes W-1…W-10 by **raw source-text scanning**. The architectural rule and the implemented check therefore differ in reach:

| | |
|---|---|
| **Architecture rule (W-2 / RED-2)** | the expression path must not *consult* the election's operational overlay — a guard there would be Reading B, which the PO did not choose |
| **Implemented check** | the file's source text must not *contain* `Inoperative`, `OperationalCondition` or `unableToFunction` — **including inside comments and docblocks** |

**Evidence (real, not hypothetical):** during GREEN-2 the UC-1 handler's explanatory comment — which stated that the overlay is deliberately *not* consulted — tripped `test_w2_the_expression_path_carries_no_inoperative_guard`. The code consulted nothing; only the prose named the concept.

## Why it matters

The UC-1 handler currently **cannot document the rule it obeys using the rule's own vocabulary.** That is a small but real cost: the clearest available explanation of a governance-critical absence has to be paraphrased, and a future author may reintroduce the plain wording and read the resulting failure as an architecture violation rather than a scanner artifact.

The same pattern applies to the other lexical guards (W-3's arithmetic tokens, W-5's time tokens, W-6's lifecycle tokens, W-7's escalation tokens).

## What was deliberately NOT done

**The guard was not changed.** Ruled at GREEN-2 review: verification infrastructure is not modified mid-flight, because changing the measuring instrument during implementation introduces noise exactly where the causal proof (one use case → its tests green) must stay clean. GREEN implementations obey the guard as written; the handler's comment was rephrased instead.

## Proposed work (needs its own authorization; not part of `EM-IMPL-002`)

1. Normalize source before lexical scanning — strip comments and docblocks (e.g. `token_get_all()`, dropping `T_COMMENT`/`T_DOC_COMMENT`) and scan the code tokens only.
2. Apply the normalization to every lexical guard in the file, not just W-2, so the guards' reach becomes uniform.
3. Keep the guards **failing-first by construction**: the granted-surface anchor must remain, so a normalized scan can never pass vacuously.
4. Re-run the full application suite plus the frozen core suite; the guard count and outcomes must be unchanged (a normalization that changes a verdict is a finding, not a refactor).

## Explicitly out of scope

Weakening any forbidden shape. This item changes **where the scanner looks**, never **what is forbidden**.

## Traceability

`EM-IMPL-002` GREEN-2 authorization (condition 5, registered) · developer guide `developer_guide/election_operating_core/03_step_application_layer_green2_uc1.md` · RED commit `1f4b4c5f` (the guards as accepted) · the Phase-1 refinement notes already recorded in the guard file's header.
