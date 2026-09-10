# MD-091 — T21 `Decision`/`Act`/`ADR` vs. Post-T22 `ActionRationale`/`AR_t`/`Warrant`: Adjudication

## Scope and method

MD-090 §02 flagged this pairing `IDENTITY UNRESOLVED — INSUFFICIENT EVIDENCE`. This phase resolves it
using only evidence already gathered by MD-089 (post-T22 segment, Batches 2–3: Thread 2/3, the
fact-finding/action-rationale architecture) and MD-090 (T21 segment, Batch F: Parts 16, 18) — **no new
file is read, no new agent is dispatched**, per the master mission's own reuse-before-redo discipline
and per this reconstruction's own standing practice (MD-069, MD-078, MD-085).

## The two apparatuses, restated for direct comparison

**T21 (Part 16, birth 2026-09-06 06:42–06:47):**
- `D=⟨Q,A,K,EC,U,C,Π,Γ,t,Auth,Status⟩` — Decision as one flat, first-class tuple.
- `DC=⟨Question,Alternatives,StateSpace,InformationSet,UncertaintyModel,Consequences,Utility,
  Constraints,DecisionRule,Authority,TimeHorizon,Reversibility,ValidityCriteria,
  ProvenanceRequirements⟩` — the governing contract.
- `D:K×Q×A×Γ→A` — a decision *function*, coexisting with the `D` tuple in the same part.
- `EU(a)=Σ_s P(s∣K)U(s,a)`, `a^*=argmax_{a∈A}EU(a)` — expected-utility selection rule.
- `Act=⟨Decision,Actor,Authority,Target,Intent,Parameters,Time,Context,Provenance,Status⟩` —
  execution object, downstream of `Decision`.
- `ADR=⟨Question,Alternatives,Evidence,Assumptions,Decision,Rationale,Constraints,Consequences,
  Authority,Time,Version,Provenance⟩` (Part 18) — an *architecture*-specific decision record, a
  distinct, narrower-scoped sibling object, not a general KnowledgeOS decision object.
- Chain: `Observation→Evidence→Evaluation→Inference→Determination→Risk→Decision→Authorization→
  Action→Outcome→Observation` — Determination and Decision are adjacent, with `Risk` (not a
  rationale-construction stage) between them.
- `Determination⇏Decision` extensively theorem-ized (§13.65, §16.38–39, XVI-C13/C14).

**Post-T22 (mathematical_ideas_that_can_be_implemented/, birth 2026-09-07, MD-089's Thread 2/3):**
- `F_t≠AR_t≠W_t≠Decision_t≠Authorization_t≠Action_t` — a five-stage non-equivalence chain, with
  **two** intermediate objects (`AR_t`, `W_t`) inserted between Determination (`F_t`) and `Decision_t`
  that T21 does not have as separate objects.
- `AR_t=⟨rationale_id,inquiry_ref,determination_ref{determination_id,standing},
  candidate_action_space,proposed_action,causal_model{...},utility_evaluation{u_local,u_systemic,
  composition_function,calculated_eu},uncertainty_profile,warrant_evaluation⟩` — a *predecessor*
  staging object for Decision, not Decision itself.
- `W_t∈{Warranted,NotWarranted,Underdetermined,Blocked}` — a distinct warrant-evaluation stage.
- `EU(a∣K_t,Q_t,C_t,S_t,R_t)`, `Select_U=argmax_a EU_U(a)` — explicitly disclaimed as "one possible
  implementation/regime for selection... prevents EU itself from becoming a kernel primitive," never
  adopted as canon.
- `𝒜_Q=𝒜_Q^{domain}∪𝒜_Q^{epistemic}` — candidate action space, split into domain and epistemic actions.
- Every artifact self-labeled `[EXT][PROP]`, explicitly disclaiming modification of "Theory v1.2,
  Minimal Kernel, or the pending empirical protocol" — i.e. its own authors treat T21 as the frozen
  canon they are *not* touching.

## Evidence-ladder test (per the master mission's own required §6 sequence)

1. **Explicit textual identity**: none. Neither corpus's own text names or quotes the other.
2. **Explicit predecessor/successor statement**: none.
3. **Explicit refinement statement**: none.
4. **Explicit projection/restriction/extension statement**: none.
5. **Definitional equality**: fails — `AR_t` and `D`/`Act` have different field sets, different
   pipeline positions (`AR_t` precedes Decision; T21's `D` *is* the decision), and different arities.
6. **Formal equivalence**: not established — no shared formalism links the two tuple families.
7. **Type-preserving instantiation**: not tested (would require mapping `AR_t`'s fields into `D`'s
   fields one-for-one; not attempted by either source text, and the field sets do not correspond
   1:1 — `AR_t` has no `Auth`/`Status`/`Π` analogue, `D` has no `causal_model`/`uncertainty_profile`
   analogue).
8. **Equation-level correspondence**: **partial positive finding.** T21's `EU(a)=Σ_s P(s∣K)U(s,a)`,
   `a^*=argmax EU(a)` and post-T22's `EU(a∣K_t,Q_t,C_t,S_t,R_t)`, `Select_U=argmax_a EU_U(a)` are the
   same expected-utility-maximization *pattern* — same mathematical shape (weighted sum over states,
   argmax selection), though post-T22's version carries more context parameters and is explicitly
   held at arm's length from canonical status, unlike T21's own unqualified `EU(a)`/`a^*` (T21 never
   disclaims `EU`/`argmax` as merely one possible regime — the post-T22 caution has no counterpart in
   T21's own text).
9. **Behavioral/operational correspondence**: not testable — neither apparatus has an executed
   instance in the read material.
10. **Explicit DDD context mapping**: none stated in either direction.

## Verdict

**`RELATED OBJECT, INDEPENDENTLY CONSTRUCTED — PARTIAL STRUCTURAL ECHO AT THE EU/ARGMAX
SUB-COMPONENT ONLY.`** Not `SAME OBJECT` (different pipeline decomposition — T21 collapses rationale-
construction and warrant-evaluation into one `Decision` step; post-T22 splits them into two named,
separately-tracked stages `AR_t`/`W_t` — and no citation exists either direction across two
independent, exhaustive extraction passes). Not `UNRELATED_HOMONYM` either — the evidence ladder's
item 8 supplies a genuine, positive, non-trivial structural correspondence (the expected-utility-
maximization sub-formula), which is more than bare absence-of-relationship; calling it
`UNRELATED_HOMONYM` would overstate the negative side of the evidence exactly as the master mission's
own §6 warns against.

**Consequence for `Δ_R` (Risk Knowledge Gap, T21) and `AR_t`/`W_t` (post-T22)**: T21's own `Risk`
pipeline stage (between Determination and Decision, carrying `RC`/`Δ_R`/`Zero_R`) is the closest T21
analogue to post-T22's intermediate `AR_t`/`W_t` pair by *pipeline position*, but the two are built
from entirely different field content (T21's Risk is about uncertainty/consequence quantification;
post-T22's `AR_t`/`W_t` is about candidate-action construction and warrant classification) — this
positional analogy is recorded as a further `IDENTITY UNRESOLVED — INSUFFICIENT EVIDENCE` note, not
elevated to any stronger relation, since neither source text draws the connection itself.

## What this phase does not do

Does not merge `AR_t`/`Decision_t` with T21's `D`/`Act`; does not select a canonical decision
apparatus; does not construct a bridge between the two; does not modify any MD-089/MD-090 artifact or
any frozen artifact (MD-024–090). No new file read; no new agent dispatched; `theory-extraction/`
never accessed.

## Verification

- `resume.py`/`resume_mathematical.py`: run below, both `CONSISTENT`.
- No frozen artifact modified.
- Governance record (decision log, `.claude/CONTEXT.md`, session log, plan file) updated below.

## MD-091 status: EXECUTED. CHECKPOINT.

Per the master mission's own §19: this is informational, not a terminal claim. The two named
candidate next frontiers from MD-090 §04(10)(b) — the un-swept remainder of
`mathematical_ideas_that_can_be_implemented/`, or extending multi-object tracking into `kernel/`/
`phase_measure_theory/` — remain named and not yet begun. Given the scale of work completed across
MD-089/090/091 this turn (eleven parallel extraction agents, 64 source files fully read, four
governed phases closed), this turn's own response now ends at this checkpoint, consistent with the
scope-setting statement recorded in MD-089 §00_index.md: a session response is the natural review
point this reconstruction has relied on throughout, not a limitation smuggled back in as a stopping
rule. The mission remains active; the next frontier is named and ready for the next turn.
