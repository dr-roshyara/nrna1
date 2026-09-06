---
artifact: E · TRANSFORMATION-THEORY
mandate: 20260830_1852 §11
date: 2026-08-30
status: FORMALLY DERIVED · signature derived by distinguishability, not chosen
---

# Transformation Theory

## 1. The signature, DERIVED — not chosen syntactically

**Method:** begin at `T(K,o)`. Add a parameter **only** when two cases with identical existing arguments
must map to different results. Each rejection below is an executed distinguishability test.

| Parameter | Test | Verdict |
|---|---|---|
| **policy** | same `(K,o)`: rejected under `evidence-required`, accepted under `open` | **REQUIRED** |
| **authority** | same `(K,o,policy)`: architect may supersede a frozen assertion, intern may not | **REQUIRED** |
| actor | two different architects, same authority, same op | **REJECTED** — identical `K'`; actor affects `Π`/History only |
| evidence | rides inside the assertion's `e` | **REJECTED** — already carried by `o` |
| context | rides inside the assertion's `c` | **REJECTED** — already carried by `o` |
| time | affects **when**, not **what** | **REJECTED** — belongs to History |

```
T : 𝕂 × Op × Policy × Authority  ⇀  𝕂 × Outcome                    PARTIAL
```

> **§11 asked whether `T(K,o,actor,authority,policy,evidence,context,time)` is needed.**
> **ANSWER: NO. Four of the eight parameters are eliminable, each by an executed argument.**
> The eight-parameter form is not wrong — it is **not minimal**, and it hides which arguments actually
> discriminate outcomes.

**`Outcome` is required in the codomain.** A partial function that merely fails cannot distinguish
*"rejected by policy"* from *"rejected by authority"* — and both must be recorded in History. **Returning
`𝕂` alone loses the rejection reason.**

## 2. Properties — tested

| Property | Verdict | Basis |
|---|---|---|
| **deterministic** | **YES** | executed; identical inputs → identical outputs |
| **closed** | **YES** | `T(K,·) ∈ 𝕂` by construction |
| **total** | **NO** — deliberately partial | rejection is a first-class outcome |
| **composable** | **YES**, associatively | `T` returns `𝕂` |
| **identity operation** | **YES** — `noop` | `T(K, noop, ·, ·) = (K, ok)` |
| **replayable** | **YES**, order-dependently | `Replay(H) = fold(T, ∅, H)`, executed |
| **provenance-preserving** | **YES** | `Π` is carried on the assertion, never reconstructed |
| **governance-observable** | **YES** | every rejection is an `Outcome`, recorded |
| **monotone** | **NO** | `retract` shrinks `𝒜` |
| **invertible** | **NO** | `retract` cascade is lossy — executed counterexample |

## 3. The state machine

```
K_t  --( o, policy, authority )-->  K_{t+1}                        [transformation]
                 |
                 +--> Outcome ∈ {ok, rejected(policy), rejected(authority), rejected(structure)}
                 +--> History_{t+1} = History_t ⌢ ⟨o, policy, authority, actor, wallclock, Outcome⟩

Assessment : P × Evidence × Context × Policy → Σ                   [NOT a transformation — K unchanged]
Replay     : History → 𝕂                                           [NOT K → K]
```

**Actor and wall-clock appear in History and nowhere else.** This is the executed result of §1 and it is the
sharpest available statement of the state/history boundary:

> **Everything that affects WHAT the next state is belongs to `T`. Everything that affects only WHO did it
> and WHEN belongs to History. `K` holds neither.**

## 4. Step 254's congruence condition — adopted from the other stream

Step 254 (independent, contamination-checked) contributes:

```
F ∘ T̂  =  T ∘ F
```

A representation `F` is **adequate** for a transformation set iff it commutes with those transformations —
a homomorphism condition. **This is strictly stronger than my removal tests**, which only show that no
component can be *deleted*; the commuting square shows the representation *tracks* the dynamics.

**Applied to `K = (𝒜, ℛ)`:** the square commutes for `assert`, `relate`, `merge`, `supersede`, `refine`,
`resolve`, `replay`. **It does NOT commute for `retract`** — because the `ℛ`-cascade means the abstract
"remove one assertion" and the concrete "remove and cascade" produce different diagrams.

> **`retract` is the one operation where `K = (𝒜,ℛ)` fails Step 254's own adequacy test.** Either `retract`
> is excluded from the transformation set (knowledge is append-only — consistent with `ReplayAssertion`'s
> immutability invariant), or the representation must carry tombstones. **RECORDED AS OPEN.**

And step 254's `Minimality(K | 𝒯)` — **minimality is relative to the transformation set** — is the exact
distinction the mandate's §2 demands. **It confirms my minimality proof is type A.** See artifact I.

## 5. Classification

| Claim | Class |
|---|---|
| `T : 𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome` | **FORMALLY DERIVED** by distinguishability |
| Four parameters eliminable | **EXECUTED** |
| `Outcome` required in the codomain | **FORMALLY DERIVED** |
| deterministic · closed · partial · composable | **EXECUTED** |
| not monotone · not invertible | **FORMALLY PROVEN** — counterexamples |
| actor and time belong to History alone | **FORMALLY DERIVED** |
| `F ∘ T̂ = T ∘ F` as the adequacy criterion | **CORPUS ESTABLISHES** — step 254, independent |
| **`retract` breaks the commuting square** | **VERIFIER OBSERVATION — OPEN** |
