# 08 — Scenario Suite (Part X)

Sixteen deterministic scenarios. Each declares the **ambient carriers the situation supplies** and
the **capabilities that must be achievable**. A scenario passes iff every required capability is
achievable *given that scenario's ambient set* — so a scenario that supplies no `IdealState` cannot
be passed by producing a `Determination`.

| # | Scenario | Ambient beyond {World, Context, Inquiry, K_t} | Required capabilities |
|---|---|---|---|
| S1 | Nexus server runs RHEL 9.8 | Policy | C1 C2 C3 C18 C21 C25 |
| S2 | Server has 31 GB RAM | Policy | C1 C2 C3 C25 |
| S3 | Nexus listens on port 8081 | Policy | C1 C2 C3 C25 |
| S4 | Conflicting evidence: A says 8081, B says 8082 | Policy | C1 C2 C3 C5 C11 C18 C25 |
| S5 | Ambiguous semantics: "the server is available" | — | C1 C2 C14 C19 C21 |
| S6 | Temporal revision: t1 version X, t2 version Y | Policy | C1 C5 C11 C15 C24 |
| S7 | Missing evidence: is 8081 externally reachable? | IdealState | C8 C12 C20 |
| S8 | Competing hypotheses H1 direct vs H2 proxy | Policy | C5 C6 C10 C19 |
| S9 | Model criticism: fits, assumptions questionable | Policy, Rule | C7 C9 C10 C16 C17 |
| S10 | Same observation, different meaning by context | — | C2 C14 C21 C22 |
| S11 | "Migrate?" vs "migrate without downtime?" | IdealState | C8 C12 C23 |
| S12 | Smallest adequate answer from a large `K_t` | IdealState | C12 C23 |
| S13 | Select next evidence-gathering action under cost | ActionSet, Objective | C13 |
| S14 | Non-identifiability: observations cannot separate H1/H2 | Policy | C5 C6 C19 C20 |
| S15 | Provenance conflict: different authority levels | Policy | C5 C10 C18 C25 |
| S16 | Representation equivalence, same semantics | — | C2 C3 C4 C22 |

## Design notes

* **S7 and S11–S12 are the only scenarios supplying `IdealState`.** This encodes prior constraint 5
  and makes property P6 (adequacy) testable rather than assumed.
* **S13 is the only scenario supplying `Objective` and `ActionSet`.** Action selection is therefore
  *structurally isolated* from epistemic determination — matching the corpus, which places the
  Lord/action lane outside the epistemic projection. If `Select` were doing epistemic work, it would
  have shown up elsewhere. It does not.
* **S9 is the only scenario supplying `Rule`.** Deductive inference is thereby not available for free.
* Scenarios do **not** declare which operators must be used. They declare capabilities. Any operator
  set that reaches them passes.
