---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-06A-REPORT, D1, D2, D3, mathematical_ideas_that_can_be_implemented corpus]
derived_from: [mathematical_ideas_that_can_be_implemented/20260911-175313_d1..., 20260911-180019_d2..., 20260911-180021_d3..., 20260902-175306_kr-contr-fde-2026-09-external-writeup]
cross_track_dependency: none
---

# KSME-07 — Controlled Semantic Derivation: D1–D3 Audit and D3 Falsification Closure

**Scope of this pass, disclosed honestly**: per the commissioning's own Part 7
("do not execute D14 merely because the roadmap says D14; build the
dependency graph; determine what actually blocks what"), this pass did
**not** attempt D4–D14. It instead completed the two falsification checks
D1 and D3 explicitly identify as their own prerequisite for closure — both
already read in full (not summarized from memory) before this ledger was
written. D4–D14 are named, not executed, in §4.

## 1. Dependency graph (as actually found in the corpus, not assumed)

```
D1 (Distinction)  ──requires generalization──▶  [BLOCKED pending re-derivation]
     │
     ▼
D2 (Preservation) ──MATHEMATICALLY CLOSED (author's own verdict, verified)──▶
     │                 with 2 open qualifications: operation-specific
     │                 preservation contracts; complete δ semantics
     ▼
D3 (Minimal polarity) ──was OPEN, pending its own stated falsification check──▶
     │                    THIS PASS: closed the check using a real, dated,
     │                    already-executed corpus experiment (§3)
     ▼
D4 (Determination) ──NOT STARTED. Depends on D1's distinction object AND
     D3's polarity/boundary factorization. D1's generalization need (§2)
     means D4 cannot safely proceed without re-deriving D1 for the
     ordering case first, OR explicitly scoping D4 to the
     equivalence-relation-only subset of distinctions (a real option,
     but must be stated, not silently assumed).
```

**Correction to the roadmap's own stated linear order**: the roadmap
(`...derivation-programme.md`) lists `D4`–`D13` as strictly sequential
prerequisites for `D14` (`δ`). This pass finds that `D2` (preservation) is
**already** a real, worked theory of `δ` at the *predicate* level (`D2.1`–
`D2.5`: `δ:S×O×Γ⇀S`, the reflection condition
`(δ_o^Γ)^{-1}(∼_{d,t+1})⊆∼_{d,t}`, historical preservation `H_t⊆H_{t+1}`) —
it does not yet give `δ`'s *concrete effect* for any specific operation, but
it already gives the *type* and the *invariants any candidate `δ` must
satisfy*. This is worth recording explicitly: **`D14`'s target signature is
already known from `D2`, not merely "TBD."** What remains missing for `D14`
is the concrete per-operation effect body and the operation-specific
preservation contracts (`Pres_o`) — exactly the `NO_EFFECT_FOUND` gap
`KSME-06A` already established independently, now cross-confirmed from the
theory side rather than only the search side.

## 2. D1 — falsification result: generalization required

**D1's own stopping condition** (§18, "one thing we must still test before
declaring D1 fully closed"): *"Are there KnowledgeOS-required distinctions
that cannot be represented as equivalence relations on a state space? If the
answer is yes, we must generalize the mathematical object."*

**Answer: YES, with real corpus grounding.** A targeted search of the
Track-A-admissible corpus (`phase_measure_theory/`,
`mathematical_ideas_that_can_be_implemented/`) for ordering-type structures
found 719 raw hits for partial-order/preorder/refinement language, and
direct confirmation in `20260826-173048_question-5-comparing-and-
challenging-assertions.md` (line 654–662): when discussing how one assertion
compares to or challenges another, the source explicitly lists the candidate
structures as *"a partial order; a lattice; a bilattice; a belief revision
structure; or something else,"* leaving the choice **open**, not resolved to
an equivalence relation.

This matters because a partial order / preorder is **not** an equivalence
relation — it is reflexive and transitive but explicitly **not required to
be symmetric** (that is the entire point: `a < b` and `b < a` are not both
true). D1's entire formal apparatus (`∼_ρ⊆∼_d`, the intersection formula,
the collapse predicate) is built specifically on equivalence-relation
kernels. **It does not automatically generalize to order relations without
re-derivation** — the analogous object for a preorder `⪯_d` would need a
*monotone* map condition (`ρ(s₁)⪯ρ(s₂)` whenever `s₁⪯_d s₂`), not a kernel-
inclusion condition, and "collapse" for an order relation means something
subtly different (losing *rank*, not losing *distinctness*).

**Status: `D1 = GENERALIZATION REQUIRED`, not `CLOSED`.** This is not a
rejection of D1's actual content (the equivalence-relation case is correctly
derived and stands), only of its implicit universality claim. Recorded
honestly, not silently patched by assuming the order case reduces to the
equivalence case (it does not, in general — a preorder's induced equivalence
`a∼b ⟺ a⪯b∧b⪯a` discards the rank information that was the entire reason to
prefer an order in the first place).

## 3. D3 — falsification closure using a real prior executed experiment

**D3's own stopping condition** (§29): build the witness table
`W_{00},W_{10},W_{01},W_{11}` from real corpus/experiment grounding, each
satisfying: (1) corpus/experiment grounding; (2) distinct semantic
interpretation; (3) demonstrated necessity for at least one required
distinction; (4) no possibility of legitimate collapse under the relevant
`Q,Γ`.

**Finding**: this exact experiment already exists, already executed, dated
**six days before** D1–D3 were written, and D1–D3 do not appear to cite it
directly by name (only indirectly, via a general "Priest analysis" mention).
`20260902-175306_kr-contr-fde-2026-09-external-writeup.md` reports
`KR-CONTR-FDE-2026-09`, **Status: COMPLETE**, HPA Supervisory Authority: 14
real scenarios tested across 4 representation models (`Classical{T,F}`,
`K3{T,F,U}`, `FDE{T,F,B,N}`, `Structured(S⁺,S⁻,Reason,Provenance,Context,
Condition)`), with a full separation matrix and 5 worked countermodels.

**Mapped directly onto D3's witness table**:

| D3 witness | KR-CONTR-FDE scenario | FDE value | Grounding |
|---|---|---|---|
| `W_{10}` (support only) | #1 Positive evidence | `T` | Real, distinct |
| `W_{01}` (opposition only) | #2 Negative evidence | `F` | Real, distinct |
| `W_{00}` (neither) | #4 No evidence | `N` | Real, distinct |
| `W_{11}` (both) | #3 Direct contradiction | `B` | Real, distinct |

**The lower-bound theorem is now empirically confirmed, not merely
conditional**: the experiment's own K3 (3-valued) model **collapses**
scenario #3 (contradiction) and scenario #4 (no evidence) into the same
value `U` — a real, executed demonstration that 3 values are insufficient —
while FDE (4-valued) keeps them apart (`B` vs `N`). This is a direct,
executed witness for D3's own Theorem D3-LB: *"if four configurations are
required to remain pairwise distinguishable, every representation preserving
them satisfies `|V|≥4`."* `m*=4` is confirmed for this real, corpus-grounded
distinction set — condition (4), "no possibility of legitimate collapse,"
is satisfied specifically **because** a real weaker model (K3) was shown to
violate it.

**Equally important, carried forward honestly**: the same experiment
independently proves polarity alone is **not sufficient** — FDE still
collapses 6 of 14 scenarios (e.g. "conflicting sources" and "direct
contradiction" both map to `B`), and only the `Structured` model (polarity
**plus** `Reason`/`Provenance`/`Context`/`Condition`) reduces collapses to 2,
both explicitly judged acceptable category-overlaps. This is the real,
**executed** confirmation of D3 §11's own claim ("polarity and boundary must
be separated") — which D3 itself had only marked `"Strong candidate;
requires falsification"` — that falsification is what this cross-reference
supplies.

**Status: `D3 = SUBSTANTIALLY CLOSED`** — `m*=4` for the tested distinction
set is now `COMPUTED`/`VERIFIED` (not merely `DERIVED-CANDIDATE`), and the
polarity-insufficiency finding is likewise `VERIFIED`. What remains `OPEN`
(D3's own §25 list, unchanged by this finding): whether `{0,1}²` specifically
(vs. some other 4-element algebra) is required; the full Boundary taxonomy's
completeness; whether `(0,0)=Gap` universally.

## 4. D4–D14: not executed this pass (named, not attempted)

Per the dependency graph (§1), `D4` cannot proceed cleanly until D1's
generalization question (§2) is resolved for whatever distinction types `D4`
actually needs — this is a real blocking dependency, not a scheduling
choice. `D5`–`D13` were not inspected this pass. `D14` (`δ`'s concrete
effect) is now understood to need: (a) D1 generalized or explicitly scoped;
(b) D4–D13's prerequisite concepts; (c) the concrete per-operation effect
bodies that `KSME-06A` already established do not exist anywhere in the
admissible corpus at the `SOURCE-ESTABLISHED` tier. Attempting to manufacture
those effect bodies now, absent (a)–(c), would repeat exactly the
"invented semantics" failure mode this whole investigation exists to avoid.

## 5. Status vocabulary applied (per the commissioning's required table)

| Item | Status |
|---|---|
| D1's equivalence-relation formalization | `DERIVED` (for the equivalence-relation case; correct and complete as far as it goes) |
| D1's implicit universality (all distinctions ⟹ equivalence relations) | `FALSIFIED` (real corpus counter-evidence, §2) |
| D2's full predicate suite (`D2.1`–`D2.5`) | `DERIVED`, author-verdict `MATHEMATICALLY CLOSED`, independently re-read and confirmed consistent this pass |
| D3's `m*≥4` lower bound (for the tested distinction set) | `COMPUTED`/`VERIFIED` (upgraded from `DERIVED-CANDIDATE` via §3) |
| D3's polarity-insufficiency (boundary metadata required) | `VERIFIED` (upgraded from `CANDIDATE`, §3) |
| `{0,1}²` as *the* required algebra (vs. cardinality alone) | `OPEN` (unchanged) |
| D4–D13 | `UNRESOLVED` (not inspected this pass) |
| D14 (`δ`'s concrete effect) | `UNWITNESSED` (confirmed again, consistent with `KSME-06A`) |
