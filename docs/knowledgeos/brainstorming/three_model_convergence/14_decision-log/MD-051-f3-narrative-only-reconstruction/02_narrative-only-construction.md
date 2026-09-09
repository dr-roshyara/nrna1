# MD-051 §02 — Narrative-Only `Obs`/`Beh_𝔠(F3)` Construction

**Evidence base, exhaustive**: `03-capability-model.md`, `04-operator-contracts.md`,
`06-composition-rules.md`, `12-randomized-results.md` (all under
`docs/knowledgeos/research/kernel-reduction/`). No other file consulted. No executable file opened.

**Anti-circularity discipline applied throughout** (mirroring `04`'s own "anti-circularity device"):
no capability, atom, or carrier definition below is introduced by this phase — each is located,
quoted or precisely paraphrased, and tagged with its evidence level. Where this phase performs a
*computation* (the `Reach` fixpoint), the computation follows a **source-stated procedure** (`06`'s
own `Reach(S)` formula) applied to **source-stated tables** (`04`'s atom table, `06`'s derivation
table) — it is not a new rule invented to fit a desired outcome.

## Evidence-level legend

`[SD]` SOURCE-DEFINED (stated verbatim or near-verbatim in an admitted file) · `[LD]` LOGICALLY
DERIVABLE (a mechanical consequence of `[SD]` material, shown here) · `[RC]` RECONSTRUCTED (this
phase's own organizing step, built only from `[SD]`/`[LD]` material) · `[NS]` NOT SPECIFIED BY SOURCE
· `[HP]` HYPOTHETICAL.

## 1. The capability space — `𝔠_KOS` analogue

`[SD]` `03` supplies a 25-member capability table (C1–C25), each with a realizing carrier and a kind
(artifact/power/invariant). `[SD]` "Minimal" is explicitly disambiguated into five notions (A–E); the
experiment targets **B (semantic minimality)** and **C (compositional minimality)** — `[SD]` formally:
`C_required ⊆ Reach(S)` (adequacy) and `∀o∈S: C_required ⊄ Reach(S\{o})` (irredundancy).

This is F3's own `𝔠_KOS`-analogue: a **stated target capability set**, not invented by this phase.

## 2. The carrier universe and atom vocabulary — `Obs` analogue

`[SD]` `06` gives **9 ambient carriers** (`World, Context, Inquiry, IdealState, Policy, Rule,
Objective, ActionSet, EpistemicState(K_t)`) and **14 derived carrier kinds** (`Observation,
SemanticContent, Representation, Evidence, Relation, Hypothesis, Claim, Defeater, Verdict, NormDelta,
Gap, Determination, Decision, Discrimination`) — 23 kinds total.

`[SD]` `04` gives **14 productive atoms** and a per-operator atom table for the 13 C0 operators plus
`Qualify` (held back from C0 specifically):

| Operator | Atom(s) |
|---|---|
| Observe | world-contact |
| Interpret | meaning-assignment |
| Represent | symbolic-encoding |
| Relate | relational-linking |
| Discriminate | difference-decision |
| Hypothesize | content-generation |
| Infer | entailment |
| DetectGap | norm-comparison, difference-decision |
| Challenge | adversarial-negation |
| Validate | warrant-assessment |
| Revise | state-mutation |
| Determine | norm-comparison, closure-judgment |
| Select | preference-over-actions |
| **Qualify** | evidential-qualification **(sole holder)** |

`[RC]` Define `Obs(K)` for an operator set `K` as the vector of the 9 ambient carriers (always
present) together with whichever of the 14 derived carrier *kinds* are producible by `K` — this
reading is a direct restatement of `03`'s own achievement criterion (below), not a new object.

## 3. `Reach(S)` and the achievement criterion — `Beh_𝔠` analogue, SOURCE-DEFINED

`[SD]` `06` states the construction verbatim:

```
Reach(S) = μA. AMBIENT ∪ { k | ∃o∈S : k ∈ derive(A, o.atoms) }
```

— monotone, terminating, independent of operator names — and:

`[SD]` "A capability `c` is **achieved** iff `c.kinds ⊆ Reach(S)` and `c.atoms ⊆ atom_pool(S)`."

**This is the single most important finding of this phase**: `Beh_𝔠(K) := Reach(Ops(K))`, together
with the achievement criterion above, is **not a research construction this phase (or MD-050) had to
invent** — it is **verbatim source-defined** in the admitted narrative file `06`. MD-050 arrived at
functionally the same object by reading the executable's `reach.py` and self-labeled it "a research
construction... explicitly labeled a research construction rather than corpus-established fact."
That self-labeling was **more conservative than the evidence warranted** — the construction was
corpus-established all along, in the properly-admitted narrative lane, MD-050 simply never opened the
file that stated it.

## 4. The derivation table, `[SD]` verbatim from `06`

| Inputs | Atom | → Kind |
|---|---|---|
| World | world-contact | Observation |
| Observation, Context | meaning-assignment | SemanticContent |
| SemanticContent | symbolic-encoding | Representation |
| Observation, Policy | evidential-qualification | **Evidence** |
| Representation \| SemanticContent | relational-linking | Relation |
| Representation \| SemanticContent | content-generation | Hypothesis |
| Representation,Rule \| Claim,Rule \| Hypothesis,Rule | entailment | Claim |
| Claim \| Hypothesis | adversarial-negation | Defeater |
| Claim,Evidence \| Hypothesis,Evidence | warrant-assessment | **Verdict** |
| EpistemicState, IdealState | norm-comparison | NormDelta |
| NormDelta | difference-decision | Gap |
| NormDelta, Inquiry | closure-judgment | Determination |
| ActionSet, Objective | preference-over-actions | Decision |
| EpistemicState | state-mutation | EpistemicState′ (same kind, mutated) |
| any of {Representation,SemanticContent,Hypothesis,Claim,Evidence,Observation,Verdict,Relation,NormDelta} | difference-decision | Discrimination |

## 5. Computed `Beh_𝔠(C0)` — `[LD]`, hand-traced from §4, cold

Atom pool for `C0` (13 operators, per §2's table) = `{world-contact, meaning-assignment,
symbolic-encoding, relational-linking, difference-decision, content-generation, entailment,
norm-comparison, adversarial-negation, warrant-assessment, state-mutation, closure-judgment,
preference-over-actions}` — **missing `evidential-qualification`** (`Qualify`'s sole atom, and `C0`
excludes `Qualify` by definition, per `04`).

Tracing the fixpoint against §4's table:

- `World → Observation` ✓ (world-contact available)
- `Observation,Context → SemanticContent` ✓
- `SemanticContent → Representation` ✓
- `Observation,Policy → Evidence` **✗ BLOCKED** — `evidential-qualification` not in `C0`'s atom pool
- `Representation|SemanticContent → Relation` ✓
- `Representation|SemanticContent → Hypothesis` ✓
- `Representation,Rule → Claim` ✓ (Rule is ambient)
- `Claim|Hypothesis → Defeater` ✓
- `Claim,Evidence|Hypothesis,Evidence → Verdict` **✗ BLOCKED** — requires `Evidence`, unreachable
- `EpistemicState,IdealState → NormDelta` ✓
- `NormDelta → Gap` ✓
- `NormDelta,Inquiry → Determination` ✓
- `ActionSet,Objective → Decision` ✓
- `{Representation,...} → Discrimination` ✓ (several qualifying inputs already reached)

**Result**: `Beh_𝔠(C0)` reaches 9 ambient + 12 of 14 derived kinds = **21 of 23 total carrier kinds**.
Missing exactly: **`Evidence`, `Verdict`**.

## 6. Computed `Beh_𝔠(C0_plus)` — `[LD]`

Adding `Qualify` supplies `evidential-qualification`:

- `Observation,Policy → Evidence` now ✓
- `Claim,Evidence → Verdict` now ✓ (Claim already reached)

**Result**: `Beh_𝔠(C0_plus)` reaches all 23 kinds — **complete**.

## 7. Three formal results, `[LD]`, cross-checked against `[SD]` empirical evidence in `12`

**(a) `C0 ≺_cap C0_PLUS`, strict.** `Beh_𝔠(C0) ⊊ Beh_𝔠(C0_plus)` (21/23 vs. 23/23, §5–6) — a strict
capability-preorder inequality, `[LD]` from the computation above.

**(b) `Qualify` is irreducible.** `[SD]` `04` states directly: "`DetectGap` holds no atom exclusively.
Every other operator in `C0∪{Qualify}` holds at least one atom that nothing else holds" — `Qualify`'s
`evidential-qualification` is exclusive by the atom table itself. `[LD]` confirmed by computation:
removing `Qualify` from any set collapses `Evidence`/`Verdict` reachability (§5). **Independently
corroborated, not merely computed**: `[SD]` `12`'s own robustness table (Part XXIII, 7 alternative
models) reports "`Qualify` irreducible | **8/8 variants** | robust" — an empirical, source-stated
confirmation this phase's hand-computation did not need to invent.

**(c) `DetectGap` is redundant given `{Determine, Discriminate}`.** `[SD]` `04`: `DetectGap`'s atoms
are `{norm-comparison, difference-decision}` — both shared, respectively, with `Determine`
(`norm-comparison, closure-judgment`) and `Discriminate` (`difference-decision`). `[LD]`: removing
`DetectGap` from `C0_plus` leaves both atoms still supplied (by `Determine` and `Discriminate`
respectively), so every row of §4's table that fires under `C0_plus` still fires under
`C0_plus\{DetectGap}` — `Beh_𝔠(C0_plus\{DetectGap}) = Beh_𝔠(C0_plus)` = complete/23, unchanged.
**Independently corroborated**: `[SD]` `12`'s robustness table reports "`DetectGap` is derivable |
**8/8 variants**, including V4 which was built specifically to protect it | robust" — again a
source-stated empirical result, not this phase's own invention.

## 8. What remains `[NS]`/`[HP]` — honestly bounded

- Whether the 23-kind carrier universe is itself *exhaustive* of everything a real epistemic system
  needs is `[HP]` — `03` explicitly scopes "cognitive universality" (Notion E) **out of scope, not
  tested, not claimed**.
- `Infer`'s own atom (`entailment`) participates in `Claim` production but no admitted file states
  an `Infer`-specific irreducibility/redundancy result as clean as `Qualify`'s or `DetectGap`'s —
  `12`'s robustness table lists `Infer` among "8/8 irreducible," which is `[SD]`, but no *additional*
  structural argument (of the kind given for (b)/(c) above) is attempted here, since none was
  requested and none is needed for the F3-internal question this phase addresses.
- Whether this construction, once fixed, corresponds to any *other* candidate kernel family (F1, F5,
  MinKer) is explicitly out of scope for this phase (see `00_index.md`) — addressed only where it can
  be established from these same four narrative files directly, in §03.
