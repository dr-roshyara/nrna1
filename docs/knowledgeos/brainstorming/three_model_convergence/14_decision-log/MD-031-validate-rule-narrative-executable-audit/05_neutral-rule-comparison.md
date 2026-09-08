# Neutral Rule Comparison

Semantic role established before correspondence is declared (per this study's own requirement — a
shared name like `Claim` is not, by itself, evidence of a shared concept).

## Semantic-role check, both sources

In **both** `06-composition-rules.md` and `kr/carriers.py`, `Claim`/`Evidence`/`Hypothesis`/`Verdict`
are used identically: labels for carrier *kinds* in one shared typed-composition system, never given
an independent domain definition beyond "a kind derivable from these inputs via this atom." Neither
source treats `Claim` as (for example) a DDD entity with identity, or as a natural-language claim
outside this formal system. **The roles genuinely match** — this is not a case of the same word
denoting different concepts in the two sources (contrast with the general risk the authorization
warned about).

## Dimension-by-dimension table

| Dimension | Narrative (`06`) | Executable (`kr/carriers.py`) | Match? |
|---|---|---|---|
| Input carrier (set 1) | `Claim, Evidence` | `{CLAIM, EVIDENCE}` | **Identical** |
| Input carrier (set 2) | `Hypothesis, Evidence` | `{HYPOTHESIS, EVIDENCE}` | **Identical** |
| Atom/derivation label | `warrant-assessment` | `A_WARRANT = "warrant-assessment"` | **Identical string** |
| Output carrier | `Verdict` | `VERDICT = "Verdict"` | **Identical** |
| Explanatory note on `Claim`'s own restriction | "`Claim` is producible only via `entailment`... DEDUCTIVE by construction" | Same restriction present structurally in `DERIVATION_RULES` (`Claim` only appears as an output of the `entailment`-atom rows) — not stated in prose in the code | **Same underlying constraint; narrative states it in prose, code only in structure** |
| Explanatory note on `Qualify`/`Evidence` dependency | "`Verdict` requires `Evidence`. This is where the absence of `Qualify` from `C0` becomes fatal." | Same fact, computed and confirmed in `baseline.json` (`C10` lost under `C0`, achieved under `C0_plus`) — not stated in prose anywhere in the code itself | **Same underlying fact; narrative states it as an assertion, code demonstrates it as a computed result** |
| `Defeater`/challenge involvement | **Not mentioned anywhere in the derivation table or its prose** | `V6` variant *adds* a `Defeater` requirement, explicitly framed as an alternative, not the baseline | **`06` is silent on the `V6` alternative — it neither endorses nor rules it out; it simply doesn't discuss it** (see `07`) |
| Framing | Stated as the governing rule, unconditionally | Stated as the code's own declared `V0` baseline, one of ≥2 tested variants | **`06` presents the rule with more apparent certainty (no hedge) than the code's own internal framing (`V0` labeled explicitly as one variant among several tested)** |

## What this table shows, precisely

Every checkable field matches exactly, at both the lexical and structural level — this goes beyond
"same carrier names appear" (the instruction's own named risk): the *combination* (two named input
sets, one atom, one output, one explanatory constraint on `Claim`, one explanatory fact about
`Evidence`/`Qualify`) matches as a whole, not merely a shared vocabulary item in isolation. The one
asymmetry found: `06` does not acknowledge the `V6` alternative at all, while the code both encodes
`V6` and explicitly frames the two-input rule as only its own `V0` baseline. This asymmetry is
addressed directly in `07`.
