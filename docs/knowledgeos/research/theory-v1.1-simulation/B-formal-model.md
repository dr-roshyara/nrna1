# B — Formal Model and Mapping to the Theory

## Concrete → semantic mapping (required by §2.12)

| Theory object | Concrete representation | Module |
|---|---|---|
| `RealityState` | `World.truth : dict` — **latent, evaluator-only** | `kos/world.py` |
| Channel / acquisition mechanism | `Channel(id, prop, reliability, ambiguous, source, available)` | `kos/world.py` |
| `Observation` | `E.observations[oid] = {prop, token, source, ambiguous, t, channel, prov}` | `kos/state.py` |
| `Evidence` | `E.evidence[eid] = {of, prop, token, source, reliability, derived_from, prov}` | " |
| `Interpretation` | `E.interpretations[iid] = {of, prop, context, readings(tuple), prov}` — **many-valued** | " |
| `HypothesisSpace H_Q` | `E.hypotheses[prop] : list` | " |
| `EvidenceAssessment` | `{prop, per_h : {h → weight}, source, derived_from, model, prov}` — **target-relative** | " |
| `Determination A_t` | `E.determinations[prop] = {A : list, status, weight, weights, independent_sources, standard_met, causal_status, provenance, assumptions}` | " |
| `EpistemicState E_t` | `EpistemicState` dataclass (10 slots + append-only `history`) | " |
| `KnowledgeState K_t` | `knowledge_attribution(E,Q,C,EC)` → `{prop → {status, A, attributed, value, …}}` | " |
| `Inquiry Q` | `Inquiry(id, target, purpose, context, requirements, constraints)` | `kos/inquiry.py` |
| `EpistemicStandard S^epi` | `EpistemicStandard(min_weight, require_independent_sources, require_corroboration)` | " |
| `EpistemicContract EC` | `EpistemicContract(standard, requirements, attribution_policy)` | " |
| `IdealState I_t` | `ideal_state(Q,C,S,EC)` → the requirement set under the contract | " |
| `Gap Δ_t` | `Gap(K,Q,C,EC)` = `{ r ∈ Req : ¬Sat(K,r) }` (DEF-21) | " |
| `Zero` | `Zero(K,…) ⟺ Δ_t = ∅` (DEF-22) — a **predicate** | " |
| `Model M_t` | `{id, structure, assumptions, beta, r2, causal_status}` | `kos/scenarios.py` |
| `History` | `E.history : list` — **append-only**; supersession stores the prior record | `kos/state.py` |
| `Identity` | `E.identity : str` — stable across `K_t → K_{t+1}` | " |
| Decision lane | `Propose → Decide → Authorize → Act`, four separate functions | `kos/transitions.py` |

## Backbone (§47 of the theory)

```
O_t → ℱ_t → K_t → EC_t → Δ_t → T_t → K_{t+1}
```

realized as

```
Acquire → Qualify → Interpret → OpenHypothesisSpace → Assess → Determine → Γ
        → Req/Sat → Gap → Zero → Propose → Decide → Authorize → Act → Acquire′
```

## The two design decisions that make the results meaningful

**1. Instance-level, not reachability-level.** The prior lane (`KR-2026-09-01`) reasoned about
*which carrier kinds are reachable*; its own `THM-9` then established `Reachability ≠ Adequacy`.
This simulator therefore carries **content**: tokens, readings, weights, sources, provenance. Only
that makes factivity, dependence, ambiguity and fabrication testable at all.

**2. Oracle independence is enforced structurally.** `World.truth` is read by the **evaluator only**.
A static audit greps every agent-side module for `.truth` and reports any hit as smuggling. Result:
**zero hits** (`results/audits.json`). This is what makes `P11` an empirical test rather than a
definition — and, as it turns out, it is also *why* factivity fails.

## Declared assumptions (§27 — none introduced silently)

| Assumption | Where it enters | Consequence if changed |
|---|---|---|
| a lexicon maps tokens to admissible readings, context-indexed | `Interpret` | ambiguity handling changes |
| an admission policy assigns per-source reliability | `Qualify` | the factivity failure rate changes; the *possibility* does not |
| the standard is a threshold + corroboration rule | `Determine` | D/P12 outcomes change |
| `Determine` maximizes weight among admissible hypotheses | `Determine` | tie-breaking changes; set-valuedness does not |
| dependence is declared via `derived_from`, not inferred | `Qualify` | G/P8 becomes untestable if removed |
