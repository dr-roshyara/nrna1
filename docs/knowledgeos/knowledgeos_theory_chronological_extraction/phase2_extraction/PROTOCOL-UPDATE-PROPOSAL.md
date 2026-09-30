# Protocol Update Proposal — post-EXP-0002

## SECTIONS TO MODIFY *(extend, never duplicate)*

| § | Change | Reason |
|---|---|---|
| **13.0** `Experiment` record | add the **mandatory experimental sequence** TEST → RAW → INTERPRET → IMPACT as required fields | the record exists; the *sequence discipline* does not. ⚠️ **Named "experimental sequence", NOT "four stages"** — §9 already owns that phrase for F0–F3 |
| **16** Quality gates | +6 gates (`Q45`–`Q50`) | new rules need enforcement points |
| **15** Artifacts | add `CORPUS-CONTRIBUTION-AUDIT.md` | new required output |
| **5A.4** levels | add a **pointer** to realization layers, stated as ⭐ **orthogonal** to epistemic levels | ⛔ must not become a 7th epistemic level |

## SECTIONS TO ADD

| § | Content | Reason |
|---|---|---|
| ⭐ **5B** | **Corpus Contribution Audit + theory-coverage requirement** | nothing currently checks that the theory *covers* its corpus |
| ⭐ **13A** | **Five realization layers** — semantic · structural · behavioral · operational · empirical | EXP-0002 proved these are distinct and were being collapsed |
| ⭐ **13B** | **Statistical discipline for counts** | extends §12's D5; the protocol has no rule against a convenience count becoming a law |

## SECTIONS LEFT UNCHANGED

`0 · 1 · 2 · 3 · 3A · 3B · 3C · 4 · 4A · 5 · 6 · 7 · 8 · 9 · 10 · 11 · 12 · 14 · 17 · 18 · 19` — all valid, none contradicted.

## SCHEMA CHANGES

`Experiment` gains `stage_1_test` · `stage_2_raw_result` · `stage_3_interpretation` · `stage_4_impact` · `realization_layer` · `count_discipline`.
New: `ContributionRecord` (file · contribution · classification · theory object · location · status · reason · follow-up).

## NEW GATES

`Q45` coverage · `Q46` theory representation · `Q47` experimental sequence · `Q48` realization layers distinguished · `Q49` counts justified · `Q50` no silent promotion.

## NEW OUTPUTS

`CORPUS-CONTRIBUTION-AUDIT.md` *(human-readable, per §14 of the commission)*.

## ⛔ Collisions avoided

| Existing term | New term | Kept apart |
|---|---|---|
| §9 "four stages" = F0–F3 formalization | "**experimental sequence**" | ✅ different name |
| §5A.4 six epistemic levels A–F | "**realization layers**" A–E | ✅ declared **orthogonal**, not a level |
| §12 D5 statistical dimension | §13B count discipline | ✅ 13B is the *rule*; D5 is the *report* |
