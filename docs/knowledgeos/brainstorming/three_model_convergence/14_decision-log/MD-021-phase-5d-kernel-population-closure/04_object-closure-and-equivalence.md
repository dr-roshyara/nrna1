# Phase 5D — Object Closure and Equivalence

## Central research question, answered

**Is the Phase-5C 13-object register sufficiently complete? No — additional objects discovered.**
At least 6 new, distinct, well-evidenced Kernel-object candidates exist in the P1 population that
were not in Phase 5C's register (`03`). **Outcome: Additional objects discovered — not
closure-supported, not fully indeterminate.** This is a **document-population** finding: it says the
13-object register does not exhaust the 116-document P1 census; it does **not** say the corpus
"actually contains 19 objects" — several of the 6 new candidates remain themselves candidates for
merger with each other or with the 13, pending equivalence work below, and P2/P3's own populations
were only sampled, not censused (`01`), so even 19 is not asserted as a ceiling.

## KERNEL-OBJ-04 attribution finding (audit finding only — Phase 5C's register is NOT modified)

**Finding**: Phase 5C's `02_kernel-object-register.md` states KERNEL-OBJ-04's "first evidenced
occurrence" is seq 0150, with source documents 0150 and 0157. This phase's P1 digest read found:

- **seq 0150**'s actual content is DeepSeek's four competing Kernel *hypotheses* (Admission Boundary /
  Epistemic Decision Core / Knowledge Consistency Boundary / Constitutional Transition Engine) — no
  "six-part aggregate" appears in its digest content.
- **seq 0156**'s content ("Round 8... DeepSeek fully answers the 12 Fundamental Kernel Questions...
  critique explicitly rejects DeepSeek's Q12 conclusion (KnowledgeAggregate as the smallest
  consistent...)") directly names the KnowledgeAggregate conclusion that seq 0157 (the already-known
  falsification target) falsifies.
- **seq 0157**'s own content ("the six-part aggregate is falsified") is consistent with 0156 being its
  direct predecessor/target, not 0150.

**Verdict**: **`CONFIRMED, evidence level 1`** — raw-source spot-check performed (see `09`) directly
against the three source files:
`kernel/20260823-110950-kernel-domain-level-brainstorming-admission-hypotheses.md` (seq 0150),
`kernel/20260823-114358-deepseek-kernel-twelve-fundamental-questions.md` (seq 0156), and
`kernel/20260823-114530-aggregate-hypothesis-falsification-atomicity-vs-relatedness.md` (seq 0157).
Findings: (a) seq 0150 contains 0 occurrences of a six-part invariant and no "KnowledgeAggregate"
proposal — its content is the four Admission-Boundary-family hypotheses, unrelated to the aggregate
question; (b) seq 0156 raw text (lines 507, 561, 1025) states verbatim: *"The smallest consistency
boundary is the KnowledgeAggregate, which contains: Identity, Claim, Evidence References,
Justification, Epistemic State, Confidence, History, Relationships"*; (c) seq 0157 raw text (lines
19–20) states verbatim: *"Does the proposed six-part invariant — Identity + Evidence References +
Justification + Epistemic State + Confidence + History — actually require one aggregate?"* —
**the exact six-part list is a strict 6-of-8 subset of seq 0156's own eight-field KnowledgeAggregate**
(excluding Claim and Relationships), and seq 0157 explicitly frames it as continuing "the next
research" from the prior (0156) round. **This confirms, at the highest evidence level available, that
seq 0156 — not seq 0150 — is the true origin of the six-part aggregate invariant Phase 5C registered
as KERNEL-OBJ-04.** Per the authorization's explicit instruction, Phase 5C's register is **NOT
repaired** — this correction is recorded here only, deferred to a separately authorized step.

## KERNEL-OBJ-08 / seq-2293 possible collision (audit finding only)

**Finding**: Phase 5C cites seq 2293 (path under `phase_measure_theory/knowledgeos_kernel/`) as
KERNEL-OBJ-08's source (the `K_t=` tuple, verified at "line 827" per Phase 5C's own spot-check #4).
This phase's P1 digest also shows a **main-corpus seq 2293** (`meta_research`, "GĪTĀ-LENS MODEL OF
KNOWLEDGEOS... THE ULTIMATE SYNTHESIS OF CHAPTERS 1-18: The Nine-Component Kernel Tuple
𝔎_t=(K,K_t,N,M_t,B_t,O,δ,Z_t,Γ_t)"). **These describe visibly different tuples** (a `K_t=` construction
vs. a nine-component `𝔎_t=(...)`), which is consistent with the corpus's own known pattern of
`seq` numbers not being globally unique across the main-corpus register and the `phase_measure_theory/`
subtree's own internal file-numbering (already flagged as a general risk area, never previously
confirmed at this specific number). **Recorded as an open flag, not resolved** — determining whether
these are (a) two genuinely different files that happen to share the number "2293" in different
numbering schemes, or (b) a single file whose content spans both descriptions, requires a raw-source
check outside this phase's own P1 scope (the `phase_measure_theory/` file itself is P2, sampled not
censused this phase). **No change made to KERNEL-OBJ-08.**

## Equivalence adjudication — new candidates (six-level ladder, default UNRESOLVED)

| Pair | Lexical | Conceptual | Functional | Structural | Formal | Identity | Verdict |
|---|---|---|---|---|---|---|---|
| NEW-OBJ-03 (`K_OS=(A,T,P,E,I,S,X,R)`) vs. any 8-primitive kernel in the 13-object register | shared arity (8) | possible | not tested | not tested | not tested | not tested | **UNRESOLVED** — the source's own "confirmed via direct comparison to overlap only 3 of 8" already demonstrates non-identity with *some* other kernel, but that comparison's target was not itself identified in the digest excerpt; not assumed to be any specific one of the 13 |
| NEW-OBJ-05 (`K=(K,C,T,E,A)`) vs. NEW-OBJ-06 (`𝔎_5=(G,σ,θ,λ,π)`) | shared arity (5) | not tested | not tested | not tested | not tested | not tested | **UNRESOLVED** — same-arity, adjacent-sequence proximity is explicitly named in `03` as insufficient evidence |
| NEW-OBJ-05 vs. `S_Kernel=(D,E,S,T,U)` (seq 0377 family) | shared arity (5) | not tested | not tested | not tested | not tested | not tested | **UNRESOLVED** |
| NEW-OBJ-02 (DeepSeek's 4 hypotheses) vs. KERNEL-OBJ-04 (six-part aggregate, per the attribution finding above) | none (different object type — hypothesis set vs. an aggregate invariant) | none | none | none | none | none | **NOT EQUIVALENT** — the attribution finding above is a *citation* correction, not an equivalence claim; NEW-OBJ-02 and KERNEL-OBJ-04 remain two distinct objects regardless of which seq is cited as KERNEL-OBJ-04's origin |
| NEW-OBJ-01 (K1-K8 capacities) vs. any tuple-form candidate | none (list vs. tuple — different object type) | not tested | not tested | n/a | n/a | n/a | **UNRESOLVED, likely NOT COMPARABLE without first re-typing both by object-category** (per Phase 3's own object-typing discipline) |
| NEW-OBJ-04 (GN-31 ratification) vs. any tuple object | n/a (governance event, not itself an object) | n/a | n/a | n/a | n/a | n/a | **NOT APPLICABLE** — GN-31 is a process event, not a comparable Kernel-object candidate; its *relationship* to the tuples proposed in its own seq-neighborhood (0654–0867) is separately `UNRESOLVED`, not an equivalence question |

**No pair reaches structural correspondence or above.** This mirrors Phase 5C's own finding (0 of 6
original pairs reaching structural correspondence) — the pattern of proliferating, non-adjudicated
candidates continues into the newly discovered population, rather than resolving there.

## Closure verdict

**Mixed closure.** The 13-object register remains valid for what it describes (no contradiction
found), but is **not complete** — P1 alone yields 6 further distinct candidates, P2/P3 remain only
sampled, and one attribution-precision issue (KERNEL-OBJ-04) and one seq-collision flag
(KERNEL-OBJ-08) are recorded as open audit findings. **No canonical Kernel is constructed. No object
count is asserted as final.**
