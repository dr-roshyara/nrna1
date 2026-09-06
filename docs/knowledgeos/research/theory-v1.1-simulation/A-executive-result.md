# A — Executive Result

**Experiment** `KR-SIM-2026-09-02` · **Model** `KnowledgeOS-Simulation-v1.1` · **Status** `[EXP]`
**Theory under test** v1.0 (`DEF-1..33`, `AX-1..7`, `THM-1..10`, `I1..I9`) + the v1.1 correction
`E_t ≠ K_t`, `K_t = Γ(E_t,Q,C,EC) ⊆ E_t`.

> Not canonical architecture · not a production implementation · not a final kernel · not a proven
> theory · not a validated ontology. **A simulation of the theory.**

## Research question

Can the theory execute a complete epistemic lifecycle without collapsing distinctions it requires?

## Overall result

**`[EXP]` The theory is executable and its distinctions survive — with one exception, and the
exception is structural rather than incidental.**

* All 10 scenario families (A–J) execute the full lifecycle.
* 19 of 20 properties hold across the deterministic suite.
* **`P11` (factivity) fails, and it fails necessarily.**

## The major failure — and it is a theorem, not a bug

Theory v1.1 asserts both

```
(1)  Knows(a,p,c,t) → True(p,c,t)                    DEF-1, factivity
(2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)        v1.1 attribution
```

**These are jointly unsatisfiable for any total `Γ` that attributes anything.**

Witness (`results/factivity.json`): two worlds whose truths differ — `os = RHEL9.8` vs `RHEL8.6` —
produce a **bit-identical epistemic state** (verified by fingerprint over observations, evidence,
interpretations, assessments, determinations, hypotheses, rejections). Since `Γ` is a function of
`E`, it returns an identical `K`. The attribution is true in one world and false in the other.

Policy sweep — the only `Γ` that escapes is the one that attributes nothing:

| attribution policy | attributes anything? | factivity violated |
|---|---|---|
| `justified-unique` | yes | **yes** |
| `justified-any` | yes | **yes** |
| `none` | no | no |

Randomized confirmation over 10 000 trials: **420 factivity violations**, conditional failure rate
**0.1237** given that an attribution was made. Diagnosis: **414 / 665** violations when a source the
admission policy rated reliable reported a false value, **0 / 2 607** otherwise. The agent had no
signal distinguishing the two.

## Major successes

| | |
|---|---|
| **S3 anti-fabrication** | incomplete evidence never produced a value (B, J, AD-7) |
| **S4 unresolved competition** | `Reject(H1)` never became `Accept(H2)`; `A_t` stayed set-valued (C, F) |
| **S5 target-relative assessment** | weights are per-hypothesis, never a scalar "strength" |
| **S6 evidence dependence** | the dependence-aware run drops the copy; the naive run double counts (G, AD-3) |
| **S7/S8 explicit standards & models** | identical evidence + identical reality, different `S^epi` → different determination (D, P12) |
| **S10 inquiry-relative adequacy** | same `K_t`, different `I_t`, different `Δ_t`, different `Zero` (E, P5, P20) |
| **S11 history** | `K_1 ≠ K_2` with identity stable and `K_1` recoverable (I, P19) |
| **S14 no information creation** | the provenance audit catches an injected unsupported conclusion (AD-18) |
| **S15 decision lane** | Determination → Proposal → Decision → Authorization → Action, all four separable (P15–P17) |
| **S17 unknown taxonomy** | UNOBSERVED / UNINTERPRETED / UNDERDETERMINED / UNOBSERVABLE stayed distinct (P13) |

## Major unresolved questions

1. **What object is `K_t`, if it cannot be factive?** (see L/G1–G3)
2. **`Adequate` and `Zero` are extensionally identical** — `DEF-20` and `DEF-22` both reduce to
   `Δ_t = ∅`. One does no independent work. (CIRC-3)
3. **Semantic equivalence remains circular** — behaviour is compared under a projection the
   experimenter chooses. (CIRC-5)
4. **Revision without retraction produces permanent underdetermination** — the theory has `Revise`
   but the corpus's `Supersede / Retract / Expire` are unmodelled. (CE-3)

## Breakthrough

The factivity result is the first genuinely **negative structural theorem** the programme has
produced about its own definitions, and it is *sharp*: it does not say the theory is wrong, it says
**exactly two of its clauses cannot both hold**, and it names the three repairs available (see H).

## Remaining proof obligations

The impossibility is demonstrated by an explicit witness under this model, not proved in general.
A general proof needs only the observation that `Γ`'s domain excludes truth — which is a one-line
argument, but it has not been written as a theorem in the theory document.
