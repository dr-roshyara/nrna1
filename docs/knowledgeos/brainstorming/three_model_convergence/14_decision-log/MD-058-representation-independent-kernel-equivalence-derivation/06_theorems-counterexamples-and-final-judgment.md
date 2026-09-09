# MD-058 §06 — Theorems, Counterexamples, DDD, Success Condition, Final Judgment

## Theorem/counterexample register (§14), each as `Definition → Motivation → Premises → Derivation →
Alternatives → Counterexample → Status`

### P1 — atom-closure invariance under net-preserving operator merges

- **Definition**: for `K'` obtained from `K` by merging operators with identical net atom-production,
  `Reach(Ops(K)) = Reach(Ops(K'))`.
- **Motivation**: test R1/R2 non-circularly against the one candidate with real semantics.
- **Premises**: F3's own `Reach`/carrier construction (MD-050); the merge preserves net atom
  production (disclosed assumption).
- **Derivation**: `03`.
- **Alternatives**: a merge that does NOT preserve net atom production — not tested, explicitly out
  of scope for P1's own premise.
- **Counterexample**: C1 (below) shows the *bound* on `𝒪` under which P1's consequence for `Obs`
  actually holds.
- **Status**: **MATHEMATICALLY DERIVED**, conditional on its stated premise.

### C1 — `𝒪`-exposure counterexample

- **Definition**: an intermediate atom exposed under one representation and not another can make
  `Obs_𝒪` distinguish representations with identical net external behaviour.
- **Motivation**: adversarially search for the limits of P1.
- **Premises**: `𝒪` unconstrained (current corpus state — `𝒪_K` not closed, R10).
- **Derivation**: `03`.
- **Consequence**: surfaces **R1a** — a new, explicit sub-requirement, not previously stated by any
  corpus source.
- **Status**: **COUNTEREXAMPLE, MATHEMATICALLY DERIVED**.

## DDD classification (§11)

- `Obs_{Q,𝒪}(K)` and the induced `≈_{Q,𝒪}`: a **Specification** (a predicate over pairs, parameterized
  by external inputs `Q,𝒪`) — not a Domain Service (it performs no action), not a Policy (it does not
  govern permitted transitions), not a Value Object (it is not itself a datum attached to `K`).
- `𝒪`/`𝒯`/`𝒪_K` (the observation/operation registries): candidate **Policy** objects — their own
  mandatory-membership rule (R10, `N-4`) is precisely a policy decision, not yet made.
- `MinKer` itself, once restated over `𝔎_adm/≈_{Q,𝒪}` (`04` §9.4): a **mathematical meta-level
  construct** operating over equivalence classes of domain objects — not itself a domain object.
- **Explicitly kept separate, per §11**: identity of an implementation (not addressed here) ≠
  identity of a domain object (F1–F8 as registered) ≠ identity of a capability (R2's own forbidden
  ground) ≠ semantic equivalence (`≈_{Q,𝒪}`, this phase's own object) ≠ governance identity
  (ratification status, R6/R10 — a separate axis entirely, never conflated with any of the above).

## Success condition (§15) — a mixed result, not forced to a single letter

- **Condition A** (fully derived and instantiated for ≥2 candidates): **NOT MET** — only 1 of 6
  candidates (F3) is instantiable.
- **Condition B** (a minimal candidate relation is derived, with open assumptions): **MET** —
  `Obs_{Q,𝒪}`-equality (`02`), open on `Q,𝒪`'s own closure (R10) and on `≡`'s remaining extra strength
  beyond `≈` (H7/R5).
- **Condition C** (requirements admit multiple non-equivalent relations, proving a design/governance
  choice is unavoidable): **ALSO MET** — H7 is a direct, confirmed instance of exactly this.
- **Condition D** (derivation fails because a precisely identified primitive is missing): **MET for
  5 of 6 candidates** — the missing primitive (an `Obs`/`Beh` instantiation for F1/F4/F5/F6/K0) is
  named precisely, not vaguely, and traces to MD-049's own zero-hit finding.

**This reconstruction reports the honest conjunction (B ∧ C ∧ D), not a forced single letter** — the
authorizing prompt's own §15 explicitly allows this ("Any of A–D is a scientifically useful result");
choosing one letter here would understate what was actually found.

## §18 — Final Research Judgment (the four required questions, answered separately)

1. **What can now be mathematically derived?** A representation-independent-*in-form*, parameterized
   observational-equivalence relation (`Obs_{Q,𝒪}`-equality), proven to be an actual equivalence
   relation (R4, trivially); a proof that `MinKer` must quotient by this relation before minimality is
   well-posed at all (`04` §9.4); a concrete, non-circular representation-independence test
   methodology (P1/C1) reusable on any future candidate with real semantics; a new, explicit
   sub-requirement (`R1a`) that the corpus itself never stated.
2. **What remains dependent on a modelling/design choice?** Whether `≡_sem` should be strictly
   stronger than `≈_{Q,𝒪}` (H7), and if so how; how to handle possible non-uniqueness of `MinKer`'s
   own minimal element (`04` §9.8–9); which representation is used to attempt `Obs`/`Beh` for
   F1/F4/F5/K0, should that ever be attempted (not attempted here).
3. **What remains dependent on governance?** Closing `𝒪`/`𝒪_K` with a mandatory-membership rule that
   also satisfies R1a's representation-neutrality (the corpus's own `N-4`, now sharpened by this
   phase); ratifying (or rejecting) any specific candidate `≡_sem` formula (`N-1′`); whether MinKer
   should be redefined as set-valued under non-uniqueness (a genuine new governance question this
   phase surfaces, not previously named).
4. **What is the smallest missing input preventing GA-001/GA-038 from being resolved?** A single,
   named pair: **(i)** a representation-neutral, closed `𝒪` (governance act, R10/R1a/N-4) — without
   it, no `Obs`/`≈` instance can even be *verified* representation-independent, let alone adopted;
   **(ii)** an `Obs`/`Beh` construction for at least one candidate other than F3 (a research act, not
   attempted here per this phase's own hard boundary against inventing candidate semantics) — without
   it, GA-001 has nothing to compare F3 against. **Both are named precisely; neither is closed by
   this phase.**

## Preservation note

The derived relation (`Obs_{Q,𝒪}`-equality, with `R1a`) is preserved here as a **candidate
mathematical theory** — explicitly not canonical KnowledgeOS architecture, not adopted, not ratified,
not merged into F1–F8. **HARD STOP.** No canonicalization. No governance ratification. No
implementation.
