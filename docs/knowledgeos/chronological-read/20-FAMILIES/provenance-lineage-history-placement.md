# provenance-lineage-history-placement

**Scope(s):** OBJECT · **Row count:** 15 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** History=(K_0,T_1..T_t) OUTSIDE K; Lineage=reachability in R_der; Pi IN A · **Aliases:** mandate s16 six tests
**Candidate group membership (NOT an identity claim):** Ungrouped (`group_ids` empty) — no mechanical signal connected this label to any other label via group_ids in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0041, scope OBJECT: "An executed placement matrix determining, test by test, which provenance/lineage/history-related questions are answerable from K=(A,R) alone: assertion provenance and lineage (via R_der reachability) are answerable; transition provenance, merge provenance, withdrawal record, contestation record, evidence provenance, and source-independence are all NOT answerable from K alone."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1708 §"lineage from K alone : [] -- empty, correctly: no ancestors ... history from K alone : NOT ANSWERABLE ... => Step 265 s265.2's base-case argument CONFIRMED by construction: at t=0 lineage is empty, so any model that DEFINES provenance as 'lineage of the history' has nothing to define it with. Provenance must be carried, not derived. This is the strongest single argument in Step 265."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1739. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (no retracted_by, no superseded_by, not contested). DORMANT is a recency heuristic (rows span batches B0041-B0042) — not a confirmed retirement of this finding.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1731, S1739 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1708 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1708 (x4), S1709 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1708, S1709, S1720, S1731, S1739 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1708 (x6), S1709, S1720, S1731 (x3) — 11 total |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Two rows carry classified rationale evidence. First, the derived placement matrix: assertion provenance (Π) is IN K and answerable; lineage is DERIVED FROM K and answerable in O(n+m); transition provenance, merge provenance, withdrawal record, and contestation record are NOWHERE in the model and not answerable; evidence provenance lives OUTSIDE K as bare ids and is not answerable; independence of two sources has NO RELATION and is not answerable [S1731]. Second, an independent re-derivation argues the t=0 counterexample is a genuine proof, not a preference: at t=0 History is empty, so an imported assertion's origin is unrecoverable under a model where provenance IS transformation history — therefore provenance must be intrinsic (Π), independently confirmed by Step 265's own statement that internal transformation history "cannot replace external provenance because it is empty at the initial state" [S1739]. `rationale_truncated_count` is 0, so no further rationale-bearing rows are known to exist beyond this capture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

**S1708** (`docs/knowledgeos/brainstorming/verification/gap-discovery/exec/exp_provenance.py`) — six executed tests, all types include EXPERIMENTAL-RESULT:
1. `types=[EXPERIMENTAL-RESULT, VALIDATION]` — TEST 1: "confirms by construction Step 265 s265.2's base-case argument: for an assertion imported at t=0, provenance is answerable directly from K (carried in Pi), lineage computed from K is correctly empty (no ancestors), but history is not answerable from K alone — K cannot distinguish 'imported at t=0' from 'asserted at t=0 by a person'; concludes provenance must be carried rather than derived from history, since at t=0 there is no history to derive it from." Lineage claim: SOURCE-CLAIMED-IDENTITY with "Step 265 s265.2 base-case argument". Experiment: hypothesis "provenance can be derived from lineage/history rather than carried directly on the assertion"; conclusion "CONFIRMED: provenance must be carried, not derived".
2. `types=[EXPERIMENTAL-RESULT, LIMITATION]` — TEST 2: "after a one-step transformation (restatus), provenance Pi is unchanged/preserved on the assertion, but who performed the restatus and when is not answerable from K, demonstrating Pi records the origin of a claim, not the origin of a state change, and Step 265's placement matrix has no row for transition provenance at all." Missing: "transition-level provenance". Experiment conclusion: "transition provenance is unrepresented".
3. `types=[EXPERIMENTAL-RESULT, DISTINCTION]`, type_signature domain "assertion id, K" codomain "list of ancestor ids", total=TOTAL, deterministic=YES — TEST 3: "lineage(c) is computable purely from K's relation set in O(n+m) time — called the one genuinely computable relation in the whole theory — but shows Pi(c) ('internal/rule') and lineage(c) (reaching back to the vendor-sourced a0) give different answers about origin, warning the corpus's use of 'provenance' for both must not be conflated." Experiment conclusion: "lineage is computable (O(n+m)); it must not be conflated with Pi".
4. `types=[EXPERIMENTAL-RESULT, VALIDATION, LIMITATION], completeness=PARTIAL` — TEST 4: "two assertions of the same proposition from different sources (vendor, scanner) are correctly representable as two distinct assertions of one proposition (validating P≠A for corroboration), but whether the two sources are genuinely independent is not answerable, since the theory has only a dependence relation (R_der) and no independence relation — absence of a derives-edge is not evidence of independence, it may simply be unrecorded." Missing: "an independence relation on evidence/assertion sources".
5. `types=[EXPERIMENTAL-RESULT, CORRECTION], completeness=PARTIAL` — TEST 5: "Step 265 s265.11's merge test passes (assertion-level provenance survives a merge, distinguishable via Pi.source), but shows the merge event itself (when it happened, who authorized it) has no record inside K at all; cross-references EXP-10b's finding that different merge orders can produce different K under a latest-wins rule, meaning the unrecorded merge order is semantically load-bearing information that is silently lost." Dual-labeled with `merge-algebra-question` (not among this batch's 20 assigned labels). Missing: "a record of the merge event: when, by whom, in what order". Lineage claims: SOURCE-CLAIMED-IDENTITY with "Step 265 s265.11 merge test"; SOURCE-CLAIMED-EXTENSION of "exp_identity.py EXP-10b".
6. `types=[EXPERIMENTAL-RESULT, VALIDATION] scope=THEORY-LEVEL` — TEST 6 (decisive test): "two different histories (H_a: assert(v); H_b: assert(s), assert(v), withdraw(scanner)) replay to structurally-equal states K(H_a)=K(H_b); of seven audit-relevant questions posed against K alone, exactly the three that are provenance/lineage questions ... are answerable, and exactly the four history-dependent questions ... are not; concludes Provenance and Lineage belong to the state K, while History does not reduce to K, making Step 247's meta-structure script-K_t=(K_t,H_t) a mathematical necessity rather than a mere convenience." Dual-labeled with `k-equality-membership-underdetermination` (not among this batch's 20 assigned labels). Lineage claim: SOURCE-CLAIMED-IDENTITY with "Step 247's meta-structure script-K_t=(K_t,H_t)". Experiment conclusion: "History does NOT reduce to the state; script-K_t=(K_t,H_t) is necessary".

**S1709** (`docs/knowledgeos/brainstorming/verification/gap-discovery/11-PROVENANCE-LINEAGE-HISTORY.md`):
7. `types=[DISTINCTION, RESTATEMENT] scope=OBJECT` — "Restates and formalizes the Provenance-vs-Lineage distinction with a concrete worked example (Pi(c)=internal/rule@2 vs lineage(c)=[a0,b] tracing back to a vendor origin), warning the corpus's use of the single word 'provenance' for both meanings is a live source of confusion."
8. `types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL` — Finding "PL-6," called the central result of the document: "the six-test placement matrix shows exactly two of eight audit-relevant concepts (assertion provenance, lineage) are answerable from K alone, while transition provenance, merge provenance, withdrawal record, contestation record, evidence provenance, and source-independence are not; concludes Step 247's meta-structure K_t=(K_t,H_t) is mathematically necessary, and criticizes the corpus for deriving this necessity by argument at Step 247 and then silently dropping H from the terminal model from Step 262 onward (relegating history to 'external'), a demotion shown by execution to cost exactly four audit-relevant questions." Lineage claim: SOURCE-CLAIMED-CONTRADICTION of "the terminal model's dropping of H from Step 262 onward".

**S1720** (`docs/knowledgeos/brainstorming/verification/gap-discovery/16-MASTER-GAP-REGISTER.md`), batch B0042:
9. `types=[EXPERIMENTAL-RESULT, RESTATEMENT] scope=THEORY-LEVEL` — "Sixteen results are registered as SURVIVED and load-bearing for any successor theory, including: provenance must be carried not derived (S-01); lineage is computable in O(n+m), the one unambiguously decidable relation in the theory (S-02); 𝒦=(K,H) is necessary, 4 audit questions unanswerable from K alone (S-03); Evidence(O,P,C,R) is a relation not a substance (S-04); admission≠truth (S-05); P≠A makes independent corroboration representable (S-06); no scalar operator suffices for evidence aggregation (S-08); K=(𝒜,ℛ) is instantiated and running — 40 assertions, 59 typed relations (S-12); two orthogonal status axes are running and schema-enforced (S-13); a real constitutional self-amendment rule exists (S-14)." Dual-labeled with `state-congruence-criterion`, `evidence-algebra-invariants`, `k-instantiated-in-ekp-empirical` (none among this batch's 20 assigned labels).

**S1731** (`docs/knowledgeos/brainstorming/verification/gap-discovery/exec/OUT-provenance.txt`), batch B0042, all `scope=THEORY-LEVEL`, `completeness=N/A`:
10. `types=[EXPERIMENTAL-RESULT]` — TEST 1: "at t=0 an imported assertion's provenance is answerable from K alone (<vendor/import@0>) but its history is not (K carries no H, so 'imported at t=0' is indistinguishable from 'asserted at t=0 by a person'); this confirms by construction Step 265 §265.2's base-case argument that provenance must be carried, not derived from an empty history — the strongest single argument in Step 265."
11. `types=[EXPERIMENTAL-RESULT, DISTINCTION]` — TEST 3: "lineage (reachability in R_der) is derivable from K alone and is the one genuinely computable relation in the whole theory (O(n+m)), but Π and lineage give different answers about an assertion's origin (Π says 'internal/rule', lineage traces to the ultimate vendor source) and the corpus uses 'provenance' for both in different steps — they must not be conflated."
12. `types=[EXPERIMENTAL-RESULT]` — TEST 6 (decisive): "two histories (H_a: assert(v); H_b: assert(s); assert(v); withdraw(scanner)) produce structurally identical current states K(H_a)=K(H_b)=['v']. From K alone, three questions are answerable ... and four are not ... Conclusion: Provenance and Lineage belong to the state; History does not reduce to the state; therefore Step 247's 𝒦_t=(K_t,H_t) is necessary, not merely convenient." Experiment conclusion: "𝒦=(K,H) is necessary, not merely convenient, confirming Step 247".
13. `types=[EXPLANATION]` — see Rationale section above (the derived placement matrix summary).

**S1739** (`docs/knowledgeos/brainstorming/verification/independent/09-PROVENANCE-LINEAGE-ATTACK.md`), batch B0042, `scope=THEORY-LEVEL`:
14. `types=[ARGUMENT], completeness=COMPLETE` — see Rationale section above (the t=0 counterexample as "a proof, not a preference").
15. `types=[DISTINCTION], completeness=N/A` — "Four provenance-related objects are verified distinct, each independently anchored: Π (AssertionOrigin, intrinsic, t=0-safe), EvidenceProvenance (chain of custody of one evidence item, a required field of E_q per 230.15), History(T) (ordered transformation record, executed History(K)≠K), and MessageProvenance ((correlationId,causationId), measured in 18 PHP files under app/Contexts/**) — the only place in the theory where a terminology overload was fully resolved, offered as the model for the other six overloaded terms."

## Notes for P3
- This is an unusually strong, multiply-independently-corroborated evidentiary base: the same six-test placement matrix and its decisive TEST 6 finding appear near-verbatim across four separate source documents (S1708 the executable script, S1709 a write-up, S1731 the script's text output, and S1720's survivor register), plus a fully independent re-derivation (S1739) reaching the same conclusion via a different argument path. P3 should treat this as one of the more solidly evidenced findings in this batch.
- S1709's row 8 (Finding PL-6) contains an explicit governance criticism: the corpus derived K_t=(K_t,H_t)'s necessity by argument at Step 247, then silently dropped H from the terminal model from Step 262 onward. This internal corpus tension (argued-necessary vs. later-dropped) is worth flagging to P3 as a load-bearing finding about the corpus's own consistency, not merely about this label's content.
- Three rows (S1708's TEST 5, TEST 6; S1720's row) are dual-labeled with other working_labels not in this batch's 20 assigned labels (`merge-algebra-question`, `k-equality-membership-underdetermination`, `state-congruence-criterion`, `evidence-algebra-invariants`, `k-instantiated-in-ekp-empirical`) — flagged for P3's awareness of adjacent material.
