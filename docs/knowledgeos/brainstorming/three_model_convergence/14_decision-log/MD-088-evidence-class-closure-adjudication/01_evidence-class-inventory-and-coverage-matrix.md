# MD-088 §01 — Evidence-Class Inventory and Coverage Matrix

## The nineteen classes (per the mission's own §2)

1. Explicit textual identity · 2. Explicit predecessor/successor statement · 3. Explicit refinement/
extension statement · 4. Explicit projection/restriction statement · 5. Formal mathematical equality ·
6. Formal definitional equality · 7. Type-preserving instantiation · 8. Explicit parameter fixing ·
9. Equation-level correspondence · 10. Dependency-level correspondence · 11. Worked-example
correspondence · 12. Input/output behavioral correspondence · 13. Operational/executable
correspondence · 14. Explicit bounded-context/context mapping · 15. Explicit source citation/cross-
reference · 16. Explicit "these are alternatives" statement · 17. Explicit abstraction/simplification
statement · 18. Explicit notation-change statement · 19. Explicit transformation rule between
representations.

No twentieth class was suggested by the corpus itself during this phase's own targeted checks; the
list is not asserted to be exhaustive beyond what was investigated.

Legend: **NF** = tested, not found · **NA** = tested, not applicable (the class does not meaningfully
apply to this pair's own structure) · **UNTESTED** = genuinely not yet investigated, named explicitly.

## Pair 1 — `EC₀` ↔ T21 `EC`

| Class | Status | Basis |
|---|---|---|
| 1 Textual identity | NF | 425-file sweep, MD-087 |
| 2 Predecessor/successor | NF | same |
| 3 Refinement/extension | NF | same |
| 4 Projection/restriction | NF | same |
| 5 Formal mathematical equality | NA | `EC` is a data container, not a function — no equation to test |
| 6 Formal definitional equality | NF | field-by-field comparison, MD-086 — partial match only |
| 7 Type-preserving instantiation | **UNTESTED** | whether `EC₀`'s 7-tuple could be viewed as one instance of a more general type `EC₆`/`EC₇` also instantiates has not been formally tested |
| 8 Parameter fixing | NA | no parameter-fixing language relevant to `EC` found |
| 9 Equation-level correspondence | NA | no equation relates the two forms |
| 10 Dependency-level correspondence | NF (partial) | both feed a `Req(EC)`-shaped function — a real, if thin, correspondence, MD-086 |
| 11 Worked-example correspondence | NF | **new this phase** — T22's own worked example uses `EC` only as a bare, uninstantiated symbol, confirmed by direct check; no concrete instance exists to compare |
| 12 Behavioral correspondence | NA | `EC` is not itself an executed function |
| 13 Operational/executable correspondence | NF | `kos/inquiry.py`'s own 4-field `EC` checked against both — no clean match (MD-086/087) |
| 14 Context mapping | **UNTESTED (partial)** | a general DDD framing exists (Determination Context, MD-078 §01) but no formal, rigorous context-mapping test has been applied specifically to this pair — see §02 |
| 15 Source citation | NF | MD-069's own independence check |
| 16 "Alternatives" statement | NF | **new this phase** — zero hits |
| 17 Abstraction/simplification statement | NF | **new this phase** — zero hits specific to `EC` (one generic, unrelated corroborating finding located, see §00) |
| 18 Notation-change statement | NF | **new this phase** — zero hits specific to `EC` |
| 19 Transformation rule | NF | consistent with all of the above |

## Pair 2 — `r_A` ↔ `r_B`; Pair 3 — `r_I` ↔ `r_B`

Classes 1–6, 15–19 tested `NF` across the same 425-file window (MD-087, this phase's own re-scoped
sweep for classes 16–18). Class 7 (type-preserving instantiation) — **UNTESTED**: whether `r_A`'s own
seven fields could be read as one instantiation of `r_B`'s own abstract sort has not been formally
tested (no field list exists in `r_B`'s own Def 2.18 to test against, so this may in fact be `NA` rather
than `UNTESTED` — recorded as `UNTESTED/LIKELY NA`, not resolved either way). Class 9 (equation-level) —
`NA`, no equation relates them. Class 10 (dependency-level) — `NF` (partial): both feed `Req(EC)`-shaped
sets. Class 11 (worked-example) — **UNTESTED**: no check has been made for whether any worked example
anywhere instantiates a concrete `r` with fields checkable against both `r_A`'s tuple and `r_B`'s
abstract sort. Class 13 (operational) — `NF`, `r_I`'s own source checked directly (MD-087), no
citation. Class 14 (context mapping) — **UNTESTED**, same caveat as `EC`.

## Pairs 4–6 — `Γ` family

Classes 1–6, 15–19 tested `NF` across all 21 T21 parts (MD-086/087, this phase's own re-scoped sweep
for 16–18, zero hits). Class 7 — **UNTESTED**: whether `Γ_C`'s own 4 named fields could be read as a
type-preserving partial instantiation of `Γ_B`'s own 7-tuple has not been formally tested beyond the
field-name comparison already performed. Class 9 — `NA`. Class 10 — **UNTESTED**: no check has been
made for whether `Γ_C`/`Γ_D`/`Γ_E` and `Γ_B` are ever *consumed by the same downstream function* in a
way that would constrain their relationship. Class 11 — **UNTESTED**: no worked example anywhere has
been checked for a concrete `Γ` instance. Class 14 — **UNTESTED**, same caveat.

## Pairs 7–10 — `Sat` family

Classes 1–6, 15–19 tested `NF`/`NF` extensively (MD-086/087). Class 7 — `NA` for most pairs (arity
differences make instantiation-of-a-common-type not meaningfully posable without inventing one). Class
9 (equation-level correspondence) — **the one class most directly relevant to `Sat`, already
substantially tested**: `[Def 6.18]`'s own equation (`Sat=Det_r∘EvalReq`) is the one genuine equation-
level correspondence found anywhere in this family (MD-078/082), already fully incorporated into every
prior phase's own findings. Class 10 (dependency-level) — `NF` (fully tested): `Sat(K,r,Γ)`'s own wiring
into `Δ_p`/`Zero_p` (MD-082) *is* a dependency-level correspondence, already established and central to
this whole reconstruction's own central correction. Class 11 (worked-example) — `NF`: T22's own worked
example is the flagship instance, already fully examined (MD-076/082) — it stipulates `Sat` values
directly, never deriving them, which *is* the answer to this evidence class, not an untested gap. Class
13 — `NF`, `kos/inquiry.py`/`kos12` checked directly (MD-087). Class 14 — **UNTESTED**, same caveat as
the other three families.

## Summary of genuinely untested classes, corpus-wide

- **Class 7 (type-preserving instantiation)**: untested or ambiguously `NA` for `EC`, `r`, `Γ`; largely
  `NA` for `Sat` given established arity incompatibilities.
- **Class 10 (dependency-level correspondence)**: partially tested for `EC`/`r`; **untested** for `Γ`;
  fully tested and already central to the reconstruction's own findings for `Sat`.
- **Class 11 (worked-example correspondence)**: now closed for `EC` and `Sat` (both `NF`, one new this
  phase); **untested** for `r` and `Γ`.
- **Class 14 (formal DDD context-mapping)**: **untested, rigorously, for all four families** — a
  general framing exists (bounded-context candidates named in Part III §3.58, MD-078) but no pair-by-
  pair formal mapping test has been performed anywhere in this reconstruction. This is the single most
  consistent, cross-family gap found by this audit — addressed directly in §02.
