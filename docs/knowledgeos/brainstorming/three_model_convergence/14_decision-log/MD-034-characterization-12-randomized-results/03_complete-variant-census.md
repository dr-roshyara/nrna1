# Complete Variant Census

The file's own "Robustness across alternative models" table (lines 132–145), transcribed in full —
every row, no omission.

| Variant | What it changes | Operators that become derivable |
|---|---|---|
| **V0** | baseline as declared | `Discriminate`, `DetectGap` |
| **V1** | `meaning-assignment` and `symbolic-encoding` are ONE atom | `Interpret`, `Represent`, `Discriminate`, `DetectGap` |
| **V2** | `Infer` may act on `SemanticContent`, not only `Representation` | `Discriminate`, `DetectGap` |
| **V3** | closure-judgment not primitive: adequacy = difference-decision over the norm delta | `Discriminate`, `DetectGap`, `Determine` |
| **V4** | `DetectGap`'s difference-decision is scoped to `NormDelta` | `DetectGap` only |
| **V5** | action choice = difference-decision + `Objective` | `Discriminate`, `DetectGap`, `Select` |
| **V6** | a `Verdict` requires a surviving-defeater step | `Discriminate`, `DetectGap` |
| **V7** | raw `C0`, no `Qualify` (baseline lost: C10 C16 C17 C25) | `Discriminate`, `DetectGap`, `Validate` |

## Per-variant, does it concern `Validate`?

| Variant | Concerns `Validate`? | How |
|---|---|---|
| V0 | Indirectly | the baseline itself, against which every other variant is compared |
| V1 | No | `Interpret`/`Represent` atoms |
| V2 | No | `Infer`'s domain |
| V3 | No | `Determine`'s closure-judgment |
| V4 | No | `DetectGap`'s scope |
| V5 | No | `Select`'s domain |
| **V6** | **Yes, directly** | changes the `Verdict`-derivation rule itself — the exact object `Validate`'s own output depends on |
| V7 | Yes, directly (degenerate) | removes `Qualify`, which makes `Evidence` (and therefore `Verdict`) unreachable — `Validate`'s atom becomes inert. The document's own text (line 157) explicitly rejects this as a genuine result: *"This is a vacuous derivability and is rejected as evidence. `[NEG]`"* |

## The "Reading the robustness table" section (lines 147–157), the `Validate`-relevant row quoted
exactly

*"`Validate` irreducible | 7/8 — 'derivable' under V7 | **degenerate**: in V7 nothing can produce a
`Verdict` at all, so `Validate`'s atom is inert. This is a vacuous derivability and is rejected as
evidence. `[NEG]`"*

## Does the document itself distinguish "V6 appears" from "V6 is inferable" from "V6 is absent"?

**V6 appears — directly, by name, with an explicit definition and a dedicated discussion.** This is
not an inference this study constructs; it is the document's own stated content (line 144, and the
entire "Causal / model-criticism check" section, `04`).
