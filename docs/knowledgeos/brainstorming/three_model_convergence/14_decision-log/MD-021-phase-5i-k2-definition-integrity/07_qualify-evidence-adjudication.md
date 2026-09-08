# Phase 5I — `Qualify`/Evidence Adjudication (the A–E classification, per the authorization's §8)

## The three variants, re-stated

1. `Qualify: Observation → Evidence` (seq 0630 §49.76, `Evidence = QualifiedObservation`).
2. `Qualify: Observation × Policy → Evidence` (D285-6's own gloss of the K-1→K-2 projection).
3. `CaptureAndQualify(O)` (seq 0795 §170.4, a 1-argument gate with a named-conditions checklist).

## Testing A–E

- **A. Equivalent formulations**: **Not evidenced.** No document states variant 2's extra `Policy`
  argument is compatible with variant 1/3's single-argument form (e.g., via currying or a default).
- **B. Partial specialization**: **Plausible but not evidenced** — variant 2 could be read as a
  *more general* version of variant 1 (adding a `Policy` parameter), with variant 1 as its
  `Policy`-independent specialization — but no document draws this connection explicitly.
- **C. Temporal evolution**: **Weakly supported by dating alone** — seq 0630 (2026-08-28) and seq 0795
  (2026-08-29) both predate the D285 package (2026-08-31) by 2–3 days, which is *consistent with*
  D285-6's own 2-argument form being a later refinement, but **dating alone is not sufficient
  evidence** (per this reconstruction's own standing discipline against inferring derivation from
  chronology alone).
- **D. Distinct competing definitions**: **Also plausible** — variants 1/3 and variant 2 could simply
  be two independently-authored characterizations that were never reconciled, exactly like the
  Assertion-unpacking conflict (`02`).
- **E. Unresolved**: **The governing verdict** — B, C, and D are each partially supported, none
  decisively, and no document adjudicates between them.

## Type-level / predicate-level / algorithm / implementation / governance distinction (per the authorization's §8's own required breakdown)

| Level | Status |
|---|---|
| Type-level definition | **EVIDENCED**, though with a 1-vs-2-argument inconsistency (`04` of Phase 5H, restated) |
| Predicate-level definition | **PARTIALLY EVIDENCED** — the "potential conditions" checklist (seq 0795) names *categories* of conditions but does not formalize them as an evaluable logical predicate |
| Algorithm | **NOT EVIDENCED** |
| Implementation | **NOT EVIDENCED** — none of the three executable scripts implements `Qualify`; `e_equality.py`'s `A(...)` constructor takes `e` as a raw given parameter, never derives it from an `Observation` via any `Qualify`-like function |
| Governance rule | **NOT EVIDENCED** — no ratification record for `Qualify` itself was found (contrast K-1's own explicit `FA-4`/`D-FA-6` ratification) |

## Verdict

**E — UNRESOLVED**, with the specific, disclosed structure that (B) partial-specialization and
(C) temporal-evolution are the two most plausible readings, neither confirmed. **Not computable
anywhere** is confirmed independently at the implementation level: this phase specifically checked
whether any of the three executable scripts actually calls a `Qualify`-shaped function, and found
none does — `e_equality.py`'s `e` parameter is supplied directly by the caller in every worked example,
never derived from an `Observation` argument through any qualifying step.
