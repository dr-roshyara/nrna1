# 03 — Bootstrap Boundary (mandate §§5, 15)

## §5 The Step 288 claim, classified

> **Step 288:** *"the bootstrap boundary is `𝒪`/`𝒯` ratification, decidable by authority rather than derivation."*

Against the mandate's A–F scale:

$$\boxed{\textbf{F — NOT ESTABLISHED. And, as stated, REFUTED.}}$$

| Evidence | |
|---|---|
| **EXECUTED** | `cut {𝒪, 𝒯} → acyclic? **False**`. Removing both leaves all four cycles intact |
| **EXECUTED** | `cut {≡} → acyclic? **True**` — unique minimal cut, size 1 |
| **structural** | `𝒪` and `𝒯` are not members of any cycle; they are upstream sources |

⚠️ **What survives of the Step 288 claim:** `𝒪`/`𝒯` **is** a hard blocker, and **no incoming derivation
edge into it was found** (`01`). **That part stands.** What fails is the inference from *"it is an
unresolved prerequisite of the cycles"* to *"it is the cycle-breaking node."*

## §5 Is there a derivation route to `𝒪`/`𝒯`?

| Candidate route | Verdict |
|---|---|
| derive `𝒪` from `K` | 🔴 circular — `K` is downstream of `minimality ← congruence ← 𝒯` |
| derive from invariants `ℐ` | 🔴 `ℐ` is downstream of `≡` (`287`) |
| derive from a minimality proof | 🔴 `259`: *"no valid minimality proof before transformation congruence analysis"* |
| derive from the ratified 8 primitives | 🔴 `C-022`/`049` ratifies **primitives**, not operations; the 8 have **never been shown minimal against an operation space** |
| enumerate the forced lower bound | 🟡 `256.2`/`259.7` give **14 forced, upper bound 18** — a **candidate set**, never a **membership rule** |
| **declare membership** | ✅ the only route with no unmet precondition |

$$\boxed{\begin{array}{c}\textbf{No derivation route to } \mathcal O/\mathcal T \textbf{ was found in the ratified material.}\\ \textbf{This is an ABSENCE OF EVIDENCE for an incoming edge, not a proof that none exists.}\end{array}}$$

⚠️ **`225.11`/`224.11` discipline: absence of evidence stays `Unknown`.** A later step may find a
derivation; this finding would then be **SUPERSEDED**, not contradicted.

## §15 THE MINIMAL CUT SET — computed, not preselected

$$\boxed{\textbf{Minimal feedback vertex set} = \{\equiv\} \qquad \textbf{size 1, unique}}$$

**Exhaustive search over all 12 decision-resolvable nodes, sizes 1–4. Exactly one cut of size 1; it is
the only minimal cut.**

> ### And the corpus said so all along
> `261.23`: *"final kernel selection must stop while **equality** remains ambiguous."*
> **The corpus located the block at equality. Step 288's `𝒪`/`𝒯` gloss moved it, and the computed graph
> moves it back.** ✅ **Independent confirmation of `261.23`'s own wording, by a method `261` did not use.**

## The two boundaries are DIFFERENT, and both are needed

| | Node(s) | What it does | Class |
|---|---|---|---|
| **ACYCLICITY boundary** | **`{≡}`** | breaks all four feedback loops | resolving it is **NORMATIVE** (`N-1` + Decision 3) |
| **DERIVATION boundary** | **`{𝒪, 𝒯}`** | unblocks the 8 derivable prerequisites (`≈`, congruence, minimality, bindings, canonicalization, merge, invariants, sufficiency) | **NORMATIVE** — no derivation route found |

$$\boxed{\text{Cutting the cycles} \neq \text{unblocking the derivation. } \{\equiv\} \text{ does the first; } \{\mathcal O, \mathcal T\} \text{ does the second.}}$$

**Minimum set for *acyclicity*: `{≡}`. Minimum set for *progress*: `{≡, 𝒪, 𝒯}`.**
⚠️ **Neither is proposed, chosen, or populated here.**

### A dependency worth stating explicitly
`≡` can be resolved **two ways**, and they are not equivalent:
- **by declaration** — a governance act fixing `≡`; breaks the cycles immediately, and **does not need `𝒪`**
- **by derivation** — select `≡` by requiring congruence (`258.9`); **needs `𝒯` and `δ` first**

> **So `{≡}` is a cut only under the *declaration* route.** Under the derivation route the cycle
> reasserts itself through `𝒯 → congruence → ≡`. **This is the precise sense in which the problem is
> underdetermined: the formal material cannot select `≡`, and an authority can.**
> **No recommendation between the two routes is made** — that is `N-1`/`N-2` territory.

## STATUS
**EMPIRICALLY VERIFIED** minimal cut `{≡}`, size 1, unique, exhaustive over 12 nodes ·
**REFUTED** Step 288's `𝒪`/`𝒯` bootstrap claim as stated · **ESTABLISHED** two distinct boundaries
(acyclicity vs derivation); no derivation route into `𝒪`/`𝒯` found · **INTERPRETATION** that "declare
membership" is a legitimate mechanism — recorded as such, not as architecture · **NORMATIVE** `N-1`,
Decision 3, `𝒪`, `𝒯` · **DEFERRED** every choice
