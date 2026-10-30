# C49, the window commission: step 1 (blind inventory)

*2026-10-30. Window reviewer W (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the sources below, in index order, each read whole to its last line (read long files in consecutive parts; some files have no final newline). For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0237 | `docs/knowledgeos/architecture/20260822-0915-KOS-EP01-P4-Constitutional-Invariant-Map-v1.0.md` | 345 |
| S0238 | `docs/knowledgeos/reviews/2026-08-22-KOS-EP01-P5-evidence-validation-launch-prompt.md` | 90 |
| S0239 | `docs/knowledgeos/architecture/20260822-0927-KOS-EP01-P5-Constitutional-Invariant-Decision-Matrix-v1.0.md` | 256 |
| S0240 | `docs/knowledgeos/architecture/20260822-0939-KOS-EP01-Step5-Kernel-Decision.md` | 108 |

All are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the sources above. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file, including files the sources cite. The inventory is blind.
- **Never open** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on anything a source says about them; mark such items "(access)". Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`, except a targeted check of these four paths against the allowed list.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Hand-back
Begin "C49 STEP 1: BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.
