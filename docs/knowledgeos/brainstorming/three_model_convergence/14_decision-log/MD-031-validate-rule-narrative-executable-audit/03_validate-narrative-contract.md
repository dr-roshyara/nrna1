# `06-composition-rules.md` Read Cold — the Narrative `Validate` Contract

Read in full, before re-consulting `kr/carriers.py` or any prior MD-030 finding about it. Quoted
verbatim where it matters.

## Does the document mention "Validate"?

**Once, only, and not in the derivation table**: line 53, a rhetorical aside — *"This is what keeps
`Infer ≟ Validate ∘ Hypothesize` an honest question rather than a definitional trick."* This is not a
contract statement; it names `Validate` only to illustrate why the type discipline matters
elsewhere. **The derivation table itself (lines 31–47) never mentions any operator name** — this is
deliberate: the document's own design principle (stated at lines 20–29, "a carrier kind is not
stamped by the operator that produced it... otherwise 'only `DetectGap` can make a `Gap`' would be
true by fiat") is the same anti-circularity discipline already found in the executable code's
`carriers.py` docstring, expressed independently here in prose.

## What the document states, unconditionally, about the `warrant-assessment` → `Verdict` step

Table row (line 41), transcribed exactly:

> `Claim, Evidence` | `Hypothesis, Evidence` → `warrant-assessment` → `Verdict`

Plus explicit prose (lines 49–56, "Note the type discipline..."):

> *"`Claim` is producible **only** via `entailment` — so its warrant kind is DEDUCTIVE by
> construction. A `Verdict` produced by generate-and-test is *not* a `Claim`... `Verdict` requires
> `Evidence`. This is where the absence of `Qualify` from `C0` becomes fatal."*

This is a direct, unconditional, source-stated derivation rule and an explicit textual explanation of
its consequence (the same C0-vs-C0-plus-Qualify reachability fact MD-030 found computed in
`baseline.json`) — stated here in prose, in a narrative document, independent of any code read.

## The contract, per the required field list — `Validate_N`

| Field | Value | Source location | Epistemic status |
|---|---|---|---|
| Input | `{Claim, Evidence}` or `{Hypothesis, Evidence}` | line 41, derivation table | **explicit, unconditional** |
| Output | `Verdict` | line 41 | **explicit, unconditional** — also independently confirmed by `03-capability-model.md`'s C10 row (already admitted, per MD-029) |
| Derivation/atom | `warrant-assessment` | line 41 | **explicit** — matches `04`'s already-admitted atom assignment for `Validate` |
| Preconditions (operator-specific) | NOT SPECIFIED BY SOURCE — the table states carrier/atom preconditions for the *derivation step*, not operator-specific preconditions beyond "the listed inputs must be available" | — | the general mechanism is explicit; an operator-specific precondition beyond input-availability is not stated |
| Postconditions | NOT SPECIFIED BY SOURCE | — | the table states only what is produced, not a postcondition predicate over it |
| Warrant requirement | `Claim` is producible only via `entailment` (DEDUCTIVE); a generate-and-test `Verdict` is explicitly NOT a `Claim` | lines 51–52 | **explicit** — a genuine type-discipline constraint, narrower than "any `Claim`-like thing" |
| Policy dependence | `Evidence` itself requires `Policy` (via the `Observation, Policy → Evidence` row, line 36) — so `Validate`'s own input chain is policy-dependent one step upstream | line 36, chained | **explicit, but indirect** — not stated as a `Validate`-specific policy dependency, inferred by following the derivation chain one more step, using only this document's own table |
| Context dependence | Not directly — `Context` only appears in the `Observation, Context → SemanticContent` row, two steps removed from `Verdict`'s own direct inputs | — | NOT SPECIFIED BY SOURCE as a direct dependency |
| Failure/undefined behavior | NOT SPECIFIED BY SOURCE — the document states what `Reach` does NOT do (§"What `Reach` deliberately does NOT include," lines 71–78: "No 'obvious' closures added by hand... No helper functions") but this is a property of the whole engine, not `Validate`-specific failure semantics | lines 71–78 | general engine property, not operator-specific |
| Composition constraints | The `Reach(S)` fixpoint definition (lines 58–69) governs how `Validate`'s step composes with every other operator's step — general, not `Validate`-specific | lines 58–69 | explicit, general mechanism |
| Presented as | **fact/derivation** — the document states the table as the governing rule ("A carrier kind is... derived from... so any operator set able to introduce the required atoms... produces the carrier"), not as a hypothesis, recommendation, or experiment | lines 20–29 | direct textual framing |

## How "the narrative source specifies `Validate`'s own contract" is actually established

The table itself never names `Validate`. The chain from this document alone plus one already-admitted
document is:

1. `04-operator-contracts.md` (admitted; MD-029's own `02` already extracted this): `Validate`'s atom
   is `warrant-assessment`.
2. `06-composition-rules.md` (this study): the derivation rule for the atom `warrant-assessment` is
   `{Claim,Evidence}`|`{Hypothesis,Evidence}` → `Verdict`.
3. Therefore: `Validate`'s own input carriers are `{Claim,Evidence}` or `{Hypothesis,Evidence}`.

**Every link in this chain is a source-stated fact from the narrative lane itself** (steps 1 and 2 are
each directly quoted, not inferred from silence or from code). This is a materially different, and
stronger, kind of construction than MD-029's own earlier attempt (which reasoned from capability-name
*semantics*, e.g. "C7 sounds like it should feed `Validate`") — this chain requires no semantic
guessing, only combining two explicit atom/carrier statements. It is still, formally, a **constructed
inference** (no single sentence in the corpus states "Validate's input is Claim and Evidence" using
that exact operator name) — but it is a narrative-only construction, built without consulting the
executable code at any point in this section.
