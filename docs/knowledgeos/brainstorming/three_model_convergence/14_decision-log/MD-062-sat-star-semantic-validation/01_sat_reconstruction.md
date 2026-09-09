# MD-062 §01 — Reconstruct MD-061 Exactly; Locate Every Modelling Choice

## The candidate, reproduced verbatim from MD-061 §03

$$Sat^*(K_t,r) := 1 \text{ if } \pi_{\text{component}_r}(\Sigma_t(K_t)) \in \text{Accept}_r,\ \text{else } 0,
\qquad r=(\text{component}_r,\text{Accept}_r)$$

## Every modelling choice, scored against Phase A's own four questions

| Choice | Logically necessary? | Merely convenient? | Changes the meaning of satisfaction? | Equally defensible alternative exists? |
|---|---|---|---|---|
| **Requirement shape** `r=(component_r,Accept_r)` | **NO** — `Req(EC_t)` is untyped; nothing forces this shape | Largely — the minimal shape usable against `Σ_t`'s typed fields | **YES, substantively** — reduces "satisfaction" to a single-field value test, discarding cross-field interaction and `EC_t` entirely | **YES** — e.g. a joint constraint over multiple `Σ_t` fields, or (per `04`'s new finding) a requirement carrying its own Reason/Provenance/Context/Condition, matched against the state's boundary metadata rather than a bare value |
| **Set-membership, not an ordinal rule** | NO | Yes, primarily — the safest minimal choice | Comparatively small — membership can express a threshold as a special case (`Accept_r=\{v:v≥\theta\}`), so this choice does not by itself lose expressiveness | Yes — an explicit order relation, if evidence existed (it doesn't, MD-060) |
| **Restriction to `Σ_t`/V7-shaped `K_t`** | Yes, given the corpus's current typing state (only `Σ_t` was known typed at MD-061's own time) | — | **YES, substantively** — silently narrows "does `K_t` satisfy `r`" from a question over an 11-component object to a question over one component | Yes — `04` finds `C_t` is a second candidate; more may exist |

## What this reconstruction confirms, unchanged from MD-061

`Sat*` remains well-typed, deterministic, and passes T1/T2/T3/T7 exactly as MD-061 found (not
re-derived here — reused by reference, per this phase's own "do not modify MD-061" instruction).

## What this reconstruction newly exposes

**The requirement-shape choice is the load-bearing one for everything that follows.** By defining
`r` as `(component_r,Accept_r)` with `Accept_r` supplied directly, **`EC_t` — the very object M0043
declares makes satisfaction purpose-relative — never enters the construction at any point.** This is
not a simplification of `EC_t`'s role; it is its **total absence**, and is the central finding
developed in `02`–`04`.
