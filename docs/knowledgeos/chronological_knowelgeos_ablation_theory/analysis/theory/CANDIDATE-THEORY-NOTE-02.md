# Candidate theory note 02 — the state machine described by ES-006.1–.4 (+ Metamodel §6, CAP-001 §9)

⚠ generated · research hypothesis · not canonical · no model selected. **Tags:** [SOURCE] released wording · [ASSUME] modelling assumption · [DERIVED] consequence under stated assumptions · [HYP] hypothesis · [TEST] computational result.
**Correction of note 01:** "authority must leave generated before frozen" is **I5-MODEL** [DERIVED under ASSUME: forward one-axis moves]. It is not a corpus invariant.

## 1. Source facts (ES-006.2–.4, L22–49; hash `349b7d5d…`)
- **.2 Research Freeze** [SOURCE]:
  - "*(ARB 2026-07-11 — a milestone, not a rule)*": self-declared non-rule.
  - The knowledge theory is "frozen: no theoretical refinement; **changes only from pilot/operational evidence**".
  - Closed hierarchy: Constitution → Engineering Platform → Project Knowledge → Pilot Evidence; "no Level 5 exists".
- **.3 Harvest Discipline** [SOURCE]:
  - External knowledge enters as **pattern cards with source provenance**; evaluation is pattern-by-pattern at two levels.
  - The Pattern Evidence Register tracks four **frozen** metrics: independent sources · contradictions · implementation evidence · **promotion status**, with "**no composite scores**".
  - Research dossiers are input-only, "never architecture until promoted through ES-006.1".
- **.4 Harvest Question** [SOURCE]:
  - "Did this work REVEAL reusable engineering knowledge?"
  - Roles: project **reveals** · engineer **captures** · platform **governs** · engineering **promotes**.
  - "**No is a fully valid outcome**".
  - Flow: Project Outcome (implementation · verification · qualification · retrospective, "the ONLY legitimate harvest inputs") → Observation → DetermineReusePotential (No → continue) → DetermineArtifactType (pattern card · guide · qualification improvement · candidate standard · research — "or, on reflection, nothing") → ES-006.1 Promotion Ladder → Qualification → Engineering.
  - "a standard is only one possible destination".
- **R-37 (locate only):** the primary register row is `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` **L23** (`## Rulings (living, append-only)`, sha256 `7795c14bf2551ce176d34f41fbb57e9fb418bcd27f49c66d74f3535a9541454f`). The other hits are citations. Not read.

## 2. Transition semantics (the state machine the sources describe)

| Transition | Actor [SOURCE] | Precondition | Effect | Object | Reversible? |
|---|---|---|---|---|---|
| Observe | engineer (captures) | a Project Outcome of a legitimate kind | an Observation exists | new object (observation) | not stated |
| DetermineReusePotential | not stated | Observation | No → end (valid, healthy) · Yes → continue | same | — |
| DetermineArtifactType | not stated | Yes | **Kind ∈ {pattern card, guide, qualification improvement, candidate standard, research, nothing}** | **creates a typed artifact** (derivation, a new identity) | not stated |
| Promote (ES-006.1) | **engineering** (promotes) | demonstrated operational evidence (.1 L20) | ladder rung +1 | same artifact | forward only as written |
| Qualify (.1) | not stated | — | promote **or** "not needed" (both success) | same | — |
| Amend-frozen (.2) | not stated | **pilot/operational evidence** (necessary) | the content of a frozen theory changes | same | — |
| Enter-architecture (.3) | — | promoted through ES-006.1 | a dossier becomes architecture | same | — |

**Not stated anywhere released** (still): demote · revoke · invalidate · reassess · revalidate · expire. Supersede and historicize appear only as status/authority values (Metamodel §6), with no rule.

## 3. Candidate state dimensions (the minimum the evidence requires)
- **Identity + derivation link** [SOURCE .4]: an artifact is *created from* an observation, so identity is not fixed across the pipeline.
- **K kind** [SOURCE .4 + Metamodel typing]: required. It is set by an event (DetermineArtifactType), not given in advance.
- **L ladder rung** [SOURCE .1/.4]: required.
- **E evidence vector** [SOURCE .3]: independent sources, contradictions, implementation evidence, **non-aggregated**. So E is a partial order (product), not a scalar.
- **P promotion status** [SOURCE .3]: tracked separately from E. **Whether P is L or a function of L is not stated** (a candidate dependent variable).
- **S status** (frozen …) [SOURCE §6, .2]: applies to theories and artifacts.
- **A authority, M maturity** [SOURCE §6 only]: *not mentioned in ES-006.1–.4*. Their independence is unconfirmed here.
- **Terminology** [DERIVED from wording]: "maturity" has **three** senses:
  - Metamodel M: Candidate → … → Standard;
  - ES-006.1's "maturity vocabulary" (Evidence = proven · Pattern = proven · Capability = strong hypothesis · Principle = research question), which maps **kind → epistemic confidence**;
  - the ladder "Engineering Standard".
  - No source equates them, so **A2 is resolved as "not established; three distinct notions"**.

## 4. Candidate invariants
- **J1** [SOURCE .1]: Promote(x) → operational evidence of necessity. Necessary; timing unstated.
- **J2** [SOURCE .2]: Change(frozen x) → pilot/operational evidence. Necessary. *Evidence-gated amendment ≠ loss of standing.*
- **J3** [SOURCE .3]: architecture(x) → promoted-through-ES-006.1(x).
- **J4** [SOURCE .4]: the harvest inputs ⊆ {implementation, verification, qualification, retrospective}.
- **J5** [SOURCE .3]: no scalar aggregation of E (no composite score).
- **J6** [SOURCE .1 + .4]: non-promotion is a *valid terminal outcome* at two points (Qualify: not needed; ReusePotential: No).
- **I5-MODEL** [DERIVED under ASSUME] (note 01): kept, re-tagged.

## 5. Model assumptions used here
- [ASSUME] The .4 flow is a sequence of decision events, as its arrows depict.
- [ASSUME] "promotion status" (.3) and the ladder rung (.1) may coincide; this is kept open.
- Nothing else is assumed. No verifier was built for the pipeline: its semantics are declarative and acyclic as drawn, so enumeration would only restate the diagram.

## 6. Computational consequences
- **[TEST]** In the frozen MT results, none of the 5 × 18 property classes differ between chain3, V and diamond. So **MT conclusions are invariant to whether evidence is totally ordered**.
- **[DERIVED]** Under MT semantics, J5 (a non-aggregated, partially ordered E) changes no Gate 2.5 result.
- **[TEST]** Frozen engine (secondary check; cumulative 24 cells over L0-REL-03/04/06/07/08):
  - **12 SILENT · 6 AMBIGUOUS · 1 SUPPORTED**.
  - **EP-12** (promotion is an event distinct from the resulting state) is **SUPPORTED**: the "engineering promotes" act plus "promotion status" as a tracked metric. It is STRUCTURALLY-CONSISTENT for all models, so it eliminates nothing.
  - EP-01 (authority named, no scope), EP-05a (qualification ≠ validation unless stated) and EP-11 (contradictions tracked, no consequence) are AMBIGUOUS.
  - **No model constraint; no flag.**

## 7. Contradictions and ambiguities
- **B1:** ".2 is a milestone, not a rule" limits J2's normative force.
- **B2:** "contradictions" are tracked (.3), but **no rule says what happens when they appear**. This is the precise gap for EQ-3/EQ-4/EQ-11.
- **B3:** qualification is both a ladder rung (.1) and a harvest-input activity (.4). The term is overloaded.
- **B4:** the actors for DetermineReusePotential and DetermineArtifactType are unstated. The authority for Promote is "engineering", while the ARB authors the rules. **Authorization of an individual promotion is not described** (EQ-1 stays open).

## 8. MT1/MT2/P0/P1 as projections
- **Still adequate as diagnostic projections.** EQ-2 (check timing) and EQ-3/EQ-4 (post-promotion loss) remain the right questions, and **neither is answered by .2–.4**.
- **Expressiveness limits** (not fixed by modifying MT):
  - typed-artifact **creation by derivation** (a new identity);
  - the multi-stage decision pipeline with valid "No" terminals;
  - evidence-gated amendment of frozen objects;
  - separate recorded promotion status;
  - role-attributed acts.
- **Harmless:** a non-aggregated E is already covered (the [TEST] above).

## 9. MK status
- **Supported in part.** The evidence now *requires* identity plus derivation, K, L and E (as a vector), and probably S.
- **Weakened in part.** A and M are *not* re-attested by ES-006.1–.4, and "maturity" splits into three notions. MK should therefore not carry M as one axis.
- **Revised candidate (not built):** X = (id, derived-from, K, L, E⃗, S, [P?]). Events: Observe, DetermineReusePotential, DetermineArtifactType, Promote, Qualify, Amend-frozen. The exits (Supersede, Historicize) and any loss mechanism remain unattested as rules.

## 10. Single highest-information next target
- **The R-37 register row**: `ADR-AIP-LOG-Platform-Rulings.md` L23, sha256 `7795c14b…`.
- ES-006.1 says it applies R-37 ("the burden-of-proof rule") to promotion. It is the one located source that could state **when** the evidence burden must be met, which is EQ-2 and tests MT1-P1 (IG 0.811).
- It is a single row, so it is the smallest possible release.
- **B2 (what contradictions do)** is the next target after that.
