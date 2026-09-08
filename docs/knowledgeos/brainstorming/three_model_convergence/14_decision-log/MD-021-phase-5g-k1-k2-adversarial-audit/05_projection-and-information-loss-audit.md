# Phase 5G — Projection and Information-Loss Audit

## Formal specification of $\pi_K$ (constructed by the corpus's own D285-6, re-verified this phase)

- **Domain**: $S_1 = \{E,S,T,O,P,R,\Pi,A\}$ (K-1's 8 primitives).
- **Codomain**: $S_2 = (\mathcal{A},\mathcal{R})$, i.e. the pair (Assertion-composite, Relation).
- **Mapping**: $Proposition \mapsto$ field of $\mathcal{A}$ · $Entity \mapsto$ nested field (inside
  Proposition, inside $\mathcal{A}$) · $Relation \mapsto \mathcal{R}$ directly · $State \mapsto$ "the
  carrier" (D285-6's own phrase, itself under-specified — **NOT EVIDENCED** beyond this label) ·
  $Observation \mapsto e$, **after** `Qualify` · $\{Event, Policy, Action\} \mapsto \emptyset$
  (explicitly dropped, declared external).
- **Totality**: **NOT TOTAL as evidenced** — the mapping for `Observation` requires `Qualify`, which
  "has no body in the corpus" (D285-6 §4a); the mapping is therefore **partial** in the precise sense
  that at least one component's image is not computably defined for all inputs.
- **Computability**: **NOT COMPUTABLE**, for the `Observation` component specifically (blocked on
  `Qualify: Observation × Policy → Evidence`, undefined). The other five mapped components
  (`Entity, Proposition, Relation, State*, and the three dropped primitives`) do not carry the same
  named blocker, but their own computability was not independently tested by D285-6 either — **their
  status is "assumed computable by omission of a stated blocker," not "confirmed computable."** This
  is a precision this phase adds: Phase 5F's own `05` stated "definable and not computable" as if this
  applied to the whole map; it more precisely applies to the `Observation` component, with the rest
  untested either way.

## Information-loss table (per the authorization's §9)

| K-1 component | Present in K-2? | Representation | Preserved? | Lost? | Evidence |
|---|---|---|---|---|---|
| Entity | Yes | Nested field (2 levels deep) | Partially — present, but not addressable as a top-level primitive | Structural prominence lost, content preserved | D285-6 §3 |
| State | Ambiguous | "the carrier" (unspecified) | **NOT EVIDENCED** whether preserved | **NOT EVIDENCED** | D285-6 §3, `NOT EVIDENCED` marker applied |
| Event | No | — | No | **Yes, explicitly dropped** — "declared external" | D285-1 §2, D285-6 §3 |
| Observation | Partially | Recoverable via Sañjaya, mapped through unimplemented `Qualify` | **Not currently** (function has no body) | **Currently yes, in practice; not in principle** | D285-6 §4a |
| Proposition | Yes | Direct field of Assertion | Yes | No | D285-1/D285-6 §2/§3 |
| Relation | Yes | Direct top-level component | Yes, but with asymmetric structural weight (`08`) | No | D285-1 §1 |
| Policy | No | — | No | **Yes, explicitly dropped** | D285-1 §2 |
| Action | No | — | No | **Yes, explicitly dropped** | D285-1 §2 |

**Classification of the loss** (per the authorization's own required disposition, §9): `Event`,
`Policy`, `Action` are **(3) intentionally excluded** (the corpus's own words: "declared external," a
deliberate scope decision, not an oversight). `Observation` is **(5) computationally inaccessible**
(the mapping is defined but not executable, pending `Qualify`'s implementation). `State` is
**(4) unavailable / NOT EVIDENCED** — its own mapping target ("the carrier") is itself undefined
precisely enough to classify.

## Verdict

**The projection $\pi_K$ is real, well-evidenced, and precisely characterizable as partial and
non-total** — three primitives are excluded by design, one is blocked by an unimplemented function,
and one (`State`) has an under-specified target that this phase cannot classify with confidence.
**This is a more precise, and slightly more cautious, account than Phase 5F's own `05`**, which treated
"definable, not computable" as a blanket property of the whole map rather than isolating it to the
`Observation` component specifically.
