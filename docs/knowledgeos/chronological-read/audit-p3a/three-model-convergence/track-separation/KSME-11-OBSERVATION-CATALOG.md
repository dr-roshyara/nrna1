---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-11-BOUNDED-KERNEL-READINESS]
derived_from: [Step 258, 261; D285-7; 10-GOVERNANCE-HANDOFF-STEP-285-RATIFICATION; step-291/02; step-292/07; 30-THE-EIGHT-PRIMITIVES]
cross_track_dependency: none
---

# KSME-11 — Observation Catalog

## The 8 ratified primitives (verbatim)

$$K_t=\{Entity,\ State,\ Event,\ Observation,\ Proposition,\ Relation,\ Policy,\ Action\}$$

Source: `research/D285-1-STATE-ONTOLOGY-MATRIX.md` (`step-049`/claim-registry `C-022`, ratified `FA-4`,
"50 attack classes, no counterexample" — the highest tier used anywhere in this corpus). Independently
reconfirmed identically in `10-GOVERNANCE-HANDOFF-STEP-285-RATIFICATION.md` (2026-08-31 21:01) and
`30-THE-EIGHT-PRIMITIVES-HOLD-CONVERGENCE-ON-THE-ANSWER.md` (2026-09-01 02:26, an independent
philosophical-lane reconciliation, "0 new primitives"), and reconfirmed a third time at `38` (2026-09-01
22:19): "Still ZERO new primitives... Steps 288–291 unchanged." **Thread stable, unrevised, across a full
two calendar days.**

Caution (`30 §1`, same session): the eight are "the vocabulary in which state is expressed — not its
components, not its fields" — per `258.35`'s ban on defining `K` compositionally as a tuple. One live
defect: `Karma` double-mapped to both `Event` and `Action` in the philosophical-lane cross-check,
unresolved (`GK-Q4`).

**Terminological trap** (flagged by the investigating fork, not resolved by the corpus): `Observation` is
itself one of the 8 primitives — a *kind of thing a state can contain* — which is a different object from
an *observation function* `o:E→Y` used to build `∼_B`. The corpus does not appear to disambiguate these
two senses anywhere found; any future D1/D4-adjacent work touching this material must keep them separate.

## Where the "0 enumerated against 8 primitives" gap originates (traced to source)

`Step 261.21` (2026-08-30, same day as Steps 255/260 — read directly, not just cited): defines `𝒪_K`
(state observations) and asserts, in the same breath, that it is "the closed set of permitted state
observations" — **without ever enumerating it.** `261.22` states the correct dependency chain
(`Equality→Congruence→Quotient→Minimality`); `261.23` lists six reasons `≡_K` cannot yet be called final,
reason 1 being exactly "the observation set is not fully closed." **The gap was named on day one, not
discovered later** — `D285-7`'s restatement one day later ("nobody has enumerated `𝒪` against the ratified
8 primitives — and that, not minimality, is why `𝒪_core` cannot close") and `10-GOVERNANCE-HANDOFF`'s
governance act #4 ("determine and ratify `𝒪_core` against the ratified `K_t`" — listed, never taken) are
both restatements of Step 261.21's original finding, continuous across a 2026-08-30→09-01 thread.

## The observation-candidate inventory (verbatim from `step-291/02_observation-inventory.md`, 2026-08-31 23:24)

**10 candidates, 0 declared permitted**, case classified "C — semantically bounded, not formally closed":

| # | Observation | Source | Typed? | Executable? | Behavioral-relevance evidence | Inferred primitive (DERIVED — corpus never maps this) | Tier |
|---|---|---|---|---|---|---|---|
| 1 | `TraceOrigin(x)` | `258.15` | 🔴 untyped | untyped, unregistered | ✅ **already used as the corpus's own executed congruence counterexample** | Event/provenance | SOURCE-ESTABLISHED (existence) / DERIVED (mapping) |
| 2 | `ExplainRevision(x)` | `258.16` | 🔴 untyped | untyped, unregistered | ✅ same executed counterexample role, for revision history | Event | SOURCE-ESTABLISHED / DERIVED |
| 3 | `Assess(K,x)` | `259.7/8` | ✅ `K×X→Assessment` | typed, no body | ⚠️ corpus itself: "whether it is an observation is undetermined" (may be an operation, not an observation) | Proposition (weak) | HYPOTHESIS |
| 4 | `Authorize(...)` | `259.7/8` | ✅ | typed, no body | ⚠️ governance act, not observation, per corpus's own kind-3 classification | Policy (weak) | HYPOTHESIS |
| 5 | `Compare(P1,P2,C,Ω)` | `012 §46` | ✅ `→𝒬` | typed, no body | ⚠️ operates over Propositions, not states directly | Proposition | DERIVED |
| 6 | `orphan(a)⟺deg_ℛ(a)=0` | `281`/EKP lint | 🟡 | ✅ computable, total, cheap | 🟡 implemented, relevance untested | Relation | DERIVED |
| 7 | `circular_dependency` | EKP lint | 🟡 | ✅ implemented | 🟡 | Relation | DERIVED |
| 8 | `Lineage=Π∘ℛ_der*` | `47`/`I-Π` | 🟡 derived | 🟡 | 🟡 | Relation/Event | DERIVED |
| 9 | 5 `Σ`-axis readings (`π_A…π_C`) | Q4A | ✅ | typed | ⚠️ `Σ`-level; no `π:K→Σ` exists (`289 §5`) — **not even connected to `K_t`** | State (weak, disconnected) | HYPOTHESIS |
| 10 | `deg_ℛ`, graph queries | `38.87` | 🟡 | 🟡 three graphs, "must not be collapsed" | 🟡 | Relation | DERIVED |

## Primitive-coverage analysis (the crux; the corpus itself never performs this mapping)

| Primitive | Candidate observations | Coverage |
|---|---|---|
| Entity | **none** | 🔴 zero |
| State | `Σ`-axis readings (weak, disconnected from `K_t`) | ⚠️ weak/contested |
| Event | `TraceOrigin`, `ExplainRevision`, `Lineage` | ✅ covered — and the two most load-bearing candidates in the whole inventory |
| Observation | **none** | 🔴 zero |
| Proposition | `Assess`, `Compare` | ⚠️ covered but both flagged uncertain-kind |
| Relation | `orphan`, `circular_dependency`, `deg_ℛ`/graph queries | ✅ covered |
| Policy | `Authorize` | ⚠️ single, uncertain-kind candidate |
| Action | **none** | 🔴 zero |

**Result: at most 5 of 8 primitives have any candidate observation at all, and 3 (Entity, Observation,
Action) have zero anywhere in the admissible corpus.**

## Forward continuity check (per the strengthened rule)

`step-292/07_observation-sensing-qualify.md` (2026-09-02, one day after the inventory) is the latest dated
material on this exact question: *"`Observation` OPEN, `Qualify` OPEN, `𝒪/𝒯` OPEN... Reiter changes none
of them."* **Reconfirms, does not revise**, the open status — a continuous unresolved thread from
2026-08-30 (Step 261.21) through 2026-09-02, four days, multiple independent checkpoints, no closure.

## Honest verdict

**𝒪_B cannot currently be constructed as a source-grounded, primitive-complete observation set.**
Constructing one covering all 8 primitives would require inventing observations for Entity, Observation,
and Action that do not exist anywhere in the corpus — which this investigation's own discipline forbids.
A bounded `𝒪_B` restricted to the 5 partially-covered primitives is source-groundable, but must be
reported as **partial**, with its gaps stated plainly, never papered over as complete.
