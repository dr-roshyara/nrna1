# 11 — E20 Reclassification: BLOCKED → NOT APPLICABLE (core) / DEFERRED (Assessment)

**For consumption by Step 282 before the closure matrix is finalized.**

**This document does not modify `verification/step-280/`.** That package is another session's
executed report and is left intact; amending an executed record retroactively is the pattern this
investigation has criticized. **This is an amendment record, offered for ratification.**

---

## 1. The current classification

`step-280/03-END-TO-END-TEST-RESULTS.md`:

| Test | Domain | Verdict | Level | Reason |
|---|---|---|---|---|
| **E20** | Statistics | **BLOCKED** | **0** | *"no `(Ω,𝓕,P)` anywhere; `str` is ORDINAL"* |

and `step-280/08-GAP-REGISTER-UPDATE.md` classifies it:

| Mismatch | Category | Justification |
|---|---|---|
| E20 calibration | **`T` — theory defect** | *"no probability space is defined anywhere"* |

`step-280/10` records `Blocked: 1/24 (E20 Statistical Calibration)` and
`Level ≤3 (below substantive): 3/24 (E17=3, E19=2, **E20=0**)`.

---

## 2. What changed

Step 282 (`…_step_282_required-completion-and-correction-part.md` §1) rules:

$$\boxed{\text{T-3} = \text{NOT REQUIRED FOR THE CORE THEORY}}$$

> *"No such probability structure is currently required by the canonical state-transition model
> `K_{t+1} = δ(K_t, e_t)`. Nor is it required for: Unknown; Missing; Supported; Refuted; Conflicted;
> Superseded; provenance …"*

and the corpus's terminal Σ derivation (`…_step_272b_…` §7.2) independently states
**"no probability required, no numerical confidence required."**

---

## 3. The reclassification

**A test blocked for want of a construct the theory has since declared unnecessary is not evidence
of incompleteness. It is a stale test.**

Blocking presupposes that the missing thing is *owed*. Once T-3 rules probability out of the core,
nothing is owed, so nothing is blocked.

| Layer | Old | **New** | Ground |
|---|---|---|---|
| **Core theory** | BLOCKED (`T` — theory defect) | **NOT APPLICABLE** | T-3: probability is not required by `δ`, `Σ`, provenance, or any core construct |
| **Assessment layer** | — | **DEFERRED** | `Qualify`, `Assess`, `Compare` still need an **order** over evidence (`07` §2). Calibration becomes applicable **iff** Assessment declares a numeric semantics — which it has not |

**And the error-taxonomy row must move with it:**

| | Old | **New** |
|---|---|---|
| E20 calibration | `T` — theory defect | **not a defect at core; `E` — empirical, deferred at the Assessment layer** |

---

## 4. Effect on the Step 280 verdict — **none**

Stated explicitly, because the reclassification must not be allowed to look like an improvement it
is not:

| Step 280 finding | Affected? |
|---|---|
| **EC = NOT ACHIEVED** | **No** |
| Reason 1 — Critical Failure #7 (missingness) | **No** — and it is now repaired by Step 281, verified independently |
| Reason 2 — **15 of 24 constructs have no real-environment observation** | **No** — E20 is one of the 15, and it stays in that count for a *different* reason: the EKP has no measurement executor |

> **E20 moves out of "BLOCKED" and stays in "NOT OBSERVABLE."** The blocked count falls 1 → 0;
> the unobserved count remains 15.

**Net effect: the residual gap is one row smaller, and the closure verdict is unchanged.**

---

## 5. What survives from the original E20 finding

The second half of E20's reason — **`str` is ORDINAL** — is **not** retired by T-3 and must be
carried forward. `07` §4 established by exhaustive search that **176 (portfolio, threshold) decision
rules over the five-level strength scale flip under order-preserving re-encoding**.

> **T-3 removes the requirement for a probability space. It does not license arithmetic on an
> ordinal scale.**

So the live constraint is narrower and sharper than E20 stated it:

$$\boxed{\text{If Assessment introduces any numeric rule over strength, that rule must be shown meaningful under Roberts' admissibility.}}$$

This is `G-12`, and it remains **open at the Assessment layer** (`10` §4).

---

## 6. Proposed register lines for Step 282

```
E20  Statistical Calibration
     core        : NOT APPLICABLE   (T-3 — probability not required)
     assessment  : DEFERRED         (applicable iff Assessment declares numeric semantics)
     observation : NOT OBSERVABLE   (unchanged — no measurement executor in the EKP)
     taxonomy    : E (empirical), not T (theory defect)

G-22 / MT-1  no (Ω,𝓕,P)
     core        : OUT OF SCOPE by declaration (T-3)      [was: theoretical hole]

G-12 / MT-5  no empirical relational structure
     core        : OUT OF SCOPE by declaration (T-3)
     assessment  : OPEN            [176 flipping rules, `07` §4]

blocked count : 1 -> 0
unobserved    : 15 -> 15   (unchanged)
EC verdict    : NOT ACHIEVED (unchanged)
```

**Ratification required.** This amendment is proposed, not applied; `step-280/` is unmodified.
