# Admissible Evidence Census

A full-text search of all three admitted files (not a sample) for every required term, run once,
results reported completely — no term's absence is asserted without the search that established it.

## Search terms and full results

`grep -ni "precondition\|postcondition\|failure\|error\|reject\|invalid\|contradict\|exception\|determinis\|mutation\|side.effect\|non-determin" 03-capability-model.md 04-operator-contracts.md 06-composition-rules.md`:

```
06-composition-rules.md:46:| `EpistemicState` | `state-mutation` | `EpistemicState′` |
04-operator-contracts.md:33:| `state-mutation` | commit to `K_t`, history-preserving |
04-operator-contracts.md:60:| `Revise` | `state-mutation` | the only commit to `K_t`; history preserving | STRONG |
03-capability-model.md:50:| C11 | revise epistemic state | atom `state-mutation` | power |
03-capability-model.md:54:| C15 | represent temporal conditions | atom `state-mutation` | invariant |
03-capability-model.md:63:| C24 | support learning from new observations | `Observation` + `state-mutation` | artifact |
03-capability-model.md:84:### Modification M-3 — C20 given an explicit failure mode
03-capability-model.md:91:### Modifications considered and REJECTED
03-capability-model.md:93:| Proposal | Why rejected |
```

## Per-hit classification (every hit accounted for, none silently dropped)

| Hit | Concerns `Validate`? | Classification |
|---|---|---|
| `06`:46, `04`:33/60, `03`:50/54/63 — all `state-mutation` | **No** — all six occurrences concern `Revise` (or capabilities `C11`/`C15`/`C24`, none of which is `C10`) | Not applicable to `Validate` — but see `03` for the important *negative* inference this supports (`Validate` explicitly has none) |
| `03`:84 "explicit failure mode" | **No** — concerns `C20` ("support non-identifiability"), not `C10` | Not applicable |
| `03`:91/93 "REJECTED"/"Why rejected" | **No** — concerns rejected *capability-merger proposals* (a methodology table), not `Validate`'s own operational failure semantics | Not applicable |

## Direct result

**Zero hits, across all three admitted files, for any precondition, postcondition, failure/error/
exception/rejection/invalid-input/contradiction-handling semantics attributable to `Validate`
specifically.** This is a genuine, exhaustive negative finding (census, not sample), classified per
this study's own required discipline: **`NOT SPECIFIED BY SOURCE`** — not "does not exist," not
"contradicted," not "not applicable." The admissible population is simply silent on these fields for
this operator.

## `V6` search — full result

`grep -n "V6" 03-capability-model.md 04-operator-contracts.md 06-composition-rules.md` → **zero
matches, any file.**

`grep -no "V[0-9]"` across all three files → exactly three hits: `V1` (`03`:95), `V5` (`03`:96), `V4`
(`04`:76). No other variant number appears anywhere in the admissible population.

## Indirect state-effect finding, worth surfacing precisely (from `04`'s own general contract, not a
search hit but read directly during the cold full-read)

`04`, "Common to all" (lines 44–46): *"State effects = none, except `Revise`."* Since `Validate ≠
Revise`, this is a direct, source-stated fact: **`Validate` has no state effects.** This is a genuine
postcondition-adjacent fact (a negative one) that the grep census above did not surface as a keyword
hit (it doesn't use the word "postcondition"), recorded here so the census above is not mistaken for
the complete picture — see `03` for its proper place in the contract reconstruction.
