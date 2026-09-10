# EKS-54 — A single theory rewrite defines its own core symbols six different ways

**Renumbering note**: initially drafted as `EKS-52`, found already taken by a same-day, concurrent
session's own ticket (`EKS-52-lanes-do-not-enumerate-each-other...`) at write time; `EKS-53` was also
already taken. Renumbered to `EKS-54`, the next free number, per this project's own established
same-day-collision discipline (see `EKS-07` and its own repeated prior instances).

## What was found

The "Theory-00-21" rewrite (`mathematical_ideas_that_can_be_implemented/20260906-*theory-part-*`, 21
parts plus two worked-example files, written in one continuous ~7.5-hour session on 2026-09-06) reads,
and is cited elsewhere in this reconstruction, as a single coherent theory — MD-067 through MD-077 cite
individual Part-VI definitions (`[05-40]`, `[05-41]`) as if they belong to one settled formal system.

A full, direct, part-by-part read of all 21 parts (performed for MD-078, `14_decision-log/
MD-078-controlled-operational-closure-construction/01_theory-object-registry-and-definition-evolution-registry.md`)
found this is not the case for the theory's own foundational symbols:

- **`r`** (a "requirement") is given **at least six mutually incompatible internal structures** across
  the 21 parts — including two *different* structures for the *same* named symbol *within the same
  file*, twice over (Part IX §9.2 vs §9.47; Part X §10.6 vs §10.27).
- **`Γ`** (context) is given **at least four mutually incompatible structured definitions** (a 7-field
  tuple in Part II; a 4-field-plus-ellipsis form in Part VIII; a differently-named 6-field `Γ_I` in
  Part X; a differently-named 8-field `Γ_R` in Part 21), none cross-referencing any other.
- **`Zero(·,·)`**, **`Det(·)`**, and **`Decision`/`Dec`/`D`** each show comparable arity and
  structural drift from Part to Part, with no reconciling document found anywhere in the 21 parts.

No part of the rewrite discloses this drift or points a reader toward a canonical choice among the
variants. Part I's own explicit promise ("the exact structure will be refined later," referring to the
`EC.Rules` field) is never honored in any of the remaining 20 parts — the same silence extends to every
other drifting symbol.

## Why this is load-bearing

This reconstruction's own citation practice (MD-067 onward) treats Theory-00-21 the way it treats any
single, internally-consistent primary source — citing one Part's definition without independently
verifying the other 20 parts agree. This finding shows that practice carries a real, demonstrated risk:
a future phase that cites, say, Part VI's own bare, unstructured use of `Γ` and separately consults
Part II's 7-tuple `Γ=⟨D,P,T,U,C,V,A⟩∈Ctx` for "the" definition of `Γ` would be silently combining two
objects the source itself never reconciles — the exact failure mode this whole reconstruction's own
standing discipline (`SAME OBJECT`/`RELATED OBJECT`/`UNRELATED_HOMONYM`, never merge without evidence)
exists to prevent, but which is easy to miss precisely *because* Theory-00-21 presents itself, and has
so far been treated, as one document rather than 21 loosely-coupled sketches.

This is not merely "interesting" corpus texture: any future authorized construction phase (e.g. a
`SAT-OPERATIONAL-CLOSURE-v1`-style effort, per `EKS-48`'s own still-open three-way decision) that
attempts to draw `r`, `Γ`, or `Zero`'s own internal structure from "the T21 rewrite" without first
resolving which Part's version is intended will be making an undisclosed construction choice among
several corpus-native candidates, not consuming a single corpus-native definition — a distinction this
project's own Construction Provenance discipline requires to be kept explicit.

## Why this is not already covered

Checked against `EKS-44` (Sat typed but never computed — a different, narrower finding, about the
missing computation rule, not about internal notational consistency), `EKS-45` (two-document
`K_t`/`Δ_t` bare-notation collision, extended to `Req`/`r` — concerns collisions *between separate
research threads*, not *within a single continuously-written document*), `EKS-47` (Γ has no definition
anywhere — corrected by MD-078 itself: Γ has *several* competing definitions, not none; this ticket is
the more precise, corrected successor finding for that specific claim), `EKS-50` (a third,
unintegrated classification/governance pipeline — a different kind of object entirely). None names the
specific, evidenced finding that a *single* document's own internal Parts disagree with each other.

## Recommended disposition

Not a request to reconcile the drift (that would be construction, requiring separate authorization per
this project's own standing discipline). Recommended: when any future phase next has occasion to cite
a Theory-00-21 Part-level definition for `r`, `Γ`, `Zero`, `Det`, or `Decision`, it should explicitly
name which Part's own formulation it is using and note, at minimum by reference to this ticket, that
other Parts of the same rewrite define the same symbol differently.

## Status

`PROPOSED`. Filed by MD-078 (`three_model_convergence/14_decision-log/model-boundary-decisions.md`,
this phase's own entry). Not authorized for action; governance disposition pending.
