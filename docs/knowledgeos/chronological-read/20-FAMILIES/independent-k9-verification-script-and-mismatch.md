# independent-k9-verification-script-and-mismatch

**Scope(s):** `OBJECT` · **Row count:** 2 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Closure(K_4)`, `Closure(K_9)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0843**: [`independent-k9-verification-script-and-mismatch` · `k9-closure-computation-script` · `k9-guardrail-and-headline-negative` · `qualify-closure-robustness-answered-no`] — labels share the notation 'Closure(K_9)'
- **G0845**: [`independent-k9-verification-script-and-mismatch` · `k9-guardrail-and-headline-negative`] — labels share the notation 'Closure(K_4)'
- **G1083**: [`independent-k9-verification-script-and-mismatch` · `k9-verification-mismatch-and-ocore-fragility`] — working_label token overlap Jaccard=0.50 (shared tokens: ['and', 'k9', 'mismatch', 'verification'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope OBJECT): An independent re-implementation (via direct import of the original source module) verifying the K9-closure document's seven published numeric claims; its paired output (S2799) finds one of those claims does not reproduce.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2799] §"Closure(K_4) size 18 of 29 blocked 15 unblocked 3. published: 18 constructs / 15 blocked -> REPRODUCED. ... Closure(K_9) size 20 of 29 blocked 17 unblocked 3. published: 20 constructs -> REPRODUCED. ... published: INCOMPARABLE -> REPRODUCED. ... reached ONLY via its own seed. The degeneracy warning "
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2800] §"Independent verification of 2026-09-06-KOS-K9-CLOSURE-CONDITIONAL-DEPENDENCY-COMPUTATION.md. Uses the SOURCE program's graph and closure procedure by direct import -- not the review lane's transcription -- so the check is independent of that transcription. Read-only. Asserts nothing about kernel min"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S2800`. Candidate lifecycle: **ACTIVE**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **ACTIVE** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2800 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2799]` types=[VALIDATION] scope=OBJECT — "Sections 1-5: the independent direct-import verification reproduces the published K4 control (18 of 29 constructs, 15 blocked), the published K9 closure under the review lane's declared mapping (20 of 29, 17 blocked, from 10 named seeds), the published incomparability finding (neither Closure(K4) nor Closure(K9) is a subset of the other, with K4\K9={History,Rejection,Replay}, K9\K4={Assessment,Authority,Authorization,Determination,Qualification}, 15 in the intersection, 6 reached by neither), confirms the Qualification degeneracy warning is correct (Qualification is reached only via its own C-7 seed, not independently), and reproduces all five published one-at-a-time mapping-sensitivity closure sizes (C-1->16, C-2->20, C-4->21, C-5->20, C-6->22)." (anchor: "Closure(K_4) size 18 of 29 blocked 15 unblocked 3. published: 18 constructs / 15 blocked -> REPRODUCED. ... Closure(K_9) size 20 of 29 blocked 17 unblocked 3. published: 20 constructs -> REPRODUCED...")
- `[S2800]` types=[EXPERIMENT, VALIDATION] scope=OBJECT — "Header and structure: implements an independent verification of the K9-closure conditional-dependency computation document by directly importing the original minimum_implementable.py module's graph G and closure() function (bypassing any risk of a transcription error in the review lane's own copy of that graph), then re-derives K4 and K9 seed sets and reruns seven numbered checks against the document's own published numbers: (1) K4 control reproduces 18/29 with 15 blocked; (2) K9 closure under the declared mapping; (3) incomparability of Closure(K4) and Closure(K9); (4) whether Qualification's presence in Closure(K9) is definitional (tested by removing C-7's seed and rechecking); (5) one-at-a-time mapping sensitivity against five published per-row closure sizes; (6) the full product over all five perturbable rows, checking the published 15..22 range claim; (7) whether O_core enters the closure through a single row only." (anchor: "Independent verification of 2026-09-06-KOS-K9-CLOSURE-CONDITIONAL-DEPENDENCY-COMPUTATION.md. Uses the SOURCE program's graph and closure procedure by direct import -- not the review lane's transcri...")

## Notes for P3
- This label carries 3 candidate-group memberships beyond G0759 (see group list above) — a comparatively dense set of mechanical cross-links, which may make it a useful anchor point for P3 reconciliation, but none of these links are identity claims and each must be assessed on its own evidence.
