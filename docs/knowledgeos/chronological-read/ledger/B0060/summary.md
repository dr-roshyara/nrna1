# B0060 Extraction Summary

**Batch:** B0060 (40 files, S2478-S2519, with S2493 and S2515 absent per print_batch.py)
**Commit:** 39fdef05dc027c6264b6c349a26362a59191a35f
**Files processed:** 40/40 (all read in full; none FIREWALL-LIMITED)
**Contributions extracted:** 520
**New index proposals:** 10

## Content overview

This batch covers 2026-09-02 brainstorming and executed-experiment material, and splits into two
largely separate, occasionally colliding tracks:

1. **The main "Contr/Composition/Decision" research lane** (S2478-S2487, S2490-S2492, S2494-S2498,
   S2502-S2503, S2505-S2510, S2516, S2518): a disciplined, self-correcting HPA-supervised programme
   that (a) revises the KnowledgeOS TODO register (A-J) and corrects five prior dependency
   assumptions; (b) runs KR-CONTR-MODELS-2026-09, finding there are two contradiction models not
   three (M3≅M4 by relabeling, MD refuted) and that the fourth-value question is a corollary of
   composition, plus a "masking" discovery (D-0's earlier model agreement was an artifact of
   existentially-absorbing Zero readings); (c) runs KR-CONTR-EVAL-2026-09, proving the contradiction
   obstruction is structural not cardinal (no flat domain of any cardinality is adequate; chi ranges
   3-21 depending on the required-distinction set) and that a structured (value,reason) representation
   is the surviving candidate, with reason confirmed as a genuine non-trivial field (not a disguised
   state identifier); (d) runs KR-COMP-2026-09 and KR-COMP-SEP-2026-09, finding the criteria select a
   frame qualifier (phi contains {time,context}) rather than a composition rule, eliminate last-wins
   twice (a C1 semantic failure and a deeper well-definedness/order-dependence failure), and surface a
   new C7 frame-refinement-invariance criterion that makes majority's admissibility depend entirely on
   an unresolved question ("is a frame a representational partition or a semantic context?"); (e)
   issues two formal decision records (DECISION-01 non-evidential invariance, DECIDED; DECISION-02
   semantic status of the frame, REQUIRED) and defers the majority-vs-intraframe-only choice
   (Z-DECISION), including an explicit, retained-not-deleted withdrawal of the lane's own earlier
   premature inference favoring intraframe-only. The lane's final synthesis (S2518) reframes the whole
   research object as Required-Distinctions -> Evaluation-Representation -> Typed-Boundary ->
   Composition -> Zero, with Contradiction as one required distinction, not the root, and finds
   boundary ambiguity affects every Standing class (not just the empty one).

2. **External-literature extraction and a parallel, less-rigorous "Gap Theory"/reconciliation thread**
   (S2488-S2489, S2499-S2501, S2504, S2511-S2514, S2517, S2519): disciplined [EXT]->[PROP] extractions
   from Priest (non-classical logic), Rice (legal argumentation), Shapiro (varieties of logic), Ziem
   (frame semantics), and Brachman & Levesque (KR&R), mostly corroborating the main lane's findings
   from independent domains (representation determines available distinctions; reject-H1 does not
   imply accept-H2; invalid does not imply false). Separately, a parallel thread (S2499-S2501, S2513,
   S2514, S2517) uses an different, non-standard 11-tuple K_t formulation (traced to the actual v1.1
   theory in the reconciliation-strategy document S2512) and proposes a "Gap Theory v1.0" (Delta_t as
   unsatisfied-requirement set, Zero as Delta=empty) that is recommended for freezing -- in direct,
   unreconciled tension with the main lane's finding that Zero<=>Delta=empty is ambiguous and was
   retired in favor of boundary-object closure. This thread partially self-corrects at its end (S2519
   explicitly rejects Sat=Entailment and Zero=CWA, which S2517 had proposed as confirmations two files
   earlier in the same thread).

## Key cross-cutting findings

- The corpus's "evidence != decision" discipline is exercised concretely six times in this batch
  (factivity/R1, adopting M3≅M4, adopting the boundary object 𝓑, DECISION-01, and the deferred
  majority-vs-intraframe-only choice), each explicitly logged as a category the research lane
  recognizes it must not try to resolve by further experiment.
- A recurring "projection vs. structure" diagnosis gains two new instances: the value-set of a state
  (KR-CONTR) and the frame-count-as-recording-artefact finding (C7) -- both explicitly tied back to
  the same corrected "induced equivalence relation" formal object introduced in the reviewed
  structures-first proposal (S2487, S2516).
- Genuine corpus inconsistencies were identified and recorded without being resolved (per protocol):
  (a) S2485's "the burden lies between" framing directly conflicts with S2481's explicit withdrawal of
  that same claim; (b) the "Gap Theory" thread's Zero=Delta-empty/Zero=CWA/Sat=Entailment proposals
  directly conflict with the main lane's Zero-retired-for-ambiguity and Sat-semantically-incoherent
  findings; (c) S2501's "external writeup" of KR-CONTR-FDE-2026-09 reports different, cleaner numbers
  and a settled Contr definition, in tension with the internal lane's actual FDE harness result
  (summarized authoritatively in S2509/S2510 as E-FDE-1..6) and the main lane's insistence that Contr
  remains fully OPEN.

## Self-check results

All six mandatory self-checks passed:
1. TOTAL INVALID ROWS: 0
2. TOTAL UNREGISTERED LABELS: 0
3. valid lines: 520 (all valid JSON)
4. TOTAL INCONSISTENT ROWS: 0
5. TOTAL FIELD-SHAPE ERRORS: 0 (40/40 files.jsonl rows correct)
6. TOTAL SCOPE ERRORS: 0

Additional self-verification performed: no null/empty anchors; all `assumptions` entries are objects;
`explicit_date` fields are string-or-null throughout. files.jsonl source_id set verified to exactly
match the expected 40-id set in expected order (S2478-S2519 minus S2493 and S2515).

## New objects proposed (10)

knowledgeos-abcdefghij-todo-register (POSSIBLY:zero-research-roadmap-post-zero-concept),
kernel-not-selectable-verdict, optimized-model-layered-proposal
(POSSIBLY:structure-first-not-projection-first-principle), channel-relation-independence-concept,
kr-contr-2026-09-experiment-protocol, kr-contr-two-models-result, evaluation-masking-phenomenon,
w-kr-contr-eval-structured-evaluation-result, kr-comp-sep-rule-separation-result,
gap-theory-v1-delta-set-definition (POSSIBLY:v1-1-sim-theory-gap-register).
