---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-07-D4-D14-DERIVATION-LEDGER, mathematical_ideas_that_can_be_implemented/20260911-174914, mathematical_ideas_that_can_be_implemented/20260911-174632]
derived_from: [same]
cross_track_dependency: none
---

# KSME-08 — D4 Dependency Audit

**Method**: read D4's actual intended scope from the corpus's own text (not inferred from the
filename), per the commissioning's explicit instruction. Two candidate "D4"s exist in this corpus,
disclosed rather than silently picking one.

## D4, ambiguity disclosed

- **`174914`'s canonical D1–D27 programme (D4 = "EVal information sufficiency")** — this is the
  numbering the actually-executed `D1`/`D2`/`D3` files follow (`D1`=Distinction, `D2`=Preservation,
  `D3`=Minimal polarity, matching `174914` exactly), so this is treated as the authoritative "D4" for
  this audit.
- **`174632`'s earlier, informal mini-programme (its own "D4" = "Gap projection")** — a *different*
  object, superseded in numbering by `174914` but worth checking too, since the user's KSME-08
  commissioning does not specify which.

Both are audited below; **both turn out to need the same thing**, which strengthens rather than
complicates the conclusion.

## D4 (canonical, `174914` §6): "EVal information sufficiency"

Verbatim scope: *"Investigate whether `EVal=⟨V,P,W,B,C,…⟩` is necessary... Necessity test: Remove one
component: `EVal^{-P}` and ask whether two semantically distinct cases collapse. If `E1≢E2` but
`EVal^{-P}(E1)=EVal^{-P}(E2)`, then `P` is necessary... Desired output: `EVal_min`."*

This is a **collapse-detection test** — structurally identical to D1's own `Collapse(ρ,d,s1,s2)`
predicate (§9 of D1: `(s1≁_d s2) ∧ (ρ(s1)=ρ(s2))`), and to the marginal/joint relevance tests this whole
investigation has used repeatedly (KSME-01's `p3⊕p4` lesson, KSME-04/05's operation-relevance lattice).
It requires exactly two things:

1. A notion of "two cases are semantically distinct" — `E1≢E2` — which is a **distinguishability
   judgment**, i.e. an equivalence-relation-shaped question (are they in the same class or not), not a
   ranking question.
2. A notion of what a coarser representation "collapses" — again a **kernel-of-a-map** question,
   equivalence-relation-shaped by construction (`ρ(E1)=ρ(E2)` is literally testing membership in the
   same fiber).

**No ranking, ordering, or comparison operator appears anywhere in D4's own stated scope.** It never
asks "is `E1` stronger/more-supported/more-refined than `E2`" — only "are they the same or different, and
does a candidate representation preserve that."

## D4 (informal, `174632`'s own numbering): "Gap projection"

Verbatim scope: *"`EVal → Unresolved → Δ(Q,Γ,K)`. Establish whether `r∉R_req ⇒ r∉Δ`."*

This is a **set-membership** question (`r`'s presence in `R_req`, `r`'s presence in the gap set `Δ`) —
again, no ranking or comparison operator. `r∉R_req ⇒ r∉Δ` is a logical implication over set membership,
not an order-theoretic statement.

## Verdict

$$
\boxed{\text{D4, under EITHER candidate reading, requires only the equivalence/distinguishability
layer (}D1_E\text{) — not the ordering layer (}D1_O\text{).}}
$$

This is `Case A` per the KSME-08 commissioning's own taxonomy: D4 is **genuinely unlocked** by the
`D1_E`/`D1_O` separation. It does not require resolving what structure (preorder / partial order /
lattice / bilattice) `D1_O` should eventually take — that question remains open, named, and deferred,
but it does not block D4 from proceeding.

**Caveat, disclosed**: this audit covers D4 only. D5 onward in the `174914` programme includes explicitly
comparative material (`D5` — "Provenance versus warrant," testing whether more provenance implies more
adequacy; later steps discuss `≽`, epistemic progress ordering) that **will** need `D1_O` once reached.
This is not a re-opening of the blocker — it is naming, honestly, where the deferred question resurfaces,
consistent with the commissioning's own instruction not to assume the separation solves everything
permanently.
