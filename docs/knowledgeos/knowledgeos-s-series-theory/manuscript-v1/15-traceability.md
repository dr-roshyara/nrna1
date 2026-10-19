# Chapter 15. From theory to architecture: a traceability map

*Status: reviewed in the final cross-chapter review (same model family); corrections applied. **The guarantee columns are conditional THM. The requirement, test and context columns are ARCH. Nothing here is an adopted requirement.***

Each chain reads: **theory item → system concept → guarantee (when its assumptions hold) → runtime or configuration requirement → regression or invariant test → open human decision.**

## 15.1 Definitions and assumptions → system concepts

| Theory item | System concept | Runtime / configuration requirement | Human decision |
|---|---|---|---|
| inputs Σ, IC | event log; every event carries time, actor, authorization, external results | events are immutable and complete; decisions read only events | which data are "decision-relevant" (D10) |
| decisions 𝔇, OD | a versioned specification of required decisions, including legality | specifications define behaviour on illegal histories; changing 𝔇 is a migration | the decision catalogue; the specification language (Ch. 3: decidable equivalence) |
| canonical state | kernel = fold over events | the kernel is derived, never edited | — |
| contracts (Ch. 5) | contract versions; gap as a query | gap and Zero are computed, never stored as authority | which contracts and versions |
| fault model, OF, trust (Ch. 8) | documented fault model; output log; trusted attesters | records keep outputs faithfully; attestations are signed by named attesters | which fault assumptions are acceptable; who may attest |
| party model (Ch. 10) | per-party access and trust profiles | access control per party class; a challenge process with attested submissions | access and trust per party class |
| version model (DEF 11.6a) | rule versions, plus either a trusted effective-date registry with an authority log, **or** trusted procedural attestations (Prop. 10.8) | a bitemporal registry is one sufficient design (Prop. 11.10); whether it is required is OPEN (DEF 11.6a) | retroactivity |

## 15.2 Theorems → guarantees (conditional on assumptions)

| Theorem | Guarantee | Conditions |
|---|---|---|
| Thm 3.8 | equal canonical states give equal answers now and later | IC, DET, OD |
| Thm 4.5(b) | an attribute-shaped audit requirement adds a decision, and does not break uniqueness | equivalence-shaped requirement |
| Thm 7.3 | the outcome log recovers every past decision | A1, A2 |
| Cor. 8.7 | determining evidence makes decisions verifiable under any fault model | OF, trusted evidence, x-only evidence |
| Thm 9.8 | for every append-only evidence family, "verifiable at every step ⇒ determining at every step" iff (S-joint) | OF; append-only G; deterministic x-history evidence; outputs depend on the input history only |
| Thm 10.4 | party-sufficient ⇒ auditor-sufficient | dominance; epistemic, level (ii) only |
| Thm 11.3 / 11.12 | bitemporal records recover original and current decisions; a horizon bounds entries | A2; horizon k |
| Thm 12.2 / 12.5 | one privacy check suffices; deletion keeps verifiability iff confusable pairs stay separated | upward-closed Π; x-only evidence |

## 15.3 Counterexamples → regression tests

| Counterexample | Test it becomes |
|---|---|
| CE 3.6 | "store only the current answer" is rejected as a state design |
| CE 3.9 | decision specifications without behaviour on illegal histories are rejected |
| CE 4.5e / 4.6 | pair-list or cap policies are flagged at policy review |
| Thm 4.7 remark | privacy rules counting internal states are rejected |
| CE 8.11 | an outcome-only log fails the "correctness auditable" check |
| Prop. 8.10(b) sentinel | a verifier must not infer the correct value from a recognized error signal |
| CE 9.10 / 9.11 | evidence-policy calculators handle carried-forward evidence and linked fault models |
| CE 10.5 / 10.6 / 10.7 | access and trust scenario tests per party class |
| CE 11.4 / 11.9 | corrections never lose original values; historical verification uses snapshot evidence |
| Thm 12.3(c) | privacy review covers correction history |
| Prop. 13.3 | storage claims after minimization are measured, not assumed |

## 15.4 Invariants → automated tests

1. **Determinism and replay:** re-running decisions on the stored event log reproduces the recorded outcomes. Purity itself cannot be checked at runtime.
2. **Append-only:** no record entry is modified or deleted, except through governed deletion with tombstones (STAKE).
3. **Current is a query:** the "current decision" equals the latest non-superseded entry.
4. **Kernel consistency:** histories with equal kernel state give equal decisions under random continuations (property-based test).
5. **Verifiability check:** for critical decision types, the retained evidence determines the decision, or the documented fault model shows that confusable pairs are separated.

## 15.5 Candidate bounded contexts (ARCH; not adopted)

| Context | Owns | Grounding |
|---|---|---|
| **Evidence & Provenance** | the event log, attested inputs, bitemporal evidence | Ch. 2, 8, 11 |
| **Kernel** | the canonical-state projection | Ch. 3–4 |
| **Contracts & Assessment** | contracts, versions, gap queries | Ch. 5 |
| **Decision Records** | outcome logs, attestations, corrections | Ch. 7, 11 |
| **Governance** | rule versions, registry, authority, retention and deletion policy | Ch. 10–12 |

*Caveat:* a mathematical result does not establish a bounded context. These need domain evidence (source gate) and stakeholder decisions.

## 15.6 Open human decisions (consolidated)

Labels as fixed in Ch. 14 §14.2:
- **D9:** which decisions are critical.
- **D10:** which decision-relevant outcomes are recorded as events.
- **R-OPT:** the retention option (A–E, Ch. 12) per decision type.
- Local vs global correctness, where rules read past outputs.
- Retroactivity, decided together with deletion.
- The correction horizon.
- Trusted attesters.
- Per-party access and trust.
- The specification language for 𝔇.
