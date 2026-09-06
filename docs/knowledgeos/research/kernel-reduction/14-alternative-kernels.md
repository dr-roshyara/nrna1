# 14 — Alternative Kernel Candidates (Parts XVII & XVIII)

## The operator substitution graph (Part XVII)

An edge exists **only** where an explicit construction exists (§10 C).

```
        Determine ──┐
                    ├──> DetectGap          (norm-comparison + difference-decision)
        Discriminate┘

        DetectGap ─────> Discriminate       (difference-decision)

        Observe · Interpret · Represent · Relate · Hypothesize · Infer ·
        Challenge · Validate · Revise · Determine · Select · Qualify
                        ── no incoming edge ──
```

| Node class | Members |
|---|---|
| **irreducible nodes** (no donor set) | Observe, Interpret, Represent, Relate, Hypothesize, Infer, Challenge, Validate, Revise, Determine, Select, Qualify |
| **derived nodes** | DetectGap (from `{Determine, Discriminate}`), Discriminate (from `{DetectGap}`) |
| **strongly coupled cluster** | `{DetectGap, Discriminate, Determine}` — a 2-cycle plus a donor |
| **operator bundle (no substitution, joint necessity)** | `{Hypothesize, Infer}` supplying the critical lane |
| **ambiguous nodes** | Revise (mechanism vs domain concept) |
| **unsupported nodes** (evidence, not structure) | Hypothesize (2 role files), Select (3), Represent (5) |

Note the graph contains a **2-cycle**: `DetectGap ⇄ Discriminate` in one direction each. Exactly one
of the two may be removed — never both. This is why the substitution graph is more informative than
any operator count.

## Exhaustive search over minimal kernels (Part XIX)

All `2^14 = 16 384` subsets of `C0+` were evaluated against the full 25-capability requirement.

```
covering subsets            : 3
minimal (irredundant) covers: 2      both of cardinality 13
```

| Candidate | Members | Cardinality |
|---|---|---|
| **K_A** | `C0+ \ {Discriminate}` = Observe, Interpret, Represent, Relate, Hypothesize, Infer, **DetectGap**, Challenge, Validate, Revise, Determine, Select, Qualify | 13 |
| **K_B** | `C0+ \ {DetectGap}` = Observe, Interpret, Represent, Relate, **Discriminate**, Hypothesize, Infer, Challenge, Validate, Revise, Determine, Select, Qualify | 13 |

> `[EXP]` **The Pareto frontier is not a cardinality trade-off.** Both minimal kernels have exactly
> 13 operators. They differ only in *which packaging of the same 14 atoms* is chosen. Cardinality
> cannot discriminate between them — every other criterion can.

## Pareto evaluation — criteria kept separate, deliberately not collapsed

| Criterion | `K_A` (keeps DetectGap) | `K_B` (keeps Discriminate) | `C0+` (14, non-minimal) |
|---|---|---|---|
| cardinality | 13 | 13 | 14 |
| semantic coverage | 25/25 | 25/25 | 25/25 |
| compositionality | one operator carries 2 unrelated atoms | every operator ≤ 2 *related* atoms | redundant |
| **coupling** | **higher** — `DetectGap` must serve as the general discriminator for `Discrimination`, `Gap`, C19, C20 | **lower** — each power sits with its own responsibility | n/a |
| **domain cohesion** | **poor** — `DetectGap` becomes "gap detection *and* all discrimination" | **good** — `Discriminate` = *Buddhi*, `Determine` = adequacy | n/a |
| interpretability | a gap detector that also decides every alternative is hard to name honestly | matches the corpus's own operator families | n/a |
| falsifiability | same | same | same |
| implementation independence | same | same | same |
| **evidence strength** | contradicted by corpus MR-3 (`DetectGap ∈ 𝒪?`, a derived evaluation) | consistent with MR-3 **and** with SD-2 | n/a |

**No weighted score is computed.** Collapsing these into one number would manufacture false
precision, and a sensitivity analysis over invented weights would only measure the invention. The
criteria disagree in a *structured* way — coupling and cohesion both favour `K_B`, and evidence
strength breaks the tie in the same direction — which is more informative than any ranking.

## Recommendation on the frontier — provisional

`[PROP]` **`K_B` is the better-supported candidate**, on four independent grounds that agree:

1. **ablation** — `DetectGap` holds no exclusive atom;
2. **composition** — `Gap := difference-decision(norm-comparison(K_t, I_t))` type-checks with no smuggling;
3. **corpus** — Question 15 already classifies `DetectGap` into `𝒪?` derived evaluations, *not* the
   state-changing class, independently of and earlier than this experiment;
4. **DDD** — `DetectGap` has no invariant of its own and no independent reason to change.

**This is a research recommendation, not a canonization.** `K_B` is a *candidate*, its status is
`IRREDUCIBLE-CANDIDATE` member-wise, and §15 states what would falsify it.

## A structurally different candidate — the shape question (Q9) `[PROP]`

The strongest single structural result of the experiment is that **all 14 atoms are irreducible while
only 12 of 14 operators are**. That asymmetry says the operator set is the wrong level of description.

```
𝒦_exp = ( 𝒫 , ℛ , δ )

  𝒫 = 14 powers irreducible *relative to C1-C25 and this algebra*  (atoms; no redundancy)
  ℛ = the carrier derivation rules           (admissible composition relations)
  δ = state-mutation                         (the single commit to K_t)
```

Operators are then **packagings** of `𝒫` — a naming and cohesion layer, chosen for domain
cohesion rather than determined by the epistemics. Under this reading, `K_A` and `K_B` are not two
kernels but **two packagings of one kernel**, which explains cleanly why they have identical
cardinality and identical coverage.

`[PROP]` `[OPEN]` This is offered as a *shape hypothesis* requiring its own experiment, not as a
result. In particular it is untested whether `𝒫` is stable under a different capability
decomposition — V1 already showed that fusing two atoms is a defensible alternative model.
