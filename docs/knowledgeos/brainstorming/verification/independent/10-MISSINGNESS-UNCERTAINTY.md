---
artifact: 10-MISSINGNESS-UNCERTAINTY
date: 2026-08-30
status: **MISSINGNESS: 8 of 9 distinctions EXIST — and 3 of them EXECUTE. UNCERTAINTY: typed object exists, algebra absent.**
---

# 10 · Missingness and Uncertainty

## 1. Missingness — the nine mandated distinctions

**First, the vocabulary fact that explains why this looked unresolved:** the word **`Missingness`
has 0 definitional occurrences corpus-wide** (`02` §1). The *concept* is present under four other
names — `Zero`, `abhāva`, `Missing`, `not assessed`. **Searching for the word finds nothing;
searching for the capability finds a great deal.**

| # | Distinction | Corpus construct | Source | Executes? |
|---|---|---|---|---|
| 1 | **not asked** | `DIMENSION = not assessed` | Zero taxonomy, `20260826-105229` | — |
| 2 | **asked but no answer** | `DIMENSION = unresolved` (Zero-B) | same | — |
| 3 | **unknown** | `Unknown` — *"no evidence either way"* | `25D.7 case A` | ✅ **`zero_reference.py`** |
| 4 | **absent** | `Missing` — *"expected governed artifact absent"* | `25D.7 case B` | ✅ **`zero_reference.py`** |
| 5 | **no evidence found** | `NO_EVIDENCE ≠ INVALID_EVIDENCE`; abhāva classifier's `Unknown` | Zero laws; abhāva pipeline | — |
| 6 | **searched but insufficient** | `Insufficient` | `25D.4` | ✅ **`zero_reference.py`** |
| 7 | **deliberately omitted** | `NotApplicable` / `Atyantābhāva` (impossible by constraint) | `25D.4`; abhāva | ✅ **`zero_reference.py`** |
| 8 | **deleted** | `Pradhvaṃsābhāva` — *existed, removed* | abhāva typology | — |
| 9 | **never existed** | `Prāgabhāva` — *prior absence* | abhāva typology | — |

**Eight of nine have a named corpus construct. Four of them are executable, tested, passing code.**

### 1.1 The executable proof

`zero_reference.py`, run this pass (exit 0, 8/8 falsification tests PASS), implements the ten-value
set of `25D.4 + 25D.7` and discriminates in code:

```python
return "Missing"   # expected governed artifact absent (25D.7 case B)
return "Unknown"   # no evidence either way            (25D.7 case A)
```
and its output distinguishes them side by side:
```
CurrentVersionKnown          Satisfied
BackupVerified               Unknown   <- gap
GovernanceApprovalObtained   Missing   <- gap
```

Plus the scalar-collapse counterexample:
```
vecA = {"r1":"Missing","r2":"Unknown"}    vecB = {"r1":"Unknown","r2":"Missing"}
|gaps(A)| = |gaps(B)| = 2, yet A != B as work plans (PASS)
```

> **Any claim that this corpus "cannot distinguish `unknown` from `absent`" is falsified by running
> a script the corpus already contains.**

### 1.2 The five non-collapse laws, stated by the corpus itself

`20260826-105229` (band A, pre-programme):

| Law | Gloss (verbatim) |
|---|---|
| `UNKNOWN ≠ ABSENT` | Not knowing ≠ knowing it doesn't exist |
| `UNRESOLVED ≠ INVALID` | Not resolved ≠ false |
| **`NOT_ASSESSED ≠ LOW_CONFIDENCE`** | **Not checked ≠ low confidence** |
| `NOT_APPLICABLE ≠ UNKNOWN` | Doesn't apply ≠ unknown |
| `NO_EVIDENCE ≠ INVALID_EVIDENCE` | No evidence ≠ evidence against |

and boxed: **`UnknownValue(D) ≠ UnknownDimension(D)` — *"These must never collapse into one state."***

Step 271.22, independently, in band B: **`Missing ≠ False`**, and `EvalPolicy` *"may need a third
result: `Undetermined`."*

### 1.3 Where the distinctions live — the four-kind separation

| Kind | Lives in | Construct |
|---|---|---|
| **ontological** — the world lacks it | `W` | the four abhāva types |
| **observational** — `Ω` cannot see it | `Ω` | 31.18–31.19 |
| **inquisitorial** — nobody asked | **`D_t`, recognised dimensions** | `DIMENSION = not assessed` |
| **representational** — asked, answered, unstored | `K` | `VALUE = unknown` (Zero-A) |

### 1.4 What is actually missing — one component, precisely

`K = (𝒜, ℛ)` has **no recognised-dimension set `D_t`**. Executed: a dimension never assessed and a
dimension assessed with no result **both render as *no assertion mentioning that dimension***. They
are **provably indistinguishable in `K`**.

The Zero model does not have this problem because it carries the layer:
```
Ω    = potential knowledge space        D_t  = dimensions currently recognised
K_t  = state over recognised dimensions Z_t  = Ω \ Represented(K_t)
```
With `D_t`, *"not assessed"* is `D ∈ D_t ∧ ¬∃a ∈ 𝒜 : a.P.D = D` — decidable in `O(n)`.

### 1.5 Is the distinction *required* by the theory's stated purpose? (mandate §11)

**Yes — and this is not a preference.** The theory's purpose is **governed knowledge transformation**.
Its own admissibility law blocks on `Unknown` (`42.12`, executed: `Unknown → Block`). **A gate that
must block on `Unknown` and cannot tell `Unknown` from `not asked` will block identically on a
question nobody has posed and on a question that has defeated investigation** — which are different
governance situations requiring different acts. **The distinction is load-bearing for the gate
algebra the theory already has.**

## 2. Uncertainty

| Question | Answer |
|---|---|
| qualitative uncertainty | ✅ `U(H).type ∈ {Interval, SetValued, Qualitative}` (31.24) |
| quantitative probability | ✅ typed: `q = (H, P, Model, Context, Time, Evidence)` (31.22) |
| confidence | 🟡 present in prose; **never distinguished from `str`** |
| evidential strength | 🟡 `Σ.str`, ordinal, **with no rule to compute it** |
| epistemic status | ✅ ten values (`25D.4`), executed |

**`strength ≠ probability ≠ status` — the corpus enforces this**, at 31.23:
> `Unknown(H)` **is a TYPE DISTINCTION, not `P(H)=0.5`.**

### 2.1 The typed object exists

```
U(H) = (type, value, model, scope, source)
type ∈ { Probability, Interval, SetValued, Unknown, Qualitative }
```
*"This is more flexible than forcing every assertion into a probability."*

**The claimed theory's stated reason for excluding uncertainty — *"no probability space in 1664
files"* — is answered by a construction that deliberately does not require one.**

### 2.2 Is uncertainty required, optional, external, parametric, or out of scope?

**PARAMETRIC, with a required carrier.** The corpus's position is consistent across three
independent places:

- 31.24 makes the **type** a parameter (`Probability` is one of five options, not the model);
- `no averaging` (42.10) and the ordinal `str` finding forbid arithmetic on unqualified scales;
- `exp01_recheck.py`, executed this pass, **proves** no scalar operator suffices as the foundation.

> **So: the *carrier* `U(H)` is required; the *regime* filling it is parametric.** That is exactly
> `H₂₇₁`'s shape, arrived at independently.

### 2.3 What is genuinely absent

- **`U`'s algebra** — no `U × U → U`, no equality on `U`, no rule for combining `Interval` with
  `Probability`.
- **`U`'s relation to `Σ`** — both grade epistemic force; nothing states how they relate.
- **`Threshold` has no type.** Numeric thresholds (`0.1, 0.6, 0.73, 0.8, 0.95, 10, 10.2`) are applied
  with **no scale type declared**, and **`θ` is simultaneously used for a threshold and for the
  sample mean `(1/n)Σᵢ Xᵢ`.** The corpus cites Roberts on meaningfulness and has a `no averaging`
  law — and never applied either to its own thresholds. **New finding; on no prior register.**

## 3. Verdict

| | Verdict |
|---|---|
| **Missingness** | 🟡 **8 of 9 distinctions EXIST in the corpus; 4 EXECUTE.** The gap is **one missing component (`D_t`) and a vocabulary that hides the capability** — `G4 governance` + `G3 structural`, **not** an expressive limit |
| **Uncertainty** | 🟡 **typed carrier EXISTS (31.24); its ALGEBRA does not.** `G3` on the algebra, `G1 parametric` on the regime |
| **`Threshold`** | 🔴 **untyped, and conflated with an estimator — `G3` + `G6`** |
